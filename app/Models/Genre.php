<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;
    public function songs() {
        return $this->hasMany('app/Song'); //remnant from when i tried linking them together, didnt work but i will let it stay to show i tried
    }
}
