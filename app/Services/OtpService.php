<?php

namespace App\Services;

use App\Models\EmailOtp;
use App\Models\PhoneOtp;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class OtpService
{
    protected int $ttlSeconds;
    protected ?string $mockCode;

    public function __construct()
    {
        $this->ttlSeconds = (int) config('auth.otp.ttl_seconds', 300);
        $mock = config('auth.otp.mock_code');
        $this->mockCode = $mock === null || $mock === '' ? null : (string) $mock;
    }

    public function issuePhoneOtp(User $user, string $phone): PhoneOtp
    {
        return PhoneOtp::create([
            'user_id' => $user->id,
            'phone' => $phone,
            'code' => $this->generateCode(),
            'expires_at' => now()->addSeconds($this->ttlSeconds),
            'sent_via' => 'mock',
        ]);
    }

    public function verifyPhoneOtp(User $user, string $code): bool
    {
        $otp = PhoneOtp::query()
            ->where('user_id', $user->id)
            ->where('code', $code)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->latest('id')
            ->first();

        if (! $otp) {
            return false;
        }

        $otp->forceFill(['verified_at' => now()])->save();
        $user->forceFill(['phone_verified_at' => now(), 'phone' => $otp->phone ?? $user->phone])->save();

        return true;
    }

    public function issueEmailOtp(User $user, string $email): EmailOtp
    {
        return EmailOtp::create([
            'user_id' => $user->id,
            'email' => $email,
            'code' => $this->generateCode(),
            'expires_at' => now()->addSeconds($this->ttlSeconds),
            'sent_via' => 'mock',
        ]);
    }

    public function verifyEmailOtp(User $user, string $code): bool
    {
        $otp = EmailOtp::query()
            ->where('user_id', $user->id)
            ->where('code', $code)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->latest('id')
            ->first();

        if (! $otp) {
            return false;
        }

        $otp->forceFill(['verified_at' => now()])->save();
        $user->forceFill(['email_verified_at' => now(), 'email' => $otp->email ?? $user->email])->save();

        return true;
    }

    public function ttlSeconds(): int
    {
        return $this->ttlSeconds;
    }

    public function secondsRemaining(CarbonInterface $expiresAt): int
    {
        return max(0, Carbon::now()->diffInSeconds($expiresAt, false));
    }

    protected function generateCode(): string
    {
        if ($this->mockCode !== null) {
            return $this->mockCode;
        }

        return (string) random_int(100000, 999999);
    }
}
