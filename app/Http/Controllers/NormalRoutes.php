<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensor;
use App\Models\SensorStatus;

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
        $statuses = SensorStatus::all(); 
        return view('dashboard', compact('sensors','statuses'));

    }
}
