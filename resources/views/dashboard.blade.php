<x-layouts.admin title="Dashboard">

    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Selamat datang, {{ Auth::user()->name }} 👋</h2>
        <p class="text-sm text-gray-500 mt-1">Ini adalah halaman admin StepUp.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('admin.rewards.index') }}"
           class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a4 4 0 00-4-4H6a2 2 0 00-2 2v2m8 0h8m-8 0H4m8 0v13M4 8h16" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Kelola</p>
                <p class="font-semibold text-gray-900 dark:text-white">Rewards</p>
            </div>
        </a>
    </div>

</x-layouts.admin>
