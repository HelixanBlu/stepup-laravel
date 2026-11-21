<x-layouts.admin title="Edit Badge">
    <div class="mb-6">
        <a href="{{ route('admin.badges.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Kembali ke Daftar Badge
        </a>
    </div>

    <div class="max-w-3xl rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h2 class="mb-6 text-lg font-bold text-gray-900 dark:text-white">Edit Badge</h2>
        @include('admin.badges.form', ['badge' => $badge])
    </div>
</x-layouts.admin>
