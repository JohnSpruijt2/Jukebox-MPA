<?php

namespace App\Http\Controllers;

use App\Http\Controllers\listController;

use App\Models\Song;
use App\Models\SavedList;
use App\Models\Genre;

use Illuminate\Http\Request;
use Auth;
use DB;
use Cookie;
use Tracker;
use Session;
class songController extends Controller
{
    //
    public function index() {
        $user = Auth::user();
        $songs = Song::all();
        $songs = listController::calculateTime($songs);
        $lists = SavedList::where('userId', $user->id)->first();
        $genres = Genre::all();
        return view('songs', ['songs' => $songs, 'lists' => $lists, 'playLists' => Session::get('saved_list'), 'genres' => $genres]);
    }

    public function genreSort(Request $request) {
        $user = Auth::user();
        $id = $request->id;
        $songs = Song::where('genre_id', $id)->get();
        $lists = SavedList::where('userId', $user->id)->get();
        $genres = Genre::all();
        return view('songs', ['songs' => $songs, 'lists' => $lists, 'playLists' => Session::get('saved_list'), 'genres' => $genres]);
    }

    public function genresShow() {
        $genres = Genre::all();
        return view('genres' , ['genres' => $genres]);
    }

    public function details(Request $request) {
        $id = $request->id;
        $details = Song::where('id', $id)->get();
        $genres = Genre::all();
        $details = listController::calculateTime($details);
        return view('details' , ['details' => $details[0], 'genres' => $genres]);
    }
}
