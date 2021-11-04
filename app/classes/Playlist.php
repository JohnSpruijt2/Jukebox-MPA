<?php
namespace App\Classes;
use Session;
use Auth;
class Playlist 
{
    private $name;
    private $songs = array();

    private function updateSession() {
        Session::put('saved_list', [$this->name, $this->songs]);
    }
    
    public function deletePlayList() {
        Session::put('saved_list', null);
    }

    public function __construct() {
        if (Session::get('saved_list') != null) {
            $this->name = Session::get('saved_list')[0];
            $this->songs = Session::get('saved_list')[1];
        } else {
            $this->name = 'Playlist';
            Session::put('saved_list', [$this->name, $this->songs]);
        }
        
    }


    public function changeName($name) {
        $this->name = $name;
        $this->updateSession();
    }
    public function getName() {
        return $this->name;
    }
    
    public function getAll() {
        return [$this->name, $this->songs];
    }

    public function getSongs() {
        return $this->songs;
    }

    public function addSong($id) {
        array_push($this->songs, $id);
        $this->updateSession();
    }

    public function removeSong($id) {
        for ($i = 0; $i < count($this->songs); $i++) {
            if ($this->songs[$i] != null) {
                if ($this->songs[$i] == $id) {
                    unset($this->songs[$i]);
                }
            }
            
        }
        $this->songs = array_values($this->songs);
        $this->updateSession();
    }

    
}