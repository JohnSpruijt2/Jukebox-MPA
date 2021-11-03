<?php

namespace App\Http\Controllers;
use App\Classes\Playlist;
use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\SavedList;
use App\Models\SavedListsSong;
use App\Models\Genre;
use Auth;
use DB;
use Cookie;
use Tracker;
use Session;
class dashController extends Controller
{
    //
    public function index() {
        $user = Auth::user();
        if (SavedList::getListByUser($user->id) != null) {
            $playlists = SavedList::getListsByUser($user->id);
        } else {
            $playlists = null;
        }
        $tempPlay = Playlist::getSavedList();
        return view('dash', ['playlists' => $playlists, 'tempPlaylists' => $tempPlay]);
    }
}
