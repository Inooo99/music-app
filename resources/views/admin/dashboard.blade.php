@extends('layouts.admin')

@section('content')

<section id="home" class="bg-gradient-to-b from-white to-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold text-primary mb-4">Dashboard Admin</h2>
        <p class="text-xl text-gray-600 mb-6">Kelola seluruh koleksi lagu, artis, dan file audio di sini.</p>
        
        <div class="flex gap-4 justify-center">
            <a href="#tambah-lagu" class="bg-primary text-white px-8 py-3 rounded-button font-semibold hover:bg-blue-800 transition-colors">
                Tambah Lagu Baru
            </a>
            <a href="{{ route('user.player') }}" target="_blank" class="border-2 border-primary text-primary px-8 py-3 rounded-button font-semibold hover:bg-primary hover:text-white transition-colors">
                Lihat Player User
            </a>
        </div>
    </div>
</section>

<section id="data-lagu" class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-3xl font-bold text-gray-900">Daftar Lagu</h3>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Artis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Genre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($songs as $index => $song)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $song->judul }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $song->artis }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $song->genre }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $song->durasi }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <form action="{{ route('admin.songs.destroy', $song->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus lagu {{ $song->judul }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ route('admin.songs.edit', $song->id) }}" class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-button transition-colors inline-flex items-center">
                                        <i class="ri-edit-line mr-1"></i>Edit
                                    </a>
                                    <button type="submit" class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1 ml-4 rounded-button transition-colors">
                                        <i class="ri-delete-bin-line mr-1"></i>Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada lagu yang diupload.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<section id="tambah-lagu" class="py-12 bg-white">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Tambah Lagu Baru</h3>
            
            @if ($errors->any())
                <div class="mb-4 bg-red-50 text-red-600 p-3 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.songs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Judul Lagu</label>
                    <input type="text" name="judul" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Artis</label>
                    <input type="text" name="artis" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Genre</label>
                    <select name="genre" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary bg-white">
                        <option value="">Pilih Genre</option>
                        <option value="Pop">Pop</option>
                        <option value="Rock">Rock</option>
                        <option value="Jazz">Jazz</option>
                        <option value="Dangdut">Dangdut</option>
                        <option value="Indie">Indie</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durasi (Contoh: 4:20)</label>
                    <input type="text" name="durasi" required placeholder="4:20" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cover Album (Gambar)</label>
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-20 bg-gray-100 border rounded-lg flex items-center justify-center overflow-hidden">
                            <img id="cover-preview" src="#" class="w-full h-full object-cover hidden">
                            <i id="cover-icon" class="ri-image-add-line text-2xl text-gray-400"></i>
                        </div>
                        <input type="file" name="cover" accept="image/*" class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100"
                        onchange="document.getElementById('cover-preview').src = window.URL.createObjectURL(this.files[0]); document.getElementById('cover-preview').classList.remove('hidden'); document.getElementById('cover-icon').classList.add('hidden');">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, JPEG. Max: 2MB.</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">File Audio (MP3)</label>
                    <input type="file" name="audio" accept=".mp3,.wav" required class="w-full p-2 border border-gray-300 rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">Maksimal 15MB</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700">Lirik Lagu (Format LRC)</label>
                </div>
                    
                    <textarea name="lyrics" rows="10" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary font-mono text-sm leading-relaxed" 
                        placeholder="Lirik dengan format LRC akan muncul di sini setelah proses sinkronisasi."></textarea>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const fileInput = document.querySelector('input[name="audio"]');
                        const syncAudio = document.getElementById('sync-audio');
                        const rawLyricsInput = document.getElementById('raw-lyrics');
                        const finalLrcInput = document.getElementById('final-lrc');
                        const startSyncBtn = document.getElementById('btn-start-sync');
                        const syncControls = document.getElementById('sync-controls');
                        const markBtn = document.getElementById('btn-mark-time');
                        const currentLineDisplay = document.getElementById('current-line-preview');
                        const nextLineDisplay = document.getElementById('next-line-preview');

                        let lines = [];
                        let currentLineIndex = 0;
                        let lrcResult = [];

                        // 1. Load Audio ke Player Preview saat file dipilih
                        fileInput.addEventListener('change', function(e) {
                            const file = e.target.files[0];
                            if (file) {
                                const url = URL.createObjectURL(file);
                                syncAudio.src = url;
                            }
                        });

                        // 2. Tombol Mulai Sync
                        startSyncBtn.addEventListener('click', function() {
                            if (!rawLyricsInput.value.trim()) {
                                alert('Paste dulu lirik mentahnya!');
                                return;
                            }
                            if (!syncAudio.src) {
                                alert('Pilih file lagu dulu di input file audio!');
                                return;
                            }

                            // Split lirik per baris dan hapus baris kosong
                            lines = rawLyricsInput.value.split('\n').map(l => l.trim()).filter(l => l !== '');
                            currentLineIndex = 0;
                            lrcResult = [];
                            finalLrcInput.value = ''; // Reset hasil

                            // Tampilkan UI Sync
                            syncControls.classList.remove('hidden');
                            startSyncBtn.classList.add('hidden');
                            rawLyricsInput.disabled = true; // Kunci input

                            updateDisplay();
                            syncAudio.play(); // Auto play lagu
                            markBtn.focus(); // Fokus ke tombol biar bisa dispasi
                        });

                        // 3. Fungsi Menandai Waktu (Syncing)
                        function markTime() {
                            if (currentLineIndex >= lines.length) return;

                            const currentTime = syncAudio.currentTime;
                            const formattedTime = formatTimestamp(currentTime);
                            const text = lines[currentLineIndex];

                            // Gabungkan Waktu + Teks
                            const lrcLine = `${formattedTime} ${text}`;
                            lrcResult.push(lrcLine);

                            // Update textarea hasil realtime
                            finalLrcInput.value = lrcResult.join('\n');
                            finalLrcInput.scrollTop = finalLrcInput.scrollHeight; // Auto scroll ke bawah

                            currentLineIndex++;
                            updateDisplay();
                        }

                        // 4. Update Teks Preview
                        function updateDisplay() {
                            if (currentLineIndex < lines.length) {
                                currentLineDisplay.innerText = lines[currentLineIndex];
                                markBtn.innerText = `SYNC: "${lines[currentLineIndex]}"`;
                                
                                if (currentLineIndex + 1 < lines.length) {
                                    nextLineDisplay.innerText = "Next: " + lines[currentLineIndex + 1];
                                } else {
                                    nextLineDisplay.innerText = "End of lyrics";
                                }
                            } else {
                                currentLineDisplay.innerText = "SELESAI!";
                                markBtn.innerText = "Lirik Selesai (Simpan Lagu Sekarang)";
                                markBtn.classList.remove('bg-blue-600');
                                markBtn.classList.add('bg-green-600');
                                syncAudio.pause();
                            }
                        }

                        // 5. Event Listener Tombol & Spasi
                        markBtn.addEventListener('click', markTime);

                        document.addEventListener('keydown', function(e) {
                            // Jika sedang mode sync dan tekan Spasi
                            if (!syncControls.classList.contains('hidden') && e.code === 'Space') {
                                e.preventDefault(); // Mencegah scroll halaman
                                markTime();
                            }
                        });

                        // Helper Format Waktu [mm:ss.xx]
                        function formatTimestamp(seconds) {
                            const m = Math.floor(seconds / 60);
                            const s = Math.floor(seconds % 60);
                            const ms = Math.floor((seconds % 1) * 100);
                            return `[${pad(m)}:${pad(s)}.${pad(ms)}]`;
                        }

                        function pad(n) {
                            return n < 10 ? '0' + n : n;
                        }
                    });
                </script>
                
                <div class="pt-4">
                    <button type="submit" class="w-full bg-primary text-white py-3 rounded-button font-semibold hover:bg-blue-800 transition-colors">
                        Simpan Lagu
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection