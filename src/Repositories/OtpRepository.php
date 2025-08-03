<?php

namespace Package\Auth\Repositories;

use Package\Auth\Models\UserOtp;
use Illuminate\Database\Eloquent\Model;
use Package\Auth\Contracts\OtpRepositoryInterface;

class OtpRepository implements OtpRepositoryInterface
{
    /**
     * Find OTP by user ID and type
     */
    public function findByUserIdAndType(int $userId, int $type): ?Model
    {
        return UserOtp::query()
            ->where('user_id', $userId)
            ->where('otp_type', $type)
            ->first();
    }

    /**
     * Find OTP by phone and type
     */
    public function findByPhoneAndType(string $phone, int $type): ?Model
    {
        return UserOtp::query()
            ->where('send_to_phone', $phone)
            ->where('otp_type', $type)
            ->first();
    }

    /**
     * Find unverified OTP by phone and type
     */
    public function findUnverifiedByPhoneAndType(string $phone, int $type): ?Model
    {
        return UserOtp::query()
            ->where('send_to_phone', $phone)
            ->where('otp_type', $type)
            ->where('is_otp_verified', false)
            ->first();
    }

    /**
     * Check if unverified OTP exists by phone and type
     */
    public function existsUnverifiedByPhoneAndType(string $phone, int $type): bool
    {
        return UserOtp::query()
            ->where('send_to_phone', $phone)
            ->where('otp_type', $type)
            ->where('is_otp_verified', false)
            ->exists();
    }

    /**
     * Create OTP record
     */
    public function create(array $data): Model
    {
        return UserOtp::create($data);
    }

    /**
     * Update OTP record
     */
    public function update(Model $otp, array $data): bool
    {
        return $otp->update($data);
    }

    /**
     * Delete OTP record
     */
    public function delete(Model $otp): bool
    {
        return $otp->delete();
    }

    /**
     * Increment failed attempts
     */
    public function incrementFailedAttempts(Model $otp): void
    {
        $otp->increment('failed_attempts_count');
        $otp->update(['last_failed_attempt_date' => now()]);
    }
} 