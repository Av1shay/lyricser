<?php


namespace App\Services;


use App\Exceptions\SongNotFoundException;
use App\Http\Responses\QueryResponse;
use App\Jobs\ProcessWords;
use App\Models\Song;
use App\Models\User;
use App\Repositories\Contracts\SongRepositoryInterface;
use App\Services\Contracts\SongServiceInterface;
use App\Services\Contracts\UploaderInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;


class SongService implements SongServiceInterface
{
    protected SongRepositoryInterface $songRepository;

    protected UploaderInterface $uploader;

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
    public function getById(int $id): ?Song
    {
        if ($id < 1) {
            throw new Exception('$id param must be greater then 0');
        }

        return $this->songRepository->getById($id);
    }


    public function getLyrics(int $id): string
    {
        if ($id < 1) {
            throw new Exception('$id param must be greater then 0');
        }

        $song = $this->songRepository->getById($id);

        if (!$song) {
            return '';
        }

        $songContent = $this->uploader->getFileContent($song->text_filename);

        return str_replace($song->stanzas_delimiter, "\n", $songContent);
    }

    public function findAll(): Collection
    {
        return $this->songRepository->findAll();
    }

    public function querySongs(array $data, ?User $user): QueryResponse
    {
        return $this->songRepository->query($data, $user);
    }

    public function getRecentSongs(): array
    {
        $maxSongsCount = 10;
        return $this->songRepository->getRecent($maxSongsCount);
    }
}
