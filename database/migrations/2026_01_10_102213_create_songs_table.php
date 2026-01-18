<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('artis');
            $table->string('genre');
            $table->string('durasi'); // String misal "4:20"
            $table->string('file_path'); // Lokasi file MP3
            $table->string('cover_path')->nullable(); // Cover album
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('songs');
    }
};