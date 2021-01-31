<?php


namespace App\Repositories\Contracts;


use App\Http\Responses\QueryResponse;
use App\Models\Song;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;


interface SongRepositoryInterface
{
    public function create(array $data): Song;
    public function getById(int $id): ?Song;
    public function findAll(): Collection;
    public function query(array $data, ?User $user): QueryResponse;
}
