<?php


namespace App\Services;


use App\Repositories\SongRepository;
use App\Services\Contracts\SongServiceInterface;

class SongService implements SongServiceInterface
{
    protected $repository;

    public function __construct(SongRepository $repository)
    {
        $this->repository = $repository;
    }

    public function upload(array $fileData)
    {
        // TODO: Implement upload() method.

    }
}
