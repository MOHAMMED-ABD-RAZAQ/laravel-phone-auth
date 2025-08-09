<?php

namespace Package\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'password' => 'required|string|min:6',
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
            'password.required' => __('auth-package::auth.password_required'),
            'password.string' => __('auth-package::auth.password_must_be_string'),
            'password.min' => __('auth-package::auth.password_min_length'),
        ];
    }
} 