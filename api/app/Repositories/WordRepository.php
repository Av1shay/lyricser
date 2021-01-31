<?php


namespace App\Repositories;

use App\Http\Responses\QueryResponse;
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

    public function query(array $filter): QueryResponse
    {
        $nextCursor = null;
        $query = Word::query();

        if (!empty($filter['songId'])) {
            $query->where('song_id', '=', $filter['songId']);
        }

        if (!empty($filter['wordTerm'])) {
            $query->where('value', 'like', "%{$filter['wordTerm']}%");
        } else if (!empty($filter['words']) && is_array($filter['words'])) {
            $query->whereIn('value', $filter['words']);
        }

        if (!empty($filter['line'])) {
            $query->where('line', '=', $filter['line']);
        }

        if (!empty($filter['stanza'])) {
            $query->where('stanza', '=', $filter['stanza']);
        }

        $totalCount = $query->count();

        if (!empty($filter['maxResults'])) {
            if (!empty($filter['nextCursor'])) {
                $query->where('id', '>=', intval($filter['nextCursor']));
            }

            $limitWithNextRow = $filter['maxResults'] + 1;

            $query->limit($limitWithNextRow);

            $results = $query->get();
            $resCount = count($results);

            if ($resCount == $limitWithNextRow) {
                // next cursor is the id of the last record
                $nextCursor = $results[$resCount - 1]['id'];
                $results->forget($resCount - 1);
            }
        } else {
            $results = $query->get();
        }

        return new QueryResponse($totalCount, $results, $nextCursor);
    }
}
