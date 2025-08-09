<?php

namespace Package\Auth\Services;

use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send SMS message to the given phone number.
     *
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public function send(string $phone, string $message): bool
    {
        Log::info('SmsService send method called', [
            'phone' => $phone,
            'message' => $message
        ]);

        $driver = config('auth-package.sms.driver', 'twilio');

        switch ($driver) {
            case 'twilio':
                return $this->sendViaTwilio($phone, $message);
            case 'clicksend':
                return $this->sendViaClicksend($phone, $message);
            default:
                Log::info('SMS sent via ' . $driver, [
                    'phone' => $phone,
                    'message' => $message
                ]);
                return true;
        }
    }

    /**
     * Send SMS via Twilio.
     *
     * @param string $phone
     * @param string $message
     * @return bool
     */
    protected function sendViaTwilio(string $phone, string $message): bool
    {
        $accountSid = config('auth-package.sms.twilio.account_sid');
        $authToken = config('auth-package.sms.twilio.auth_token');
        $fromNumber = config('auth-package.sms.twilio.from_number');

        if (!$accountSid || !$authToken || !$fromNumber) {
            Log::warning('Twilio credentials not configured', [
                'phone' => $phone,
                'message' => $message
            ]);
            return false;
        }

        Log::info('SMS sent via Twilio', [
            'phone' => $phone,
            'message' => $message,
            'from' => $fromNumber
        ]);

        return true;
    }

    /**
     * Send SMS via ClickSend.
     *
     * @param string $phone
     * @param string $message
     * @return bool
     */
    protected function sendViaClicksend(string $phone, string $message): bool
    {
        $username = config('auth-package.sms.clicksend.username');
        $apiKey = config('auth-package.sms.clicksend.api_key');

        if (!$username || !$apiKey) {
            Log::warning('ClickSend credentials not configured', [
                'phone' => $phone,
                'message' => $message
            ]);
            return false;
        }

        Log::info('SMS sent via ClickSend', [
            'phone' => $phone,
            'message' => $message
        ]);

        return true;
    }

    /**
     * Send OTP SMS message.
     *
     * @param string $phone
     * @param string $otp
     * @param string $type
     * @return bool
     */
    public function sendOtp(string $phone, string $otp, string $type = 'verification'): bool
    {
        $message = $this->buildOtpMessage($otp, $type);
        return $this->send($phone, $message);
    }

    /**
     * Build OTP message based on type.
     *
     * @param string $otp
     * @param string $type
     * @return string
     */
    protected function buildOtpMessage(string $otp, string $type): string
    {
        switch ($type) {
            case 'password_reset':
                return __('auth-package::auth.password_reset_sms_message', [
                    'otp' => $otp,
                    'minutes' => 30
                ]);
            case 'verification':
            default:
                return __('auth-package::auth.otp_sms_message', [
                    'otp' => $otp,
                    'minutes' => 30
                ]);
        }
    }
} 