<?php
namespace App\Classes;
class Playlist 
{
    public $name;
    public $songs = array();

    public function __construct($name) {
        $this->name = $name;
    }

    public function changeName($name) {
        $this->name = $name;
    }

    public function addSong($id) {
        $newSong = new Song($id);
        array_push($this->songs, $newSong);
    }
}