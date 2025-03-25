<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensor;

class NormalRoutes extends Controller
{
    public function contact(){
        return view('contact');
    }
    public function about(){
        return view('about');
    }
    public function home(){
        $sensors = Sensor::all();
        return view('index', compact('sensors'));
    }
    public function dashboard(){
        $sensors = Sensor::all();
        return view('dashboard', compact('sensors'));
    }
}
