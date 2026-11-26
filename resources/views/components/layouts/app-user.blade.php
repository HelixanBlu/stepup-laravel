@props(['title' => 'StepUp'])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900"
      x-data
      x-init="Alpine.store('sidebar', { open: window.innerWidth >= 1024, toggle() { this.open = !this.open } })">

    @include('components.layouts.sidebar-user')

    <div x-data="{ open: $store.sidebar.open }"
         x-bind:class="open ? 'lg:ml-64' : 'lg:ml-16'"
         class="transition-all duration-300 ease-in-out min-h-screen flex flex-col">

        {{-- Top bar --}}
        <header class="sticky top-0 z-20 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 h-16 flex items-center justify-between px-4 md:px-6 shadow-sm">
            <div class="flex items-center gap-3">
                <button @click="$store.sidebar.toggle()"
                        class="p-1.5 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="text-base font-semibold text-gray-800 dark:text-gray-100">{{ $title }}</h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 text-sm font-semibold text-indigo-700 dark:text-indigo-300">
                    ⭐ {{ number_format(auth()->user()->poin ?? 0) }} poin
                </span>
                <span class="text-sm text-gray-600 dark:text-gray-300 hidden sm:block">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        @include('components.layouts.partials.flash')

        <main class="flex-1 p-4 md:p-6">
            {{ $slot }}
        </main>
    </div>

</body>
</html>
