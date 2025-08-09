<?php

namespace Package\Auth\Contracts;

use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    public function findByPhone(string $phoneNumber): ?Model;
    public function findById(int $id): ?Model;
    public function create(array $data): Model;
    public function update(Model $user, array $data): bool;
    public function deleteTokens(Model $user): void;
    public function createToken(Model $user, string $name, array $abilities = []): string;
    public function existsByPhone(string $phoneNumber): bool;
    public function getUserModelClass(): string;
} 