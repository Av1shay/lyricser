<?php


namespace App\Services;


use App\Repositories\Contracts\StatsRepositoryInterface;
use App\Repositories\Contracts\WordRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class StatsService implements Contracts\StatsServiceInterface
{
    private StatsRepositoryInterface $statsRepository;

    private WordRepositoryInterface $wordRepository;

    public int $topWordsLimit;

    public function __construct(StatsRepositoryInterface $statsRepository, WordRepositoryInterface $wordRepository)
    {
        $this->statsRepository = $statsRepository;
        $this->wordRepository = $wordRepository;
        $this->topWordsLimit = config('app.top_words_limit');
    }

    public function getStats(): array
    {
        $totalSongsCount = Cache::get('total_songs_count') ?? 0;
        $totalWordsCount = Cache::get('total_words_count') ?? 0;
        $avgWordsLength = Cache::get('avg_word_len') ?? 0;
        $avgWordsPerStanza = Cache::get('avg_words_per_stanza') ?? 0;
        $avgWordsPerSong = Cache::get('avg_words_per_song') ?? 0;
        $topWords = Cache::get('top_words') ?? [];

        return [
            'songsCount' => $totalSongsCount,
            'wordsCount' => $totalWordsCount,
            'avgWordsLength' => $avgWordsLength,
            'avgWordsPerStanza' => $avgWordsPerStanza,
            'avgWordsPerSong' => $avgWordsPerSong,
            'topWords' => $topWords,
        ];
    }

    public function updateStats(int $charsCount, int $wordsCount, int $stanzasCount, int $songsCount): void
    {
        $totalCharsCount = Cache::get('total_chars_count') ?? 0;
        $totalCharsCount += $charsCount;

        $totalWordsCount = Cache::get('total_words_count') ?? 0;
        $totalWordsCount += $wordsCount;

        $totalStanzasCount = Cache::get('total_stanzas_count') ?? 0;
        $totalStanzasCount += $stanzasCount;

        $totalSongsCount = Cache::get('total_songs_count') ?? 0;
        $totalSongsCount += $songsCount;

        Cache::put('total_chars_count', $totalCharsCount);
        Cache::put('total_words_count', $totalWordsCount);
        Cache::put('total_stanzas_count', $totalStanzasCount);
        Cache::put('total_songs_count', $totalSongsCount);

        $avgWordLen = intval(round($totalCharsCount / $totalWordsCount));
        Cache::put('avg_word_len', $avgWordLen);

        $avgWordPerStanza = intval(round($totalWordsCount / $totalStanzasCount));
        Cache::put('avg_words_per_stanza', $avgWordPerStanza);

        $avgWordsPerSong = intval(round($totalWordsCount / $totalSongsCount));
        Cache::put('avg_words_per_song', $avgWordsPerSong);

        // Top words
        $topWords = $this->wordRepository->getTopWords($this->topWordsLimit);
        Cache::put('top_words', $topWords);
    }

}
