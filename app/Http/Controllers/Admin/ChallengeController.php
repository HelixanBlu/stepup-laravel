<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ChallengeController extends Controller
{
    public function index(Request $request): View
    {
        $challenges = Challenge::query()
            ->withCount('participants')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->string('search')->trim().'%');
            })
            ->orderByDesc('id')
            ->get();

        return view('admin.challenges.index', compact('challenges'));
    }

    public function create(): View
    {
        return view('admin.challenges.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('challenges', 'public');
        }

        Challenge::create($validated);

        return redirect()->route('admin.challenges.index')
            ->with('success', 'Challenge berhasil ditambahkan.');
    }

    public function edit(Challenge $challenge): View
    {
        return view('admin.challenges.edit', compact('challenge'));
    }

    public function update(Request $request, Challenge $challenge): RedirectResponse
    {
        $validated = $this->validatedData($request);

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('challenges', 'public');

            if ($challenge->banner_image) {
                Storage::disk('public')->delete($challenge->banner_image);
            }
        }

        $challenge->update($validated);

        return redirect()->route('admin.challenges.index')
            ->with('success', 'Challenge berhasil diperbarui.');
    }

    public function destroy(Challenge $challenge): RedirectResponse
    {
        $bannerImage = $challenge->banner_image;

        $challenge->delete();

        if ($bannerImage) {
            Storage::disk('public')->delete($bannerImage);
        }

        return redirect()->route('admin.challenges.index')
            ->with('success', 'Challenge dan data terkait berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'challenge_type' => ['required', 'string', 'max:50'],
            'goal_type' => ['required', 'string', 'max:50'],
            'goal_value' => ['required', 'integer', 'min:0'],
            'reward_points' => ['required', 'integer', 'min:0'],
            'badge_reward' => ['nullable', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'string', 'max:50'],
        ]);
    }
}
