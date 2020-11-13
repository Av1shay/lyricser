<?php


namespace App\Repositories;


use App\Models\Song;
use App\Repositories\Contracts\SongRepositoryInterface;

class SongRepository implements SongRepositoryInterface
{
    public function create(array $songData): int
    {
        $song = Song::create($songData);

        return $song->id;
    }
}
