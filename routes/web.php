<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashController;
use App\Http\Controllers\listController;
use App\Http\Controllers\songController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard',  [dashController::class, 'index'])->middleware(['auth']);

Route::get('/createList', function () {return view('createList');})->middleware(['auth']);

Route::post('/createList', [listController::class, 'create'])->middleware(['auth']);

Route::get('/showList', [listController::class, 'show'])->middleware(['auth']);

Route::get('/songs', [songController::class, 'index'])->middleware(['auth']);

Route::get('/addList', [listController::class, 'addSongToList'])->middleware(['auth']);

Route::get('/addPlayList', [listController::class, 'addSongToPlayList'])->middleware(['auth']);

Route::get('/showPlayList', [listController::class, 'showPlay'])->middleware(['auth']);

Route::get('/saveList', [listController::class, 'saveList'])->middleware(['auth']);

Route::get('/genre', [songController::class , 'genreSort'])->middleware(['auth']);

Route::get('/genres', [songController::class, 'genresShow'])->middleware(['auth']);

Route::get('/removePlaySong', [listController::class , 'removePlaySong'])->middleware(['auth']);

Route::get('/removeSong', [listController::class , 'removeSong'])->middleware(['auth']);

Route::get('/removePlayList', [listController::class, 'removePlayList'])->middleware(['auth']);

Route::get('/removeList', [listController::class, 'removeList'])->middleware(['auth']);

Route::Get('/editPlayList' , [listController::class, 'editPlayList'])->middleware(['auth']);
Route::post('/editPlayList', [listController::class, 'confirmEditPlayList'])->middleware(['auth']);

Route::get('/editList' , [listController::class, 'editList'])->middleware(['auth']);
Route::post('/editList', [listController::class, 'confirmEditList'])->middleware(['auth']);

Route::get('/details', [songController::class, 'details'])->middleware(['auth']);

require __DIR__.'/auth.php';
