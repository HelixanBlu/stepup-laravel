@php
    $isEditing = isset($collection);
    $selectedChallengeIds = old('challenge_ids', $isEditing ? $collection->challenges->pluck('id')->all() : []);
@endphp

<form method="POST"
      action="{{ $isEditing ? route('admin.collections.update', $collection) : route('admin.collections.store') }}"
      enctype="multipart/form-data"
      class="space-y-5">
    @csrf
    @if ($isEditing)
        @method('PUT')
    @endif

    <div>
        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Collection <span class="text-red-500">*</span></label>
        <input id="name" name="name" type="text" value="{{ old('name', $collection->name ?? '') }}" required class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('name') border-red-400 @enderror">
        @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
        <textarea id="description" name="description" rows="3" class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('description') border-red-400 @enderror">{{ old('description', $collection->description ?? '') }}</textarea>
        @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="cover_image" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Cover Image</label>
        @if ($isEditing && $collection->cover_image && Storage::disk('public')->exists($collection->cover_image))
            <div class="mb-3 flex items-center gap-3">
                <img src="{{ Storage::disk('public')->url($collection->cover_image) }}" alt="{{ $collection->name }}" class="h-12 w-20 rounded-lg border border-gray-200 object-cover dark:border-gray-600">
                <span class="text-xs text-gray-400">Cover saat ini. Upload baru untuk mengganti.</span>
            </div>
        @endif
        <input id="cover_image" name="cover_image" type="file" accept="image/jpeg,image/png,image/webp" class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-300 @error('cover_image') border-red-400 @enderror">
        <p class="mt-1 text-xs text-gray-400">JPG, PNG, atau WebP — maks. 2 MB.</p>
        @error('cover_image')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="challenge_ids" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Challenges</label>
        <select id="challenge_ids" name="challenge_ids[]" multiple size="7" class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('challenge_ids') border-red-400 @enderror">
            @foreach ($challenges as $challenge)
                <option value="{{ $challenge->id }}" @selected(in_array($challenge->id, $selectedChallengeIds))>{{ $challenge->title }} — {{ ucfirst($challenge->status) }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-400">Pilih lebih dari satu dengan Ctrl atau Cmd.</p>
        @error('challenge_ids')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        @error('challenge_ids.*')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-indigo-700">{{ $isEditing ? 'Simpan Perubahan' : 'Simpan Collection' }}</button>
        <a href="{{ route('admin.collections.index') }}" class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Batal</a>
    </div>
</form>
