<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User Model Configuration
    |--------------------------------------------------------------------------
    |
    | This option controls the user model class that will be used for
    | authentication. You can change this to use your own user model.
    |
    */
    'user_model' => env('AUTH_USER_MODEL', 'App\Models\User'),

    /*
    |--------------------------------------------------------------------------
    | User Resource Configuration
    |--------------------------------------------------------------------------
    |
    | This option controls the user resource class that will be used for
    | API responses. You can change this to use your own resource class.
    |
    */
    'user_resource' => env('AUTH_USER_RESOURCE', 'App\Http\Resources\UserResource'),

    /*
    |--------------------------------------------------------------------------
    | Phone Column Configuration
    |--------------------------------------------------------------------------
    |
    | This option controls the phone column name in the users table.
    | You can change this if your users table uses a different column name.
    |
    */
    'phone_column' => env('AUTH_PHONE_COLUMN', 'phone'),

    /*
    |--------------------------------------------------------------------------
    | Additional User Fields
    |--------------------------------------------------------------------------
    |
    | This option controls which additional fields will be saved when
    | registering a user. Add your custom fields here.
    |
    */
    'additional_user_fields' => [
        // Add your custom fields here
        // 'field_name',
    ],

    /*
    |--------------------------------------------------------------------------
    | OTP Configuration
    |--------------------------------------------------------------------------
    |
    | These options control the OTP generation and validation settings.
    |
    */
    'otp_length' => env('AUTH_OTP_LENGTH', 6),
    'otp_expiration_minutes' => env('AUTH_OTP_EXPIRATION_MINUTES', 30),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    |
    | These options control the rate limiting for OTP verification and resend.
    |
    */
    'max_verify_attempts' => env('AUTH_MAX_VERIFY_ATTEMPTS', 3),
    'max_resend_count' => env('AUTH_MAX_RESEND_COUNT', 3),
    'suspend_time_minutes' => env('AUTH_SUSPEND_TIME_MINUTES', 120),
    'resend_delay_minutes' => env('AUTH_RESEND_DELAY_MINUTES', 0),
    'verify_delay_minutes' => env('AUTH_VERIFY_DELAY_MINUTES', 0),

    /*
    |--------------------------------------------------------------------------
    | SMS Configuration
    |--------------------------------------------------------------------------
    |
    | These options control the SMS service configuration.
    |
    */
    'sms' => [
        'driver' => env('AUTH_SMS_DRIVER', 'twilio'),
        'twilio' => [
            'account_sid' => env('TWILIO_ACCOUNT_SID'),
            'auth_token' => env('TWILIO_AUTH_TOKEN'),
            'from_number' => env('TWILIO_FROM_NUMBER'),
        ],
        'clicksend' => [
            'username' => env('CLICKSEND_USERNAME'),
            'api_key' => env('CLICKSEND_API_KEY'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes Configuration
    |--------------------------------------------------------------------------
    |
    | These options control the route prefixes and middleware.
    |
    */
    'routes' => [
        'prefix' => env('AUTH_ROUTES_PREFIX', 'api/auth'),
        'middleware' => [
            'api',
        ],
        'auth_middleware' => [
            'auth:sanctum',
        ],
    ],
]; 