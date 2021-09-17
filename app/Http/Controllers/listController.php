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
        if (Session::get('saved_list') != null) {
            $id = count(Session::get('saved_list'));
            $id++;
        } else {
            $id = 1;
        }
        
        $newData = array('name'=>$name, 'userId'=>$user->id, 'id'=>$id);
        if (Session::get('saved_list') == null) {
            $data = [];
            $middleData = [];
        } else {
            $data = Session::get('saved_list');
            $middleData = $data[0];
        }
        
        array_push($middleData, $newData);
        $data = $middleData;
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

    public function addSongToPlayList(Request $request) {
        $user = Auth::user();
        $listId = $request->lid;
        $songId = $request->sid;
        $list = Session::get('saved_list')[0][$listId-1];
        if ($list['userId'] != $user->id) {
            return redirect('/dashboard');
        }
        if (Session::get('saved_song') == null) {
            $data = [];
            $middleData = [];
        } else {
            $data = Session::get('saved_song');
            $middleData = $data[0];
        }
        $newData = array('sid'=>$songId, 'lid'=>$listId);
        array_push($middleData, $newData);
        $data = $middleData;
        
        Session::put('saved_song', [$data]);
        return redirect('/showPlayList?id='.$listId);
    }




    public function showPlay(Request $request) {
        $user = Auth::user();
        $list = Session::get('saved_list')[0];
        $id = $request->id;
        $listInfo = $list[$id-1];
        if ($user->id != $listInfo['userId']) {
            return redirect('/dashboard');
        }
        if (Session::get('saved_song') != null) {
            $songsIds = Session::get('saved_song')[0];
            $songs = [];
            foreach ($songsIds as $songid) {
                $song = DB::select('SELECT * FROM songs where id='.$listInfo['id']);
                array_push($songs, $song[0]);
            }
            
        }
        
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

        
        return view('showPlayList' , ['list' => $listInfo, 'songs' => $songs]);
    }
}
