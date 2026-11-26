<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StepUp — Walk, Earn, Live Better</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">

    {{-- Navbar --}}
    <nav class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur border-b border-gray-100 h-16 flex items-center px-6 justify-between">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center font-bold text-white text-sm">S</div>
            <span class="font-bold text-gray-900 text-lg">StepUp</span>
        </div>
        <div class="flex items-center gap-3">
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                       class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Dashboard Admin</a>
                @endif
                <a href="{{ route('user.rewards.index') }}"
                   class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition-colors">
                    Buka App
                </a>
            @else
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Login</a>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition-colors">
                    Daftar Gratis
                </a>
            @endauth
        </div>
    </nav>

    {{-- Hero --}}
    <section class="min-h-screen flex flex-col items-center justify-center px-6 pt-16 text-center bg-gradient-to-br from-indigo-50 via-white to-violet-50">
        <div class="max-w-3xl mx-auto">
            <span class="inline-flex items-center rounded-full bg-indigo-100 text-indigo-700 px-3 py-1 text-xs font-semibold mb-6">
                🏃 Walk · Earn · Redeem
            </span>
            <h1 class="text-5xl md:text-6xl font-bold text-gray-900 leading-tight">
                Ubah Setiap Langkah <br>
                Menjadi <span class="text-indigo-600">Reward</span>
            </h1>
            <p class="mt-6 text-lg text-gray-500 max-w-xl mx-auto">
                StepUp mengubah aktivitas fisikmu menjadi poin nyata. Ikuti challenges, raih badge, dan tukarkan poinmu dengan voucher dan hadiah eksklusif.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                @auth
                    <a href="{{ route('user.rewards.index') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-8 py-4 text-base font-semibold text-white hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                        Buka Dashboard →
                    </a>
                @else
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-8 py-4 text-base font-semibold text-white hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                        Mulai Sekarang — Gratis
                    </a>
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center rounded-xl border-2 border-gray-200 px-8 py-4 text-base font-semibold text-gray-700 hover:border-indigo-300 hover:text-indigo-600 transition-colors">
                        Login
                    </a>
                @endauth
            </div>
        </div>

        {{-- Feature cards --}}
        <div class="mt-20 grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-4xl w-full mx-auto">
            <div class="rounded-2xl bg-white border border-gray-100 p-6 text-left shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-xl mb-4">🏃</div>
                <h3 class="font-semibold text-gray-900">Track Aktivitas</h3>
                <p class="mt-1 text-sm text-gray-500">Catat langkah, jarak, dan kalori dari aktivitasmu setiap hari.</p>
            </div>
            <div class="rounded-2xl bg-white border border-gray-100 p-6 text-left shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center text-xl mb-4">⚡</div>
                <h3 class="font-semibold text-gray-900">Join Challenges</h3>
                <p class="mt-1 text-sm text-gray-500">Ikuti tantangan harian, mingguan, dan event khusus untuk earn poin.</p>
            </div>
            <div class="rounded-2xl bg-white border border-gray-100 p-6 text-left shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center text-xl mb-4">🎁</div>
                <h3 class="font-semibold text-gray-900">Redeem Reward</h3>
                <p class="mt-1 text-sm text-gray-500">Tukarkan poinmu dengan voucher, diskon, dan hadiah eksklusif.</p>
            </div>
        </div>
    </section>

</body>
</html>
