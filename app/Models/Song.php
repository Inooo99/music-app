<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul', 
        'artis', 
        'genre', 
        'durasi', 
        'file_path', 
        'cover_path',
        'lyrics'
    ];
}