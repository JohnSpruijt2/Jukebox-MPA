<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $playlists = DB::select('select * from saved_lists where userId=' . $user->id);
        var_dump(Session::get('saved_list'));
        return view('dash', ['playlists' => $playlists]);
    }
}
