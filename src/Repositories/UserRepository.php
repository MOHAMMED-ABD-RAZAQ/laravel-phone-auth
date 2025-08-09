<?php

namespace Package\Auth\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Package\Auth\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected string $userModel;

    public function __construct()
    {
        $this->userModel = config('auth-package.user_model', 'App\Models\User');
    }

    /**
     * Find user by phone number
     */
    public function findByPhone(string $phoneNumber): ?Model
    {
        $phoneColumn = config('auth-package.phone_column', 'phone');
        
        return $this->userModel::query()
            ->where($phoneColumn, $phoneNumber)
            ->first();
    }

    /**
     * Find user by ID
     */
    public function findById(int $id): ?Model
    {
        return $this->userModel::find($id);
    }

    /**
     * Create new user
     */
    public function create(array $data): Model
    {
        $phoneColumn = config('auth-package.phone_column', 'phone');
        
        return $this->userModel::create([
            'name' => $data['name'],
            $phoneColumn => $data['phone'],
            'email' => $data['email'],
            'password' => $data['password'],
            'verified_at' => null,
            ...$data['additional_fields'] ?? []
        ]);
    }

    /**
     * Update user
     */
    public function update(Model $user, array $data): bool
    {
        return $user->update($data);
    }

    /**
     * Delete user tokens
     */
    public function deleteTokens(Model $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * Create user token
     */
    public function createToken(Model $user, string $name, array $abilities = []): string
    {
        return $user->createToken($name, $abilities)->plainTextToken;
    }

    /**
     * Check if user exists by phone
     */
    public function existsByPhone(string $phoneNumber): bool
    {
        $phoneColumn = config('auth-package.phone_column', 'phone');
        
        return $this->userModel::query()
            ->where($phoneColumn, $phoneNumber)
            ->exists();
    }

    /**
     * Get user model class name
     */
    public function getUserModelClass(): string
    {
        return $this->userModel;
    }
} 