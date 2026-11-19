@php
    $isEditing = isset($challenge);
@endphp

<form method="POST"
      action="{{ $isEditing ? route('admin.challenges.update', $challenge) : route('admin.challenges.store') }}"
      enctype="multipart/form-data"
      class="space-y-5">
    @csrf
    @if ($isEditing)
        @method('PUT')
    @endif

    <div>
        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Judul Challenge <span class="text-red-500">*</span></label>
        <input id="title" name="title" type="text" value="{{ old('title', $challenge->title ?? '') }}" required
               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('title') border-red-400 @enderror">
        @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Deskripsi</label>
        <textarea id="description" name="description" rows="3"
                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('description') border-red-400 @enderror">{{ old('description', $challenge->description ?? '') }}</textarea>
        @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="banner_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Banner</label>
        @if ($isEditing && $challenge->banner_image)
            <div class="mb-3 flex items-center gap-3">
                <img src="{{ Storage::disk('public')->url($challenge->banner_image) }}" alt="{{ $challenge->title }}" class="w-20 h-12 rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                <span class="text-xs text-gray-400">Banner saat ini. Upload baru untuk mengganti.</span>
            </div>
        @endif
        <input id="banner_image" name="banner_image" type="file" accept="image/jpeg,image/png,image/webp"
               class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-300 @error('banner_image') border-red-400 @enderror">
        <p class="mt-1 text-xs text-gray-400">JPG, PNG, atau WebP — maks. 2 MB.</p>
        @error('banner_image')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="challenge_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tipe Challenge <span class="text-red-500">*</span></label>
            <select id="challenge_type" name="challenge_type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('challenge_type') border-red-400 @enderror">
                @foreach (['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly', 'team' => 'Team', 'event' => 'Event'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('challenge_type', $challenge->challenge_type ?? 'daily') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('challenge_type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Status <span class="text-red-500">*</span></label>
            <select id="status" name="status" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('status') border-red-400 @enderror">
                @foreach (['draft' => 'Draft', 'active' => 'Active', 'completed' => 'Completed', 'archived' => 'Archived'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $challenge->status ?? 'draft') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('status')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="goal_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tipe Target <span class="text-red-500">*</span></label>
            <select id="goal_type" name="goal_type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('goal_type') border-red-400 @enderror">
                @foreach (['steps' => 'Steps', 'distance' => 'Distance', 'active_minutes' => 'Active Minutes'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('goal_type', $challenge->goal_type ?? 'steps') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('goal_type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="goal_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nilai Target <span class="text-red-500">*</span></label>
            <input id="goal_value" name="goal_value" type="number" min="0" value="{{ old('goal_value', $challenge->goal_value ?? 0) }}" required
                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('goal_value') border-red-400 @enderror">
            @error('goal_value')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="reward_points" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Reward Poin <span class="text-red-500">*</span></label>
            <input id="reward_points" name="reward_points" type="number" min="0" value="{{ old('reward_points', $challenge->reward_points ?? 0) }}" required
                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('reward_points') border-red-400 @enderror">
            @error('reward_points')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="badge_reward" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Badge Reward</label>
            <input id="badge_reward" name="badge_reward" type="text" value="{{ old('badge_reward', $challenge->badge_reward ?? '') }}"
                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('badge_reward') border-red-400 @enderror">
            @error('badge_reward')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
            <input id="start_date" name="start_date" type="date" value="{{ old('start_date', isset($challenge) ? $challenge->start_date->format('Y-m-d') : '') }}" required
                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('start_date') border-red-400 @enderror">
            @error('start_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Selesai <span class="text-red-500">*</span></label>
            <input id="end_date" name="end_date" type="date" value="{{ old('end_date', isset($challenge) ? $challenge->end_date->format('Y-m-d') : '') }}" required
                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('end_date') border-red-400 @enderror">
            @error('end_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors shadow-sm">
            {{ $isEditing ? 'Simpan Perubahan' : 'Simpan Challenge' }}
        </button>
        <a href="{{ route('admin.challenges.index') }}" class="rounded-lg border border-gray-300 dark:border-gray-600 px-5 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Batal</a>
    </div>
</form>
