<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Buat Akun Baru</h2>

        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                <input type="text" name="name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Nama Kamu">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                <input type="email" name="email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="email@contoh.com">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Minimal 4 karakter">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Ulangi Password</label>
                <input type="password" name="password_confirmation" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Ketik ulang password">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">
                Daftar Sekarang
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login disini</a>
        </p>
    </div>

</body>
</html>