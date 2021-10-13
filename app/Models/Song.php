<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    public function genre() {
        return $this->belongsTo('app/Genre'); //remnant from when i tried linking them together, didnt work but i will let it stay to show i tried
    }

    public static function getSongs() {
    $songs = Song::all();
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

    public static function getSongsByGenre($id) {
        $songs = Song::where('genre_id', $id)->get();

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
}
