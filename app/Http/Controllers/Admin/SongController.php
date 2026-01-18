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
   // 2. Proses Update Data (VERSI FIX)
   public function update(Request $request, string $id)
   {
       // 1. Ambil data lagu lama
       $song = Song::findOrFail($id);

       // 2. Validasi (Sesuaikan nama field dengan form & database kamu)
       $request->validate([
           'judul'  => 'required|string|max:255',
           'artis'  => 'required|string|max:255',
           'genre'  => 'required|string',
           'durasi' => 'required|string',
           'cover'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Pakai 'cover' bukan 'cover_path'
           'audio'  => 'nullable|file|max:50000', // Pakai 'audio' bukan 'audio_path'
           'lyrics' => 'nullable|string',
       ]);

       // 3. Siapkan data dasar yang mau diupdate
       $data = [
           'judul'  => $request->judul,
           'artis'  => $request->artis,
           'genre'  => $request->genre,
           'durasi' => $request->durasi,
           'lyrics' => $request->lyrics,
       ];

       // 4. Logika Update Gambar (Hanya jika user upload gambar baru)
       if ($request->hasFile('cover')) {
           // Hapus file lama fisik jika bukan default
           if ($song->cover_path && $song->cover_path !== 'covers/default_album.jpg' && Storage::disk('public')->exists($song->cover_path)) {
               Storage::disk('public')->delete($song->cover_path);
           }
           // Upload baru dan update array data (kolom di DB namanya 'cover_path')
           $data['cover_path'] = $request->file('cover')->store('covers', 'public');
       }

       // 5. Logika Update Lagu (Hanya jika user upload lagu baru)
       if ($request->hasFile('audio')) {
           // Hapus file lama fisik
           if ($song->file_path && Storage::disk('public')->exists($song->file_path)) {
               Storage::disk('public')->delete($song->file_path);
           }
           // Upload baru dan update array data (kolom di DB namanya 'file_path')
           $data['file_path'] = $request->file('audio')->store('songs', 'public');
       }

       // 6. Eksekusi Update ke Database
       $song->update($data);

       return redirect()->route('admin.dashboard')->with('success', 'Lagu berhasil diperbarui!');
   }    
}