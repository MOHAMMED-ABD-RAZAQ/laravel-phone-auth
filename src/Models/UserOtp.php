<?php

namespace Package\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = null;

    /**
     * Predefined otp types.
     *
     * @var int
     */
    public const SIGNUP_TYPE = 0;
    public const RESTORE_PASSWORD_TYPE = 1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "user_otps";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'otp_type',
        'otp_code',
        'send_to_phone',
        'is_otp_verified',
        'failed_attempts_count',
        'last_failed_attempt_date',
        'last_resend_date',
        'resend_count',
        'verified_at',
        'created_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'last_failed_attempt_date',
        'last_resend_date',
        'verified_at',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = ["created_at"];

    /**
     * Get the user that owns the OTP.
     */
    public function user()
    {
        $userModel = config('auth-package.user_model', 'App\Models\User');
        return $this->belongsTo($userModel);
    }

    /**
     * Scope a query to only include signup OTPs.
     */
    public function scopeSignup($query)
    {
        return $query->where('otp_type', self::SIGNUP_TYPE);
    }

    /**
     * Scope a query to only include password reset OTPs.
     */
    public function scopePasswordReset($query)
    {
        return $query->where('otp_type', self::RESTORE_PASSWORD_TYPE);
    }

    /**
     * Scope a query to only include unverified OTPs.
     */
    public function scopeUnverified($query)
    {
        return $query->where('is_otp_verified', false);
    }

    /**
     * Scope a query to only include verified OTPs.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_otp_verified', true);
    }

    /**
     * Check if OTP is expired.
     */
    public function isExpired(): bool
    {
        return $this->last_resend_date->addMinutes(30)->isPast();
    }

    /**
     * Check if OTP is verified.
     */
    public function isVerified(): bool
    {
        return $this->is_otp_verified;
    }

    /**
     * Mark OTP as verified.
     */
    public function markAsVerified(): void
    {
        $this->update([
            'is_otp_verified' => true,
            'verified_at' => now(),
        ]);
    }
} 