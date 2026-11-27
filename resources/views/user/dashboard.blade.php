<x-layouts.app-user title="Dashboard">

    {{-- Welcome --}}
    <div class="mb-6 rounded-2xl bg-gradient-to-r from-indigo-600 to-violet-600 p-6 text-white shadow-lg">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold">Selamat datang, {{ $user->name }}! 👋</h1>
                <p class="mt-1 text-sm text-indigo-200">Terus aktif dan raih reward lebih banyak hari ini.</p>
            </div>
            <div class="shrink-0 text-center">
                <p class="text-3xl font-bold">{{ number_format($user->poin ?? 0) }}</p>
                <p class="text-xs text-indigo-200">poin tersedia</p>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
        <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 shadow-sm text-center">
            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $totalJoined }}</p>
            <p class="mt-1 text-xs text-gray-500">Challenge Diikuti</p>
        </div>
        <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 shadow-sm text-center">
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $totalCompleted }}</p>
            <p class="mt-1 text-xs text-gray-500">Challenge Selesai</p>
        </div>
        <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 shadow-sm text-center">
            <p class="text-2xl font-bold text-pink-600 dark:text-pink-400">{{ $totalRedemptions }}</p>
            <p class="mt-1 text-xs text-gray-500">Reward Ditukar</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Active Challenges --}}
        <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-gray-900 dark:text-white">⚡ Challenge Aktif</h2>
                <a href="{{ route('user.challenges.index') }}"
                   class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Semua →</a>
            </div>

            @if($activeChallenges->isEmpty())
                <p class="text-sm text-gray-400 py-4 text-center">Belum ada challenge aktif.</p>
            @else
                <div class="space-y-3">
                    @foreach($activeChallenges as $challenge)
                        @php
                            $goalUnit = match($challenge->goal_type) {
                                'steps' => 'langkah', 'distance' => 'km', 'active_minutes' => 'menit', default => ''
                            };
                        @endphp
                        <div class="rounded-xl border border-gray-100 dark:border-gray-700 p-3">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $challenge->title }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Goal: {{ number_format($challenge->goal_value) }} {{ $goalUnit }} · ⭐ {{ $challenge->reward_points }} pts
                                    </p>
                                </div>
                                @if($challenge->is_joined)
                                    @if($challenge->completion_status === 'completed')
                                        <span class="shrink-0 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-medium px-2 py-0.5">✓ Selesai</span>
                                    @else
                                        <span class="shrink-0 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 text-xs font-medium px-2 py-0.5">Joined</span>
                                    @endif
                                @endif
                            </div>
                            @if($challenge->is_joined && $challenge->completion_status !== 'completed')
                                <div class="mt-2">
                                    <div class="h-1.5 w-full rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                        <div class="h-1.5 rounded-full bg-indigo-500" style="width:{{ $challenge->progress_percent }}%"></div>
                                    </div>
                                    <p class="text-right text-xs text-gray-400 mt-0.5">{{ $challenge->progress_percent }}%</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Quick Nav --}}
        <div class="space-y-4">
            <h2 class="font-bold text-gray-900 dark:text-white">🚀 Menu Cepat</h2>
            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('user.rewards.index') }}"
                   class="flex items-center gap-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-xl shrink-0">🎁</div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Rewards</p>
                        <p class="text-xs text-gray-500">Tukarkan poinmu sekarang</p>
                    </div>
                </a>
                <a href="{{ route('user.challenges.index') }}"
                   class="flex items-center gap-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 rounded-xl bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-xl shrink-0">⚡</div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Challenges</p>
                        <p class="text-xs text-gray-500">Join dan update progressmu</p>
                    </div>
                </a>
                <a href="{{ route('user.community.index') }}"
                   class="flex items-center gap-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 rounded-xl bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center text-xl shrink-0">👥</div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Socials</p>
                        <p class="text-xs text-gray-500">Bagikan aktivitasmu</p>
                    </div>
                </a>
            </div>

            {{-- Recent Redemptions --}}
            @if($recentRedemptions->isNotEmpty())
                <div class="mt-2">
                    <h2 class="font-bold text-gray-900 dark:text-white mb-3">📋 Redeem Terakhir</h2>
                    <div class="space-y-2">
                        @foreach($recentRedemptions as $r)
                            <div class="flex items-center justify-between rounded-xl border border-gray-100 dark:border-gray-700 px-3 py-2.5">
                                <span class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $r->nama_reward }}</span>
                                <span class="ml-2 shrink-0 text-xs font-semibold text-red-500">-{{ number_format($r->poin_digunakan) }} pts</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-layouts.app-user>
