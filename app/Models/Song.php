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
}
