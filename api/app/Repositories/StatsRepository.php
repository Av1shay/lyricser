<?php


namespace App\Repositories;


use App\Repositories\Contracts\SongRepositoryInterface;
use App\Repositories\Contracts\WordRepositoryInterface;

class StatsRepository implements Contracts\StatsRepositoryInterface
{
    private SongRepositoryInterface $songRepository;

    private WordRepositoryInterface $wordRepository;

    public function __construct(SongRepositoryInterface $songRepository, WordRepositoryInterface $wordRepository)
    {
        $this->songRepository = $songRepository;
        $this->wordRepository = $wordRepository;

    }

    public function getAll(): array
    {
        $songsCount = $this->songRepository->count();
        $wordsCount = $this->wordRepository->count();

        return ['songsCount' => $songsCount, 'wordsCount' => $wordsCount];
    }
}
