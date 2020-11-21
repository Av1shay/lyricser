<?php


namespace App\Services\Contracts;


use App\Models\Song;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

interface SongServiceInterface
{
    public function add(array $data, UploadedFile $uploadedFile): Song;
    public function getById(int $id): Song;
    public function findAll(): Collection;
}
