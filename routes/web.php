<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SlotController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'isadmin'])->group(function () {
    Route::get('/', [SlotController::class, 'home'])->name('home');
    Route::get('/dashboard', [SlotController::class, 'index'])->name('dashboard');
    Route::resource('slot', SlotController::class);
    Route::post('/fetch-slot', [SlotController::class, 'fetchSlot'])->name('fetchSlot');
    Route::post('/book-appointment', [SlotController::class, 'bookAppointment'])->name('bookAppointment');
});