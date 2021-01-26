<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    Route::post('/{id}/wordsBag', [UserController::class, 'storeWordsBag']);
    Route::delete('/{id}/wordsBag/{bagId}', [UserController::class, 'deleteWordsBag']);
    Route::post('/{id}/expression', [UserController::class, 'storeExpression']);
    Route::delete('/{id}/expression/{expressionId}', [UserController::class, 'deleteExpression']);
});

Route::post('/register', [RegistrationController::class, 'store']);
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);


Route::prefix('song')->group(function () {
    Route::get('/', [SongController::class, 'index']);
    Route::get('/{id}', [SongController::class, 'getById']);
    Route::get('/{id}/lyrics', [SongController::class, 'getLyrics']);
    Route::post('/', [SongController::class, 'store'])
        ->middleware('parseRequestArrays');
});

Route::prefix('word')->group(function () {
    Route::get('/', [WordController::class, 'index']);
    Route::get('/context', [WordController::class, 'getWordsWithContext']);
    Route::get('/song/{songId}', [WordController::class, 'getSongWords']);
});



