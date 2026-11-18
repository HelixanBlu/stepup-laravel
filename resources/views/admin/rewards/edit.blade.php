<x-layouts.admin title="Edit Reward">

      <div class="mb-6">
          <a href="{{ route('admin.rewards.index') }}"
  class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200
  transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
              </svg>
              Kembali ke Daftar Reward
          </a>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Edit Reward</h2>

          <form method="POST"
                action="{{ route('admin.rewards.update', $reward->id_reward) }}"
                enctype="multipart/form-data"
                class="space-y-5">
              @csrf
              @method('PUT')

              {{-- Nama Reward --}}
              <div>
                  <label for="name_reward" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                      Nama Reward <span class="text-red-500">*</span>
                  </label>
                  <input type="text"
                         id="name_reward"
                         name="name_reward"
                         value="{{ old('name_reward', $reward->name_reward) }}"
                         class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm
                                focus:border-indigo-500 focus:ring-indigo-500 text-sm
                                @error('name_reward') border-red-400 @enderror">
                  @error('name_reward')
                      <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                  @enderror
              </div>

              {{-- Deskripsi --}}
              <div>
                  <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                      Deskripsi <span class="text-red-500">*</span>
                  </label>
                  <textarea id="description"
                            name="description"
                            rows="3"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm
                                   focus:border-indigo-500 focus:ring-indigo-500 text-sm
                                   @error('description') border-red-400 @enderror">{{ old('description', $reward->description) }}</textarea>  
                  @error('description')
                      <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                  @enderror
              </div>

              {{-- Poin & Stok --}}
              <div class="grid grid-cols-2 gap-4">
                  <div>
                      <label for="poin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                          Poin <span class="text-red-500">*</span>
                      </label>
                      <input type="number"
                             id="poin"
                             name="poin"
                             value="{{ old('poin', $reward->poin) }}"
                             min="0"
                             class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm
                                    focus:border-indigo-500 focus:ring-indigo-500 text-sm
                                    @error('poin') border-red-400 @enderror">
                      @error('poin')
                          <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                      @enderror
                  </div>

                  <div>
                      <label for="stok" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                          Stok
                      </label>
                      <input type="number"
                             id="stok"
                             name="stok"
                             value="{{ old('stok', $reward->stok) }}"
                             min="0"
                             class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm
                                    focus:border-indigo-500 focus:ring-indigo-500 text-sm
                                    @error('stok') border-red-400 @enderror">
                      @error('stok')
                          <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                      @enderror
                  </div>
              </div>

              {{-- Gambar --}}
              <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                      Gambar
                  </label>

                  {{-- Preview gambar lama --}}
                  @if ($reward->gambar && file_exists(public_path('uploads/' . $reward->gambar)))
                      <div class="mb-3 flex items-center gap-3">
                          <img src="{{ asset('uploads/' . $reward->gambar) }}"
                               alt="{{ $reward->name_reward }}"
                               class="w-16 h-16 rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                          <span class="text-xs text-gray-400">Gambar saat ini. Upload baru untuk mengganti.</span>
                      </div>
                  @endif

                  <input type="file"
                         id="gambar"
                         name="gambar"
                         accept="image/jpeg,image/png,image/webp"
                         class="w-full text-sm text-gray-500 dark:text-gray-400
                                file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-300
                                @error('gambar') border-red-400 @enderror">
                  <p class="mt-1 text-xs text-gray-400">JPG, PNG, atau WebP — maks. 2 MB. Kosongkan jika tidak ingin mengganti.</p>
                  @error('gambar')
                      <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                  @enderror
              </div>

              {{-- Tombol --}}
              <div class="flex items-center gap-3 pt-2">
                  <button type="submit"
                          class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors      
  shadow-sm">
                      Simpan Perubahan
                  </button>
                  <a href="{{ route('admin.rewards.index') }}"
                     class="rounded-lg border border-gray-300 dark:border-gray-600 px-5 py-2 text-sm font-medium text-gray-700
  dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                      Batal
                  </a>
              </div>
          </form>
      </div>

  </x-layouts.admin>