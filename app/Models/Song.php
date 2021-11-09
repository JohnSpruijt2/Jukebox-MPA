<?php

namespace App\Models;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;
    protected $fillable = array('name', 'artist', 'duration', 'created_at', 'updated_at');
    public function genre() {
        return $this->belongsTo(Genre::class); //remnant from when i tried linking them together, didnt work but i will let it stay to show i tried
    }

    public static function getSongsByList ($id) {
        return Song::join('saved_lists_songs', 'songs.id' , '=', 'saved_lists_songs.songId')->where('listId', $id)->get();
    }

    public static function getSongById($id) {
        return Song::where('id', $id)->get()[0];
    }
    
    public static function getSongsByGenre($genre) {
        return Song::where('genre_id', $genre)->get();
    }
}