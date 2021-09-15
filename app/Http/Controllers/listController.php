<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
class listController extends Controller
{
    //
    public function create(Request $request) {
        $name = $request->input('listName');
        $user = Auth::user();
        DB::insert('INSERT INTO `saved_lists`(`name`, `userId`) VALUES ("'.$name.'",'.$user->id.')');
        return redirect('/dashboard');
    }
}
