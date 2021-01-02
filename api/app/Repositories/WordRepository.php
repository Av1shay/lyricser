<?php


namespace App\Repositories;

use App\Http\Responses\QueryWordsResponse;
use App\Models\Word;
use App\Repositories\Contracts\WordRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class WordRepository implements WordRepositoryInterface
{
    protected $table = 'words';

    public function count(): int
    {
        return Word::count('*');
    }

    public function getWordsBySongId(int $songId): Collection
    {
        return Word::where('song_id', '=', $songId)->get();
    }

    /**
     * @param array $data
     * @throws Exception
     */
    public function insertsWithTransaction(array $data): void
    {
        DB::beginTransaction();

        try {
            DB::table($this->table)->insert($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function query(array $filter): QueryWordsResponse
    {
        $nextCursor = null;
        $query = Word::query();

        if (!empty($filter['songId'])) {
            $query->where('song_id', '=', $filter['songId']);
        }

        if (!empty($filter['wordTerm'])) {
            $query->where('value', 'like', "%{$filter['wordTerm']}%");
        }

        $totalCount = $query->count();

        if (!empty($filter['maxResults'])) {
            if (!empty($filter['nextCursor'])) {
                $query->where('id', '>', intval($filter['nextCursor']));
            }

            $limitWithNextRow = $filter['maxResults'] + 1;

            $query->limit($limitWithNextRow);

            $results = $query->get();
            $reCount = count($results);

            if ($reCount == $limitWithNextRow) {
                // next cursor is the id of the last record
                $nextCursor = $results[$reCount - 1]['id'];
                unset($results[$reCount - 1]);
            }
        } else {
            $results = $query->get();
        }

        return new QueryWordsResponse($totalCount, $results, $nextCursor);
    }
}
