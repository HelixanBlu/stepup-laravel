<x-layouts.app-user title="Rewards">

    {{-- Balance Card --}}
    <div class="mb-6 rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-500 p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-200">Your Rewards Balance</p>
                <p class="mt-1 text-3xl font-bold" id="user-poin-display">{{ number_format(auth()->user()->poin ?? 0) }}</p>
                <p class="text-sm text-indigo-200">poin tersedia</p>
            </div>
            <div class="flex flex-col items-end gap-2">
                <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a4 4 0 00-4-4H6a2 2 0 00-2 2v2m8 0h8m-8 0H4m8 0v13M4 8h16" />
                    </svg>
                </div>
                <button onclick="openHistoryModal()"
                        class="text-xs font-medium bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-full transition-colors">
                    View History
                </button>
            </div>
        </div>
    </div>

    {{-- Rewards Grid --}}
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Exclusive Rewards</h2>
        <span class="text-sm text-gray-500">{{ $rewards->count() }} reward tersedia</span>
    </div>

    @if($rewards->isEmpty())
        <div class="rounded-xl border border-dashed border-gray-300 dark:border-gray-700 p-10 text-center">
            <p class="text-gray-500">Belum ada reward tersedia.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5" id="rewardGrid">
            @foreach($rewards as $reward)
                @php
                    $bisaRedeem = ($reward->stok > 0) && ((auth()->user()->poin ?? 0) >= $reward->poin);
                @endphp
                <article class="reward-card flex flex-col rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm overflow-hidden transition-all hover:shadow-md"
                         id="reward-card-{{ $reward->id_reward }}"
                         data-poin="{{ $reward->poin }}"
                         data-stok="{{ $reward->stok }}">

                    {{-- Gambar --}}
                    <div class="h-40 bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center overflow-hidden">
                        @if($reward->gambar)
                            <img src="{{ asset('uploads/'.$reward->gambar) }}"
                                 alt="{{ $reward->name_reward }}"
                                 class="w-full h-full object-cover">
                        @else
                            <svg class="w-16 h-16 text-indigo-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a4 4 0 00-4-4H6a2 2 0 00-2 2v2m8 0h8m-8 0H4m8 0v13M4 8h16" />
                            </svg>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex flex-col flex-1 p-4 gap-3">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $reward->name_reward }}</h4>
                            @if($reward->description)
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 line-clamp-2">{{ $reward->description }}</p>
                            @endif
                        </div>

                        {{-- Poin & Stok --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($reward->poin) }}</span>
                                <span class="text-xs text-gray-500">poin</span>
                            </div>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                {{ $reward->stok > 0 ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}"
                                  id="stok-badge-{{ $reward->id_reward }}">
                                Stok: <span id="stok-{{ $reward->id_reward }}">{{ $reward->stok }}</span>
                            </span>
                        </div>

                        {{-- Redeem Button --}}
                        <button
                            id="btn-redeem-{{ $reward->id_reward }}"
                            onclick="openRedeemModal({{ $reward->id_reward }}, '{{ addslashes($reward->name_reward) }}', {{ $reward->poin }})"
                            @if(!$bisaRedeem) disabled @endif
                            class="w-full rounded-xl py-2.5 text-sm font-semibold transition-all
                                {{ $bisaRedeem
                                    ? 'bg-indigo-600 text-white hover:bg-indigo-700 active:scale-95'
                                    : 'bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500 cursor-not-allowed' }}">
                            @if($reward->stok <= 0)
                                Stok Habis
                            @elseif((auth()->user()->poin ?? 0) < $reward->poin)
                                Poin Kurang
                            @else
                                Redeem Sekarang
                            @endif
                        </button>
                    </div>
                </article>
            @endforeach
        </div>
    @endif

    {{-- Modal Konfirmasi Redeem --}}
    <div id="redeem-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
        <div class="w-full max-w-sm mx-4 rounded-2xl bg-white dark:bg-gray-800 shadow-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">🎁 Konfirmasi Redeem</h3>
                <button onclick="closeRedeemModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Anda akan menukarkan:</p>
            <p class="text-base font-semibold text-indigo-600 dark:text-indigo-400 mb-1" id="redeem-nama-display">-</p>
            <p class="text-sm text-gray-700 dark:text-gray-300">Poin digunakan: <span class="font-bold" id="redeem-poin-display">0</span></p>
            <p class="mt-2 text-xs text-gray-400">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="mt-5 flex gap-3">
                <button onclick="closeRedeemModal()"
                        class="flex-1 rounded-xl border border-gray-300 dark:border-gray-600 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Batal
                </button>
                <button id="btn-confirm-redeem" onclick="confirmRedeem()"
                        class="flex-1 rounded-xl bg-indigo-600 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition-colors">
                    Ya, Redeem!
                </button>
            </div>
        </div>
    </div>

    {{-- Modal History --}}
    <div id="history-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
        <div class="w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-gray-800 shadow-xl flex flex-col max-h-[80vh]">
            <div class="flex items-center justify-between p-5 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">📋 Redemption History</h3>
                <button onclick="closeHistoryModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-4" id="history-content">
                @if($history->isEmpty())
                    <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                        <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a4 4 0 00-4-4H6a2 2 0 00-2 2v2m8 0h8m-8 0H4m8 0v13M4 8h16" />
                        </svg>
                        <p class="text-sm">Belum ada riwayat redeem.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($history as $item)
                            <div class="flex items-center gap-3 rounded-xl border border-gray-100 dark:border-gray-700 p-3">
                                <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center shrink-0">
                                    <span class="text-sm">🎁</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $item->nama_reward }}</p>
                                    <p class="text-xs text-gray-400">{{ $item->tanggal_redeem?->format('d M Y, H:i') }}</p>
                                </div>
                                <span class="text-sm font-semibold text-red-500 shrink-0">-{{ number_format($item->poin_digunakan) }} pts</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
    let pendingRedeemId = 0;
    let pendingRedeemPoin = 0;
    let currentUserPoin = {{ auth()->user()->poin ?? 0 }};

    function openRedeemModal(rewardId, rewardName, poin) {
        pendingRedeemId = rewardId;
        pendingRedeemPoin = poin;
        document.getElementById('redeem-nama-display').textContent = rewardName;
        document.getElementById('redeem-poin-display').textContent = poin.toLocaleString('id-ID');
        document.getElementById('redeem-modal').classList.remove('hidden');
    }

    function closeRedeemModal() {
        document.getElementById('redeem-modal').classList.add('hidden');
    }

    function openHistoryModal() {
        document.getElementById('history-modal').classList.remove('hidden');
    }

    function closeHistoryModal() {
        document.getElementById('history-modal').classList.add('hidden');
    }

    function confirmRedeem() {
        const btn = document.getElementById('btn-confirm-redeem');
        btn.disabled = true;
        btn.textContent = 'Memproses...';

        const form = new FormData();
        form.append('reward_id', pendingRedeemId);
        form.append('_token', document.querySelector('meta[name=csrf-token]').content);

        fetch('{{ route('user.rewards.redeem') }}', { method: 'POST', body: form })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = 'Ya, Redeem!';
                closeRedeemModal();

                if (data.success) {
                    currentUserPoin = data.data.sisa_poin;
                    document.getElementById('user-poin-display').textContent = currentUserPoin.toLocaleString('id-ID');

                    const stokEl = document.getElementById('stok-' + pendingRedeemId);
                    if (stokEl) stokEl.textContent = data.data.sisa_stok;

                    updateAllButtons();
                    showToast('success', data.message);
                    // reload history
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showToast('error', data.message);
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.textContent = 'Ya, Redeem!';
                closeRedeemModal();
                showToast('error', 'Terjadi kesalahan koneksi.');
            });
    }

    function updateAllButtons() {
        document.querySelectorAll('.reward-card').forEach(card => {
            const id = card.id.replace('reward-card-', '');
            const poin = parseInt(card.dataset.poin);
            const stok = parseInt(document.getElementById('stok-' + id)?.textContent ?? '0');
            const btn = document.getElementById('btn-redeem-' + id);
            if (!btn) return;

            if (stok <= 0) {
                btn.disabled = true;
                btn.textContent = 'Stok Habis';
                btn.className = btn.className.replace('bg-indigo-600 text-white hover:bg-indigo-700 active:scale-95', 'bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500 cursor-not-allowed');
            } else if (currentUserPoin < poin) {
                btn.disabled = true;
                btn.textContent = 'Poin Kurang';
                btn.className = btn.className.replace('bg-indigo-600 text-white hover:bg-indigo-700 active:scale-95', 'bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500 cursor-not-allowed');
            } else {
                btn.disabled = false;
                btn.textContent = 'Redeem Sekarang';
                btn.className = btn.className.replace('bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500 cursor-not-allowed', 'bg-indigo-600 text-white hover:bg-indigo-700 active:scale-95');
            }
        });
    }

    function showToast(type, message) {
        const old = document.getElementById('toast-notif');
        if (old) old.remove();
        const el = document.createElement('div');
        el.id = 'toast-notif';
        el.className = `fixed bottom-5 right-5 z-[100] flex items-center gap-2 rounded-xl px-4 py-3 text-sm font-medium shadow-lg transition-all
            ${type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'}`;
        el.innerHTML = `<span>${message}</span><button onclick="this.parentElement.remove()" class="ml-2 text-white/80 hover:text-white">✕</button>`;
        document.body.appendChild(el);
        setTimeout(() => { if (el.parentElement) el.style.opacity = '0'; setTimeout(() => el.remove(), 300); }, 4000);
    }

    // close modal on overlay click
    ['redeem-modal', 'history-modal'].forEach(id => {
        document.getElementById(id)?.addEventListener('click', function(e) {
            if (e.target === this) this.classList.add('hidden');
        });
    });
    </script>
</x-layouts.app-user>
