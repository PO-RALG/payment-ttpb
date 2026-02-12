<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\RefreshTokenRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $user = User::query()
            ->where('email', $credentials['identifier'])
            ->orWhere('phone', $credentials['identifier'])
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'identifier' => ['The provided credentials are invalid.'],
            ]);
        }

        return $this->issueTokensFor($user, $request);
    }

    public function refresh(RefreshTokenRequest $request): JsonResponse
    {
        $hashed = $this->hashToken($request->validated()['refresh_token']);

        $token = PersonalAccessToken::query()
            ->where('refresh_token_hash', $hashed)
            ->first();

        if (! $token || $token->refresh_token_expires_at?->isPast()) {
            throw ValidationException::withMessages([
                'refresh_token' => ['The refresh token is invalid or has expired.'],
            ]);
        }

        $user = $token->tokenable;

        if (! $user) {
            $token->delete();

            throw ValidationException::withMessages([
                'refresh_token' => ['The refresh token is invalid.'],
            ]);
        }

        $token->delete();

        return $this->issueTokensFor($user, $request);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    protected function issueTokensFor(User $user, Request $request): JsonResponse
    {
        $roles = $user->roles()
            ->with(['permissions:id,code'])
            ->get();

        $permissions = $roles
            ->flatMap(fn ($role) => $role->permissions->pluck('code'))
            ->unique()
            ->values()
            ->all();

        if (empty($permissions)) {
            $permissions = ['*'];
        }

        $accessToken = $user->createToken('ttpb-api', $permissions);

        $refreshToken = Str::random(64);
        $accessTokenModel = $accessToken->accessToken;
        $accessTtl = (int) config('sanctum.expiration', 10);
        $refreshTtlMinutes = (int) config('sanctum.refresh_expiration', 60 * 24);

        $accessTokenModel->forceFill([
            'expires_at' => now()->addMinutes($accessTtl),
            'refresh_token_hash' => $this->hashToken($refreshToken),
            'refresh_token_expires_at' => now()->addMinutes($refreshTtlMinutes),
            'ip_address' => $request->ip(),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 255),
        ])->save();

        return response()->json([
            'access_token' => $accessToken->plainTextToken,
            'token_type' => 'Bearer',
            'expires_in' => $accessTtl * 60,
            'refresh_token' => $refreshToken,
            'refresh_expires_in' => $refreshTtlMinutes * 60,
            'user' => $this->formatUserPayload($user, $roles),
        ]);
    }

    protected function formatUserPayload(User $user, Collection $roles): array
    {
        return [
            'first_name' => $user->first_name,
            'middle_name' => $user->middle_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'roles' => $roles->map(function ($role) {
                return [
                    'name' => $role->name,
                    'code' => $role->code,
                    'permissions' => $role->permissions
                        ->map(fn ($permission) => ['code' => $permission->code])
                        ->unique('code')
                        ->values(),
                ];
            })->values(),
        ];
    }

    protected function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }
}
