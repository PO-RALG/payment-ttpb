<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ChangePasswordRequest;
use App\Http\Requests\API\CreateUserRequest;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\RefreshTokenRequest;
use App\Models\Setup\Permission;
use App\Models\Setup\Role;
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

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'identifier' => ['The account has been deactivated.'],
            ]);
        }

        if (! $user->first_login_at) {
            $user->forceFill([
                'first_login_at' => now(),
            ])->save();
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

    public function storeUser(CreateUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $rolesInput = $data['roles'] ?? [];
        unset($data['roles']);

        $temporaryPassword = $this->defaultTemporaryPassword();

        $data['password'] = Hash::make($temporaryPassword);
        $data['must_change_password'] = true;
        $data['is_active'] = true;

        $user = User::create($data);

        $roleIds = $this->determineRoleIds($rolesInput);
        $this->syncUserRoles($user, $roleIds, $request->user());

        $user->load('roles.permissions');

        return response()->json([
            'message' => 'User created successfully.',
            'data' => [
                'user' => $this->formatUserPayload($user, $user->roles),
                'temporary_password' => $temporaryPassword,
            ],
        ], 201);
    }

    public function deactivateUser(Request $request, User $user): JsonResponse
    {
        $active = (bool) $request->input('active', false);

        $user->forceFill([
            'is_active' => $active,
        ])->save();

        return response()->json([
            'message' => $active ? 'User reactivated successfully.' : 'User deactivated successfully.',
            'data' => [
            'user_name' => trim($user->first_name . ' ' . ($user->middle_name ? $user->middle_name . ' ' : '') . $user->last_name),
            'is_active' => $user->is_active,
            ],
        ]);
    }

    public function resetUserPassword(User $user): JsonResponse
    {
        $temporaryPassword = $this->defaultTemporaryPassword();

        $user->forceFill([
            'password' => Hash::make($temporaryPassword),
            'must_change_password' => true,
        ])->save();

        $user->tokens()->delete();

        return response()->json([
            'message' => 'Password reset successfully.',
            'data' => [
                'user_name' => trim($user->first_name . ' ' . ($user->middle_name ? $user->middle_name . ' ' : '') . $user->last_name),
                'temporary_password' => $temporaryPassword,
            ],
        ]);
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $currentPassword = $request->input('current_password');
        if (! $user->must_change_password && (! $currentPassword || ! Hash::check($currentPassword, $user->password))) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($request->input('new_password')),
            'must_change_password' => false,
        ])->save();

        return response()->json([
            'message' => 'Password changed successfully.',
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
            'must_change_password' => $user->must_change_password,
            'roles' => $roles->map(function ($role) {
                return [
                    'id' => $role->id,
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

    protected function determineRoleIds(?array $rolesInput): array
    {
        $ids = collect($rolesInput ?? [])
            ->pluck('id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($ids)) {
            return [$this->ensureDefaultRole()->id];
        }

        $existing = Role::query()
            ->whereIn('id', $ids)
            ->pluck('id')
            ->all();

        if (count($existing) !== count($ids)) {
            throw ValidationException::withMessages([
                'roles' => ['One or more roles are invalid.'],
            ]);
        }

        return $existing;
    }

    protected function ensureDefaultRole(): Role
    {
        $role = Role::where('code', 'NEW_USER_ROLE')->first();

        if (! $role) {
            $permission = Permission::query()->firstOrCreate(
                ['code' => 'NEW_USER'],
                [
                    'name' => 'New user permission',
                    'created_by' => 'system',
                    'updated_by' => 'system',
                    'active' => true,
                ]
            );

            $role = Role::create([
                'name' => 'New User',
                'code' => 'NEW_USER_ROLE',
                'created_by' => 'system',
                'updated_by' => 'system',
                'active' => true,
            ]);

            $role->permissions()->attach($permission->id, [
                'created_by' => 'system',
                'updated_by' => 'system',
                'active' => true,
            ]);
        }

        return $role;
    }

    protected function syncUserRoles(User $user, array $roleIds, ?User $actor = null): void
    {
        $pivotData = [];
        $actorId = $actor?->id;
        $actorLabel = (string) ($actorId ?? 'system');

        foreach ($roleIds as $roleId) {
            $pivotData[$roleId] = [
                'assigned_at' => now(),
                'assigned_by_user_id' => $actorId,
                'created_by' => $actorLabel,
                'updated_by' => $actorLabel,
                'active' => true,
            ];
        }

        $user->roles()->sync($pivotData);
    }

    protected function defaultTemporaryPassword(): string
    {
        return (string) config('auth.default_temp_password', env('DEFAULT_TEMP_PASSWORD', 'ChangeMe123!'));
    }
}
