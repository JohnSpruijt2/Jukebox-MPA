<?php

namespace App\Http\Controllers;

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
        $songs = DB::select('select * from songs');
        foreach ($songs as $song) {             //convert seconds into minutes and seconds
            $duration = $song->duration;
            $minutes = floor($duration/60);
            $second = $minutes*60;
            $seconds = $duration-$second;
            if ($seconds <10) {
                $seconds = '0'.$seconds;
            }
            $song->duration = $minutes.':'.$seconds;
        }
        $lists = DB::select('select * from saved_lists where userId='.$user->id);
        $genres = DB::select('select * from `genres`');
        return view('songs', ['songs' => $songs, 'lists' => $lists, 'playLists' => Session::get('saved_list')[0], 'genres' => $genres]);
    }

    public function genreSort(Request $request) {
        $user = Auth::user();
        $id = $request->id;
        $songs = DB::select('select * from songs where genre_id='.$id);
        foreach ($songs as $song) {             //convert seconds into minutes and seconds
            $duration = $song->duration;
            $minutes = floor($duration/60);
            $second = $minutes*60;
            $seconds = $duration-$second;
            if ($seconds <10) {
                $seconds = '0'.$seconds;
            }
            $song->duration = $minutes.':'.$seconds;
        }
        $lists = DB::select('select * from saved_lists where userId='.$user->id);
        $genres = DB::select('select * from `genres`');
        return view('songs', ['songs' => $songs, 'lists' => $lists, 'playLists' => Session::get('saved_list')[0], 'genres' => $genres]);
    }

    public function genresShow() {
        $genres = DB::select('select * from `genres`');
        return view('genres' , ['genres' => $genres]);
    }
}
