<?php

namespace Package\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPasswordResetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'phone' => 'required|string',
            'otp' => 'required|string|size:6',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'phone.required' => __('auth-package::auth.phone_required'),
            'phone.string' => __('auth-package::auth.phone_must_be_string'),
            'otp.required' => __('auth-package::auth.otp_required'),
            'otp.string' => __('auth-package::auth.otp_must_be_string'),
            'otp.size' => __('auth-package::auth.otp_must_be_six_digits'),
        ];
    }
} 