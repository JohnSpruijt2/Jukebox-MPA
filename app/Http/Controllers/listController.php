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
            $id = count(Session::get('saved_list')[0]);
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
        $genres = DB::select('select * from `genres`');
        return view('showList', ['list' => $listInfo[0], 'songs' => $songs, 'genres' => $genres]);
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
        $songsIds = Session::get('saved_song')[0];
        $id = $request->id;
        $listInfo = $list[$id-1];
        $songs = null;
        if ($user->id != $listInfo['userId']) {
            return redirect('/dashboard');
        }
        if (Session::get('saved_song') != null) {
            
            $songs = [];
            foreach ($songsIds as $songId) {
                if ($songId['lid'] == $id) {
                    $song = DB::select('SELECT * FROM songs where id='.$songId['sid']);
                
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
        }
        $genres = DB::select('select * from `genres`');
        return view('showPlayList' , ['list' => $listInfo, 'songs' => $songs, 'genres' => $genres]);
        
    }

    public function saveList(Request $request) {
        $user = Auth::user();
        $list = Session::get('saved_list')[0];
        $savedSongs = Session::get('saved_song')[0];
        $id = $request->id;
        $listInfo = $list[$id-1];
        if ($user->id != $listInfo['userId']) {
            return redirect('/dashboard');
        }
        DB::insert('INSERT INTO `saved_lists`(`name`, `userId`) VALUES ("'.$listInfo['name'].'",'.$user->id.')');

        $newListId = DB::select('SELECT * FROM `saved_lists` ORDER BY id DESC LIMIT 0, 1')[0]->id;
        
        if ($savedSongs != null) {
            foreach ($savedSongs as $savedSong) {
                if ($savedSong['lid'] == $id) {
                    DB::insert('INSERT INTO `saved_lists_songs`(`songId`, `listId`) VALUES ("'.$savedSong['sid'].'",'.$newListId.')');
                }
                
            }
        }
        $sessionTemp = Session::get('saved_list');
        $sessionTemp[0][$id-1] = null;
        Session::put('saved_list', $sessionTemp);
        return redirect('/showList?id='.$newListId);
    }

    public function removePlaySong(Request $request) {
        $sid = $request->sid;
        $lid = $request->lid;
        $savedSongs = Session::get('saved_song');
        $i = 0;
        foreach ($savedSongs[0] as $savedSong) {
            if ($savedSong['sid'] == $sid && $savedSong['lid'] == $lid) {
                unset($savedSongs[0][$i]);
            }
            $i++;
        }
        $newSavedSongs = [array_values($savedSongs[0])];
        Session::put('saved_song', $newSavedSongs);
        return redirect('/showPlayList?id='.$lid);
    }

    public function removeSong(Request $request) {
        $sid = $request->sid;
        $lid = $request->lid;
        DB::delete('delete from saved_lists_songs where songId='.$sid.' AND listId='.$lid);
        return redirect('/showList?id='.$lid);
    }
    
}
