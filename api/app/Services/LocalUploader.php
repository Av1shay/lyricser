<?php


namespace App\Services;

use App\Services\Contracts\UploaderInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class LocalUploader implements UploaderInterface
{
    protected $driver = 'local';

    public function put(string $filename, UploadedFile $uploadedFile): string
    {
        return Storage::disk($this->driver)->putFileAs(
            'songs',
            $uploadedFile,
            $filename,
        );
    }

    public function getFileContent(string $path): string
    {
        return Storage::disk($this->driver)->get($path);
    }
}
