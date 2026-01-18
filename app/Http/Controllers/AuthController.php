<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // --- BAGIAN LOGIN (Kode Lama/Standar) ---
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    // 1. Validasi input
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // 2. Coba Login
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // --- LOGIKA PENENTUAN ARAH (DISINI PERUBAHANNYA) ---
        
        // Ambil data user yang sedang login
        $user = Auth::user();

        // Cek Role-nya
        // Pastikan tulisan 'admin' sesuai persis dengan isi database kamu (huruf kecil/besar berpengaruh)
        if ($user->role === 'admin') {
            // Jika Admin, lempar ke Dashboard Admin
            return redirect()->route('admin.dashboard');
        } else {
            // Jika User Biasa, lempar ke Player Musik
            return redirect()->route('user.player');
        }
    }

    // 3. Jika Gagal Login
    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
}
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // --- BAGIAN REGISTER ---
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4|confirmed',
        ]);

        // 2. Buat User Baru di Database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Default role user biasa
            'role' => 'user', 
        ]);

        // 3. (HAPUS ATAU KOMENTAR BARIS INI SUPAYA TIDAK AUTO-LOGIN)
        // Auth::login($user); 

        // 4. Lempar ke halaman Login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}