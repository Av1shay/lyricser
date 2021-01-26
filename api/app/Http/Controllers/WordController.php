<?php

namespace App\Http\Controllers;

use App\Enums\QueryTypes;
use App\Services\Contracts\WordServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WordController extends Controller
{
    protected WordServiceInterface $wordService;

    public function __construct(WordServiceInterface $wordService)
    {
        $this->wordService = $wordService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = $request->only(['songId', 'wordTerm', 'maxResults', 'nextCursor', 'bagId']);

        $data = [
            'songId' => isset($query['songId']) ? intval($query['songId']) : null,
            'wordTerm' => isset($query['wordTerm']) ? urldecode($query['wordTerm']) : null,
            'maxResults' => isset($query['maxResults']) ? intval($query['maxResults']) : null,
            'bagId' => isset($query['bagId']) ? $query['bagId'] : null,
            'nextCursor' => $query['nextCursor'] ?? null,
        ];

        $queryWordsRes = $this->wordService->queryWords($data, $request->user());

        return response()->json($queryWordsRes->toArray());
    }

    public function getWordsWithContext(Request $request) {
        $queryWordsRes = $this->wordService->getWordsWithContext([]);

        return response()->json($queryWordsRes->toArray());
    }

    /**
     * Get a specific resource.
     *
     * @param $songId
     * @return JsonResponse
     */
    public function getSongWords($songId)
    {
        $songId = intval($songId);

        if ($songId <= 0) {
            return response()->json(['error' => 'Provided parameter song ID is not a valid integer'], 400);
        }

        $words = $this->wordService->getSongWords($songId);

        return response()->json($words);
    }
}
