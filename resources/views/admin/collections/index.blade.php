<x-layouts.admin title="Collections">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Collections</h2>
            <p class="mt-0.5 text-sm text-gray-500">Kelola kumpulan challenge untuk pengguna.</p>
        </div>
        <a href="{{ route('admin.collections.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-indigo-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            Tambah Collection
        </a>
    </div>

    <form method="GET" action="{{ route('admin.collections.index') }}" class="mb-6">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama collection..." class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
    </form>

    @if ($collections->isEmpty())
        <div class="rounded-xl border border-dashed border-gray-300 bg-white px-5 py-14 text-center shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <svg class="mx-auto mb-3 h-10 w-10 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 00-2-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada collection yang sesuai.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($collections as $collection)
                <article class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <a href="{{ route('admin.collections.show', $collection) }}" class="block">
                        @if ($collection->cover_image && Storage::disk('public')->exists($collection->cover_image))
                            <img src="{{ Storage::disk('public')->url($collection->cover_image) }}" alt="{{ $collection->name }}" class="h-40 w-full object-cover">
                        @else
                            <div class="flex h-40 items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-700">
                                <svg class="h-12 w-12 text-white/70" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 00-2-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                            </div>
                        @endif

                        <div class="p-5">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $collection->name }}</h3>
                            <p class="mt-2 min-h-10 text-sm text-gray-500 dark:text-gray-400">{{ $collection->description ?: 'Tidak ada deskripsi.' }}</p>
                            <p class="mt-4 text-sm font-medium text-indigo-600 dark:text-indigo-300">{{ $collection->challenges_count }} {{ Str::plural('challenge', $collection->challenges_count) }}</p>
                        </div>
                    </a>
                    <div class="flex items-center justify-end gap-2 border-t border-gray-100 px-5 py-4 dark:border-gray-700">
                        <a href="{{ route('admin.collections.edit', $collection) }}" class="rounded-md bg-amber-50 px-2.5 py-1.5 text-xs font-medium text-amber-700 transition-colors hover:bg-amber-100 dark:bg-amber-900/20 dark:text-amber-400 dark:hover:bg-amber-900/40">Edit</a>
                        <form method="POST" action="{{ route('admin.collections.destroy', $collection) }}" onsubmit="return confirm('Hapus collection ini? Challenge yang terkait tidak akan dihapus.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-md bg-red-50 px-2.5 py-1.5 text-xs font-medium text-red-700 transition-colors hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40">Hapus</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</x-layouts.admin>
