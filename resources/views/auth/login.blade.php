<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Manajemen Lagu</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e3a8a',
                        secondary: '#64748b'
                    },
                    borderRadius: {
                        DEFAULT: '8px',
                        'button': '8px'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">

    <div class="max-w-md w-full mx-4">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-primary">Sistem Manajemen Lagu</h1>
            <p class="text-gray-600 mt-2">Silakan masuk untuk melanjutkan</p>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-8">
            
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ri-mail-line text-gray-400"></i>
                        </div>
                        <input type="email" id="email" name="email" required 
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200" 
                            placeholder="nama@email.com">
                    </div>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ri-lock-line text-gray-400"></i>
                        </div>
                        <input type="password" id="password" name="password" required 
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200" 
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-900">Ingat Saya</label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary text-white py-3 rounded-button font-semibold hover:bg-blue-800 transition-colors duration-200">
                    Masuk
                </button>
            </form>
            <div class="mt-4 text-center">
                <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:underline">Belum punya akun? Daftar disini</a>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ url('/') }}" class="text-sm text-gray-600 hover:text-primary flex items-center justify-center gap-1">
                <i class="ri-arrow-left-line"></i> Kembali ke Halaman Utama
            </a>
        </div>
    </div>

</body>
</html>