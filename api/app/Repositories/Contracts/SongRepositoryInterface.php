<?php


namespace App\Repositories\Contracts;


use App\Models\Song;
use Illuminate\Database\Eloquent\Collection;


interface SongRepositoryInterface
{
    public function create(array $data): Song;
    public function getById(int $id): ?Song;
    public function findAll(): Collection;
    public function query(array $data): Collection;
}
