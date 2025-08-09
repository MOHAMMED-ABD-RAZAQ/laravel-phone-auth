<?php

namespace Package\Auth\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Package\Auth\Contracts\UserRepositoryInterface;
use Package\Auth\Helpers\PhoneCleanerHelper;
use Package\Auth\Helpers\ResponseBuilder;

class AuthService
{
    protected UserRepositoryInterface $userRepository;
    protected OtpService $otpService;
    protected SmsService $smsService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        OtpService $otpService,
        SmsService $smsService
    ) {
        $this->userRepository = $userRepository;
        $this->otpService = $otpService;
        $this->smsService = $smsService;
    }

    /**
     * Authenticate user with phone and password
     */
    public function authenticate(string $phone, string $password): array
    {
        $phoneNumber = (new PhoneCleanerHelper($phone))->clean();

        if (!$phoneNumber) {
            throw new \InvalidArgumentException(__('auth-package::auth.invalid_phone_number'));
        }

        $user = $this->userRepository->findByPhone($phoneNumber);

        if (!$user || !Hash::check($password, $user->password)) {
            throw new \InvalidArgumentException(__('auth-package::auth.failed'));
        }

        // Delete existing tokens
        $this->userRepository->deleteTokens($user);

        // Create new token
        $token = $this->userRepository->createToken($user, 'auth_token');

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * Register new user
     */
    public function register(array $data): array
    {
        $phoneNumber = (new PhoneCleanerHelper($data['phone']))->clean();
        
        if (!$phoneNumber) {
            throw new \InvalidArgumentException(__('auth-package::auth.invalid_phone_number'));
        }
        
        if ($this->userRepository->existsByPhone($phoneNumber)) {
            throw new \InvalidArgumentException(__('auth-package::auth.phone_already_registered'));
        }

        // Create user
        $user = $this->userRepository->create([
            'name' => $data['name'],
            'phone' => $phoneNumber,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'additional_fields' => $data['additional_fields'] ?? []
        ]);
        
        // Create token
        $token = $this->userRepository->createToken($user, 'auth_token');
        
        // Send OTP for verification
        $this->sendSignupOtp($user);
        
        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * Send signup OTP
     */
    public function sendSignupOtp(Model $user): void
    {
        $otpCode = $this->otpService->generateOtp();
        $phoneColumn = config('auth-package.phone_column', 'phone');
        
        // Create OTP record
        $this->otpService->createOtpRecord(
            $user->id,
            $user->{$phoneColumn},
            \Package\Auth\Models\UserOtp::SIGNUP_TYPE,
            $otpCode
        );
        
        // Send SMS
        $this->smsService->sendOtp($user->{$phoneColumn}, $otpCode, 'verification');
    }

    /**
     * Request password reset
     */
    public function requestPasswordReset(string $phone): void
    {
        $phoneNumber = (new PhoneCleanerHelper($phone))->clean();

        if (!$phoneNumber) {
            throw new \InvalidArgumentException(__('auth-package::auth.invalid_phone_number'));
        }

        $user = $this->userRepository->findByPhone($phoneNumber);

        if (!$user) {
            throw new \InvalidArgumentException(__('auth-package::auth.user_not_found'));
        }

        // Check if password reset already requested
        if ($this->otpService->existsUnverifiedPasswordResetOtp($phoneNumber)) {
            return; // Already requested
        }

        // Send password reset OTP
        $this->sendPasswordResetOtp($user);
    }

    /**
     * Send password reset OTP
     */
    public function sendPasswordResetOtp(Model $user): void
    {
        $otpCode = $this->otpService->generateOtp();
        $phoneColumn = config('auth-package.phone_column', 'phone');
        
        // Create OTP record
        $this->otpService->createOtpRecord(
            $user->id,
            $user->{$phoneColumn},
            \Package\Auth\Models\UserOtp::RESTORE_PASSWORD_TYPE,
            $otpCode
        );
        
        // Send SMS
        $this->smsService->sendOtp($user->{$phoneColumn}, $otpCode, 'password_reset');
    }

    /**
     * Change password
     */
    public function changePassword(Model $user, string $newPassword): array
    {
        $this->userRepository->update($user, [
            'password' => Hash::make($newPassword)
        ]);

        // Delete existing tokens
        $this->userRepository->deleteTokens($user);

        // Create new token
        $token = $this->userRepository->createToken($user, 'auth_token');

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * Logout user
     */
    public function logout(Model $user): void
    {
        $this->userRepository->deleteTokens($user);
    }

    /**
     * Mark user as verified
     */
    public function markUserAsVerified(Model $user): bool
    {
        return $this->userRepository->update($user, ['verified_at' => now()]);
    }

    /**
     * Find user by ID
     */
    public function findUserById(int $id): ?Model
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Create token for user
     */
    public function createToken(Model $user, string $name, array $abilities = []): string
    {
        return $this->userRepository->createToken($user, $name, $abilities);
    }
} 