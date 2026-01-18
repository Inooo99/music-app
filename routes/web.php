<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\SongController;
use App\Http\Controllers\User\MusicController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- AUTHENTICATION ---
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- ADMIN ROUTES ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/songs/{id}/edit', [SongController::class, 'edit'])->name('songs.edit'); // Form Edit
    Route::put('/songs/{id}', [SongController::class, 'update'])->name('songs.update');  // Proses Update
    
    // Dashboard (Read)
    Route::get('/dashboard', [SongController::class, 'index'])->name('dashboard');
    
    // Create (Store)
    Route::post('/songs', [SongController::class, 'store'])->name('songs.store');
    
    // Delete (Destroy)
    Route::delete('/songs/{id}', [SongController::class, 'destroy'])->name('songs.destroy');
    
});

// --- USER PLAYER PLACEHOLDER ---
Route::middleware(['auth'])->get('/music', [MusicController::class, 'index'])->name('user.player');