# API Documentation

## Overview

This document provides comprehensive API documentation for the Laravel Phone Authentication Package.

## Base URL

```
https://your-domain.com/api/auth
```

## Authentication

All protected endpoints require a Bearer token in the Authorization header:

```
Authorization: Bearer {token}
```

## Endpoints

### 1. User Registration

**POST** `/register`

Register a new user with phone number.

#### Request Body

```json
{
    "name": "John Doe",
    "phone": "+1234567890",
    "email": "john@example.com",
    "password": "password123"
}
```

#### Response (200)

```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "phone": "+1234567890",
            "email": "john@example.com",
            "verified_at": null
        },
        "token": "1|abc123def456..."
    },
    "message": "User registered successfully"
}
```

#### Validation Rules

- `name`: required, string, max:255
- `phone`: required, string, unique phone number
- `email`: required, email, unique
- `password`: required, string, min:8

---

### 2. User Login

**POST** `/login`

Authenticate user with phone and password.

#### Request Body

```json
{
    "phone": "+1234567890",
    "password": "password123"
}
```

#### Response (200)

```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "phone": "+1234567890",
            "email": "john@example.com"
        },
        "token": "2|def456ghi789..."
    }
}
```

#### Validation Rules

- `phone`: required, string
- `password`: required, string

---

### 3. OTP Verification

**POST** `/verify-otp`

Verify registration OTP (requires authentication).

#### Request Body

```json
{
    "otp": "123456"
}
```

#### Response (200)

```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "phone": "+1234567890",
            "email": "john@example.com",
            "verified_at": "2024-01-01T12:00:00.000000Z"
        }
    },
    "message": "OTP verification successful"
}
```

#### Validation Rules

- `otp`: required, string, size:6

---

### 4. Resend OTP

**POST** `/resend-otp`

Resend registration OTP (requires authentication).

#### Request Body

```json
{}
```

#### Response (200)

```json
{
    "success": true,
    "message": "OTP resent successfully to +1234567890"
}
```

---

### 5. Request Password Reset

**POST** `/request-password-reset`

Request password reset OTP.

#### Request Body

```json
{
    "phone": "+1234567890"
}
```

#### Response (200)

```json
{
    "success": true,
    "message": "Password reset OTP sent to +1234567890"
}
```

#### Validation Rules

- `phone`: required, string

---

### 6. Verify Password Reset OTP

**POST** `/verify-password-reset-otp`

Verify password reset OTP.

#### Request Body

```json
{
    "phone": "+1234567890",
    "otp": "123456"
}
```

#### Response (200)

```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "phone": "+1234567890"
        },
        "token": "3|jkl789mno012..."
    },
    "message": "Password reset OTP verified"
}
```

#### Validation Rules

- `phone`: required, string
- `otp`: required, string, size:6

---

### 7. Resend Password Reset OTP

**POST** `/resend-password-reset-otp`

Resend password reset OTP.

#### Request Body

```json
{
    "phone": "+1234567890"
}
```

#### Response (200)

```json
{
    "success": true,
    "message": "Password reset OTP resent to +1234567890"
}
```

#### Validation Rules

- `phone`: required, string

---

### 8. Change Password

**POST** `/change-password`

Change user password (requires authentication).

#### Request Body

```json
{
    "password": "newpassword123"
}
```

#### Response (200)

```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "phone": "+1234567890"
        },
        "token": "4|pqr012stu345..."
    },
    "message": "Password changed successfully"
}
```

#### Validation Rules

- `password`: required, string, min:8

---

### 9. Logout

**POST** `/logout`

Logout user (requires authentication).

#### Request Body

```json
{}
```

#### Response (200)

```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

---

### 10. Get Profile

**GET** `/profile`

Get authenticated user profile.

#### Response (200)

```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "phone": "+1234567890",
            "email": "john@example.com",
            "verified_at": "2024-01-01T12:00:00.000000Z"
        }
    }
}
```

## Error Responses

### Validation Error (422)

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "phone": ["The phone number format is invalid"],
        "password": ["The password must be at least 8 characters"]
    }
}
```

### Authentication Error (401)

```json
{
    "success": false,
    "message": "Unauthenticated"
}
```

### Not Found Error (404)

```json
{
    "success": false,
    "message": "User not found"
}
```

### OTP Error (400)

```json
{
    "success": false,
    "message": "OTP verification failed"
}
```

### Rate Limit Error (429)

```json
{
    "success": false,
    "message": "Too many attempts. Please try again in 2 minutes"
}
```

## Status Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 404 | Not Found |
| 422 | Validation Error |
| 429 | Too Many Requests |
| 500 | Server Error |

## Rate Limiting

- **OTP Verification**: 3 attempts per 2 minutes
- **OTP Resend**: 3 attempts per 2 minutes
- **Login**: 5 attempts per 15 minutes

## Security Features

- **Token Expiration**: Tokens expire after 24 hours
- **OTP Expiration**: OTPs expire after 30 minutes
- **Rate Limiting**: Prevents abuse
- **Input Validation**: Comprehensive validation
- **Phone Cleaning**: Standardizes phone numbers
- **Failed Attempt Tracking**: Limits brute force attacks

## Phone Number Format

The package accepts phone numbers in various formats and automatically cleans them:

- `+1234567890`
- `1234567890`
- `+1 (234) 567-8900`
- `+1-234-567-8900`

All phone numbers are stored in international format: `+1234567890`

## Testing

### cURL Examples

#### Register User

```bash
curl -X POST http://your-domain.com/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "phone": "+1234567890",
    "email": "john@example.com",
    "password": "password123"
  }'
```

#### Login

```bash
curl -X POST http://your-domain.com/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "+1234567890",
    "password": "password123"
  }'
```

#### Verify OTP

```bash
curl -X POST http://your-domain.com/api/auth/verify-otp \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "otp": "123456"
  }'
```

#### Get Profile

```bash
curl -X GET http://your-domain.com/api/auth/profile \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## SDK Examples

### JavaScript (Axios)

```javascript
import axios from 'axios';

const api = axios.create({
    baseURL: 'https://your-domain.com/api/auth'
});

// Register
const register = async (userData) => {
    const response = await api.post('/register', userData);
    return response.data;
};

// Login
const login = async (credentials) => {
    const response = await api.post('/login', credentials);
    return response.data;
};

// Verify OTP
const verifyOtp = async (otp, token) => {
    const response = await api.post('/verify-otp', { otp }, {
        headers: { Authorization: `Bearer ${token}` }
    });
    return response.data;
};
```

### PHP (Guzzle)

```php
use GuzzleHttp\Client;

$client = new Client([
    'base_uri' => 'https://your-domain.com/api/auth'
]);

// Register
$response = $client->post('/register', [
    'json' => [
        'name' => 'John Doe',
        'phone' => '+1234567890',
        'email' => 'john@example.com',
        'password' => 'password123'
    ]
]);

$data = json_decode($response->getBody(), true);
```

## Support

For API support, please refer to the main documentation or contact the maintainers. 