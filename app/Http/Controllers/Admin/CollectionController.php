<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CollectionController extends Controller
{
    public function index(Request $request): View
    {
        $collections = Collection::query()
            ->withCount('challenges')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->string('search')->trim().'%');
            })
            ->orderByDesc('id')
            ->get();

        return view('admin.collections.index', compact('collections'));
    }

    public function create(): View
    {
        $challenges = Challenge::orderBy('title')->get();

        return view('admin.collections.create', compact('challenges'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $challengeIds = $validated['challenge_ids'] ?? [];
        unset($validated['challenge_ids']);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('collections', 'public');
        }

        $collection = Collection::create($validated);
        $collection->challenges()->sync($challengeIds);

        return redirect()->route('admin.collections.index')
            ->with('success', 'Collection berhasil ditambahkan.');
    }

    public function show(Collection $collection): View
    {
        $collection->load('challenges');

        return view('admin.collections.show', compact('collection'));
    }

    public function edit(Collection $collection): View
    {
        $collection->load('challenges');
        $challenges = Challenge::orderBy('title')->get();

        return view('admin.collections.edit', compact('collection', 'challenges'));
    }

    public function update(Request $request, Collection $collection): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $challengeIds = $validated['challenge_ids'] ?? [];
        unset($validated['challenge_ids']);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('collections', 'public');

            if ($collection->cover_image) {
                Storage::disk('public')->delete($collection->cover_image);
            }
        }

        $collection->update($validated);
        $collection->challenges()->sync($challengeIds);

        return redirect()->route('admin.collections.index')
            ->with('success', 'Collection berhasil diperbarui.');
    }

    public function destroy(Collection $collection): RedirectResponse
    {
        $coverImage = $collection->cover_image;

        $collection->delete();

        if ($coverImage) {
            Storage::disk('public')->delete($coverImage);
        }

        return redirect()->route('admin.collections.index')
            ->with('success', 'Collection berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'challenge_ids' => ['nullable', 'array'],
            'challenge_ids.*' => ['integer', 'exists:challenges,id'],
        ]);
    }
}
