<?php

namespace App\Http\Controllers;

use App\Exceptions\SongNotFoundException;
use App\Http\Requests\CreateSong;
use App\Models\Song;
use App\Services\Contracts\SongServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SongController extends Controller
{
    protected $songService;

    public function __construct(SongServiceInterface $songService)
    {
        $this->songService = $songService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = $request->only(['title', 'maxResults',]);

        $data = [
            'title' => isset($query['title']) ? urldecode($query['title']) : null,
            'maxResults' => isset($query['maxResults']) ? intval($query['maxResults']) : null,
        ];

        $songs = $this->songService->querySongs($data);

        return response()->json($songs);
    }

    /**
     * Get a specific resource.
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
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

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

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
