<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Song;
use Illuminate\Support\Facades\Storage;

class SongController extends Controller
{
    // Menampilkan Dashboard Admin
    public function index()
    {
        // Ambil semua lagu urut dari yang terbaru
        $songs = Song::latest()->get();
        return view('admin.dashboard', compact('songs'));
    }

    // Menyimpan Lagu Baru
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'artis' => 'required|string|max:255',
            'genre' => 'required|string',
            'durasi' => 'required|string',
            'audio' => 'required|file|max:50000', // Validasi audio
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar (Max 2MB)
            'lyrics' => 'nullable|string',
        ]);

        // 1. Upload Audio
        $audioPath = null;
        if ($request->hasFile('audio')) {
            $audioPath = $request->file('audio')->store('songs', 'public');
        }

        // 2. Upload Cover (LOGIKA BARU)
        $coverPath = null;
        if ($request->hasFile('cover')) {
            // Simpan ke folder 'covers' di storage public
            $coverPath = $request->file('cover')->store('covers', 'public');
        } else {
            // Jika admin tidak upload cover, pakai gambar default
            $coverPath = 'covers/default_album.jpg'; 
            // Pastikan Anda punya file default_album.jpg di storage/app/public/covers/ atau biarkan null
        }

        // 3. Simpan ke Database
        Song::create([
            'judul' => $request->judul,
            'artis' => $request->artis,
            'genre' => $request->genre,
            'durasi' => $request->durasi,
            'file_path' => $audioPath,
            'cover_path' => $coverPath, // Simpan path gambar
            'lyrics' => $request->lyrics
        ]);

        return redirect()->back()->with('success', 'Lagu berhasil ditambahkan!');
    }

    // Menghapus Lagu
    public function destroy($id)
    {
        $song = Song::findOrFail($id);

        // Hapus file fisik jika ada
        if ($song->file_path && Storage::disk('public')->exists($song->file_path)) {
            Storage::disk('public')->delete($song->file_path);
        }

        $song->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Lagu berhasil dihapus.');
    }

    // 1. Menampilkan Form Edit dengan Data Lama
    public function edit($id)
    {
        $song = Song::findOrFail($id);
        return view('admin.edit', compact('song'));
    }

    // 2. Proses Update Data
    public function update(Request $request, $id)
    {
        $song = Song::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'artis' => 'required|string|max:255',
            'genre' => 'required|string',
            'durasi' => 'required|string',
            // File bersifat nullable (opsional), hanya validasi jika user upload file baru
            'audio' => 'nullable|file|mimes:mp3,wav,flac,mpeg,mpga,bin,application/octet-stream|max:50000',
            'cover' => 'nullable|image|max:2048',
            'lyrics' => 'nullable|string',
        ]);

        // Logic Update Audio (Hanya jika ada file baru)
        if ($request->hasFile('audio')) {
            // Hapus file lama biar server gak penuh
            if ($song->file_path && Storage::disk('public')->exists($song->file_path)) {
                Storage::disk('public')->delete($song->file_path);
            }
            // Upload baru
            $song->file_path = $request->file('audio')->store('songs', 'public');
        }

        // Logic Update Cover (Hanya jika ada file baru)
        if ($request->hasFile('cover')) {
            if ($song->cover_path && Storage::disk('public')->exists($song->cover_path)) {
                Storage::disk('public')->delete($song->cover_path);
            }
            $song->cover_path = $request->file('cover')->store('covers', 'public');
        }

        // Update Text Data
        $song->update([
            'judul' => $request->judul,
            'artis' => $request->artis,
            'genre' => $request->genre,
            'durasi' => $request->durasi,
            'lyrics' => $request->lyrics,
            // file_path & cover_path sudah dihandle diatas secara langsung ke objek $song
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Lagu berhasil diperbarui!');
    }

}