<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
class dashController extends Controller
{
    //
    public function index() {
        $user = Auth::user();
        $songs = DB::select('select * from saved_lists where userId=' . $user->id);

        return view('songs', ['songs' => $songs]);
    }
}
