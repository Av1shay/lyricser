<?php


namespace App\Repositories;


use App\Http\Responses\QueryResponse;
use App\Models\Song;
use App\Models\User;
use App\Repositories\Contracts\SongRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class SongRepository implements SongRepositoryInterface
{
    /**
     * @var Song
     */
    protected $song;

    public function __construct(Song $song)
    {
        $this->song = $song;
    }


    public function create(array $data): Song
    {
        return $this->song->create($data);
    }

    public function getById(int $id): ?Song
    {
        return $this->song->find($id);
    }

    public function findAll(): Collection
    {
        return $this->song->orderBy('updated_at', 'desc')->get();
    }

    public function query(array $data, ?User $user = null): QueryResponse
    {
        $nextCursor = null;

        if (!empty($data['expressionId']) && $user) {
            $expression = $user->getExpressionById($data['expressionId']);
        }

        if (isset($expression) && !empty($data['words'])) {
            $query = $this->expressionAndWordsQuery($expression, $data['words']);
        } else {
            $query = Song::query()->select('songs.*');

            if (isset($expression)) {
                $query->addSelect(DB::raw('group_concat(words.value separator \' \') as lyrics'))
                    ->join('words', 'songs.id', '=', 'words.song_id')
                    ->having('lyrics', 'LIKE', "%{$expression}%")
                    ->groupBy('songs.id');
            } else if (!empty($data['words'])) {
                $query->join('words', 'songs.id', '=', 'words.song_id')
                    ->whereIn('words.value', $data['words'])
                    ->groupBy('songs.id');
            }
        }

        if (!empty($data['title'])) {
            $query->where('title', 'LIKE', "%{$data['title']}%");
        }

        if (!empty($data['writer'])) {
            $query->where('writer', 'LIKE', "%{$data['writer']}%");
        }

        try {
            $totalCount = $query->count();
        } catch (QueryException $ex) {
            $cloned = $query->clone();
            $cloned->addSelect(DB::raw('count(distinct songs.id) as aggregate'));
            if ($cloned->first()) {
                $totalCount = intval($cloned->first()->aggregate);
            } else {
                $totalCount = 0;
            }
        }

        if (!empty($data['maxResults'])) {
            if (!empty($data['nextCursor'])) {
                $query->where('id', '>=', intval($data['nextCursor']));
            }

            $limitWithNextRow = $data['maxResults'] + 1;

            $query->limit($limitWithNextRow);

            $results = $query->get()->map(function ($songRow) {
                if (!is_array($songRow)) {
                    return $songRow instanceof Song ? $songRow->toArray() : (array)$songRow;
                }
                return $songRow;
            });
            $resCount = count($results);

            if ($resCount == $limitWithNextRow) {
                // next cursor is the id of the last record
                $nextCursor = $results[$resCount - 1]['id'];
                $results->forget($resCount - 1);
            }
        } else {
            $results = $query->get()->map(function ($songRow) {
                if (!is_array($songRow)) {
                    return $songRow instanceof Song ? $songRow->toArray() : (array)$songRow;
                }
                return $songRow;
            });
        }

        return new QueryResponse($totalCount, $results, $nextCursor);
    }

    private function expressionAndWordsQuery(string $expression, array $words): Builder
    {
        // Build a sub query, that filter per word value
        $sub = Song::query()
            ->select('songs.*')
            ->join('words', 'songs.id', '=', 'words.song_id')
            ->whereIn('words.value', $words)
            ->groupBy('songs.id');

        // Return the main query, that filter by expression out of the words that returned from the sub query
        return DB::table(DB::raw("({$sub->toSql()}) as songs") )
            ->mergeBindings($sub->getQuery())
            ->select('songs.*', DB::raw('group_concat(words.value separator \' \') as lyrics'))
            ->join('words', 'songs.id', '=', 'words.song_id')
            ->having('lyrics', 'LIKE', "%{$expression}%")
            ->groupBy('songs.id');
    }

    public function count(): int
    {
        return $this->song->count();
    }

    public function getRecent(int $count): array
    {
        return $this->song->orderBy('updated_at', 'desc')
            ->limit($count)
            ->get()
            ->toArray();
    }
}
