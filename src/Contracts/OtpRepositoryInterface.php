<?php

namespace Package\Auth\Contracts;

use Illuminate\Database\Eloquent\Model;

interface OtpRepositoryInterface
{
    public function findByUserIdAndType(int $userId, int $type): ?Model;
    public function findByPhoneAndType(string $phone, int $type): ?Model;
    public function findUnverifiedByPhoneAndType(string $phone, int $type): ?Model;
    public function existsUnverifiedByPhoneAndType(string $phone, int $type): bool;
    public function create(array $data): Model;
    public function update(Model $otp, array $data): bool;
    public function delete(Model $otp): bool;
    public function incrementFailedAttempts(Model $otp): void;
} 