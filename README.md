# Laravel Phone Authentication Package

A professional Laravel package for phone-based authentication with OTP verification, following clean architecture principles and Laravel best practices.

## 🚀 Features

- **Phone-based Authentication** - Login and register using phone numbers
- **OTP Verification** - Secure one-time password verification
- **Password Reset** - Phone-based password reset with OTP
- **Clean Architecture** - Repository pattern with service layer
- **Laravel Standards** - Follows Laravel package conventions
- **Flexible Configuration** - Customizable phone column and settings
- **Multi-language Support** - Built-in internationalization
- **Professional Code** - SOLID principles and clean code practices

## 📋 Requirements

- PHP 8.0+
- Laravel 9.0+
- Laravel Sanctum (for API tokens)

## 🔧 Installation

### 1. Install the Package

```bash
composer require your-vendor/laravel-phone-auth
```

### 2. Publish Configuration

```bash
php artisan vendor:publish --tag=auth-package-config
```

### 3. Run Migrations

```bash
php artisan migrate
```

### 4. Publish Language Files (Optional)

```bash
php artisan vendor:publish --tag=auth-package-lang
```

## ⚙️ Configuration

### Environment Variables

Add these to your `.env` file:

```env
AUTH_PHONE_COLUMN=phone
AUTH_USER_MODEL=App\Models\User
```

### Configuration Options

Edit `config/auth-package.php`:

```php
return [
    // Phone column name in users table
    'phone_column' => env('AUTH_PHONE_COLUMN', 'phone'),
    
    // User model class
    'user_model' => env('AUTH_USER_MODEL', 'App\Models\User'),
    
    // OTP settings
    'otp_length' => 6,
    'otp_expiration_minutes' => 30,
    'max_verify_attempts' => 3,
    'max_resend_count' => 3,
    'resend_delay_minutes' => 1,
    'verify_delay_minutes' => 1,
    'suspend_time_minutes' => 120,
];
```

## 🛣️ API Endpoints

### Public Routes

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/auth/login` | Login with phone and password |
| POST | `/api/auth/register` | Register new user |
| POST | `/api/auth/request-password-reset` | Request password reset |
| POST | `/api/auth/verify-password-reset-otp` | Verify password reset OTP |
| POST | `/api/auth/resend-password-reset-otp` | Resend password reset OTP |

### Protected Routes

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/auth/verify-otp` | Verify signup OTP |
| POST | `/api/auth/resend-otp` | Resend signup OTP |
| POST | `/api/auth/change-password` | Change password |
| POST | `/api/auth/logout` | Logout user |
| GET | `/api/auth/profile` | Get user profile |

## 📝 Usage Examples

### Registration Flow

```php
// 1. Register user (Public)
$response = $this->post('/api/auth/register', [
    'name' => 'John Doe',
    'phone' => '+1234567890',
    'email' => 'john@example.com',
    'password' => 'password123',
]);

// 2. Verify OTP (Protected - requires token)
$response = $this->withToken($token)->post('/api/auth/verify-otp', [
    'otp' => '123456',
]);
```

### Login Flow

```php
// Login (Public)
$response = $this->post('/api/auth/login', [
    'phone' => '+1234567890',
    'password' => 'password123',
]);
```

### Password Reset Flow

```php
// 1. Request password reset (Public)
$response = $this->post('/api/auth/request-password-reset', [
    'phone' => '+1234567890',
]);

// 2. Verify password reset OTP (Public)
$response = $this->post('/api/auth/verify-password-reset-otp', [
    'phone' => '+1234567890',
    'otp' => '123456',
]);

// 3. Change password (Protected - requires token)
$response = $this->withToken($token)->post('/api/auth/change-password', [
    'password' => 'newpassword123',
]);
```



## 🧪 Testing

The package includes comprehensive testing support:

- **Unit Tests**: Test individual components
- **Feature Tests**: Test complete workflows
- **Integration Tests**: Test API endpoints

Run tests with:
```bash
composer test
```

## 🔧 Customization

### Custom Phone Column

If your users table uses a different column name:

```env
AUTH_PHONE_COLUMN=mobile_number
```

### Custom User Model

```env
AUTH_USER_MODEL=App\Models\CustomUser
```



## 🛡️ Security Features

- **OTP Expiration** - OTPs expire after configurable time
- **Rate Limiting** - Prevents abuse of OTP requests
- **Failed Attempt Tracking** - Tracks and limits failed attempts
- **Secure Token Management** - Uses Laravel Sanctum
- **Input Validation** - Comprehensive request validation
- **Phone Number Cleaning** - Standardizes phone number format

## 🔄 Error Handling

The package provides consistent error responses:

```json
{
    "success": false,
    "message": "Invalid phone number",
    "errors": {
        "phone": ["The phone number format is invalid"]
    }
}
```



## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests
5. Submit a pull request

## 📄 License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## 🆘 Support

For support, please open an issue on GitHub or contact the maintainers.

---

**Built with ❤️ following Laravel best practices and clean architecture principles.** 