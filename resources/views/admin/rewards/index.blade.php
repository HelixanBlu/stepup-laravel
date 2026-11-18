<x-layouts.admin title="Rewards">

      {{-- Header aksi --}}
      <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
          <div>
              <h2 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Reward</h2>
              <p class="text-sm text-gray-500 mt-0.5">Kelola semua reward yang tersedia untuk ditukar pengguna.</p>
          </div>
          <a href="{{ route('admin.rewards.create') }}"
  class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700
  transition-colors shadow-sm">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
              </svg>
              Tambah Reward
          </a>
      </div>

      {{-- Tabel --}}
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
          <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                  <thead class="bg-gray-50 dark:bg-gray-700/50">
                      <tr>
                          <th class="px-5 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 w-10">#</th>
                          <th class="px-5 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Gambar</th>
                          <th class="px-5 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Nama Reward</th>
                          <th class="px-5 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Deskripsi</th>
                          <th class="px-5 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">Poin</th>
                          <th class="px-5 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">Stok</th>
                          <th class="px-5 py-3 text-center font-semibold text-gray-600 dark:text-gray-300">Aksi</th>
                      </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                      @forelse ($rewards as $index => $reward)
                          <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                              <td class="px-5 py-3 text-gray-500 dark:text-gray-400">
                                  {{ $index + 1 }}
                              </td>
                              <td class="px-5 py-3">
                                  @if ($reward->gambar && file_exists(public_path('uploads/' . $reward->gambar)))
                                      <img src="{{ asset('uploads/' . $reward->gambar) }}"
                                           alt="{{ $reward->name_reward }}"
                                           class="w-12 h-12 rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                                  @else
                                      <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">        
  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 
  2.25 0 013.182 0l2.909 2.909M3 21h18M3.75 3h16.5A.75.75 0 0121 3.75v16.5a.75.75 0 01-.75.75H3.75A.75.75 0 013 20.25V3.75A.75.75 0 013.75 3z"
  />
                                          </svg>
                                      </div>
                                  @endif
                              </td>
                              <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">
                                  {{ $reward->name_reward }}
                              </td>
                              <td class="px-5 py-3 text-gray-500 dark:text-gray-400 max-w-xs">
                                  <span class="line-clamp-2">{{ $reward->description }}</span>
                              </td>
                              <td class="px-5 py-3 text-right">
  <span class="inline-flex items-center rounded-full bg-indigo-50 dark:bg-indigo-900/30 px-2.5 py-0.5 text-xs font-semibold text-indigo-700   
  dark:text-indigo-300">
                                      {{ number_format($reward->poin) }} poin
                                  </span>
                              </td>
                              <td class="px-5 py-3 text-right">
                                  <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                      {{ $reward->stok > 0
                                          ? 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-300'
                                          : 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300' }}">
                                      {{ $reward->stok }}
                                  </span>
                              </td>
                              <td class="px-5 py-3">
                                  <div class="flex items-center justify-center gap-2">
                                      {{-- Edit --}}
                                      <a href="{{ route('admin.rewards.edit', $reward->id_reward) }}"
  class="inline-flex items-center gap-1 rounded-md bg-amber-50 dark:bg-amber-900/20 px-2.5 py-1.5 text-xs font-medium text-amber-700
  dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-colors">
                                          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">    
  <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0        
  01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                          </svg>
                                          Edit
                                      </a>

                                      {{-- Hapus --}}
                                      <form method="POST"
                                            action="{{ route('admin.rewards.destroy', $reward->id_reward) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus reward ini?')">
                                          @csrf
                                          @method('DELETE')
                                          <button type="submit"
  class="inline-flex items-center gap-1 rounded-md bg-red-50 dark:bg-red-900/20 px-2.5 py-1.5 text-xs font-medium text-red-700
  dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors">
                                              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107
  1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0
  00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0   
  00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                              </svg>
                                              Hapus
                                          </button>
                                      </form>
                                  </div>
                              </td>
                          </tr>
                      @empty
                          <tr>
                              <td colspan="7" class="px-5 py-12 text-center text-gray-400 dark:text-gray-500">
  <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0
  01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621
  0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                  </svg>
                                  Belum ada data reward.
                              </td>
                          </tr>
                      @endforelse
                  </tbody>
              </table>
          </div>
      </div>

  </x-layouts.admin>