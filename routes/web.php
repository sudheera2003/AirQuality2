<?php

use App\Http\Controllers\SensorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NormalRoutes;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AqiHistoriesController;
use App\Http\Controllers\ContactController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('index');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('index.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');



Route::get('/update-aqi/{sensorId}', [SensorController::class, 'updateAQI']);
Route::get('/api/sensors/aqi', [SensorController::class, 'getAQI']);




Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware('auth')->group(function () {
    Route::post('/add-location', [SensorController::class, 'store'])->name('sensor.store');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('dashboard.register');
    Route::get('/dashboard', [NormalRoutes::class, 'dashboard'])->name('dashboard');
    Route::post('/delete-location', [SensorController::class, 'destroy'])->name('sensor.destroy');

    Route::get('/dashboard', [AdminController::class, 'manageAdmins'])->name('dashboard');
    Route::post('/update-admin', [AdminController::class, 'updateAdmin'])->name('updateAdmin');
    Route::delete('/admin/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
});


Route::get('/', [NormalRoutes::class, 'home'])->name('home');
Route::get('/about', [NormalRoutes::class, 'about'])->name('about');
Route::get('/contact', [NormalRoutes::class, 'contact'])->name('contact');


Route::get('/historical', [AqiHistoriesController::class, 'index'])->name('historical');
Route::get('/historical/days/{sensor}/{month}', [AqiHistoriesController::class, 'getDays']);
Route::get('/historical/data/{sensor}/{month}/{day}', [AqiHistoriesController::class, 'getData']);

Route::get('/api/sensors/{sensor}/history', [AqiHistoriesController::class, 'history'])->name('api.sensors.history');

Route::get('/sensor/{sensorId}/historical-aqi', [AqiHistoriesController::class, 'getHistoricalAQI']);

Route::post('/contact/send', [ContactController::class, 'send']);

Route::put('/sensors/{id}', [SensorController::class, 'update'])->name('api.sensors.update');
Route::get('/sensors/{id}', [SensorController::class, 'show'])->name('api.sensors.show');