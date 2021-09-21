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

Route::get('genre', [songController::class , 'genreSort'])->middleware(['auth']);

require __DIR__.'/auth.php';
