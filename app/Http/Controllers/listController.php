<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Cookie;
use Tracker;
use Session;
class listController extends Controller
{
    //
    public function create(Request $request) {
        
        $name = $request->input('listName');
        $user = Auth::user();
        $newData = array('name'=>$name, 'userId'=>$user->id);
        if (Session::get('saved_list') == null) {
            $data = [];
        } else {
            $data = Session::get('saved_list');
        }
        
        array_push($data, $newData);
        Session::put('saved_list', [$data]);

        //DB::insert('INSERT INTO `saved_lists`(`name`, `userId`) VALUES ("'.$name.'",'.$user->id.')');
        return redirect('/dashboard');
    }

    public function show(Request $request) {
        $user = Auth::user();
        $listId = $request->id;
        $listInfo = DB::select('select * from saved_lists where id=' . $listId);
        if ($listInfo[0]->userId != $user->id) {
            return redirect('/dashboard');
        }
        $songs = DB::select('SELECT * FROM songs INNER JOIN saved_lists_songs ON songs.id=saved_lists_songs.SongId where listId='.$listId);

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

        return view('showList', ['list' => $listInfo, 'songs' => $songs]);
    }

    public function addSongToList(Request $request) {
        $user = Auth::user();
        $listId = $request->lid;
        $songId = $request->sid;
        $list = DB::select('select * from saved_lists where id=' . $listId);
        if ($list[0]->userId != $user->id) {
            return redirect('/dashboard');
        }
        DB::insert('INSERT INTO `saved_lists_songs`(`songId`,`listId`) VALUES ('.$songId.','.$listId.')');
        return redirect('/showList?id='.$listId);

    }
}
