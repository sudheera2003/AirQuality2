<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NormalRoutes;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('index');
});

Route::get('/login', [AuthController::class,'showLogin'])->name('index.login');
Route::post('/login', [AuthController::class,'login'])->name('login');


Route::post('/register', [AuthController::class,'register'])->name('register');

Route::post('/logout', [AuthController::class,'logout'])->name('logout');

Route::middleware('auth')->group(function (){
    Route::get('/register', [AuthController::class,'showRegister'])->name('dashboard.register');
    Route::get('/dashboard', [NormalRoutes::class,'dashboard'])->name('dashboard'); 
});

Route::get('/', [NormalRoutes::class,'home'])->name('home');
Route::get('/about', [NormalRoutes::class,'about'])->name('about');
Route::get('/contact', [NormalRoutes::class,'contact'])->name('contact');





