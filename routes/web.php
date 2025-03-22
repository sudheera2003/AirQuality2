<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('index');
});

Route::get('/login', [AuthController::class,'showLogin'])->name('index.login');
Route::post('/login', [AuthController::class,'login'])->name('login');

Route::get('/register', [AuthController::class,'showRegister'])->name('dashboard.register');
Route::post('/register', [AuthController::class,'register'])->name('register');

Route::post('/logout', [AuthController::class,'logout'])->name('logout');

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
