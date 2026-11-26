<x-layouts.admin title="Kelola Community">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Community Feed</h2>
            <p class="mt-0.5 text-sm text-gray-500">Moderasi postingan community pengguna.</p>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.community.index') }}" class="mb-6 flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari deskripsi..."
               class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <button type="submit"
                class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
            Cari
        </button>
        @if(request('search'))
            <a href="{{ route('admin.community.index') }}"
               class="inline-flex items-center rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                Reset
            </a>
        @endif
    </form>

    @if($posts->isEmpty())
        <div class="rounded-xl border border-dashed border-gray-300 dark:border-gray-700 p-10 text-center">
            <p class="text-sm text-gray-500">Belum ada postingan community.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($posts as $post)
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-semibold text-sm text-gray-900 dark:text-white">
                                    {{ $post->user?->name ?? 'User' }}
                                </span>
                                <span class="text-xs text-gray-400">•</span>
                                <span class="text-xs text-gray-400">{{ $post->created_at?->diffForHumans() }}</span>
                                <span class="ml-auto text-xs text-gray-500">{{ $post->comments_count }} komentar</span>
                            </div>

                            @if($post->deskripsi)
                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">{{ $post->deskripsi }}</p>
                            @endif

                            <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                                @if($post->langkah)
                                    <span>👟 {{ number_format($post->langkah) }} langkah</span>
                                @endif
                                @if($post->jarak)
                                    <span>📍 {{ $post->jarak }} km</span>
                                @endif
                                @if($post->kalori)
                                    <span>🔥 {{ $post->kalori }} kalori</span>
                                @endif
                            </div>
                        </div>

                        @if($post->gambar)
                            <img src="{{ asset('uploads/community/'.$post->gambar) }}"
                                 alt="foto aktivitas"
                                 class="w-20 h-20 rounded-lg object-cover shrink-0">
                        @endif
                    </div>

                    <div class="mt-4 flex justify-end">
                        <form method="POST" action="{{ route('admin.community.destroy', $post) }}"
                              onsubmit="return confirm('Hapus postingan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="rounded-md bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 transition-colors">
                                Hapus Post
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-layouts.admin>
