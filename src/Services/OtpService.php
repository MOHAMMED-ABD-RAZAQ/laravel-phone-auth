<?php

namespace Package\Auth\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Package\Auth\Contracts\OtpRepositoryInterface;
use Package\Auth\Models\UserOtp;

class OtpService
{
    protected OtpRepositoryInterface $otpRepository;
    protected SmsService $smsService;

    public function __construct(
        OtpRepositoryInterface $otpRepository,
        SmsService $smsService
    ) {
        $this->otpRepository = $otpRepository;
        $this->smsService = $smsService;
    }

    /**
     * Generate OTP code
     */
    public function generateOtp(int $length = null): string
    {
        $length = $length ?? config('auth-package.otp_length', 6);
        return str_pad((string) rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }

    /**
     * Create OTP record
     */
    public function createOtpRecord(int $userId, string $phone, int $otpType, string $otpCode): Model
    {
        return $this->otpRepository->create([
            'user_id' => $userId,
            'send_to_phone' => $phone,
            'otp_type' => $otpType,
            'otp_code' => $otpCode,
            'is_otp_verified' => false,
            'failed_attempts_count' => 0,
            'last_failed_attempt_date' => null,
            'last_resend_date' => now(),
            'resend_count' => 1,
        ]);
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Model $userOtp, string $otpCode): bool
    {
        $otpService = new OtpValidator($userOtp);

        // Check max attempts
        if ($otpService->isExecuteMaxAttempts()) {
            throw new \InvalidArgumentException(__('auth-package::auth.execute_max_verify_attempts', ['minutes' => 120]));
        }

        // Check if OTP is expired
        if ($otpService->checkIsExpire()) {
            throw new \InvalidArgumentException(__('auth-package::auth.expired'));
        }

        // Check if verify is too fast
        if ($otpService->isVerifyCheckTooFast()) {
            throw new \InvalidArgumentException(__('auth-package::auth.verify_is_too_fast', ['minutes' => 1]));
        }

        // Convert Arabic numbers to English
        $cleanOtp = $this->arabicNumberToEnglish($otpCode);

        if ($userOtp->otp_code !== $cleanOtp) {
            $this->otpRepository->incrementFailedAttempts($userOtp);
            return false;
        }

        return true;
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Model $userOtp): void
    {
        $otpService = new OtpValidator($userOtp);

        // Check max attempts
        if ($otpService->isExecuteMaxAttempts()) {
            throw new \InvalidArgumentException(__('auth-package::auth.execute_max_verify_attempts', ['minutes' => 120]));
        }

        // Check max resend
        if ($otpService->isExecuteMaxResend()) {
            throw new \InvalidArgumentException(__('auth-package::auth.execute_max_resend', ['minutes' => 120]));
        }

        // Check if resend is too fast
        if ($otpService->isResendRequestTooFast()) {
            throw new \InvalidArgumentException(__('auth-package::auth.resend_is_too_fast', ['minutes' => 60]));
        }

        // Generate new OTP
        $otpCode = $this->generateOtp();

        // Update existing OTP record
        $this->otpRepository->update($userOtp, [
            'otp_code' => $otpCode,
            'last_resend_date' => now(),
            'resend_count' => $userOtp->resend_count + 1,
        ]);

        // Send SMS
        $this->smsService->sendOtp($userOtp->send_to_phone, $otpCode, 'verification');
    }

    /**
     * Find OTP by user ID and type
     */
    public function findByUserIdAndType(int $userId, int $type): ?Model
    {
        return $this->otpRepository->findByUserIdAndType($userId, $type);
    }

    /**
     * Find OTP by phone and type
     */
    public function findByPhoneAndType(string $phone, int $type): ?Model
    {
        return $this->otpRepository->findByPhoneAndType($phone, $type);
    }

    /**
     * Check if unverified password reset OTP exists
     */
    public function existsUnverifiedPasswordResetOtp(string $phone): bool
    {
        return $this->otpRepository->existsUnverifiedByPhoneAndType($phone, UserOtp::RESTORE_PASSWORD_TYPE);
    }

    /**
     * Delete OTP record
     */
    public function deleteOtp(Model $userOtp): bool
    {
        return $this->otpRepository->delete($userOtp);
    }

    /**
     * Convert Arabic numbers to English numbers
     */
    public function arabicNumberToEnglish(string $number): string
    {
        $range = range(0, 9);
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

        return str_replace($arabic, $range, $number);
    }
}

/**
 * OTP Validator class for validation logic
 */
class OtpValidator
{
    private $userOtpModel;

    public function __construct($userOtpModel)
    {
        $this->userOtpModel = $userOtpModel;
    }

    /**
     * Check if otp is expired
     */
    public function checkIsExpire(): bool
    {
        $currentTime = Carbon::now();
        $otpExpireAt = Carbon::parse($this->userOtpModel->last_resend_date)
            ->addMinutes(config('auth-package.otp_expiration_minutes', 30));

        return $currentTime->gte($otpExpireAt);
    }

    /**
     * Check if otp execute max attempts
     */
    public function isExecuteMaxAttempts(): bool
    {
        $failedData = $this->userOtpModel->last_failed_attempt_date;
        $failedAttempts = $this->userOtpModel->failed_attempts_count;
        $maxAllowedAttempts = config('auth-package.max_verify_attempts', 3);

        if ($failedAttempts >= $maxAllowedAttempts) {
            $failedData = Carbon::parse($failedData);
            $period = $failedData->diffInMinutes(Carbon::now());
            $suspendTime = config('auth-package.suspend_time_minutes', 120);

            if ($period >= $suspendTime) {
                // Reset attempts after suspend time is finished
                $this->userOtpModel->last_failed_attempt_date = null;
                $this->userOtpModel->failed_attempts_count = 0;
                $this->userOtpModel->save();

                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Check if otp execute max resend
     */
    public function isExecuteMaxResend(): bool
    {
        $totalOtpResendToUser = $this->userOtpModel->resend_count;
        $resendDate = $this->userOtpModel->last_resend_date;
        $maxResendCount = config('auth-package.max_resend_count', 3);

        if ($totalOtpResendToUser >= $maxResendCount) {
            $resendDate = Carbon::parse($resendDate);
            $period = $resendDate->diffInMinutes(Carbon::now());
            $suspendTime = config('auth-package.suspend_time_minutes', 120);

            if ($period >= $suspendTime) {
                // Reset resend counter after suspend time is finished
                $this->userOtpModel->last_resend_date = null;
                $this->userOtpModel->resend_count = 0;
                $this->userOtpModel->save();

                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Check if otp resend request is too fast
     */
    public function isResendRequestTooFast(): bool
    {
        $resendDate = $this->userOtpModel->last_resend_date;

        if ($resendDate == null) {
            return false;
        }

        $resendDate = Carbon::parse($resendDate);
        $period = $resendDate->diffInSeconds(Carbon::now());
        $delay = config('auth-package.resend_delay_minutes', 1) * 60;

        if ($period < 0) {
            return true;
        }

        return $period < $delay;
    }

    /**
     * Check if otp verify check is too fast
     */
    public function isVerifyCheckTooFast(): bool
    {
        $failedDate = $this->userOtpModel->last_failed_attempt_date;

        if ($failedDate == null) {
            return false;
        }

        $failedDate = Carbon::parse($failedDate);
        $period = $failedDate->diffInSeconds(Carbon::now());
        $delay = config('auth-package.verify_delay_minutes', 1) * 60;

        if ($period < 0) {
            return true;
        }

        return $period < $delay;
    }
}