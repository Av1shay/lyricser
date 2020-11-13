<?php


namespace App\Services;


use App\Repositories\Contracts\SongRepositoryInterface;
use App\Services\Contracts\SongServiceInterface;
use App\Services\Contracts\UploaderInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SongService implements SongServiceInterface
{
    protected $repository;
    protected $uploader;

    public function __construct(SongRepositoryInterface $repository, UploaderInterface $uploader)
    {
        $this->repository = $repository;
        $this->uploader = $uploader;
    }

    public function add(array $songData, UploadedFile $uploadedFile)
    {
        $songId = $this->repository->create($songData);
        $this->uploader->put($songData['text_filename'], $uploadedFile);
    }
}
