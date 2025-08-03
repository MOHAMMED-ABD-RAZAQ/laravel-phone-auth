<?php

return [
    // General messages
    'failed' => 'بيانات الاعتماد هذه لا تتطابق مع سجلاتنا.',
    'password' => 'كلمة المرور المقدمة غير صحيحة.',
    'throttle' => 'محاولات تسجيل دخول كثيرة جداً. يرجى المحاولة مرة أخرى في :seconds ثانية.',

    // Phone number validation
    'invalid_phone_number' => 'تنسيق رقم الهاتف غير صحيح.',
    'phone_required' => 'رقم الهاتف مطلوب.',
    'phone_must_be_string' => 'رقم الهاتف يجب أن يكون نصاً.',
    'phone_already_registered' => 'رقم الهاتف هذا مسجل بالفعل.',

    // Password validation
    'password_required' => 'كلمة المرور مطلوبة.',
    'password_must_be_string' => 'كلمة المرور يجب أن تكون نصاً.',
    'password_min_length' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل.',
    'password_confirmation_required' => 'تأكيد كلمة المرور مطلوب.',
    'password_confirmation_must_be_string' => 'تأكيد كلمة المرور يجب أن يكون نصاً.',
    'password_confirmation_min_length' => 'تأكيد كلمة المرور يجب أن يكون 6 أحرف على الأقل.',
    'password_confirmation_mismatch' => 'تأكيد كلمة المرور لا يتطابق.',

    // Name validation
    'name_required' => 'الاسم مطلوب.',
    'name_must_be_string' => 'الاسم يجب أن يكون نصاً.',
    'name_max_length' => 'الاسم لا يمكن أن يكون أكثر من 255 حرف.',

    // Email validation
    'email_invalid' => 'البريد الإلكتروني يجب أن يكون عنوان بريد إلكتروني صحيح.',
    'email_max_length' => 'البريد الإلكتروني لا يمكن أن يكون أكثر من 255 حرف.',

    // Platform validation
    'platform_invalid' => 'المنصة يجب أن تكون واحدة من: ios, android, web.',

    // OTP validation
    'otp_required' => 'رمز التحقق مطلوب.',
    'otp_must_be_string' => 'رمز التحقق يجب أن يكون نصاً.',
    'otp_must_be_six_digits' => 'رمز التحقق يجب أن يكون 6 أرقام بالضبط.',

    // Login
    'login_failed' => 'فشل تسجيل الدخول. يرجى التحقق من بيانات الاعتماد.',

    // Registration
    'register_successfully' => 'التسجيل ناجح. يرجى التحقق من رقم هاتفك.',
    'register_failed' => 'فشل التسجيل. يرجى المحاولة مرة أخرى.',

    // OTP verification
    'otp_verification_failed' => 'فشل التحقق من رمز التحقق. يرجى التحقق من الرمز.',
    'otp_verification_success' => 'تم التحقق من رمز التحقق بنجاح.',
    'otp_not_found' => 'رمز التحقق غير موجود. يرجى طلب رمز جديد.',
    'otp_resent_successfully' => 'تم إعادة إرسال رمز التحقق بنجاح إلى :phone.',
    'otp_resend_failed' => 'فشل في إعادة إرسال رمز التحقق. يرجى المحاولة مرة أخرى.',

    // Password reset
    'password_reset_request_failed' => 'فشل طلب إعادة تعيين كلمة المرور. يرجى المحاولة مرة أخرى.',
    'password_reset_already_requested' => 'تم طلب إعادة تعيين كلمة المرور بالفعل. يرجى التحقق من هاتفك.',
    'password_reset_otp_sent' => 'تم إرسال رمز التحقق لإعادة تعيين كلمة المرور إلى :phone.',
    'password_reset_otp_verified' => 'تم التحقق من رمز التحقق لإعادة تعيين كلمة المرور بنجاح.',
    'password_reset_verification_failed' => 'فشل التحقق من إعادة تعيين كلمة المرور.',
    'password_reset_otp_resent' => 'تم إعادة إرسال رمز التحقق لإعادة تعيين كلمة المرور إلى :phone.',
    'password_reset_otp_resend_failed' => 'فشل في إعادة إرسال رمز التحقق لإعادة تعيين كلمة المرور.',

    // Password change
    'password_changed_successfully' => 'تم تغيير كلمة المرور بنجاح.',
    'password_change_failed' => 'فشل تغيير كلمة المرور. يرجى المحاولة مرة أخرى.',

    // Logout
    'logout_successfully' => 'تم تسجيل الخروج بنجاح.',
    'logout_failed' => 'فشل تسجيل الخروج. يرجى المحاولة مرة أخرى.',

    // Profile
    'profile_fetch_failed' => 'فشل في جلب الملف الشخصي. يرجى المحاولة مرة أخرى.',

    // User
    'user_not_found' => 'المستخدم غير موجود.',

    // OTP messages
    'execute_max_verify_attempts' => 'تم تجاوز الحد الأقصى لمحاولات التحقق. يرجى المحاولة مرة أخرى في :minutes دقيقة.',
    'execute_max_resend' => 'تم تجاوز الحد الأقصى لمحاولات إعادة الإرسال. يرجى المحاولة مرة أخرى في :minutes دقيقة.',
    'resend_is_too_fast' => 'يرجى الانتظار :minutes دقيقة قبل طلب رمز تحقق جديد.',
    'expired' => 'رمز التحقق منتهي الصلاحية. يرجى طلب رمز جديد.',

    // Request password
    'request_password_wrong_phone' => 'لم يتم العثور على مستخدم بهذا رقم الهاتف.',
    'request_password_otp_already_sent' => 'تم إرسال رمز التحقق لإعادة تعيين كلمة المرور إلى هذا رقم الهاتف بالفعل.',

    // OTP SMS messages
    'otp_sms_message' => 'رمز التحقق الخاص بك هو :otp. صالح لمدة :minutes دقيقة.',
    'password_reset_sms_message' => 'رمز إعادة تعيين كلمة المرور الخاص بك هو :otp. صالح لمدة :minutes دقيقة.',

    // Email subjects and messages
    'otp_verification_subject' => 'رمز التحقق من الهاتف',
    'otp_verification_greeting' => 'مرحباً :name,',
    'otp_verification_message' => 'رمز التحقق من هاتفك هو:',
    'otp_verification_expiry' => 'هذا الرمز سينتهي في :minutes دقيقة.',
    'otp_verification_footer' => 'إذا لم تطلب هذا الرمز، يرجى تجاهل هذه الرسالة.',

    'password_reset_subject' => 'رمز إعادة تعيين كلمة المرور',
    'password_reset_greeting' => 'مرحباً :name,',
    'password_reset_message' => 'رمز إعادة تعيين كلمة المرور الخاص بك هو:',
    'password_reset_expiry' => 'هذا الرمز سينتهي في :minutes دقيقة.',
    'password_reset_footer' => 'إذا لم تطلب هذا الرمز، يرجى تجاهل هذه الرسالة.',
]; 