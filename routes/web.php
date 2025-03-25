<?php

use App\Http\Controllers\SensorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NormalRoutes;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AqiHistoriesController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('index');
});

Route::get('/login', [AuthController::class,'showLogin'])->name('index.login');
Route::post('/login', [AuthController::class,'login'])->name('login');

Route::post('/add-location', [SensorController::class, 'store'])->name('sensor.store');


Route::get('/update-aqi/{sensorId}', [SensorController::class, 'updateAQI']);
Route::get('/api/sensors/aqi', [SensorController::class, 'getAQI']);


Route::post('/register', [AuthController::class,'register'])->name('register');

Route::post('/logout', [AuthController::class,'logout'])->name('logout');

Route::middleware('auth')->group(function (){
    Route::get('/register', [AuthController::class,'showRegister'])->name('dashboard.register');
    Route::get('/dashboard', [NormalRoutes::class,'dashboard'])->name('dashboard'); 
});

Route::get('/', [NormalRoutes::class,'home'])->name('home');
Route::get('/about', [NormalRoutes::class,'about'])->name('about');
Route::get('/contact', [NormalRoutes::class,'contact'])->name('contact');


Route::get('/historical', [AqiHistoriesController::class, 'index'])->name('historical');
Route::get('/historical/days/{sensor}/{month}', [AqiHistoriesController::class, 'getDays']);
Route::get('/historical/data/{sensor}/{month}/{day}', [AqiHistoriesController::class, 'getData']);

Route::get('/sensor/{sensorId}/historical-aqi', [AqiHistoriesController::class, 'getHistoricalAQI']);


