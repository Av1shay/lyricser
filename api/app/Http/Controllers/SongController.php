<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Services\Contracts\SongServiceInterface;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $inputData = $request->all();
        $uploadedFile = $request->file('file');
        $filename = time().$uploadedFile->getClientOriginalName();

        $songData = [
            'title' => $inputData['title'],
            'writer' => $inputData['writer'],
            'composers' => $inputData['composers'],
            'performers' => $inputData['performers'],
            'published_at' => $inputData['published_at'],
            'text_filename' => $filename,
            'text_file_format' => $inputData['text_file_format'],
            'stanzas_delimiter' => $inputData['stanzas_delimiter'],
            'upload_by' => auth()->id() ?? 1,
        ];

        $this->songService->add($songData, $uploadedFile);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
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
