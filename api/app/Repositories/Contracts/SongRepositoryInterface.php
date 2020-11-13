<?php


namespace App\Repositories\Contracts;


interface SongRepositoryInterface
{
    public function create(array $songData): int;
}
