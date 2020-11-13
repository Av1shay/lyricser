<?php


namespace App\Services\Contracts;


use Illuminate\Http\UploadedFile;

interface SongServiceInterface
{
    public function add(array $songData, UploadedFile $uploadedFile);
}
