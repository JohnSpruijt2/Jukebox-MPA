<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedListsSong extends Model
{
    use HasFactory;
    public static function insertSong($sid, $lid) {
        SavedListsSong::insert([
            'songId' => $sid,
            'listId' => $lid,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    public static function removeListsSong($id) {
        SavedListsSong::where('listId', $id)->delete();
    }
}
