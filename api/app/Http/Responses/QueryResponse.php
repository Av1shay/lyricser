<?php


namespace App\Http\Responses;


use Illuminate\Support\Collection;

class QueryResponse
{
    public int $totalCount;
    public Collection $items;

    /**
     * @var int | string | null
     */
    public $nextCursor;

    public function __construct(int $totalCount, Collection $items, $nextCursor)
    {
        $this->totalCount = $totalCount;
        $this->items = $items;
        $this->nextCursor = $nextCursor;
    }

    public function toArray(): array
    {
        return [
            'totalCount' => $this->totalCount,
            'items' => $this->items->toArray(),
            'nextCursor' => $this->nextCursor,
        ];
    }
}
