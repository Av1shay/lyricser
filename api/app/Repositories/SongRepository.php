<?php


namespace App\Repositories;


use App\Models\Song;
use App\Repositories\Contracts\SongRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SongRepository implements SongRepositoryInterface
{
    /**
     * @var Song
     */
    protected $song;

    public function __construct(Song $song)
    {
        $this->song = $song;
    }


    public function create(array $data): Song
    {
        return $this->song->create($data);
    }

    public function getById(int $id): Song
    {
        return $this->song->find($id);
    }

    public function findAll(): Collection
    {
        return $this->song->all();
    }
}
