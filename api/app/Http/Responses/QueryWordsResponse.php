<?php


namespace App\Http\Responses;


use Illuminate\Support\Collection;

class QueryWordsResponse
{
    public int $totalCount;
    public Collection $words;

    /**
     * @var int | string | null
     */
    public $nextCursor;

    public function __construct(int $totalCount, Collection $words, $nextCursor)
    {
        $this->totalCount = $totalCount;
        $this->words = $words;
        $this->nextCursor = $nextCursor;
    }

    public function toArray(): array
    {
        return [
            'totalCount' => $this->totalCount,
            'words' => $this->words->toArray(),
            'nextCursor' => $this->nextCursor,
        ];
    }
}
