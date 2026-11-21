<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\Challenge;
use App\Models\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BadgeController extends Controller
{
    public function index(Request $request): View
    {
        $badges = Badge::query()
            ->with(['challenge:id,title', 'collection:id,name'])
            ->withCount('users')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->string('search')->trim().'%');
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category', $request->string('category')->trim());
            })
            ->when($request->filled('rarity'), function ($query) use ($request) {
                $query->where('rarity', $request->string('rarity')->trim());
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->string('status')->trim());
            })
            ->orderByDesc('id')
            ->get();

        return view('admin.badges.index', compact('badges'));
    }

    public function create(): View
    {
        $challenges = Challenge::orderBy('title')->get(['id', 'title']);
        $collections = Collection::orderBy('name')->get(['id', 'name']);

        return view('admin.badges.create', compact('challenges', 'collections'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);

        if ($request->hasFile('badge_icon')) {
            $validated['badge_icon'] = $request->file('badge_icon')->store('badges', 'public');
        }

        Badge::create($validated);

        return redirect()->route('admin.badges.index')
            ->with('success', 'Badge berhasil ditambahkan.');
    }

    public function edit(Badge $badge): View
    {
        $challenges = Challenge::orderBy('title')->get(['id', 'title']);
        $collections = Collection::orderBy('name')->get(['id', 'name']);

        return view('admin.badges.edit', compact('badge', 'challenges', 'collections'));
    }

    public function update(Request $request, Badge $badge): RedirectResponse
    {
        $validated = $this->validatedData($request);

        if ($request->hasFile('badge_icon')) {
            $validated['badge_icon'] = $request->file('badge_icon')->store('badges', 'public');

            if ($badge->badge_icon) {
                Storage::disk('public')->delete($badge->badge_icon);
            }
        }

        $badge->update($validated);

        return redirect()->route('admin.badges.index')
            ->with('success', 'Badge berhasil diperbarui.');
    }

    public function destroy(Badge $badge): RedirectResponse
    {
        $badgeIcon = $badge->badge_icon;

        $badge->delete();

        if ($badgeIcon) {
            Storage::disk('public')->delete($badgeIcon);
        }

        return redirect()->route('admin.badges.index')
            ->with('success', 'Badge dan riwayat pengguna yang mendapatkannya berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'badge_icon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'category' => ['required', 'in:challenge,collection,activity,streak,special_event'],
            'rarity' => ['required', 'in:common,rare,epic,legendary'],
            'unlock_requirement' => ['nullable', 'string', 'max:255'],
            'related_challenge_id' => ['nullable', 'integer', 'exists:challenges,id'],
            'related_collection_id' => ['nullable', 'integer', 'exists:collections,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);
    }
}
