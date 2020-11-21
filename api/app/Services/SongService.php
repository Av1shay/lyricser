<?php


namespace App\Services;


use App\Jobs\ProcessWords;
use App\Models\Song;
use App\Repositories\Contracts\SongRepositoryInterface;
use App\Services\Contracts\SongServiceInterface;
use App\Services\Contracts\UploaderInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;


class SongService implements SongServiceInterface
{
    /**
     * @var SongRepositoryInterface
     */
    protected $songRepository;

    /**
     * @var UploaderInterface
     */
    protected $uploader;

    public function __construct(SongRepositoryInterface $songRepository, UploaderInterface $uploader)
    {
        $this->songRepository = $songRepository;
        $this->uploader = $uploader;
    }

    public function add(array $data, UploadedFile $uploadedFile): Song
    {
        // Upload the file to the storage
        $filepath = $this->uploader->put($data['text_filename'], $uploadedFile);

        $data['text_filename'] = $filepath;
        $data['upload_by'] = auth()->id() ?? 1;

        $song = $this->songRepository->create($data);

        // Dispatch job to populate words from this song
        ProcessWords::dispatch($song);

        return $song;
    }

    /**
     * @param int $id
     * @return Song
     * @throws Exception
     */
    public function getById(int $id): Song
    {
        if ($id < 1) {
            throw new Exception('$id param must be greater then 0');
        }

        return $this->songRepository->getById($id);
    }

    public function findAll(): Collection
    {
        return $this->songRepository->findAll();
    }
}
