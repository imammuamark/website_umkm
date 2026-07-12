<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak (403) | Panama Corner</title>
    @include('partials.favicon')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        h1, h2 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white border border-slate-100 rounded-3xl p-8 shadow-xl shadow-slate-200/50 text-center space-y-6">
        <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center text-red-500 text-3xl mx-auto">
            🚫
        </div>
        <div class="space-y-2">
            <h1 class="text-xs font-bold uppercase tracking-wider text-red-500">Error 403</h1>
            <h2 class="text-2xl font-extrabold text-slate-900 leading-tight">Akses Ditolak</h2>
            <p class="text-sm text-slate-500 leading-relaxed pt-2">
                Anda tidak memiliki izin akses untuk membuka halaman ini. Autentikasi atau wewenang Anda tidak mencukupi.
            </p>
        </div>
        <div class="pt-4 flex flex-col gap-3">
            <a href="/" class="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl text-sm font-semibold text-white bg-teal-700 hover:bg-teal-800 transition shadow-lg shadow-teal-700/10">
                Kembali ke Beranda
            </a>
            <p class="text-[10px] text-slate-400">
                Aktivitas akses mencurigakan terekam otomatis demi keamanan sistem.
            </p>
        </div>
    </div>
</body>
</html>
