<x-layouts.admin title="Challenges">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Challenge</h2>
            <p class="mt-0.5 text-sm text-gray-500">Kelola challenge aktivitas dan reward untuk pengguna.</p>
        </div>
        <a href="{{ route('admin.challenges.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            Tambah Challenge
        </a>
    </div>

    <form method="GET" action="{{ route('admin.challenges.index') }}" class="mb-6">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari judul challenge..." class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
    </form>

    @if ($challenges->isEmpty())
        <div class="rounded-xl border border-dashed border-gray-300 bg-white px-5 py-14 text-center shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <svg class="mx-auto mb-3 h-10 w-10 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a4 4 0 00-4-4H6a2 2 0 00-2 2v2m8 0h8m-8 0H4m8 0v13M4 8h16" /></svg>
            <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada challenge yang sesuai.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($challenges as $challenge)
                <article class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    @if ($challenge->banner_image && Storage::disk('public')->exists($challenge->banner_image))
                        <img src="{{ Storage::disk('public')->url($challenge->banner_image) }}" alt="{{ $challenge->title }}" class="h-40 w-full object-cover">
                    @else
                        <div class="flex h-40 items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-700">
                            <svg class="h-12 w-12 text-white/70" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                    @endif

                    <div class="p-5">
                        <div class="mb-3 flex items-start justify-between gap-3">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $challenge->title }}</h3>
                            <span class="shrink-0 rounded-full px-2.5 py-0.5 text-xs font-semibold {{ match ($challenge->status) { 'active' => 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-300', 'completed' => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300', 'archived' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300', default => 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300' } }}">{{ ucfirst($challenge->status) }}</span>
                        </div>
                        <p class="mb-4 min-h-10 text-sm text-gray-500 dark:text-gray-400">{{ $challenge->description ?: 'Tidak ada deskripsi.' }}</p>

                        <dl class="grid grid-cols-2 gap-x-4 gap-y-3 border-y border-gray-100 py-4 text-sm dark:border-gray-700">
                            <div><dt class="text-xs text-gray-400">Tipe</dt><dd class="mt-0.5 font-medium capitalize text-gray-700 dark:text-gray-200">{{ str_replace('_', ' ', $challenge->challenge_type) }}</dd></div>
                            <div><dt class="text-xs text-gray-400">Target</dt><dd class="mt-0.5 font-medium text-gray-700 dark:text-gray-200">{{ number_format($challenge->goal_value) }} {{ str_replace('_', ' ', $challenge->goal_type) }}</dd></div>
                            <div><dt class="text-xs text-gray-400">Reward</dt><dd class="mt-0.5 font-medium text-indigo-600 dark:text-indigo-300">{{ number_format($challenge->reward_points) }} poin</dd></div>
                            <div><dt class="text-xs text-gray-400">Peserta</dt><dd class="mt-0.5 font-medium text-gray-700 dark:text-gray-200">{{ $challenge->participants_count }} {{ Str::plural('participant', $challenge->participants_count) }}</dd></div>
                            <div class="col-span-2"><dt class="text-xs text-gray-400">Periode</dt><dd class="mt-0.5 font-medium text-gray-700 dark:text-gray-200">{{ $challenge->start_date->format('d M Y') }} – {{ $challenge->end_date->format('d M Y') }}</dd></div>
                        </dl>

                        <div class="mt-4 flex items-center justify-between gap-3">
                            <span class="truncate text-xs text-gray-400">{{ $challenge->badge_reward ? 'Badge: '.$challenge->badge_reward : 'Tanpa badge reward' }}</span>
                            <div class="flex shrink-0 items-center gap-2">
                                <a href="{{ route('admin.challenges.edit', $challenge) }}" class="inline-flex items-center gap-1 rounded-md bg-amber-50 px-2.5 py-1.5 text-xs font-medium text-amber-700 transition-colors hover:bg-amber-100 dark:bg-amber-900/20 dark:text-amber-400 dark:hover:bg-amber-900/40">Edit</a>
                                <form method="POST" action="{{ route('admin.challenges.destroy', $challenge) }}" onsubmit="return confirm('Hapus challenge ini? Semua data peserta dan collection terkait akan ikut terhapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 rounded-md bg-red-50 px-2.5 py-1.5 text-xs font-medium text-red-700 transition-colors hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</x-layouts.admin>
