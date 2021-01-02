<?php


namespace App\Services\Contracts;


use App\Http\Responses\QueryWordsResponse;
use \Illuminate\Support\Collection;


interface WordServiceInterface
{
    public function queryWords(string $type, array $data): QueryWordsResponse;
    public function addWords(array $words): void;
    public function getSongWords(int $songId): Collection;
    public function refreshWordsIndexCache(): Collection;
}
