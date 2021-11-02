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
}
