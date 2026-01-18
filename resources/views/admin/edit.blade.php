@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Lagu: {{ $song->judul }}</h2>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                <i class="ri-arrow-left-line mr-1"></i> Kembali
            </a>
        </div>

        <form action="{{ route('admin.songs.update', $song->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Judul Lagu</label>
                    <input type="text" name="judul" value="{{ old('judul', $song->judul) }}" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Artis</label>
                    <input type="text" name="artis" value="{{ old('artis', $song->artis) }}" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Genre</label>
                    <select name="genre" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        @foreach(['Pop', 'Rock', 'Jazz', 'Dangdut', 'Indie', 'R&B', 'Electronic'] as $g)
                            <option value="{{ $g }}" {{ $song->genre == $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Durasi</label>
                    <input type="text" name="durasi" value="{{ old('durasi', $song->durasi) }}" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>
            </div>

            <hr class="border-gray-100">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Ganti Cover (Opsional)</label>
                    <div class="flex items-center gap-4">
                        @if($song->cover_path)
                            <img src="{{ asset('storage/' . str_replace('public/', '', $song->cover_path)) }}" class="w-16 h-16 rounded object-cover border">
                        @endif
                        <input type="file" name="cover" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Ganti Audio (Opsional)</label>
                    <input type="file" name="audio" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengganti lagu.</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Lirik Lagu (LRC)</label>
                <textarea name="lyrics" rows="8" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary font-mono text-sm">{{ old('lyrics', $song->lyrics) }}</textarea>
            </div>

            <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-bold text-lg hover:bg-blue-800 transition shadow-lg">
                Update Lagu
            </button>
        </form>
    </div>
</div>
@endsection