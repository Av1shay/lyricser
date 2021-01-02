<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSong;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param CreateSong $request
     * @return JsonResponse
     */
    public function store(CreateSong $request): JsonResponse
    {

        $token = $request->user()->createToken($request->token_name);
    }

    public function get(CreateSong $request)
    {
        $user = Auth::user();

        return response()->json(['hello' => 'world']);
    }
}
