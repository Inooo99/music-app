<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Song;

class MusicController extends Controller
{
    public function index()
    {
        // Ambil semua lagu
        $songs = Song::all();
        return view('user.player', compact('songs'));
    }
}