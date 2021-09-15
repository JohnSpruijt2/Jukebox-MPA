<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
class songController extends Controller
{
    //
    public function index() {
        $songs = DB::select('select * from songs');
        foreach ($songs as $song) {
            $duration = $song->duration;
            $minutes = floor($duration/60);
            $second = $minutes*60;
            $seconds = $duration-$second;
            if ($seconds <10) {
                $seconds = '0'.$seconds;
            }
            $song->duration = $minutes.':'.$seconds;
        }
        return view('songs', ['songs' => $songs]);
    }
}
