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

    public function show(Request $request) {
        $user = Auth::user();
        $listId = $request->id;
        $list = DB::select('select * from saved_lists where userId=' . $listId);
        if ($list->userId != $user->id) {

        }
        return view('showList');
    }
}
