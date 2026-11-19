<x-layouts.admin title="Collection Detail">
    <div class="mb-6">
        <a href="{{ route('admin.collections.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Kembali ke Daftar Collection
        </a>
    </div>

    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        @if ($collection->cover_image && Storage::disk('public')->exists($collection->cover_image))
            <img src="{{ Storage::disk('public')->url($collection->cover_image) }}" alt="{{ $collection->name }}" class="h-56 w-full object-cover">
        @else
            <div class="flex h-56 items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-700">
                <svg class="h-16 w-16 text-white/70" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 00-2-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            </div>
        @endif

        <div class="p-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $collection->name }}</h2>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $collection->description ?: 'Tidak ada deskripsi.' }}</p>
                </div>
                <a href="{{ route('admin.collections.edit', $collection) }}" class="inline-flex shrink-0 justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700">Edit Collection</a>
            </div>

            <h3 class="mt-8 text-base font-semibold text-gray-900 dark:text-white">Associated Challenges</h3>
            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                @forelse ($collection->challenges as $challenge)
                    <article class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                        <div class="flex items-start justify-between gap-3">
                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $challenge->title }}</h4>
                            <span class="shrink-0 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ ucfirst($challenge->status) }}</span>
                        </div>
                        <dl class="mt-4 grid grid-cols-2 gap-3 text-sm">
                            <div><dt class="text-xs text-gray-400">Tipe</dt><dd class="mt-0.5 font-medium capitalize text-gray-700 dark:text-gray-200">{{ str_replace('_', ' ', $challenge->challenge_type) }}</dd></div>
                            <div><dt class="text-xs text-gray-400">Target</dt><dd class="mt-0.5 font-medium text-gray-700 dark:text-gray-200">{{ number_format($challenge->goal_value) }} {{ str_replace('_', ' ', $challenge->goal_type) }}</dd></div>
                            <div><dt class="text-xs text-gray-400">Reward</dt><dd class="mt-0.5 font-medium text-indigo-600 dark:text-indigo-300">{{ number_format($challenge->reward_points) }} poin</dd></div>
                        </dl>
                    </article>
                @empty
                    <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada challenge dalam collection ini.</p>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.admin>
