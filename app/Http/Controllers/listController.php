<?php

namespace App\Http\Controllers;
use App\Classes\Playlist;
use App\Models\Song;
use App\Models\SavedList;
use App\Models\SavedListsSong;
use App\Models\Genre;

use Illuminate\Http\Request;
use Auth;
use DB;
use Cookie;
use Tracker;
use Session;
class listController extends Controller
{
    //

    public static function calculateTime($songs) {
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
        return $songs;
    }

    public static function calculateTotalTime($songs) {
        $totalDuration = 0;
        foreach ($songs as $song) {             //convert seconds into minutes and seconds
            $duration = $song->duration;
            $tempTotalDuration = $totalDuration + $duration; //had to make a temporary int to be able to properly sum up the the two ints
            $totalDuration = $tempTotalDuration;
        }
        $minutes = floor($totalDuration/60); //convert TOTAL duration seconds into minutes and seconds
        $second = $minutes*60;
        $seconds = $totalDuration-$second;
        if ($seconds <10) {
            $seconds = '0'.$seconds;
        }
        $totalDuration = $minutes.':'.$seconds;
        return $totalDuration;
    }

    public function create(Request $request) {
        $name = $request->input('listName');
        if ($name != null) {
            new Playlist($name);
        }
        return redirect('/dashboard');
    }

    public function createListCheck() {
        if (Playlist::getSavedList() != null) {
            return redirect('/dashboard');
        }
        return view('createList');
    }

    public function show(Request $request) {
        $user = Auth::user();
        $listId = $request->id;
        $list = SavedList::getListById($listId)[0];
        if ($list->userId != $user->id) {
            return redirect('/dashboard');
        }
        $songs = Song::getSongsByList($listId);
        $totalDuration = $this->calculateTotalTime($songs);
        $songs = $this->calculateTime($songs);

        $genres = Genre::all();
        return view('showList', ['list' => $list, 'songs' => $songs, 'genres' => $genres, 'totalDuration' =>$totalDuration]);
    }

    public function addSongToList(Request $request) {
        $user = Auth::user();
        $listId = $request->lid;
        $songId = $request->sid;
        $list = SavedList::getListById($listId);
        $songs = Song::getSongsByList($listId);
        if ($list[0]->userId != $user->id) {
            return redirect('/dashboard');
        }
        foreach($songs as $song) {
            if ($song->songId == $songId && $song->listId == $listId) {
                return redirect('/showList?id='.$listId);
            }
        }
        SavedListsSong::insertSong($songId, $listId);
        return redirect('/showList?id='.$listId);
    }

    public function addSongToPlayList(Request $request) {
        $songId = $request->sid;
        $list = new Playlist;
        $list->addSong($songId);
        return redirect('/showPlayList');
    }

    public function showPlay(Request $request) {
        $list = new Playlist;
        $listAll = $list->getAll();
        $songs = array();
        $genres = Genre::all();
        if ($list->getSongs() != null) {
            foreach($list->getSongs() as $song) {
                $dbSong = Song::getSongById($song);
                array_push($songs, $dbSong);
            }
            $totalDuration = $this->calculateTotalTime($songs);
            $songs = $this->calculateTime($songs);
        } else {
            $songs = null;
            $totalDuration = '0:00';
        }
        
        
        return view('showPlayList' , ['list' => $listAll, 'genres' => $genres, 'songs' => $songs, 'totalDuration' => $totalDuration]);
    }

    public function saveList() {
        $user = Auth::user();
        $list = new Playlist;
        $listData = $list->getAll();
        SavedList::insertList($listData[0], $user->id);
        $listId = SavedList::latest()->first()->id;
        
        if ($list->getSongs() != null) {
            foreach ($list->getSongs() as $song) {
                SavedListsSong::insertSong($song, $listId);            
            }
        }
        $list->deletePlaylist();
        return redirect('/showList?id='.$listId);
    }

    public function removePlaySong(Request $request) {
        $list = new Playlist;
        $list->removeSong($request->sid);

        return redirect('/showPlayList');
    }

    public function removeSong(Request $request) {
        $sid = $request->sid;
        $lid = $request->lid;
        SavedListsSong::where('songId', $sid)->where('listId', $lid)->delete();
        return redirect('/showList?id='.$lid);
    }
    
    public function removePlayList() {
        $list = new Playlist;
        $list->deletePlaylist();
        return redirect('/dashboard');
    }

    public function removeList(Request $request) {
        $lid = $request->id;
        SavedList::removeList($lid);
        SavedListsSong::removeListsSong($lid);
        return redirect('/dashboard');
    }

    public function editPlayList(Request $request) {
        $list = new Playlist;
        $listData = $list->getAll();
        return view('editPList' , ['type' => 'temp','list' => $listData]);
    }

    public function confirmEditPlayList(Request $request) {
        $list = new Playlist;
        $list->changeName($request->name);
        return redirect('/showPlayList');
    }

    public function editList(Request $request) {
        $user = Auth::user();
        $lid = $request->id;
        $list = SavedList::getListById($lid)[0];
        if ($list->userId != $user->id) {
                return redirect('/dashboard');
        }
        return view('editList' , ['type' => 'saved','list' => $list]);
    }

    public function confirmEditList(Request $request) {
        $user = Auth::user();
        $lid = $request->id;
        $name = $request->input('name');
        $list = SavedList::getListById($lid)[0];
        if ($list->userId != $user->id || $name == '') {
                return redirect('/dashboard');
        }
        SavedList::updateListName($lid, $name);
        return redirect('/dashboard');
    }
}