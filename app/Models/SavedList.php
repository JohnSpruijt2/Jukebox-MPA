<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedList extends Model
{
    use HasFactory;
    public static function insertList($name, $id) {
        SavedList::insert([
            'name' => $name,
            'userId' => $id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    public static function getListById($id) {
        return SavedList::where('id', $id)->get();
    }
    public static function getListsByUser($user) {
        return SavedList::where('userId', $user)->get();
    }
    public static function getListByUser($user) {
        return SavedList::where('userId', $user)->first();
    }
    public static function removeList($id) {
        SavedList::where('id', $id)->delete();
    }
    public static function updateListName($id, $name) {
        SavedList::where('id',$id)->update([
            'name' => $name,
            'updated_at' => now()
        ]);
    }
}
