<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;

class indexController extends Controller
{
    //
    public function index() {
        $songs = DB::select('select * from songs');

        return view('songs', ['songs' => $songs]);
    }
}
