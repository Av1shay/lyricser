<?php


namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Support\Facades\Log;


class UserService implements UserServiceInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserById(int $userId): ?User
    {
        if ($userId < 1) {
            Log::warning('Can not fetch user with invalid userId value', [
                'userId' => $userId,
                'method' => __METHOD__,
            ]);
            return null;
        }

       return $this->userRepository->getById($userId);
    }

    public function upsertWordsBag(int $userId, array $bag): array
    {
        if ($userId < 1 || empty($bag)) {
            Log::warning('Can not update user bags with invalid userId/bag value', [
                'userId' => $userId,
                'bag' => json_encode($bag),
                'method' => __METHOD__,
            ]);
            return [];
        }

        return $this->userRepository->upsertWordsBag($userId, $bag);
    }

    public function upsertExpression(int $userId, array $expressionData): array
    {
        if ($userId < 1 || empty($expressionData)) {
            Log::warning('Can not update user expression with invalid userId/expression value', [
                'userId' => $userId,
                'bag' => json_encode($expressionData),
                'method' => __METHOD__,
            ]);
            return [];
        }

        return $this->userRepository->upsertExpression($userId, $expressionData);
    }

    public function deleteWordsBag(int $userId, string $bagId): bool
    {
        if ($userId < 1) {
            Log::warning('Can not fetch update user bags with invalid userId value', [
                'userId' => $userId,
                'method' => __METHOD__,
            ]);
            return false;
        }

        return $this->userRepository->deleteWordsBag($userId, $bagId);
    }

    public function deleteExpression(int $userId, string $expressionId): bool
    {
        if ($userId < 1) {
            Log::warning('Can not fetch update user bags with invalid userId value', [
                'userId' => $userId,
                'method' => __METHOD__,
            ]);
            return false;
        }

        return $this->userRepository->deleteExpression($userId, $expressionId);
    }

    public function updateUser(int $userId, array $data): bool
    {
        if ($userId < 1) {
            Log::warning('Can not update user with invalid userId value', [
                'userId' => $userId,
                'method' => __METHOD__,
            ]);
            return false;
        }

        return $this->userRepository->update($userId, $data);
    }
}
