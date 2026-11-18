<!DOCTYPE html>
  <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <title>{{ $title ?? 'Admin' }} — {{ config('app.name') }}</title>

      <link rel="preconnect" href="https://fonts.bunny.net">
      <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

      @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900"
        x-data
        x-init="Alpine.store('sidebar', {
            open: true,
            toggle() { this.open = !this.open }
        })">

      {{-- Sidebar --}}
      @include('layouts.sidebar')

      {{-- Wrapper yang geser mengikuti lebar sidebar --}}
      <div x-data="{ open: $store.sidebar.open }"
           x-bind:class="open ? 'ml-64' : 'ml-16'"
           class="transition-all duration-300 ease-in-out min-h-screen flex flex-col">

          {{-- Top Bar --}}
          <header class="sticky top-0 z-20 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 h-16 flex items-center     
  justify-between px-6 shadow-sm">

              {{-- Kiri: judul halaman --}}
              <div class="flex items-center gap-3">
                  {{-- Hamburger mobile --}}
                  <button @click="$store.sidebar.toggle()"
                          class="p-1.5 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 lg:hidden">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                      </svg>
                  </button>
                  <h1 class="text-base font-semibold text-gray-800 dark:text-gray-100">
                      {{ $title ?? 'Dashboard' }}
                  </h1>
              </div>

              {{-- Kanan: info user + logout --}}
              <div class="flex items-center gap-4">
                  <span class="text-sm text-gray-600 dark:text-gray-300 hidden sm:block">
                      {{ Auth::user()->name }}
                  </span>

                  <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit"
                              class="text-sm text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors">  
                          Logout
                      </button>
                  </form>
              </div>
          </header>

          {{-- Flash message --}}
          @if (session('success'))
              <div x-data="{ show: true }"
                   x-show="show"
                   x-init="setTimeout(() => show = false, 4000)"
                   x-transition.opacity
                   class="mx-6 mt-4 flex items-center gap-2 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700"> 
                  <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1
  0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                  {{ session('success') }}
              </div>
          @endif

          @if (session('error'))
              <div x-data="{ show: true }"
                   x-show="show"
                   x-init="setTimeout(() => show = false, 5000)"
                   x-transition.opacity
                   class="mx-6 mt-4 flex items-center gap-2 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">       
                  <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 
  1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
  clip-rule="evenodd" />
                  </svg>
                  {{ session('error') }}
              </div>
          @endif

          {{-- Main Content --}}
          <main class="flex-1 p-6">
              {{ $slot }}
          </main>
      </div>

  </body>
  </html>