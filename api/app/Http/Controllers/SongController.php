<?php

namespace App\Http\Controllers;

use App\Exceptions\SongNotFoundException;
use App\Http\Requests\CreateSong;
use App\Models\Song;
use App\Services\Contracts\SongServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SongController extends Controller
{
    protected SongServiceInterface $songService;

    protected UserServiceInterface $userService;

    public function __construct(SongServiceInterface $songService, UserServiceInterface $userService)
    {
        $this->songService = $songService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = $request->only(['title', 'writer','words', 'expressionId', 'nextCursor', 'maxResults', 'orderBy']);
        $querySanitized = [];

        foreach ($query as $paramKey => $paramVal) {
            if ($paramKey == 'words') {
                $querySanitized[$paramKey] = explode(',', urldecode($paramVal));
            } else if (is_numeric($paramVal)) {
                $querySanitized[$paramKey] = intval($paramVal);
            } else {
                $querySanitized[$paramKey] = urldecode($paramVal);
            }
        }

        $songsQueryRes = $this->songService->querySongs($querySanitized, $request->user());

        $songsQueryRes->items = $songsQueryRes->items->map(function ($song) {
            $uploadByUser = $this->userService->getUserById(intval($song['upload_by']));
            $song['upload_by'] = $uploadByUser->name ?? '';
            return $song;
        });

        return response()->json($songsQueryRes->toArray());
    }

    public function getById($id, Request $request): JsonResponse
    {
        $songId = intval($id);

        try {
            $song = $this->songService->getById($songId);
        } catch (Exception $exception) {
            return response()->json(['errorMessage' => $exception->getMessage(), 400]);
        }

        if (!$song) {
            return response()->json(['errorMessage' => 'Could not find song with ID ' . $songId], 404);
        }

        if ($request->query('withLyrics') == 1) {
            $lyrics = $this->songService->getLyrics($songId);
            $song['lyrics'] = $lyrics;
        }

        return response()->json($song);
    }

    public function getRecent()
    {
        $songs = $this->songService->getRecentSongs();
        return response()->json($songs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateSong $request
     * @return JsonResponse
     */
    public function store(CreateSong $request): JsonResponse
    {
        $uploadedFile = $request->file('file');
        $filename = time().$uploadedFile->getClientOriginalName();

        $songData = $request->only([
            'title',
            'writer',
            'composers',
            'performers',
            'published_at',
            'stanzas_delimiter',
        ]);

        $fileData = [
            'text_filename' => $filename,
            'text_file_format' => pathinfo($filename, PATHINFO_EXTENSION),
        ];

        try {
            $newSong = $this->songService->add(array_merge($songData, $fileData), $uploadedFile);
            return response()->json($newSong);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
