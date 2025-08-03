<?php

namespace Package\Auth\Traits;

use Illuminate\Http\Request;

trait HasPhoneAuth
{
    /**
     * Get the user model class name.
     *
     * @return string
     */
    protected function getUserModel(): string
    {
        return config('auth-package.user_model', 'App\Models\User');
    }

    /**
     * Get the user resource class name.
     *
     * @return string
     */
    protected function getUserResource(): string
    {
        return config('auth-package.user_resource', 'App\Http\Resources\UserResource');
    }

    /**
     * Get additional user data from request.
     *
     * @param Request $request
     * @return array
     */
    protected function getAdditionalUserData(Request $request): array
    {
        $additionalFields = config('auth-package.additional_user_fields', []);
        $data = [];

        foreach ($additionalFields as $field) {
            if ($request->has($field)) {
                $data[$field] = $request->get($field);
            }
        }

        return $data;
    }



    /**
     * Get user resource instance.
     *
     * @param mixed $user
     * @return mixed
     */
    protected function getUserResourceInstance($user)
    {
        $resourceClass = $this->getUserResource();
        
        if (class_exists($resourceClass)) {
            return new $resourceClass($user);
        }

        return $user;
    }

    /**
     * Check if user is verified.
     *
     * @param mixed $user
     * @return bool
     */
    protected function isUserVerified($user): bool
    {
        return $user->verified_at !== null;
    }

    /**
     * Get OTP expiration time in minutes.
     *
     * @return int
     */
    protected function getOtpExpirationTime(): int
    {
        return config('auth-package.otp_expiration_minutes', 30);
    }

    /**
     * Get OTP length.
     *
     * @return int
     */
    protected function getOtpLength(): int
    {
        return config('auth-package.otp_length', 6);
    }

    /**
     * Get max verification attempts.
     *
     * @return int
     */
    protected function getMaxVerifyAttempts(): int
    {
        return config('auth-package.max_verify_attempts', 3);
    }

    /**
     * Get max resend count.
     *
     * @return int
     */
    protected function getMaxResendCount(): int
    {
        return config('auth-package.max_resend_count', 3);
    }

    /**
     * Get suspend time in minutes.
     *
     * @return int
     */
    protected function getSuspendTime(): int
    {
        return config('auth-package.suspend_time_minutes', 120);
    }

    /**
     * Get resend delay in minutes.
     *
     * @return int
     */
    protected function getResendDelay(): int
    {
        return config('auth-package.resend_delay_minutes', 1);
    }

    /**
     * Get verify delay in minutes.
     *
     * @return int
     */
    protected function getVerifyDelay(): int
    {
        return config('auth-package.verify_delay_minutes', 1);
    }
} 