<?php


namespace App\Repositories\Contracts;


use App\Models\User;

interface UserRepositoryInterface
{
    public function getById(int $userId): ?User;
    public function upsertWordsBag(int $userId, array $data): array;
    public function upsertExpression(int $userId, array $data): array;
    public function deleteWordsBag(int $userId, string $bagId): bool;
    public function deleteExpression(int $userId, string $bagId): bool;
}
