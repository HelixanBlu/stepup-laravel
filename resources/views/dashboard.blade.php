<x-layouts.admin title="Dashboard">

    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Selamat datang, {{ Auth::user()->name }} 👋</h2>
        <p class="text-sm text-gray-500 mt-1">Ringkasan data platform StepUp.</p>
    </div>

    {{-- Stats cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Users</p>
            <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_users'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Challenges</p>
            <p class="mt-1 text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $stats['total_challenges'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Rewards</p>
            <p class="mt-1 text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['total_rewards'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Redemptions</p>
            <p class="mt-1 text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $stats['total_redemptions'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('admin.users.index') }}"
           class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Kelola</p>
                <p class="font-semibold text-gray-900 dark:text-white">Users</p>
            </div>
        </a>

        <a href="{{ route('admin.challenges.index') }}"
           class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Kelola</p>
                <p class="font-semibold text-gray-900 dark:text-white">Challenges</p>
            </div>
        </a>

        <a href="{{ route('admin.rewards.index') }}"
           class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a4 4 0 00-4-4H6a2 2 0 00-2 2v2m8 0h8m-8 0H4m8 0v13M4 8h16" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Kelola</p>
                <p class="font-semibold text-gray-900 dark:text-white">Rewards</p>
            </div>
        </a>

        <a href="{{ route('admin.community.index') }}"
           class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Moderasi</p>
                <p class="font-semibold text-gray-900 dark:text-white">Socials</p>
            </div>
        </a>
    </div>

</x-layouts.admin>
