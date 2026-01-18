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
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Cek role user, jika admin ke dashboard, jika user biasa ke player
            // Sesuaikan dengan kebutuhanmu, default ke home user
            return redirect()->intended('/music'); 
        }

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

    // --- BAGIAN REGISTER (BARU) ---
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
            'password' => 'required|string|min:4|confirmed', // Pastikan input password_confirmation ada di view
        ]);

        // 2. Buat User Baru di Database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Jika kamu punya kolom 'role', bisa tambahkan defaultnya disini, misal: 'role' => 'user'
        ]);

        // 3. Langsung Login otomatis setelah daftar
        Auth::login($user);

        // 4. Redirect ke halaman musik/player
        return redirect()->route('user.player');
    }
}