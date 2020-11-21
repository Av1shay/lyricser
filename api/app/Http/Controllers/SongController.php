<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSong;
use App\Models\Song;
use App\Services\Contracts\SongServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SongController extends Controller
{
    private $songService;

    public function __construct(SongServiceInterface $songService)
    {
        $this->songService = $songService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $songs = $this->songService->findAll();

        return response()->json($songs);
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
    public function store(CreateSong $request)
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function show(Song $song)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function edit(Song $song)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Models\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Song $song)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function destroy(Song $song)
    {
        //
    }
}
