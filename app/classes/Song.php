<?php 
namespace App\Classes;

class Song
{
    public $id;
    public function __construct($id) {
        $this->id = $id;
    }
}