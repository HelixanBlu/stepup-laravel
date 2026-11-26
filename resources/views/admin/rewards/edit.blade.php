<x-layouts.admin title="Edit Reward">
    <div class="mb-6">
        <a href="{{ route('admin.rewards.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Kembali ke Daftar Reward
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Edit Reward</h2>
        @include('admin.rewards.form', ['reward' => $reward])
    </div>
</x-layouts.admin>
