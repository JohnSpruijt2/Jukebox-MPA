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
            $duration = $duration/60;
            floor($duration);
        }
        return view('songs', ['songs' => $songs]);
    }
}
