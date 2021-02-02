<?php


namespace App\Repositories\Contracts;


use App\Http\Responses\QueryResponse;
use Illuminate\Database\Eloquent\Collection;

interface WordRepositoryInterface
{
    public function count(): int;
    public function getWordsBySongId(int $songId): Collection;
    public function insertsWithTransaction(array $data): void;
    public function query(array $filter): QueryResponse;
    public function getTopWords(int $count): array;
}
