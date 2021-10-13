<?php

namespace App\Http\Controllers;

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
        $songs = Song::getSongs();
        $lists = SavedList::where('userId', $user->id)->get();
        $genres = Genre::all();
        return view('songs', ['songs' => $songs, 'lists' => $lists, 'playLists' => Session::get('saved_list'), 'genres' => $genres]);
    }

    public function genreSort(Request $request) {
        $user = Auth::user();
        $id = $request->id;
        $songs = Song::getSongsByGenre($id);
        $lists = DB::select('select * from saved_lists where userId='.$user->id);
        $genres = DB::select('select * from `genres`');
        return view('songs', ['songs' => $songs, 'lists' => $lists, 'playLists' => Session::get('saved_list'), 'genres' => $genres]);
    }

    public function genresShow() {
        $genres = DB::select('select * from `genres`');
        return view('genres' , ['genres' => $genres]);
    }

    public function details(Request $request) {
        $id = $request->id;
        $details = DB::select('select * from songs where id='.$id);
        $genres = DB::select('select * from `genres`');
        foreach ($details as $detail) {             //convert seconds into minutes and seconds
            $duration = $detail->duration;
            $minutes = floor($duration/60);
            $second = $minutes*60;
            $seconds = $duration-$second;
            if ($seconds <10) {
                $seconds = '0'.$seconds;
            }
            $detail->duration = $minutes.':'.$seconds;
        }
        return view('details' , ['details' => $details[0], 'genres' => $genres]);
    }
}
