<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MusicApp - Player</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    colors: { 
                        glass: 'rgba(255, 255, 255, 0.1)',
                        glassBorder: 'rgba(255, 255, 255, 0.2)',
                    },
                    animation: {
                        'gradient': 'gradient 15s ease infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-heart': 'pulse-heart 0.3s ease-in-out',
                    },
                    keyframes: {
                        gradient: {
                            '0%, 100%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        'pulse-heart': {
                            '0%': { transform: 'scale(1)' },
                            '50%': { transform: 'scale(1.3)' },
                            '100%': { transform: 'scale(1)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: linear-gradient(-45deg, #0f172a, #1e3a8a, #312e81, #000000);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            color: white;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glass-panel-dark {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .lyric-line {
            transition: color 0.3s ease, opacity 0.3s ease;
            opacity: 0.5;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.7);
        }
        .lyric-line:hover { opacity: 0.8; }
        .lyric-line.active { opacity: 1; color: #ffffff; font-weight: 600; }
        input[type=range] { -webkit-appearance: none; background: transparent; }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none; height: 12px; width: 12px; border-radius: 50%;
            background: #ffffff; cursor: pointer; margin-top: -4px;
            box-shadow: 0 0 10px rgba(255,255,255,0.5);
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%; height: 4px; cursor: pointer;
            background: rgba(255,255,255,0.2); border-radius: 2px;
        }
    </style>
</head>
<body class="font-sans h-screen flex flex-col overflow-hidden selection:bg-blue-500 selection:text-white">
    
    <audio id="main-audio"></audio>

    <header class="glass-panel px-6 py-4 flex items-center justify-between shrink-0 h-[80px] z-20 relative">
        <div class="flex items-center gap-2">
            <h1 class="text-2xl font-bold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">MusicApp</h1>
        </div>
        
        <div class="flex-1 max-w-lg mx-8 hidden md:block group">
            <div class="relative transition-all duration-300 transform group-hover:scale-105">
                <input type="text" id="search-input" placeholder="Cari lagu, artis, atau genre..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-white/10 border border-white/10 rounded-full text-sm text-white placeholder-gray-400 focus:outline-none focus:bg-white/20 focus:border-white/30 focus:ring-1 focus:ring-white/30 transition-all">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <i class="ri-search-line"></i>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
             <span class="text-sm font-medium text-white/80 hidden sm:block tracking-wide">Halo, {{ Auth::user()->name }}</span>
             <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-white/60 hover:text-red-400 p-2 rounded-full hover:bg-white/5 transition-colors" title="Logout">
                    <i class="ri-logout-box-r-line text-xl"></i>
                </button>
            </form>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden relative z-10">
        <div class="w-[18%] glass-panel border-r-0 border-l-0 border-t-0 hidden md:flex flex-col shrink-0 pt-4">
            <nav class="p-4 space-y-2">
                <p class="px-4 text-xs font-bold text-white/40 uppercase tracking-widest mb-2">Menu</p>
                <a href="#" id="nav-library" onclick="showAllSongs()" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-white/10 text-white font-medium border border-white/5 shadow-lg backdrop-blur-sm cursor-pointer transition-all">
                    <i class="ri-music-line text-blue-300"></i> Library
                </a>
                <a href="#" id="nav-liked" onclick="filterLikedSongs()" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/60 hover:bg-white/5 hover:text-white transition-all cursor-pointer border border-transparent">
                    <i class="ri-heart-3-fill text-red-500"></i> Liked Songs
                </a>
            </nav>
        </div>

        <div class="flex-1 flex flex-col min-w-0 relative">
            <div class="p-6 overflow-y-auto h-full pb-32 no-scrollbar" id="main-scroll-area">
                
                <div class="w-full h-48 rounded-3xl bg-gradient-to-r from-blue-600/40 to-purple-600/40 backdrop-blur-md border border-white/10 mb-8 flex items-end p-6 shadow-2xl relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="relative z-10">
                        <h1 class="text-4xl font-bold mb-1" id="page-title">Your Library</h1>
                        <p class="text-white/60">Dengarkan musik favoritmu dengan tampilan baru.</p>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="flex gap-3 overflow-x-auto no-scrollbar pb-2" id="genre-filter-container"></div>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-white">Daftar Lagu</h2>
                    <span class="text-xs text-white/40 bg-white/10 px-3 py-1 rounded-full">{{ $songs->count() }} Songs</span>
                </div>
                
                <div class="glass-panel rounded-2xl overflow-hidden">
                    <div class="grid grid-cols-12 gap-4 px-6 py-4 border-b border-white/10 text-xs font-bold text-white/40 uppercase tracking-wider">
                        <div class="col-span-1">#</div>
                        <div class="col-span-5">Title</div>
                        <div class="col-span-3">Artist</div>
                        <div class="col-span-3">Duration</div>
                    </div>
                    
                    <div class="divide-y divide-white/5" id="songs-list-container">
                        @foreach($songs as $index => $song)
                        @php
                            $coverImage = $song->cover_path ? (Str::startsWith($song->cover_path, 'http') ? $song->cover_path : asset('storage/' . $song->cover_path)) : "https://via.placeholder.com/150?text=No+Cover";
                            $searchText = strtolower($song->judul . ' ' . $song->artis . ' ' . $song->genre);
                        @endphp
                        <div class="song-item grid grid-cols-12 gap-4 px-6 py-4 hover:bg-white/10 cursor-pointer transition-all group" 
                             onclick="playSong({{ $index }})"
                             data-id="{{ $song->id }}"
                             data-genre="{{ $song->genre }}"
                             data-search="{{ $searchText }}">
                            
                            <div class="col-span-1 text-white/40 flex items-center group-hover:text-white">{{ $index + 1 }}</div>
                            <div class="col-span-5 font-medium text-white flex items-center gap-4">
                                <div class="relative w-10 h-10">
                                    <img src="{{ $coverImage }}" class="w-full h-full rounded-lg object-cover shadow-lg group-hover:scale-110 transition-transform">
                                    <div class="absolute inset-0 bg-black/40 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="ri-play-fill text-white"></i>
                                    </div>
                                </div>
                                <div class="truncate">
                                    <div class="text-sm font-semibold">{{ $song->judul }}</div>
                                    <div class="text-xs text-white/40 md:hidden">{{ $song->artis }}</div>
                                </div>
                            </div>
                            <div class="col-span-3 text-white/60 flex items-center truncate text-sm">{{ $song->artis }}</div>
                            <div class="col-span-3 text-white/60 flex items-center justify-between text-sm font-mono">
                                {{ $song->durasi }}
                                <button onclick="toggleLike(event, {{ $song->id }})" class="like-btn-list text-white/20 hover:text-red-500 mr-4 transition transform hover:scale-125" data-song-id="{{ $song->id }}">
                                    <i class="ri-heart-3-fill text-lg"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div id="mini-lyrics-panel" class="absolute top-4 right-4 w-80 h-[450px] glass-panel rounded-2xl z-30 hidden flex-col shadow-2xl border-white/20 transform transition-all duration-300 backdrop-blur-3xl bg-black/60">
                <div class="p-4 border-b border-white/10 flex justify-between items-center bg-white/5">
                    <h3 class="font-bold text-white text-sm">Lyrics</h3>
                    <button onclick="expandLyrics()" class="text-white/60 hover:text-white transition p-1 rounded hover:bg-white/10" title="Full Screen">
                        <i class="ri-fullscreen-line"></i>
                    </button>
                </div>
                <div class="flex-1 relative overflow-hidden">
                    <div id="mini-lyrics-container" class="absolute inset-0 overflow-y-auto no-scrollbar p-6 text-center space-y-4 mask-image-gradient scroll-smooth"></div>
                </div>
            </div>

            <div id="full-lyrics-modal" class="absolute inset-0 bg-black/90 backdrop-blur-xl z-50 hidden flex-col transition-all duration-500 opacity-0">
                <div class="absolute top-0 left-0 right-0 p-8 flex justify-between items-center z-10 bg-gradient-to-b from-black/50 to-transparent">
                    <div class="text-left">
                        <h2 id="modal-title" class="text-2xl font-bold text-white">Judul Lagu</h2>
                        <p id="modal-artist" class="text-lg text-blue-300">Artis</p>
                    </div>
                    <button onclick="closeFullLyrics()" class="text-white/50 hover:text-white transition bg-white/10 p-3 rounded-full hover:bg-white/20">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                <div id="full-lyrics-container" class="flex-1 w-full max-w-5xl mx-auto h-full overflow-y-auto no-scrollbar text-center space-y-8 scroll-smooth py-32 px-8"></div>
            </div>
        </div>

        <div class="w-[20%] glass-panel border-r-0 border-t-0 border-b-0 hidden lg:flex flex-col shrink-0 items-center justify-center relative">
            <div class="p-6 text-center w-full">
                <div class="relative group w-48 h-48 mx-auto mb-6">
                    <img id="right-cover" src="https://via.placeholder.com/200?text=Music" class="w-full h-full rounded-2xl shadow-2xl object-cover aspect-square ring-1 ring-white/10">
                    <div class="absolute bottom-4 right-4 flex gap-1 items-end h-8 hidden" id="equalizer">
                        <span class="w-1 bg-white animate-pulse h-4"></span>
                        <span class="w-1 bg-white animate-pulse h-8"></span>
                        <span class="w-1 bg-white animate-pulse h-6"></span>
                    </div>
                </div>
                <h4 id="right-title" class="text-xl font-bold text-white truncate px-2 mb-1 drop-shadow-md">-</h4>
                <p id="right-artist" class="text-sm text-white/60 truncate px-2">-</p>
            </div>
        </div>
    </div>

    <div class="h-[90px] glass-panel-dark px-6 flex items-center justify-between shrink-0 z-50 relative">
        <div class="flex items-center gap-4 w-1/4 min-w-[200px]">
            <img id="bottom-cover" src="https://via.placeholder.com/150?text=Music" class="w-14 h-14 rounded-lg hidden md:block shadow-lg object-cover ring-1 ring-white/10">
            <div class="truncate">
                <h5 id="bottom-title" class="font-bold text-white truncate text-base hover:underline cursor-pointer">Pilih Lagu</h5>
                <p id="bottom-artist" class="text-xs text-white/60 truncate font-medium">-</p>
            </div>
            <button id="player-like-btn" onclick="toggleLikeCurrent()" class="text-white/40 hover:text-red-500 transition transform hover:scale-125">
                <i class="ri-heart-3-fill text-xl"></i>
            </button>
        </div>

        <div class="flex-1 max-w-xl flex flex-col items-center justify-center">
            <div class="flex items-center gap-6 mb-2">
                <button id="shuffle-btn" onclick="toggleShuffle()" class="text-white/40 hover:text-white transition transform active:scale-95" title="Acak Lagu">
                    <i class="ri-shuffle-line text-xl"></i>
                </button>
                
                <button onclick="prevSong()" class="text-white/60 hover:text-white transition transform active:scale-95"><i class="ri-skip-back-fill text-2xl"></i></button>
                
                <button id="play-btn" class="w-12 h-12 bg-white text-black rounded-full flex items-center justify-center hover:scale-105 transition active:scale-95">
                    <i class="ri-play-fill text-2xl ml-1" id="play-icon"></i>
                </button>
                
                <button onclick="nextSong()" class="text-white/60 hover:text-white transition transform active:scale-95"><i class="ri-skip-forward-fill text-2xl"></i></button>

                <button id="repeat-btn" onclick="toggleRepeat()" class="text-white/40 hover:text-white transition transform active:scale-95" title="Ulang Lagu">
                    <i class="ri-repeat-2-line text-xl"></i>
                </button>
            </div>
            
            <div class="w-full flex items-center gap-3 text-xs font-mono text-white/60 font-medium">
                <span id="current-time" class="w-10 text-right">0:00</span>
                <div class="flex-1 relative h-1 bg-white/10 rounded-full cursor-pointer group">
                    <input type="range" id="progress-bar" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" value="0" step="0.1" max="100">
                    <div id="progress-fill" class="h-full bg-white rounded-full w-0 group-hover:bg-blue-400 transition-all relative">
                        <div class="absolute right-0 top-1/2 -translate-y-1/2 w-3 h-3 bg-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity shadow"></div>
                    </div>
                </div>
                <span id="duration-time" class="w-10">0:00</span>
            </div>
        </div>

        <div class="w-1/4 flex justify-end gap-5 items-center min-w-[150px]">
            <button id="mic-btn" onclick="expandLyrics()" class="text-white/40 hover:text-white transition transform hover:scale-110 relative" title="Lyrics">
                <i class="ri-mic-2-line text-xl"></i>
            </button>
            <div class="flex items-center gap-2 group">
                <i class="ri-volume-up-line text-white/60 text-lg"></i>
                <input type="range" class="w-20 h-1 bg-white/20 rounded-lg appearance-none cursor-pointer" 
                       oninput="document.getElementById('main-audio').volume = this.value/100" value="100" max="100">
            </div>
        </div>
    </div>

    <script>
        const songsList = @json($songs).map(song => {
            let fileClean = song.file_path ? song.file_path.replace('public/', '') : '';
            let coverUrl = song.cover_path && !song.cover_path.startsWith('http') 
                ? "{{ asset('storage') }}/" + song.cover_path.replace('public/', '') 
                : (song.cover_path || "https://via.placeholder.com/150?text=No+Cover");

            return {
                id: parseInt(song.id), 
                title: song.judul,
                artist: song.artis,
                genre: song.genre,
                durationStr: song.durasi,
                cover: coverUrl, 
                file: fileClean ? "{{ asset('storage') }}/" + fileClean : "",
                lyrics: song.lyrics || "" 
            };
        });

        const audio = document.getElementById('main-audio');
        const progressBar = document.getElementById('progress-bar');
        const progressFill = document.getElementById('progress-fill');
        const miniLyricsContainer = document.getElementById('mini-lyrics-container');
        const fullLyricsContainer = document.getElementById('full-lyrics-container');
        const miniLyricsPanel = document.getElementById('mini-lyrics-panel');
        const fullLyricsModal = document.getElementById('full-lyrics-modal');

        // STATE
        let likedSongs = JSON.parse(localStorage.getItem('likedSongs')) || [];
        let currentLyrics = [];
        // Hapus variabel isPlaying manual, kita pakai audio.paused
        let currentIndex = 0;
        let activeLyricIndex = -1;
        let isDragging = false; 

        // New Features State
        let isShuffle = false;
        let repeatMode = 0; // 0: Off, 1: All, 2: One

        // INIT
        updateLikeButtons();
        renderGenreButtons();

        // --- CORE AUDIO EVENTS (PENTING AGAR TIDAK BUG) ---
        // Saat audio beneran mulai main, ubah icon jadi Pause
        audio.onplay = () => {
            updatePlayIcon();
        };
        // Saat audio beneran berhenti, ubah icon jadi Play
        audio.onpause = () => {
            updatePlayIcon();
        };

        // --- PLAYBACK ---
        function playSong(index) {
            if(!songsList[index]) return;
            currentIndex = index;
            const song = songsList[index];
            activeLyricIndex = -1;

            if(!song.file) { alert("File audio error"); return; }

            updateText('right-title', song.title);
            updateText('bottom-title', song.title);
            updateText('modal-title', song.title);
            updateText('right-artist', song.artist);
            updateText('bottom-artist', song.artist);
            updateText('modal-artist', song.artist);
            updateImage('right-cover', song.cover);
            updateImage('bottom-cover', song.cover);

            audio.src = song.file;
            currentLyrics = parseLRC(song.lyrics);
            renderLyrics(currentLyrics);
            updatePlayerLikeStatus(song.id);
            
            // Play Audio
            audio.play().catch(console.error);
            document.getElementById('equalizer').classList.remove('hidden');
        }

        // --- TOMBOL PLAY/PAUSE (DIPERBAIKI) ---
        function togglePlay() {
            if(!audio.src) {
                if(songsList.length > 0) playSong(0);
                return;
            }

            // LOGIKA BARU: Cek status asli audio
            if (audio.paused) {
                audio.play(); // Kalau lagi diam, jalankan
            } else {
                audio.pause(); // Kalau lagi jalan, hentikan
            }
            // Update icon akan ditangani oleh event audio.onplay/onpause di atas
        }

        function updatePlayIcon() {
            // Cek langsung ke audio.paused
            const isPaused = audio.paused; 
            const icon = document.getElementById('play-icon');
            
            // Jika Paused (True) -> Tampilkan Play Icon
            // Jika Playing (False) -> Tampilkan Pause Icon
            icon.className = !isPaused ? 'ri-pause-fill text-2xl ml-0' : 'ri-play-fill text-2xl ml-1';
        }

        // --- NEXT / PREV / ENDED LOGIC ---
        function nextSong() {
            if(!songsList.length) return;
            
            if(isShuffle) {
                let newIndex = Math.floor(Math.random() * songsList.length);
                playSong(newIndex);
            } else {
                let newIndex = currentIndex + 1;
                if(newIndex >= songsList.length && repeatMode === 1) {
                    newIndex = 0; 
                } 
                else if (newIndex >= songsList.length && repeatMode === 0) {
                    // Stop jika habis
                    return; 
                }
                else if (newIndex >= songsList.length) {
                    newIndex = 0; 
                }
                playSong(newIndex);
            }
        }

        function prevSong() {
            if(!songsList.length) return;
            let newIndex = currentIndex - 1;
            if(newIndex < 0) newIndex = songsList.length - 1;
            playSong(newIndex);
        }

        // AUTO ENDED EVENT
        audio.addEventListener('ended', () => {
            if (repeatMode === 2) { // Repeat One
                audio.currentTime = 0;
                audio.play();
            } else if (repeatMode === 1) { // Repeat All
                let newIndex = currentIndex + 1;
                if(newIndex >= songsList.length) newIndex = 0;
                playSong(newIndex);
            } else { // Repeat Off
                if (isShuffle) {
                    nextSong();
                } else {
                    if (currentIndex < songsList.length - 1) {
                        playSong(currentIndex + 1);
                    }
                    // Kalau habis playlist, biarkan berhenti (icon otomatis jadi play karena onpause)
                }
            }
        });

        // --- SHUFFLE & REPEAT TOGGLES ---
        function toggleShuffle() {
            isShuffle = !isShuffle;
            const btn = document.getElementById('shuffle-btn');
            if(isShuffle) {
                btn.classList.add('text-green-400', 'opacity-100');
                btn.classList.remove('text-white/40');
            } else {
                btn.classList.remove('text-green-400', 'opacity-100');
                btn.classList.add('text-white/40');
            }
        }

        function toggleRepeat() {
            const btn = document.getElementById('repeat-btn');
            const icon = btn.querySelector('i');
            repeatMode = (repeatMode + 1) % 3; // 0->1->2->0

            if (repeatMode === 0) { // Off
                btn.classList.remove('text-green-400', 'opacity-100');
                btn.classList.add('text-white/40');
                icon.className = 'ri-repeat-2-line text-xl';
            } else if (repeatMode === 1) { // All
                btn.classList.add('text-green-400', 'opacity-100');
                btn.classList.remove('text-white/40');
                icon.className = 'ri-repeat-2-line text-xl';
            } else { // One
                btn.classList.add('text-green-400', 'opacity-100');
                btn.classList.remove('text-white/40');
                icon.className = 'ri-repeat-one-line text-xl';
            }
        }

        // --- SEEK BAR ---
        progressBar.addEventListener('mousedown', () => isDragging = true);
        progressBar.addEventListener('touchstart', () => isDragging = true);
        progressBar.addEventListener('input', (e) => {
            const val = e.target.value;
            progressFill.style.width = val + '%';
            if(audio.duration) document.getElementById('current-time').innerText = fmtTime((val/100)*audio.duration);
        });
        progressBar.addEventListener('change', (e) => {
            isDragging = false;
            if(audio.duration) audio.currentTime = (e.target.value / 100) * audio.duration;
        });
        audio.addEventListener('timeupdate', () => {
            if(audio.duration && !isDragging) {
                const pct = (audio.currentTime / audio.duration) * 100;
                progressBar.value = pct;
                progressFill.style.width = pct + '%';
                document.getElementById('current-time').innerText = fmtTime(audio.currentTime);
                document.getElementById('duration-time').innerText = fmtTime(audio.duration);
            }
            // Sync Lyrics
            if(currentLyrics.length) {
                let idx = currentLyrics.findIndex((l, i) => {
                    const next = currentLyrics[i+1];
                    return audio.currentTime >= l.time && (!next || audio.currentTime < next.time);
                });
                if(idx !== -1 && idx !== activeLyricIndex) {
                    highlightLyrics(idx);
                    activeLyricIndex = idx;
                }
            }
        });

        // --- LIKE & GENRE ---
        function toggleLike(e, songId) {
            if(e) e.stopPropagation();
            songId = parseInt(songId);
            const idx = likedSongs.indexOf(songId);
            if(idx === -1) likedSongs.push(songId);
            else likedSongs.splice(idx, 1);
            localStorage.setItem('likedSongs', JSON.stringify(likedSongs));
            updateLikeButtons();
            if(songsList[currentIndex] && songsList[currentIndex].id === songId) updatePlayerLikeStatus(songId);
            
            const btn = document.querySelector(`button[data-song-id="${songId}"]`);
            if(btn) { btn.classList.add('animate-pulse-heart'); setTimeout(()=>btn.classList.remove('animate-pulse-heart'),300); }
        }
        function toggleLikeCurrent() { if(songsList.length) toggleLike(null, songsList[currentIndex].id); }
        function updateLikeButtons() {
            document.querySelectorAll('.like-btn-list').forEach(btn => {
                const id = parseInt(btn.getAttribute('data-song-id'));
                if(likedSongs.includes(id)) {
                    btn.innerHTML = '<i class="ri-heart-3-fill text-red-500 text-lg"></i>';
                    btn.classList.remove('text-white/20');
                } else {
                    btn.innerHTML = '<i class="ri-heart-3-line text-lg"></i>';
                    btn.classList.add('text-white/20');
                }
            });
        }
        function updatePlayerLikeStatus(id) {
            const btn = document.getElementById('player-like-btn');
            if(likedSongs.includes(id)) {
                btn.innerHTML = '<i class="ri-heart-3-fill text-red-500 text-xl"></i>';
                btn.classList.replace('text-white/40', 'text-red-500');
            } else {
                btn.innerHTML = '<i class="ri-heart-3-line text-xl"></i>';
                btn.classList.replace('text-red-500', 'text-white/40');
            }
        }
        
        function renderGenreButtons() {
            const container = document.getElementById('genre-filter-container');
            const allGenres = songsList.map(s => s.genre).filter(Boolean);
            const unique = ['All', ...new Set(allGenres)];
            container.innerHTML = '';
            unique.forEach((g, i) => {
                const btn = document.createElement('button');
                const active = i===0;
                btn.className = `px-5 py-2 rounded-full text-sm font-medium transition-all whitespace-nowrap border backdrop-blur-md ${active ? 'bg-white text-blue-900 border-white font-bold shadow-lg scale-105' : 'bg-white/10 text-white border-white/10 hover:bg-white/20 hover:border-white/30'}`;
                btn.innerText = g;
                btn.onclick = () => filterByGenre(g, btn);
                container.appendChild(btn);
            });
        }
        function filterByGenre(g, btn) {
            Array.from(btn.parentElement.children).forEach(b => b.className = 'px-5 py-2 rounded-full text-sm font-medium transition-all whitespace-nowrap border backdrop-blur-md bg-white/10 text-white border-white/10 hover:bg-white/20 hover:border-white/30');
            btn.className = 'px-5 py-2 rounded-full text-sm font-medium transition-all whitespace-nowrap border backdrop-blur-md bg-white text-blue-900 border-white font-bold shadow-lg scale-105';
            
            document.querySelectorAll('.song-item').forEach(item => {
                item.style.display = (g === 'All' || item.getAttribute('data-genre') === g) ? 'grid' : 'none';
            });
            updateSidebarUI('library');
        }

        // --- HELPERS ---
        function showAllSongs() {
            document.getElementById('page-title').innerText = "Your Library";
            document.querySelectorAll('.song-item').forEach(i => i.style.display = 'grid');
            document.getElementById('empty-liked-msg')?.remove();
            const btns = document.getElementById('genre-filter-container').children;
            if(btns.length) filterByGenre('All', btns[0]);
            updateSidebarUI('library');
        }
        function filterLikedSongs() {
            document.getElementById('page-title').innerText = "Liked Songs";
            const btns = document.getElementById('genre-filter-container').children;
            if(btns.length) filterByGenre('All', btns[0]); 

            let count = 0;
            document.querySelectorAll('.song-item').forEach(item => {
                const id = parseInt(item.getAttribute('data-id'));
                if(likedSongs.includes(id)) { item.style.display = 'grid'; count++; }
                else item.style.display = 'none';
            });
            if(count===0) {
                if(!document.getElementById('empty-liked-msg')) {
                    const msg = document.createElement('div');
                    msg.id = 'empty-liked-msg';
                    msg.className = 'text-center py-12 text-white/40 italic col-span-12';
                    msg.innerText = 'Belum ada lagu yang disukai.';
                    document.getElementById('songs-list-container').appendChild(msg);
                }
            } else document.getElementById('empty-liked-msg')?.remove();
            updateSidebarUI('liked');
        }
        function updateSidebarUI(tab) {
            const lib = document.getElementById('nav-library');
            const liked = document.getElementById('nav-liked');
            const active = ['bg-white/10', 'text-white', 'font-medium', 'border-white/5', 'shadow-lg'];
            const inactive = ['text-white/60', 'hover:bg-white/5', 'hover:text-white', 'border-transparent'];
            if(tab === 'library') {
                lib.classList.add(...active); lib.classList.remove(...inactive);
                liked.classList.remove(...active); liked.classList.add(...inactive);
            } else {
                liked.classList.add(...active); liked.classList.remove(...inactive);
                lib.classList.remove(...active); lib.classList.add(...inactive);
            }
        }
        
        // Lyrics & Text Utils
        function parseLRC(lrc) {
            if(!lrc) return [];
            return lrc.split('\n').map(l => {
                const m = /\[(\d{2}):(\d{2})(?:\.(\d{2,3}))?\]/.exec(l);
                return m ? { time: parseInt(m[1])*60 + parseInt(m[2]) + (m[3]?parseFloat("0."+m[3]):0), text: l.replace(m[0],'').trim() } : null;
            }).filter(Boolean);
        }
        function renderLyrics(data) {
            const empty = '<div class="h-full flex items-center justify-center"><p class="text-white/60 italic text-sm">Lirik tidak tersedia.</p></div>';
            miniLyricsContainer.innerHTML = fullLyricsContainer.innerHTML = data.length ? "" : empty;
            data.forEach((l, i) => {
                const createEl = (cls) => {
                    const el = document.createElement('p');
                    el.className = cls; el.id = cls.includes('text-sm') ? `mini-${i}` : `full-${i}`;
                    el.innerText = l.text; el.onclick = () => { audio.currentTime = l.time; audio.play(); };
                    return el;
                };
                miniLyricsContainer.appendChild(createEl('lyric-line text-sm py-2 px-2 text-white/50 hover:text-white transition-all duration-300 cursor-pointer rounded-lg hover:bg-white/5'));
                fullLyricsContainer.appendChild(createEl('lyric-line text-2xl md:text-4xl font-bold py-4 leading-relaxed text-white/30 hover:text-white transition-all duration-500 cursor-pointer'));
            });
        }
        function highlightLyrics(i) {
            document.querySelectorAll('.lyric-line').forEach(e => e.classList.remove('active'));
            const mini = document.getElementById(`mini-${i}`);
            const full = document.getElementById(`full-${i}`);
            if(mini) { mini.classList.add('active'); mini.scrollIntoView({behavior:"smooth", block:"center"}); }
            if(full) { full.classList.add('active'); full.scrollIntoView({behavior:"smooth", block:"center"}); }
        }

        // Toggles & Search
        function expandLyrics() { fullLyricsModal.classList.remove('hidden'); setTimeout(()=>fullLyricsModal.classList.remove('opacity-0'),10); }
        function closeFullLyrics() { fullLyricsModal.classList.add('opacity-0'); setTimeout(()=>fullLyricsModal.classList.add('hidden'),500); miniLyricsPanel.classList.add('hidden'); }
        document.getElementById('search-input')?.addEventListener('input', (e) => {
            const t = e.target.value.toLowerCase();
            document.querySelectorAll('.song-item').forEach(r => r.style.display = r.getAttribute('data-search').includes(t) ? 'grid' : 'none');
        });
        function updateText(id, t) { const el = document.getElementById(id); if(el) el.innerText = t; }
        function updateImage(id, s) { const el = document.getElementById(id); if(el) el.src = s; }
        function fmtTime(s) { return `${Math.floor(s/60)}:${Math.floor(s%60).toString().padStart(2,'0')}`; }
        const vol = document.querySelector('input[oninput*="volume"]');
        if(vol) vol.addEventListener('input', e => audio.volume = e.target.value/100);
        
        // Listener tombol play
        document.getElementById('play-btn').addEventListener('click', togglePlay);
    </script>
</body>
</html>