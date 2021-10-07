<?php

namespace App\Http\Controllers;
use App\Classes\Playlist;
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
        if ($name == null) {
            return redirect('/createList');
        }
        if (Session::get('saved_list') != null) {
            return redirect('/createList');
        }
        
        $playlist = new Playlist($name);

        Session::put('saved_list', $playlist);

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
        $totalDuration = 0;
        foreach ($songs as $song) {             //convert seconds into minutes and seconds
            $duration = $song->duration;
            $tempTotalDuration = $totalDuration + $duration; //had to make a temporary int to be able to properly sum up the the two ints
            $totalDuration = $tempTotalDuration;
            $minutes = floor($duration/60);
            $second = $minutes*60;
            $seconds = $duration-$second;
            if ($seconds <10) {
                $seconds = '0'.$seconds;
            }
            $song->duration = $minutes.':'.$seconds;
        }
        $minutes = floor($totalDuration/60); //convert total duration seconds into minutes and seconds
        $second = $minutes*60;
        $seconds = $totalDuration-$second;
        if ($seconds <10) {
            $seconds = '0'.$seconds;
        }
        $totalDuration = $minutes.':'.$seconds;
        


        $genres = DB::select('select * from `genres`');
        return view('showList', ['list' => $listInfo[0], 'songs' => $songs, 'genres' => $genres, 'totalDuration' =>$totalDuration]);
    }

    public function addSongToList(Request $request) {
        $user = Auth::user();
        $listId = $request->lid;
        $songId = $request->sid;
        $list = DB::select('select * from saved_lists where id=' . $listId);
        $songs = DB::select('select * from saved_lists_songs where listId=' . $listId);
        if ($list[0]->userId != $user->id) {
            return redirect('/dashboard');
        }
        foreach($songs as $song) {
            if ($song->songId == $songId && $song->listId == $listId) {
                return redirect('/showList?id='.$listId);
            }
        }
        DB::insert('INSERT INTO `saved_lists_songs`(`songId`,`listId`) VALUES ('.$songId.','.$listId.')');
        return redirect('/showList?id='.$listId);
    }

    public function addSongToPlayList(Request $request) {
        $user = Auth::user();
        $songId = $request->sid;
        $list = Session::get('saved_list');

        $list->addSong($songId);
        
        Session::put('saved_song', $list);
        return redirect('/showPlayList');
    }




    public function showPlay(Request $request) {
        $user = Auth::user();
        $list = Session::get('saved_list');
        $songs = array();
        $genres = DB::select('select * from `genres`');
        foreach($list->songs as $song) {
            
        }
    return view('showPlayList' , ['list' => $list, 'genres' => $genres]);
        
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
    
    public function removePlayList(Request $request) {
        $lid = $request->id;
        $savedLists = Session::get('saved_list');
        $i = 0;
        foreach ($savedLists[0] as $savedList) {
            if ($savedList['id'] == $lid) {
                $savedLists[0][$i] = null;
            }
            $i++;
        }
        Session::put('saved_list', $savedLists);

        $savedSongs = Session::get('saved_song');
        if ($savedSongs != null) {
            $i = 0;
            foreach ($savedSongs[0] as $savedSong) {
                if ($savedSong['lid'] == $lid) {
                    unset($savedSongs[0][$i]);
                }
            $i++;
            }
            $newSavedSongs = [array_values($savedSongs[0])];
            Session::put('saved_song', $newSavedSongs);
        }
        

        return redirect('/dashboard');
    }

    public function removeList(Request $request) {
        $lid = $request->id;
        DB::delete('delete from saved_lists where id='.$lid);
        DB::delete('delete from saved_lists_songs where listId='.$lid);
        return redirect('/dashboard');
    }

    public function editPlayList(Request $request) {
        $user = Auth::user();
        $lid = $request->id;
        $savedList = Session::get('saved_list');
        return view('editPList' , ['type' => 'temp','list' => $savedList]);
    }

    public function confirmEditPlayList(Request $request) {
        $savedList = Session::get('saved_list');
        $name = $request->name;
        $savedList->changeName($name);
        return redirect('/showPlayList');
    }

    public function editList(Request $request) {
        $user = Auth::user();
        $lid = $request->id;
        $savedList = DB::select('select * from saved_lists where id='.$lid)[0];
        if ($savedList->userId != $user->id) {
                return redirect('/dashboard');
        }
        return view('editList' , ['type' => 'saved','list' => $savedList]);
    }

    public function confirmEditList(Request $request) {
        $user = Auth::user();
        $lid = $request->id;
        $name = $request->input('name');
        $savedList = DB::select('select * from saved_lists where id='.$lid)[0];
        if ($savedList->userId != $user->id || $name == '') {
                return redirect('/dashboard');
        }
        DB::table('saved_lists')
            ->where('id', $lid)
            ->update(['name' => $name]);
        return redirect('/dashboard');
    }
}