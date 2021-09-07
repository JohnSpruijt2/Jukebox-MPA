<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CreateController extends Controller
{
     public function index(){
        $name = "Tony Stack";
        return view('home', compact('name'));
    }
}
