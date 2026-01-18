<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan baris ini ada
use Illuminate\Support\Facades\Hash; // Pastikan baris ini ada

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Panggil seeder lain (Category, Artist, Song)
        $this->call([
            CategoryFactory::class, // Jika error, pastikan nama classnya benar
            ArtistFactory::class,
            SongFactory::class,
        ]);
        
        // ATAU jika kamu pakai factory di masing-masing model, biarkan kode kamu yang lama.
        // FOKUS KE BAGIAN BAWAH INI:
        
        // 2. Buat AKUN ADMIN Manual
        User::factory()->create([
            'name' => 'Admin Music',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'), // Ini passwordnya
        ]);
    }
}