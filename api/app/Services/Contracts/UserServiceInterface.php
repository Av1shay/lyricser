<?php


namespace App\Services\Contracts;


use App\Models\User;

interface UserServiceInterface
{
    public function getUserById(int $userId): ?User;
    public function upsertWordsBag(int $userId, array $bag): array;
    public function upsertExpression(int $userId, array $expressionData): array;
    public function deleteWordsBag(int $userId, string $bagId): bool;
    public function deleteExpression(int $userId, string $expressionId): bool;
}
