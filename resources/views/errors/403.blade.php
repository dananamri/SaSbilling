<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Ditolak</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center" style="background: linear-gradient(135deg, #061141 0%, #12235e 60%, #E9F0FF 100%);">
    <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-4 text-center">
        <div class="text-6xl font-bold text-blue-600 mb-2">403</div>
        <h1 class="text-xl font-semibold text-gray-800 mb-2">Akses Ditolak</h1>
        <p class="text-sm text-gray-500 mb-6">
            {{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses untuk membuka halaman ini. Hubungi Super Admin jika Anda merasa seharusnya memiliki akses.' }}
        </p>
        <a href="{{ url('/') }}" class="inline-block px-5 py-2.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>
