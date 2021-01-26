<?php


namespace App\Services\Contracts;


use App\Http\Responses\QueryWordsResponse;
use App\Models\User;
use \Illuminate\Support\Collection;


interface WordServiceInterface
{
    public function queryWords(array $data, User $user): QueryWordsResponse;
    public function getWordsWithContext(array $data);
    public function addWords(array $words): void;
    public function getSongWords(int $songId): Collection;
    public function refreshWordsContextListCache(): Collection;
}
