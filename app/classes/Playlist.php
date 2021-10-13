<?php
namespace App\Classes;
use Session;
use Auth;
class Playlist 
{
    public $name;
    public $songs = array();

    public static function getSavedList() {
        return Session::get('saved_list');
    }
    
    public static function deletePlayList() {
        return Session::put('saved_list', null);
    }

    public function __construct($name) {
        $this->name = $name;
        $this->updateSession();
    }


    public function changeName($name) {
        $this->name = $name;
    }
    
    public function getAll() {
        return $this;
    }

    public function getSongs() {
        return $this->songs;
    }

    public function addSong($id) {
        $newSong = new Song($id);
        array_push($this->songs, $newSong);
        $this->updateSession();
    }

    public function removeSong($id) {
        for ($i = 0; $i < count($this->songs); $i++) {
            if ($this->songs[$i] != null) {
                if ($this->songs[$i]->id == $id) {
                    unset($this->songs[$i]);
                }
            }
            
        }
        $this->songs = array_values($this->songs);
    }

    private function updateSession() {
        Session::put('saved_list', $this);
    }
}