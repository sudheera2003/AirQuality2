<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NormalRoutes extends Controller
{
    public function contact(){
        return view('contact');
    }
    public function about(){
        return view('about');
    }
    public function home(){
        return view('index');
    }
    public function dashboard(){
        return view('dashboard');
    }
}
