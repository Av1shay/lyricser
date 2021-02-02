<?php


namespace App\Services\Contracts;


use App\Exceptions\SongNotFoundException;
use App\Models\Song;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

interface SongServiceInterface
{
    public function add(array $data, UploadedFile $uploadedFile): Song;
    public function getById(int $id): ?Song;
    public function getLyrics(int $id): string;
    public function findAll(): Collection;
    public function getRecentSongs(): array;
}
