<?php

namespace Package\Auth\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Package\Auth\Rules\UniquePhoneNumber;
use Package\Auth\Services\SmsService;
use Package\Auth\Services\OtpService;
use Package\Auth\Services\AuthService;
use Package\Auth\Repositories\UserRepository;
use Package\Auth\Repositories\OtpRepository;
use Package\Auth\Contracts\UserRepositoryInterface;
use Package\Auth\Contracts\OtpRepositoryInterface;

class AuthPackageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/auth-package.php', 'auth-package'
        );

        $this->registerBindings();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/auth-package.php' => config_path('auth-package.php'),
        ], 'auth-package-config');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'auth-package-migrations');

        // Publish language files
        $this->publishes([
            __DIR__ . '/../lang' => lang_path('vendor/auth-package'),
        ], 'auth-package-lang');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/auth.php');

        // Load language files
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'auth-package');

        // Register custom validation rules
        $this->registerValidationRules();

        // Register custom commands
        $this->registerCommands();

        // Register SMS service
        $this->registerSmsService();
    }

    /**
     * Register bindings.
     *
     * @return void
     */
    protected function registerBindings(): void
    {
        // Register repositories
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(OtpRepositoryInterface::class, OtpRepository::class);

        // Register services
        $this->app->bind(AuthService::class, function ($app) {
            return new AuthService(
                $app->make(UserRepositoryInterface::class),
                $app->make(OtpService::class),
                $app->make(SmsService::class)
            );
        });

        $this->app->bind(OtpService::class, function ($app) {
            return new OtpService(
                $app->make(OtpRepositoryInterface::class),
                $app->make(SmsService::class)
            );
        });
    }

    /**
     * Register custom validation rules.
     *
     * @return void
     */
    protected function registerValidationRules(): void
    {
        Validator::extend('unique_phone', function ($attribute, $value, $parameters, $validator) {
            $model = $parameters[0] ?? config('auth-package.user_model', 'App\Models\User');
            $ignoreId = $parameters[1] ?? null;

            $rule = new UniquePhoneNumber($model, $ignoreId);
            return $rule->passes($attribute, $value);
        }, 'The :attribute has already been taken.');
    }

    /**
     * Register custom commands.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                // Add custom commands here if needed
            ]);
        }
    }

    /**
     * Register SMS service.
     *
     * @return void
     */
    protected function registerSmsService(): void
    {
        $this->app->singleton(SmsService::class, function ($app) {
            return new SmsService();
        });

        $this->app->alias(SmsService::class, 'auth.sms');
    }
} 