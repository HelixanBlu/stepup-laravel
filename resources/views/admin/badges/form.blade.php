@php
    $isEditing = isset($badge);
@endphp

<form method="POST"
      action="{{ $isEditing ? route('admin.badges.update', $badge) : route('admin.badges.store') }}"
      enctype="multipart/form-data"
      class="space-y-5">
    @csrf
    @if ($isEditing)
        @method('PUT')
    @endif

    <div>
        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Badge <span class="text-red-500">*</span></label>
        <input id="name" name="name" type="text" value="{{ old('name', $badge->name ?? '') }}" required class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('name') border-red-400 @enderror">
        @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
        <textarea id="description" name="description" rows="3" class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('description') border-red-400 @enderror">{{ old('description', $badge->description ?? '') }}</textarea>
        @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="badge_icon" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Icon Badge</label>
        @if ($isEditing && $badge->badge_icon && Storage::disk('public')->exists($badge->badge_icon))
            <div class="mb-3 flex items-center gap-3">
                <img src="{{ Storage::disk('public')->url($badge->badge_icon) }}" alt="{{ $badge->name }}" class="h-16 w-16 rounded-full border border-gray-200 object-cover dark:border-gray-600">
                <span class="text-xs text-gray-400">Icon saat ini. Upload baru untuk mengganti.</span>
            </div>
        @endif
        <input id="badge_icon" name="badge_icon" type="file" accept="image/jpeg,image/png,image/webp" class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-300 @error('badge_icon') border-red-400 @enderror">
        <p class="mt-1 text-xs text-gray-400">JPG, PNG, atau WebP — maks. 2 MB.</p>
        @error('badge_icon')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="category" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori <span class="text-red-500">*</span></label>
            <select id="category" name="category" required class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('category') border-red-400 @enderror">
                @foreach (['challenge' => 'Challenge', 'collection' => 'Collection', 'activity' => 'Activity', 'streak' => 'Streak', 'special_event' => 'Special Event'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('category', $badge->category ?? 'challenge') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('category')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="rarity" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Rarity <span class="text-red-500">*</span></label>
            <select id="rarity" name="rarity" required class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('rarity') border-red-400 @enderror">
                @foreach (['common' => 'Common', 'rare' => 'Rare', 'epic' => 'Epic', 'legendary' => 'Legendary'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('rarity', $badge->rarity ?? 'common') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('rarity')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label for="unlock_requirement" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Persyaratan Unlock</label>
        <input id="unlock_requirement" name="unlock_requirement" type="text" value="{{ old('unlock_requirement', $badge->unlock_requirement ?? '') }}" class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('unlock_requirement') border-red-400 @enderror">
        @error('unlock_requirement')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="related_challenge_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Related Challenge</label>
            <select id="related_challenge_id" name="related_challenge_id" class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('related_challenge_id') border-red-400 @enderror">
                <option value="">Tidak ada challenge</option>
                @foreach ($challenges as $challenge)
                    <option value="{{ $challenge->id }}" @selected((string) old('related_challenge_id', $badge->related_challenge_id ?? '') === (string) $challenge->id)>{{ $challenge->title }}</option>
                @endforeach
            </select>
            @error('related_challenge_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="related_collection_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Related Collection</label>
            <select id="related_collection_id" name="related_collection_id" class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('related_collection_id') border-red-400 @enderror">
                <option value="">Tidak ada collection</option>
                @foreach ($collections as $collection)
                    <option value="{{ $collection->id }}" @selected((string) old('related_collection_id', $badge->related_collection_id ?? '') === (string) $collection->id)>{{ $collection->name }}</option>
                @endforeach
            </select>
            @error('related_collection_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></label>
        <select id="status" name="status" required class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('status') border-red-400 @enderror">
            <option value="active" @selected(old('status', $badge->status ?? 'active') === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $badge->status ?? '') === 'inactive')>Inactive</option>
        </select>
        @error('status')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-indigo-700">{{ $isEditing ? 'Simpan Perubahan' : 'Simpan Badge' }}</button>
        <a href="{{ route('admin.badges.index') }}" class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Batal</a>
    </div>
</form>
