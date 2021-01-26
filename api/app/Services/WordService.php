<?php


namespace App\Services;

use App\Enums\WordPositions;
use App\Http\Responses\QueryWordsResponse;
use App\Models\User;
use App\Models\Word;
use App\Repositories\Contracts\WordRepositoryInterface;
use App\Services\Contracts\WordServiceInterface;
use \Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;


class WordService implements WordServiceInterface
{

    const WORD_PADDING_SIZE = 4;

    protected string $wordsContextListCacheKey = 'words_context_list';

    protected WordRepositoryInterface $wordRepository;

    public function __construct(WordRepositoryInterface $wordRepository)
    {
        $this->wordRepository = $wordRepository;
    }

    public function queryWords(array $data, User $user = null): QueryWordsResponse
    {
        if (isset($data['bagId']) && $user !== null) {
            $bags = $user->getMeta('words_bags');
            $bagIndex = array_search($data['bagId'], array_column($bags, 'id'));

            if ($bagIndex !== false) {
                $data['words'] = $bags[$bagIndex]['words'];
                unset($data['bagId']);
            }
        }

        return $this->wordRepository->query($data);
    }

    public function getWordsWithContext(array $data)
    {
        $words = Cache::get($this->wordsContextListCacheKey);

        if (!$words) {
            $words = $this->refreshWordsContextListCache();
        }

//        if (!empty($data)) {
//            $wordsIndex = $this->_filterWords($data, $wordsIndex);
//        }

        return new QueryWordsResponse($words->count(), $words, null);
    }

    public function addWords(array $words): void
    {
        $this->wordRepository->insertsWithTransaction($words);
    }

    public function getSongWords(int $songId): Collection
    {
        return $this->wordRepository->getWordsBySongId($songId);
    }

    /**
     * Make a copy of words index (i.e each word value points to array of that words in our DB) and save it on the cache.
     *
     * @return Collection
     */
    public function refreshWordsContextListCache(): Collection
    {
        $queryRes = $this->wordRepository->query([]);
        $wordsByValue = $queryRes->words->groupBy('value', true);

        // Map each word to it's data
        $collection = $wordsByValue->map(function (Collection $words) {
            return $words->map(fn (Word $word) => [
                'id' => $word->id,
                'songId' => $word->song_id,
                'startIndex' => $word->start_index,
                'stanza' => $word->stanza,
                'position' => $word->position,
            ])->values()->all();
        });

        $collection = $this->_mapWordsToContext($collection);

        Cache::forget($this->wordsContextListCacheKey);
        Cache::put($this->wordsContextListCacheKey, $collection);

        return $collection;
    }

    /**
     *
     * [id => [before => '', after => '']]
     *
     * [word[] => [id => 10, before => '', after => '']]
     *
     * @param Collection $wordsIndex
     * @return array
     */
    private function _mapWordsToContext(Collection $wordsIndex): Collection
    {
        $words = collect();
        $contextMap = [];
        $paddingSize = self::WORD_PADDING_SIZE;
        $startPosHelper = [];

        // Build new associate collection where wordId points the word data
        $wordsIndex->each(function (array $wordsData, string $wordVal) use (&$words) {
            foreach ($wordsData as $wordData) {
                $id = $wordData['id'];
                $words[$id] = Arr::add($wordData, 'value', $wordVal);
            }
        });

        // Sort the collection so we can define context for each word
        $words = $words->sortBy(['songId', 'stanza', 'startIndex']);

        $words->each(function ($word, $index) use ($words, $paddingSize, &$contextMap, &$startPosHelper) {
            if (count($startPosHelper) === $paddingSize) $startPosHelper = [];

            $wordsVal = $word['value'];
            $pos = $word['position'];

            // If this word is a start word, or is right after start word, we need to make sure we don't take words before the start word
            if ($pos == WordPositions::Start || !empty($startPosHelper)) {
                $startPosHelper[] = $index;
                $before = $words->slice($startPosHelper[0], ($index - $startPosHelper[0]));
            } else {
                $before = $words->slice(($index - $paddingSize), $paddingSize);
            }

            // If this word is a end word, or is right before end word, make sure "after" is terminate at the end word
            if (($endWordIndex = $this->_isBeforeEndWord($index, $words))) {
                $after = $words->slice($index + 1, $endWordIndex - $index);
            } else {
                $after = $words->slice($index + 1, $paddingSize);
            }

            $contextMap[$wordsVal][] = [
                'id' => $word['id'],
                'songId' => $word['songId'],
                'before' => $before->pluck('value')->join(' '),
                'after' => $after->pluck('value')->join(' '),
            ];
        });

        return collect($contextMap);
    }

    private function _filterWords(array $filter, Collection $words): Collection
    {
        return $words->filter(function (array $wordData, string $wordVal) use ($filter) {
            $filteredWords = array_filter($wordData, function ($word) use ($wordVal, $filter) {
                $songIdPassedFilter = true;
                $wordTermPassedFilter = true;

                if (!empty($filter['songId'])) {
                    $songIdPassedFilter = $word['song_id'] == $filter['songId'];
                }

                if (!empty($filter['wordTerm'])) {
                    $wordTermPassedFilter = Str::contains($wordVal, $filter['wordTerm']);
                }

                return $songIdPassedFilter && $wordTermPassedFilter;
            });

            return count($filteredWords) > 0;
        });
    }

    private function _isBeforeEndWord(int $index, $words)
    {
        for ($i = 0, $j = $index; $i < self::WORD_PADDING_SIZE; $i++, $j++) {
            $pos = $words[$j]['position'] ?? null;

            if ($pos && $pos == WordPositions::End) {
                return $j;
            }
        }

        return false;
    }
}
