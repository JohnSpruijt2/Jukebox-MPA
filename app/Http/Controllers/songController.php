<?php

namespace App\Http\Controllers;

use App\Http\Controllers\listController;
use App\Classes\Playlist;
use App\Models\Song;
use App\Models\SavedList;
use App\Models\SavedListsSong;
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
        if (SavedList::getListByUser($user->id)['id'] != NULL) {
            $lists = SavedList::getListsByUser($user->id);
        } else {
            $lists = NULL;
        }
        $genres = Genre::all();
        return view('songs', ['songs' => $songs, 'lists' => $lists, 'playLists' => Playlist::getSavedList(), 'genres' => $genres]);
    }

    public function genreSort(Request $request) {
        $user = Auth::user();
        $id = $request->id;
        $songs = Song::getSongsByGenre($id);
        $songs = listController::calculateTime($songs);
        $lists = SavedList::getListsByUser('userId', $user->id);
        $genres = Genre::all();
        return view('songs', ['songs' => $songs, 'lists' => $lists, 'playLists' => Playlist::getSavedList(), 'genres' => $genres]);
    }

    public function genresShow() {
        $genres = Genre::all();
        return view('genres' , ['genres' => $genres]);
    }

    public function details(Request $request) {
        $id = $request->id;
        $details = Song::getSongById($id);
        $genres = Genre::all();
        $details = listController::calculateTime($details);
        return view('details' , ['details' => $details[0], 'genres' => $genres]);
    }
}
