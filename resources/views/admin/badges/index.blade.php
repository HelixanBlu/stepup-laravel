<x-layouts.admin title="Badges">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Badges</h2>
            <p class="mt-0.5 text-sm text-gray-500">Kelola badge pencapaian dan persyaratan unlock pengguna.</p>
        </div>
        <a href="{{ route('admin.badges.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-indigo-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            Tambah Badge
        </a>
    </div>

    <form method="GET" action="{{ route('admin.badges.index') }}" class="mb-6 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama badge..." class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        <select name="category" class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <option value="">Semua kategori</option>
            @foreach (['challenge' => 'Challenge', 'collection' => 'Collection', 'activity' => 'Activity', 'streak' => 'Streak', 'special_event' => 'Special Event'] as $value => $label)
                <option value="{{ $value }}" @selected(request('category') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="rarity" class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <option value="">Semua rarity</option>
            @foreach (['common' => 'Common', 'rare' => 'Rare', 'epic' => 'Epic', 'legendary' => 'Legendary'] as $value => $label)
                <option value="{{ $value }}" @selected(request('rarity') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <div class="flex gap-3">
            <select name="status" class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">Semua status</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
            </select>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 text-sm font-medium text-white transition-colors hover:bg-indigo-700">Filter</button>
        </div>
    </form>

    @if ($badges->isEmpty())
        <div class="rounded-xl border border-dashed border-gray-300 bg-white px-5 py-14 text-center shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <svg class="mx-auto mb-3 h-10 w-10 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
            <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada badge yang sesuai.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($badges as $badge)
                <article class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex h-40 items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-700 p-5">
                        @if ($badge->badge_icon && Storage::disk('public')->exists($badge->badge_icon))
                            <img src="{{ Storage::disk('public')->url($badge->badge_icon) }}" alt="{{ $badge->name }}" class="h-24 w-24 rounded-full object-cover ring-4 ring-white/30">
                        @else
                            <svg class="h-16 w-16 text-white/80" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                        @endif
                    </div>

                    <div class="p-5">
                        <div class="mb-3 flex items-start justify-between gap-3">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $badge->name }}</h3>
                            <span class="shrink-0 rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $badge->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">{{ ucfirst($badge->status) }}</span>
                        </div>
                        <p class="mb-4 min-h-10 text-sm text-gray-500 dark:text-gray-400">{{ $badge->description ?: 'Tidak ada deskripsi.' }}</p>

                        <dl class="grid grid-cols-2 gap-x-4 gap-y-3 border-y border-gray-100 py-4 text-sm dark:border-gray-700">
                            <div><dt class="text-xs text-gray-400">Kategori</dt><dd class="mt-0.5 font-medium capitalize text-gray-700 dark:text-gray-200">{{ str_replace('_', ' ', $badge->category) }}</dd></div>
                            <div><dt class="text-xs text-gray-400">Rarity</dt><dd class="mt-0.5 font-medium capitalize text-indigo-600 dark:text-indigo-300">{{ $badge->rarity }}</dd></div>
                            <div class="col-span-2"><dt class="text-xs text-gray-400">Persyaratan unlock</dt><dd class="mt-0.5 font-medium text-gray-700 dark:text-gray-200">{{ $badge->unlock_requirement ?: 'Tidak ada persyaratan.' }}</dd></div>
                            @if ($badge->challenge)
                                <div class="col-span-2"><dt class="text-xs text-gray-400">Related Challenge</dt><dd class="mt-0.5 font-medium text-gray-700 dark:text-gray-200">{{ $badge->challenge->title }}</dd></div>
                            @endif
                            @if ($badge->collection)
                                <div class="col-span-2"><dt class="text-xs text-gray-400">Related Collection</dt><dd class="mt-0.5 font-medium text-gray-700 dark:text-gray-200">{{ $badge->collection->name }}</dd></div>
                            @endif
                        </dl>

                        <div class="mt-4 flex items-center justify-between gap-3">
                            <span class="text-xs text-gray-400">{{ $badge->users_count }} {{ Str::plural('user', $badge->users_count) }} earned</span>
                            <div class="flex shrink-0 items-center gap-2">
                                <a href="{{ route('admin.badges.edit', $badge) }}" class="rounded-md bg-amber-50 px-2.5 py-1.5 text-xs font-medium text-amber-700 transition-colors hover:bg-amber-100 dark:bg-amber-900/20 dark:text-amber-400 dark:hover:bg-amber-900/40">Edit</a>
                                <form method="POST" action="{{ route('admin.badges.destroy', $badge) }}" onsubmit="return confirm('Hapus badge ini? Semua riwayat user_badges yang terkait akan ikut terhapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md bg-red-50 px-2.5 py-1.5 text-xs font-medium text-red-700 transition-colors hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</x-layouts.admin>
