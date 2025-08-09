<?php

namespace Package\Auth\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Package\Auth\Services\AuthService;
use Package\Auth\Services\OtpService;
use Package\Auth\Requests\LoginRequest;
use Package\Auth\Requests\RegisterRequest;
use Package\Auth\Requests\VerifyOtpRequest;
use Package\Auth\Requests\ResendOtpRequest;
use Package\Auth\Requests\RequestPasswordRequest;
use Package\Auth\Requests\VerifyPasswordResetRequest;
use Package\Auth\Requests\ResendPasswordResetRequest;
use Package\Auth\Requests\ChangePasswordRequest;
use Package\Auth\Helpers\ResponseBuilder;
use Package\Auth\Traits\HasPhoneAuth;
use Illuminate\Routing\Controller;
use Exception;

class AuthController extends Controller
{
    use HasPhoneAuth;

    protected AuthService $authService;
    protected OtpService $otpService;

    public function __construct(AuthService $authService, OtpService $otpService)
    {
        $this->authService = $authService;
        $this->otpService = $otpService;
    }

    /**
     * Login user with phone number and password
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->authenticate($request->phone, $request->password);

            return ResponseBuilder::Ok([
                'user' => $this->getUserResourceInstance($result['user']),
                'token' => $result['token'],
            ]);

        } catch (Exception $e) {
            return ResponseBuilder::Error($e->getMessage());
        }
    }

    /**
     * Register new user with phone number
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $result = $this->authService->register([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => $request->password,
                'additional_fields' => $this->getAdditionalUserData($request)
            ]);

            DB::commit();

            return ResponseBuilder::Ok([
                'token' => $result['token'],
                'user' => $this->getUserResourceInstance($result['user'])
            ], __('auth-package::auth.register_successfully'));

        } catch (Exception $e) {
            DB::rollBack();
            return ResponseBuilder::Error($e->getMessage());
        }
    }

    /**
     * Verify OTP for user registration
     */
    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();

            if (!$user) {
                return ResponseBuilder::Error(__('auth-package::auth.user_not_found'));
            }

            $userOtp = $this->otpService->findByUserIdAndType($user->id, \Package\Auth\Models\UserOtp::SIGNUP_TYPE);

            if (!$userOtp) {
                return ResponseBuilder::Error(__('auth-package::auth.otp_not_found'));
            }

            if (!$this->otpService->verifyOtp($userOtp, $request->otp)) {
                DB::commit();
                return ResponseBuilder::Error(__('auth-package::auth.otp_verification_failed'));
            }

            // Mark user as verified
            $this->authService->markUserAsVerified($user);

            // Delete OTP record
            $this->otpService->deleteOtp($userOtp);

            DB::commit();

            return ResponseBuilder::Ok([
                'user' => $this->getUserResourceInstance($user)
            ], __('auth-package::auth.otp_verification_success'));

        } catch (Exception $e) {
            DB::rollBack();
            return ResponseBuilder::Error($e->getMessage());
        }
    }

    /**
     * Resend OTP for verification
     */
    public function resendOtp(ResendOtpRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return ResponseBuilder::Error(__('auth-package::auth.user_not_found'));
            }

            $userOtp = $this->otpService->findByUserIdAndType($user->id, \Package\Auth\Models\UserOtp::SIGNUP_TYPE);

            if (!$userOtp) {
                return ResponseBuilder::Error(__('auth-package::auth.otp_not_found'));
            }

            $this->otpService->resendOtp($userOtp);

            $phoneColumn = config('auth-package.phone_column', 'phone');

            return ResponseBuilder::Ok(null, __('auth-package::auth.otp_resent_successfully', [
                'phone' => $user->{$phoneColumn}
            ]));

        } catch (Exception $e) {
            return ResponseBuilder::Error($e->getMessage());
        }
    }

    /**
     * Request password reset
     */
    public function requestPasswordReset(RequestPasswordRequest $request): JsonResponse
    {
        try {
            $this->authService->requestPasswordReset($request->phone);

            return ResponseBuilder::Ok(null, __('auth-package::auth.password_reset_otp_sent', [
                'phone' => $request->phone
            ]));

        } catch (Exception $e) {
            return ResponseBuilder::Error($e->getMessage());
        }
    }

    /**
     * Verify password reset OTP
     */
    public function verifyPasswordResetOtp(VerifyPasswordResetRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $userOtp = $this->otpService->findByPhoneAndType($request->phone, \Package\Auth\Models\UserOtp::RESTORE_PASSWORD_TYPE);

            if (!$userOtp) {
                return ResponseBuilder::Error(__('auth-package::auth.otp_not_found'));
            }

            if (!$this->otpService->verifyOtp($userOtp, $request->otp)) {
                DB::commit();
                return ResponseBuilder::Error(__('auth-package::auth.otp_verification_failed'));
            }

            $user = $this->authService->findUserById($userOtp->user_id);

            if (!$user) {
                return ResponseBuilder::Error(__('auth-package::auth.user_not_found'));
            }

            // Create new token
            $token = $this->authService->createToken($user, 'password_reset_token');

            // Delete OTP record
            $this->otpService->deleteOtp($userOtp);

            DB::commit();

            return ResponseBuilder::Ok([
                'token' => $token,
                'user' => $this->getUserResourceInstance($user)
            ], __('auth-package::auth.password_reset_otp_verified'));

        } catch (Exception $e) {
            DB::rollBack();
            return ResponseBuilder::Error($e->getMessage());
        }
    }

    /**
     * Resend password reset OTP
     */
    public function resendPasswordResetOtp(ResendPasswordResetRequest $request): JsonResponse
    {
        try {
            $userOtp = $this->otpService->findUnverifiedByPhoneAndType($request->phone, \Package\Auth\Models\UserOtp::RESTORE_PASSWORD_TYPE);

            if (!$userOtp) {
                return ResponseBuilder::Error(__('auth-package::auth.otp_not_found'));
            }

            $this->otpService->resendOtp($userOtp);

            return ResponseBuilder::Ok(null, __('auth-package::auth.password_reset_otp_resent', [
                'phone' => $request->phone
            ]));

        } catch (Exception $e) {
            return ResponseBuilder::Error($e->getMessage());
        }
    }

    /**
     * Change password
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();

            if (!$user) {
                return ResponseBuilder::Error(__('auth-package::auth.user_not_found'));
            }

            $result = $this->authService->changePassword($user, $request->password);

            DB::commit();

            return ResponseBuilder::Ok([
                'token' => $result['token'],
                'user' => $this->getUserResourceInstance($result['user'])
            ], __('auth-package::auth.password_changed_successfully'));

        } catch (Exception $e) {
            DB::rollBack();
            return ResponseBuilder::Error($e->getMessage());
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            if ($user) {
                $this->authService->logout($user);
            }

            return ResponseBuilder::Ok(null, __('auth-package::auth.logout_successfully'));

        } catch (Exception $e) {
            return ResponseBuilder::Error($e->getMessage());
        }
    }

    /**
     * Get authenticated user profile
     */
    public function profile(): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return ResponseBuilder::Error(__('auth-package::auth.user_not_found'));
            }

            return ResponseBuilder::Ok([
                'user' => $this->getUserResourceInstance($user)
            ]);

        } catch (Exception $e) {
            return ResponseBuilder::Error($e->getMessage());
        }
    }
} 