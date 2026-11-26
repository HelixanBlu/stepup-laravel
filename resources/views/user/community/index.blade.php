<x-layouts.app-user title="Community">

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: Feed --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Header --}}
        <div class="rounded-2xl bg-gradient-to-r from-indigo-600 to-violet-600 p-6 text-white shadow-lg flex items-center justify-between gap-4">
            <div>
                <h1 class="text-lg font-bold">StepUp Community</h1>
                <p class="text-sm text-indigo-200">Bagikan perjalanan kebugaran Anda, rayakan pencapaian!</p>
            </div>
            <button onclick="openPostModal()"
                    class="shrink-0 inline-flex items-center gap-2 rounded-xl bg-white/20 hover:bg-white/30 px-4 py-2.5 text-sm font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Buat Postingan
            </button>
        </div>

        {{-- Posts --}}
        <div id="postContainer" class="space-y-4">
            <div class="flex flex-col items-center justify-center py-16 text-gray-400 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                </svg>
                <p class="text-sm">Memuat postingan...</p>
            </div>
        </div>
    </div>

    {{-- RIGHT: Leaderboard --}}
    <div class="space-y-5">
        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm">
            <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">🏆 Leaderboard Community</h2>
            <div id="leaderboardList" class="space-y-3">
                <p class="text-sm text-gray-400 text-center py-4">Memuat...</p>
            </div>
        </div>

        <div class="rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 p-5 text-white shadow-lg">
            <p class="font-semibold">👥 Ajak Teman</p>
            <p class="mt-2 text-sm text-white/90">Undang teman untuk bergabung dalam tantangan seru dan capai tujuan kebugaran bersama!</p>
            <button class="mt-4 w-full rounded-xl bg-white/20 hover:bg-white/30 py-2 text-sm font-semibold transition-colors">
                Ajak Teman Sekarang
            </button>
        </div>
    </div>
</div>

{{-- Modal Buat Postingan --}}
<div id="post-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
    <div class="w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-gray-800 shadow-xl p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-gray-900 dark:text-white">Buat Postingan Baru</h3>
            <button onclick="closePostModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="formPost" class="space-y-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3" placeholder="Ceritakan aktivitasmu hari ini..."
                          class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Langkah</label>
                    <input type="number" name="langkah" min="0" placeholder="8000"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Jarak (km)</label>
                    <input type="number" name="jarak" min="0" step="0.1" placeholder="5.2"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Kalori</label>
                    <input type="number" name="kalori" min="0" placeholder="300"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Upload Gambar</label>
                <input type="file" name="gambar" accept="image/*"
                       class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:dark:bg-indigo-900/30 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-indigo-600 dark:file:text-indigo-400">
            </div>

            <button type="submit"
                    class="w-full rounded-xl bg-indigo-600 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition-colors">
                Posting
            </button>
        </form>
    </div>
</div>

{{-- Modal Komentar --}}
<div id="comment-modal" class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/50 backdrop-blur-sm hidden">
    <div class="w-full max-w-lg sm:mx-4 rounded-t-2xl sm:rounded-2xl bg-white dark:bg-gray-800 shadow-xl flex flex-col max-h-[85vh]">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-bold text-gray-900 dark:text-white">Komentar</h3>
            <button onclick="closeCommentModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="commentList" class="flex-1 overflow-y-auto p-4 space-y-3">
            <p class="text-sm text-gray-400 text-center py-6">Belum ada komentar. Jadilah yang pertama!</p>
        </div>
        <form id="formComment" class="p-4 border-t border-gray-200 dark:border-gray-700 flex gap-2">
            <input type="hidden" id="commentPostId">
            <input type="text" id="commentInput" placeholder="Tulis komentar..."
                   class="flex-1 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <button type="submit"
                    class="rounded-xl bg-indigo-600 px-4 text-sm font-semibold text-white hover:bg-indigo-700 transition-colors">
                Kirim
            </button>
        </form>
    </div>
</div>

<script>
const CSRF = document.querySelector('meta[name=csrf-token]').content;

// =====================
// LOAD POSTS
// =====================
function loadPosts() {
    fetch('{{ route('user.community.posts') }}')
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('postContainer');
            if (!data.success || data.data.length === 0) {
                container.innerHTML = `<div class="rounded-xl border border-dashed border-gray-300 dark:border-gray-700 p-10 text-center text-gray-500 text-sm">Belum ada postingan. Jadilah yang pertama!</div>`;
                return;
            }
            container.innerHTML = data.data.map(renderPost).join('');
        });
}

function renderPost(post) {
    const gambarHtml = post.gambar
        ? `<img src="{{ asset('uploads/community') }}/${post.gambar}" alt="foto" class="mt-3 w-full rounded-xl max-h-80 object-cover">`
        : '';

    const statsHtml = [
        post.langkah ? `👟 ${Number(post.langkah).toLocaleString('id-ID')} langkah` : '',
        post.jarak ? `📍 ${post.jarak} km` : '',
        post.kalori ? `🔥 ${post.kalori} kalori` : '',
    ].filter(Boolean).map(s => `<span class="text-xs bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full px-2.5 py-1">${s}</span>`).join('');

    return `
    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm" id="post-${post.id}">
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center font-bold text-indigo-600 dark:text-indigo-400">
                    ${post.nama_user?.[0]?.toUpperCase() ?? 'U'}
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">${post.nama_user}</p>
                    <p class="text-xs text-gray-400">${post.created_at}</p>
                </div>
            </div>
            ${post.is_mine ? `<button onclick="deletePost(${post.id})" class="text-xs text-red-500 hover:text-red-700 font-medium">Hapus</button>` : ''}
        </div>

        ${post.deskripsi ? `<p class="mt-3 text-sm text-gray-700 dark:text-gray-300">${post.deskripsi}</p>` : ''}
        ${gambarHtml}

        ${statsHtml ? `<div class="mt-3 flex flex-wrap gap-2">${statsHtml}</div>` : ''}

        <button onclick="openCommentModal(${post.id})"
                class="mt-4 inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 011.037-.443 48.282 48.282 0 005.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
            </svg>
            <span>${post.comments_count} komentar</span>
        </button>
    </div>`;
}

// =====================
// CREATE POST
// =====================
function openPostModal() { document.getElementById('post-modal').classList.remove('hidden'); }
function closePostModal() { document.getElementById('post-modal').classList.add('hidden'); }

document.getElementById('formPost').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = new FormData(this);
    form.append('_token', CSRF);

    fetch('{{ route('user.community.store') }}', { method: 'POST', body: form })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                closePostModal();
                this.reset();
                showToast('success', data.message);
                loadPosts();
            } else {
                const errors = Object.values(data.errors ?? {}).flat().join(', ');
                showToast('error', errors || data.message);
            }
        })
        .catch(() => showToast('error', 'Koneksi gagal.'));
});

function deletePost(id) {
    if (!confirm('Hapus postingan ini?')) return;

    fetch(`{{ url('user/community') }}/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            document.getElementById('post-' + id)?.remove();
        } else {
            showToast('error', data.message);
        }
    })
    .catch(() => showToast('error', 'Koneksi gagal.'));
}

// =====================
// COMMENTS
// =====================
function openCommentModal(postId) {
    document.getElementById('commentPostId').value = postId;
    document.getElementById('comment-modal').classList.remove('hidden');
    loadComments(postId);
}
function closeCommentModal() { document.getElementById('comment-modal').classList.add('hidden'); }

function loadComments(postId) {
    fetch(`{{ url('user/community') }}/${postId}/comments`)
        .then(r => r.json())
        .then(data => {
            const list = document.getElementById('commentList');
            if (!data.success || data.data.length === 0) {
                list.innerHTML = `<p class="text-sm text-gray-400 text-center py-6">Belum ada komentar. Jadilah yang pertama!</p>`;
                return;
            }
            list.innerHTML = data.data.map(c => `
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-full bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-xs font-bold text-violet-600 dark:text-violet-400 shrink-0">
                        ${c.nama_user?.[0]?.toUpperCase() ?? 'U'}
                    </div>
                    <div class="flex-1 rounded-xl bg-gray-50 dark:bg-gray-700/50 px-3 py-2">
                        <p class="text-xs font-semibold text-gray-900 dark:text-white">${c.nama_user} <span class="font-normal text-gray-400 ml-1">${c.created_at}</span></p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-0.5">${c.komentar}</p>
                    </div>
                </div>
            `).join('');
        });
}

document.getElementById('formComment').addEventListener('submit', function(e) {
    e.preventDefault();
    const input = document.getElementById('commentInput');
    const postId = document.getElementById('commentPostId').value;
    if (!input.value.trim()) return;

    const form = new FormData();
    form.append('community_id', postId);
    form.append('komentar', input.value);
    form.append('_token', CSRF);

    fetch('{{ route('user.community.comment') }}', { method: 'POST', body: form })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                input.value = '';
                loadComments(postId);
                loadPosts();
            } else {
                showToast('error', data.message);
            }
        })
        .catch(() => showToast('error', 'Koneksi gagal.'));
});

// =====================
// LEADERBOARD
// =====================
function loadLeaderboard() {
    fetch('{{ route('user.community.leaderboard') }}')
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            document.getElementById('leaderboardList').innerHTML = data.data.map((u, i) => `
                <div class="flex items-center gap-3">
                    <span class="w-6 text-sm font-bold ${i < 3 ? ['text-amber-500','text-gray-400','text-orange-600'][i] : 'text-gray-400'}">${i + 1}.</span>
                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">
                        ${u.name?.[0]?.toUpperCase() ?? 'U'}
                    </div>
                    <span class="flex-1 text-sm font-medium text-gray-900 dark:text-white truncate">${u.name}</span>
                    <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">${Number(u.poin).toLocaleString('id-ID')}</span>
                </div>
            `).join('');
        })
        .catch(() => {});
}

// =====================
// TOAST
// =====================
function showToast(type, msg) {
    const old = document.getElementById('toast');
    if (old) old.remove();
    const el = document.createElement('div');
    el.id = 'toast';
    el.className = `fixed bottom-5 right-5 z-[100] flex items-center gap-2 rounded-xl px-4 py-3 text-sm font-medium shadow-lg
        ${type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'}`;
    el.innerHTML = `<span>${msg}</span><button onclick="this.parentElement.remove()" class="ml-2 text-white/80 hover:text-white">✕</button>`;
    document.body.appendChild(el);
    setTimeout(() => { if (el.parentElement) { el.style.opacity = '0'; setTimeout(() => el.remove(), 300); } }, 4000);
}

// INIT
loadPosts();
loadLeaderboard();

['post-modal', 'comment-modal'].forEach(id => {
    document.getElementById(id)?.addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
});
</script>
</x-layouts.app-user>
