<?php


namespace App\Services;

use App\Services\Contracts\UploaderInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class LocalUploader implements UploaderInterface
{
    protected $driver = 'local';

    public function put(string $filename, UploadedFile $uploadedFile): void
    {
        Storage::disk($this->driver)->putFileAs(
            'files/'.$filename,
            $uploadedFile,
            $filename,
        );
    }
}
