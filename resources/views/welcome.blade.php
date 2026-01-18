<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Lagu</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#1e3a8a' },
                    borderRadius: { DEFAULT: '8px', 'button': '8px' }
                }
            }
        }
    </script>
</head>
<body class="bg-white">

    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <i class="ri-music-2-fill text-3xl text-primary mr-2"></i>
                    <h1 class="text-xl font-bold text-primary">MusicApp</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="bg-gray-100 text-primary px-4 py-2 rounded-button font-medium hover:bg-gray-200 transition">
                                    <i class="ri-dashboard-line mr-1"></i> Dashboard Admin
                                </a>
                            @else
                                <a href="{{ route('user.player') }}" class="bg-gray-100 text-primary px-4 py-2 rounded-button font-medium hover:bg-gray-200 transition">
                                    <i class="ri-play-circle-line mr-1"></i> Buka Player
                                </a>
                            @endif

                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-red-50 text-red-600 px-4 py-2 rounded-button font-medium hover:bg-red-100 transition">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="bg-primary text-white px-6 py-2 rounded-button font-semibold hover:bg-blue-800 transition">
                                Login
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </header>

    <section class="py-20 text-center bg-gradient-to-b from-white to-blue-50">
        <div class="max-w-4xl mx-auto px-4">
            <h2 class="text-5xl font-bold text-primary mb-6">Nikmati Musik Tanpa Batas</h2>
            <p class="text-xl text-gray-600 mb-8">
                Platform manajemen lagu modern untuk Admin dan pengalaman mendengarkan musik terbaik untuk User.
            </p>
            
            <div class="flex justify-center gap-4">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="bg-primary text-white px-8 py-3 rounded-button font-semibold hover:bg-blue-800 transition">
                            Kelola Lagu
                        </a>
                    @else
                        <a href="{{ route('user.player') }}" class="bg-primary text-white px-8 py-3 rounded-button font-semibold hover:bg-blue-800 transition">
                            Mulai Mendengarkan
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="bg-primary text-white px-8 py-3 rounded-button font-semibold hover:bg-blue-800 transition">
                        Masuk Sekarang
                    </a>
                @endauth
            </div>
        </div>
    </section>
    <footer class="bg-primary text-white py-10 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h4 class="text-lg font-semibold mb-2">Sistem Manajemen Lagu</h4>
                <p class="text-blue-200">© 2026 Admin Dashboard.</p>
            </div>
        </div>
    </footer>

</body>
</html>