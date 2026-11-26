<x-layouts.app-user title="Challenges">

    {{-- Hero --}}
    <div class="mb-6 rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold">Daily & Weekly Challenges</h1>
                <p class="mt-1 text-sm text-violet-200">Join challenges, earn poin, raih reward!</p>
            </div>
            <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="mb-5 flex gap-2 flex-wrap">
        @foreach(['all' => 'Semua', 'joined' => 'Joined', 'active' => 'Aktif', 'completed' => 'Selesai'] as $key => $label)
            <button onclick="filterChallenges('{{ $key }}', this)"
                    class="filter-tab rounded-full px-4 py-1.5 text-sm font-medium transition-colors
                           {{ $key === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}"
                    data-tab="{{ $key }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Challenge Grid --}}
    @if($challenges->isEmpty())
        <div class="rounded-xl border border-dashed border-gray-300 dark:border-gray-700 p-10 text-center text-gray-500">
            Belum ada challenge aktif saat ini.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5" id="challengeGrid">
            @foreach($challenges as $challenge)
                @php
                    $typeColors = [
                        'daily'   => 'from-slate-400 to-slate-600',
                        'weekly'  => 'from-violet-400 to-violet-700',
                        'monthly' => 'from-emerald-400 to-emerald-700',
                        'team'    => 'from-amber-400 to-amber-600',
                        'event'   => 'from-pink-400 to-pink-600',
                    ];
                    $gradient = $typeColors[$challenge->challenge_type] ?? 'from-indigo-400 to-indigo-600';
                    $goalUnit = match($challenge->goal_type) { 'steps' => 'langkah', 'distance' => 'km', 'active_minutes' => 'menit', default => '' };
                @endphp
                <div class="challenge-card flex flex-col rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm overflow-hidden transition-all hover:shadow-md"
                     data-joined="{{ $challenge->is_joined ? 'true' : 'false' }}"
                     data-completed="{{ $challenge->completion_status === 'completed' ? 'true' : 'false' }}"
                     data-status="{{ $challenge->status }}"
                     data-id="{{ $challenge->id }}">

                    {{-- Banner --}}
                    <div class="relative h-32 bg-gradient-to-br {{ $gradient }} flex items-center justify-center overflow-hidden"
                         @if($challenge->banner_image) style="background-image:url('{{ asset('storage/'.$challenge->banner_image) }}');background-size:cover;background-position:center;" @endif>
                        <span class="absolute top-2 left-2 rounded-full bg-white/90 px-2.5 py-0.5 text-xs font-semibold text-gray-700 capitalize">
                            {{ $challenge->challenge_type }}
                        </span>
                        @if($challenge->completion_status === 'completed')
                            <span class="absolute top-2 right-2 rounded-full bg-green-500 px-2.5 py-0.5 text-xs font-semibold text-white">✓ Selesai</span>
                        @elseif($challenge->is_joined)
                            <span class="absolute top-2 right-2 rounded-full bg-indigo-500 px-2.5 py-0.5 text-xs font-semibold text-white">Joined</span>
                        @else
                            <span class="absolute top-2 right-2 rounded-full bg-{{ $challenge->status === 'active' ? 'emerald' : 'gray' }}-500 px-2.5 py-0.5 text-xs font-semibold text-white capitalize">
                                {{ $challenge->status }}
                            </span>
                        @endif
                    </div>

                    {{-- Body --}}
                    <div class="flex flex-col flex-1 p-4 gap-3">
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $challenge->title }}</h4>
                            @if($challenge->description)
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 line-clamp-2">{{ $challenge->description }}</p>
                            @endif
                        </div>

                        {{-- Meta --}}
                        <div class="flex flex-wrap gap-3 text-xs text-gray-500 dark:text-gray-400">
                            <span class="flex items-center gap-1">
                                🎯 Goal: {{ number_format($challenge->goal_value) }} {{ $goalUnit }}
                            </span>
                            <span class="flex items-center gap-1">
                                ⭐ {{ $challenge->reward_points }} pts
                            </span>
                            <span class="flex items-center gap-1">
                                👥 {{ $challenge->participants_count }} peserta
                            </span>
                        </div>

                        {{-- Progress bar (jika sudah join) --}}
                        @if($challenge->is_joined && $challenge->completion_status !== 'completed')
                            <div>
                                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                                    <span>Progress</span>
                                    <span>{{ number_format($challenge->current_progress) }} / {{ number_format($challenge->goal_value) }} {{ $goalUnit }}</span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                    <div class="h-2 rounded-full bg-indigo-500 transition-all"
                                         style="width: {{ $challenge->progress_percent }}%"></div>
                                </div>
                                <p class="text-right text-xs text-indigo-600 dark:text-indigo-400 mt-0.5">{{ $challenge->progress_percent }}%</p>
                            </div>
                        @endif

                        {{-- Action --}}
                        <div class="mt-auto">
                            @if($challenge->completion_status === 'completed')
                                <div class="rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 py-2.5 px-3 text-center text-sm font-semibold text-green-700 dark:text-green-400">
                                    ✓ Selesai (+{{ $challenge->reward_points }} pts)
                                </div>
                            @elseif($challenge->is_joined)
                                <button
                                    onclick="openProgressModal({{ $challenge->id }}, '{{ addslashes($challenge->title) }}', '{{ $goalUnit }}', {{ $challenge->current_progress }}, {{ $challenge->goal_value }})"
                                    class="w-full rounded-xl bg-violet-600 py-2.5 text-sm font-semibold text-white hover:bg-violet-700 active:scale-95 transition-all">
                                    ⚡ Update Progress
                                </button>
                            @else
                                <button
                                    onclick="joinChallenge({{ $challenge->id }}, this)"
                                    class="w-full rounded-xl bg-indigo-600 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 active:scale-95 transition-all">
                                    ➕ Join (+{{ $challenge->reward_points }} pts)
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Modal Update Progress --}}
    <div id="progress-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
        <div class="w-full max-w-sm mx-4 rounded-2xl bg-white dark:bg-gray-800 shadow-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-900 dark:text-white">⚡ Update Progress</h3>
                <button onclick="closeProgressModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-1" id="progress-title"></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4" id="progress-status"></p>

            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1" id="progress-label">Tambah progres</label>
            <input type="number" id="progress-value" min="1" placeholder="Contoh: 1000"
                   class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2.5 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <input type="hidden" id="progress-challenge-id">

            <div class="mt-4 flex gap-3">
                <button onclick="closeProgressModal()"
                        class="flex-1 rounded-xl border border-gray-300 dark:border-gray-600 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Batal
                </button>
                <button id="btn-submit-progress" onclick="submitProgress()"
                        class="flex-1 rounded-xl bg-violet-600 py-2.5 text-sm font-semibold text-white hover:bg-violet-700 transition-colors">
                    Simpan
                </button>
            </div>
        </div>
    </div>

    <script>
    function filterChallenges(tab, btn) {
        document.querySelectorAll('.filter-tab').forEach(b => {
            b.className = b.className.replace('bg-indigo-600 text-white', 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600');
        });
        btn.className = btn.className.replace('bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600', 'bg-indigo-600 text-white');

        document.querySelectorAll('.challenge-card').forEach(card => {
            const joined = card.dataset.joined === 'true';
            const completed = card.dataset.completed === 'true';
            const status = card.dataset.status;
            let show = true;
            if (tab === 'joined') show = joined;
            else if (tab === 'active') show = status === 'active' && !completed;
            else if (tab === 'completed') show = completed;
            card.style.display = show ? '' : 'none';
        });
    }

    function joinChallenge(id, btn) {
        btn.disabled = true;
        btn.textContent = 'Joining...';

        const form = new FormData();
        form.append('challenge_id', id);
        form.append('_token', document.querySelector('meta[name=csrf-token]').content);

        fetch('{{ route('user.challenges.join') }}', { method: 'POST', body: form })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message);
                    setTimeout(() => location.reload(), 1200);
                } else {
                    showToast('error', data.message);
                    btn.disabled = false;
                    btn.textContent = '➕ Join';
                }
            })
            .catch(() => { showToast('error', 'Koneksi gagal.'); btn.disabled = false; });
    }

    function openProgressModal(id, title, unit, current, goal) {
        document.getElementById('progress-challenge-id').value = id;
        document.getElementById('progress-title').textContent = title;
        document.getElementById('progress-status').textContent = `Progress: ${current.toLocaleString('id-ID')} / ${goal.toLocaleString('id-ID')} ${unit}`;
        document.getElementById('progress-label').textContent = `Tambah progres (${unit})`;
        document.getElementById('progress-value').value = '';
        document.getElementById('progress-modal').classList.remove('hidden');
    }

    function closeProgressModal() {
        document.getElementById('progress-modal').classList.add('hidden');
    }

    function submitProgress() {
        const id = document.getElementById('progress-challenge-id').value;
        const val = document.getElementById('progress-value').value;
        if (!val || parseInt(val) < 1) { showToast('error', 'Masukkan nilai yang valid.'); return; }

        const btn = document.getElementById('btn-submit-progress');
        btn.disabled = true; btn.textContent = 'Menyimpan...';

        const form = new FormData();
        form.append('challenge_id', id);
        form.append('progress_value', val);
        form.append('_token', document.querySelector('meta[name=csrf-token]').content);

        fetch('{{ route('user.challenges.log_progress') }}', { method: 'POST', body: form })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false; btn.textContent = 'Simpan';
                closeProgressModal();
                if (data.success) {
                    showToast('success', data.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('error', data.message);
                }
            })
            .catch(() => { btn.disabled = false; btn.textContent = 'Simpan'; showToast('error', 'Koneksi gagal.'); });
    }

    function showToast(type, msg) {
        const old = document.getElementById('toast');
        if (old) old.remove();
        const el = document.createElement('div');
        el.id = 'toast';
        el.className = `fixed bottom-5 right-5 z-[100] flex items-center gap-2 rounded-xl px-4 py-3 text-sm font-medium shadow-lg
            ${type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'}`;
        el.innerHTML = `<span>${msg}</span><button onclick="this.parentElement.remove()" class="ml-2 text-white/80 hover:text-white">✕</button>`;
        document.body.appendChild(el);
        setTimeout(() => { if (el.parentElement) { el.style.opacity='0'; setTimeout(() => el.remove(), 300); } }, 4500);
    }

    document.getElementById('progress-modal')?.addEventListener('click', function(e) {
        if (e.target === this) closeProgressModal();
    });
    </script>
</x-layouts.app-user>
