<?php

return [
    // General messages
    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    // Phone number validation
    'invalid_phone_number' => 'Invalid phone number format.',
    'phone_required' => 'Phone number is required.',
    'phone_must_be_string' => 'Phone number must be a string.',
    'phone_already_registered' => 'This phone number is already registered.',

    // Password validation
    'password_required' => 'Password is required.',
    'password_must_be_string' => 'Password must be a string.',
    'password_min_length' => 'Password must be at least 6 characters.',
    'password_confirmation_required' => 'Password confirmation is required.',
    'password_confirmation_must_be_string' => 'Password confirmation must be a string.',
    'password_confirmation_min_length' => 'Password confirmation must be at least 6 characters.',
    'password_confirmation_mismatch' => 'Password confirmation does not match.',

    // Name validation
    'name_required' => 'Name is required.',
    'name_must_be_string' => 'Name must be a string.',
    'name_max_length' => 'Name may not be greater than 255 characters.',

    // Email validation
    'email_invalid' => 'Email must be a valid email address.',
    'email_max_length' => 'Email may not be greater than 255 characters.',

    // Platform validation
    'platform_invalid' => 'Platform must be one of: ios, android, web.',

    // OTP validation
    'otp_required' => 'OTP code is required.',
    'otp_must_be_string' => 'OTP code must be a string.',
    'otp_must_be_six_digits' => 'OTP code must be exactly 6 digits.',

    // Login
    'login_failed' => 'Login failed. Please check your credentials.',

    // Registration
    'register_successfully' => 'Registration successful. Please verify your phone number.',
    'register_failed' => 'Registration failed. Please try again.',

    // OTP verification
    'otp_verification_failed' => 'OTP verification failed. Please check your code.',
    'otp_verification_success' => 'OTP verified successfully.',
    'otp_not_found' => 'OTP not found. Please request a new one.',
    'otp_resent_successfully' => 'OTP resent successfully to :phone.',
    'otp_resend_failed' => 'Failed to resend OTP. Please try again.',

    // Password reset
    'password_reset_request_failed' => 'Password reset request failed. Please try again.',
    'password_reset_already_requested' => 'Password reset already requested. Please check your phone.',
    'password_reset_otp_sent' => 'Password reset OTP sent to :phone.',
    'password_reset_otp_verified' => 'Password reset OTP verified successfully.',
    'password_reset_verification_failed' => 'Password reset verification failed.',
    'password_reset_otp_resent' => 'Password reset OTP resent to :phone.',
    'password_reset_otp_resend_failed' => 'Failed to resend password reset OTP.',

    // Password change
    'password_changed_successfully' => 'Password changed successfully.',
    'password_change_failed' => 'Password change failed. Please try again.',

    // Logout
    'logout_successfully' => 'Logged out successfully.',
    'logout_failed' => 'Logout failed. Please try again.',

    // Profile
    'profile_fetch_failed' => 'Failed to fetch profile. Please try again.',

    // User
    'user_not_found' => 'User not found.',

    // OTP messages
    'execute_max_verify_attempts' => 'Maximum verification attempts exceeded. Please try again in :minutes minutes.',
    'execute_max_resend' => 'Maximum resend attempts exceeded. Please try again in :minutes minutes.',
    'resend_is_too_fast' => 'Please wait :minutes minutes before requesting a new OTP.',
    'expired' => 'OTP has expired. Please request a new one.',

    // Request password
    'request_password_wrong_phone' => 'No user found with this phone number.',
    'request_password_otp_already_sent' => 'Password reset OTP already sent to this phone number.',

    // OTP SMS messages
    'otp_sms_message' => 'Your verification code is :otp. Valid for :minutes minutes.',
    'password_reset_sms_message' => 'Your password reset code is :otp. Valid for :minutes minutes.',

    // Email subjects and messages
    'otp_verification_subject' => 'Phone Verification OTP',
    'otp_verification_greeting' => 'Hello :name,',
    'otp_verification_message' => 'Your phone verification code is:',
    'otp_verification_expiry' => 'This code will expire in :minutes minutes.',
    'otp_verification_footer' => 'If you did not request this code, please ignore this message.',

    'password_reset_subject' => 'Password Reset OTP',
    'password_reset_greeting' => 'Hello :name,',
    'password_reset_message' => 'Your password reset code is:',
    'password_reset_expiry' => 'This code will expire in :minutes minutes.',
    'password_reset_footer' => 'If you did not request this code, please ignore this message.',
]; 