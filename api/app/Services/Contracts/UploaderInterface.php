<?php


namespace App\Services\Contracts;


use Illuminate\Http\UploadedFile;

interface UploaderInterface
{
    public function put(string $filename, UploadedFile $uploadedFile): void;
}
