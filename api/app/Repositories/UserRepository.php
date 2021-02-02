<?php


namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;


class UserRepository implements UserRepositoryInterface
{

    public function getById(int $userId): ?User
    {
        return User::find($userId);
    }

    public function upsertWordsBag(int $userId, array $data): array
    {
        $ids = [];
        $user = User::find($userId);
        $wordsBags = $user->getMeta('words_bags');

        if (count($wordsBags) > 0) {
            $ids = Arr::pluck($wordsBags, 'id');
        }

        $bagId = $data['id'] ?? null;

        if (!$bagId || ($bagIndex = array_search($bagId, $ids)) === false) {
            // Create new bag
            $wordsBags[] = [
                'id' => uniqid('bag-'),
                'title' => $data['title'],
                'words' => $data['words'],
            ];
            $bagIndex = count($wordsBags) - 1;
        } else {
            // Edit existing one
            $wordsBags[$bagIndex] = [
                'id' => $bagId,
                'title' => $data['title'],
                'words' => $data['words'],
            ];
        }

        $user->setMeta('words_bags', $wordsBags);
        $user->save();

        return $wordsBags[$bagIndex];
    }

    public function upsertExpression(int $userId, array $data): array
    {
        $ids = [];
        $user = User::find($userId);
        $exps = $user->getMeta('expressions');

        if (count($exps) > 0) {
            $ids = Arr::pluck($exps, 'id');
        }

        $expId = $data['id'] ?? null;

        if (!$expId) {
            $exps[] = [
                'id' => uniqid('exp-'),
                'expression' => $data['expression'],
            ];
            $expIndex = count($exps) - 1;
        } else if (($expIndex = array_search($expId, $ids)) === false) {
            // invalid request
            Log::warning('Attempt to update user expression with non existing expression ID', [
                'expressionId' => $expId,
                'existingIds' => implode(', ', $ids),
                'userId' => $userId,
            ]);
            return [];
        } else {
            // Edit existing one
            $exps[$expIndex] = [
                'id' => $expId,
                'expression' => $data['expression'],
            ];
        }

        $user->setMeta('expressions', $exps);
        $user->save();

        return $exps[$expIndex];
    }

    public function deleteWordsBag(int $userId, string $bagId): bool
    {
        $user = User::find($userId);
        $wordsBags = $user->getMeta('words_bags');

        $bagIndex = array_search($bagId, array_column($wordsBags, 'id'));

        if ($bagIndex !== false) {
            unset($wordsBags[$bagIndex]);
            $user->setMeta('words_bags', array_values($wordsBags));
            $user->save();

            return true;
        }

        return false;
    }

    public function deleteExpression(int $userId, string $bagId): bool
    {
        $user = User::find($userId);
        $exps = $user->getMeta('expressions');

        $expIndex = array_search($bagId, array_column($exps, 'id'));

        if ($expIndex !== false) {
            unset($exps[$expIndex]);
            $user->setMeta('expressions', array_values($exps));
            $user->save();

            return true;
        }

        return false;
    }

    public function update(int $userId, array $data): bool
    {
        $user = User::find($userId);

        if (!$user) {
            throw new \Exception('User ID not found');
        }

        if (isset($data['email'])) {
            $emailExist = User::where('email', '=', $data['email'])
                ->where('id', '!=', $userId)
                ->count();

            if ($emailExist) {
                throw new \Exception('Email already exist');
            }

            $user->email = $data['email'];
        }

        if (isset($data['fullName'])) {
            $user->name = $data['fullName'];
        }

        $user->save();

        return true;
    }
}
