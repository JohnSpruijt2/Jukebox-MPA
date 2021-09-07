<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function usingview(){
        $data = [
            "name"=>"John Doe",
            "age" => "23"

        ];
        return view('bob', ["data"=>$data]);
    }
}
