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

// Group Guest: Hanya bisa diakses kalau BELUM login
Route::middleware('guest')->group(function () {
    // Login (Kode Lama Kamu)
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Register (KODE BARU - TAMBAHAN)
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// --- ADMIN ROUTES (TIDAK SAYA UBAH SAMA SEKALI) ---
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