<?php

namespace Package\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Package\Auth\Rules\UniquePhoneNumber;

class RegisterRequest extends FormRequest
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
        $userModel = config('auth-package.user_model', 'App\Models\User');
        
        return [
            'name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                new UniquePhoneNumber($userModel),
            ],
            'email' => 'nullable|email|max:255',
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
            'name.required' => __('auth-package::auth.name_required'),
            'name.string' => __('auth-package::auth.name_must_be_string'),
            'name.max' => __('auth-package::auth.name_max_length'),
            'phone.required' => __('auth-package::auth.phone_required'),
            'phone.string' => __('auth-package::auth.phone_must_be_string'),
            'email.email' => __('auth-package::auth.email_invalid'),
            'email.max' => __('auth-package::auth.email_max_length'),
            'password.required' => __('auth-package::auth.password_required'),
            'password.string' => __('auth-package::auth.password_must_be_string'),
            'password.min' => __('auth-package::auth.password_min_length'),

            'platform.in' => __('auth-package::auth.platform_invalid'),
        ];
    }
} 