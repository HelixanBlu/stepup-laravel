<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BadgeController extends Controller
{
    /**
     * Display a listing of badges.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Badge::with(['challenge:id,title', 'collection:id,name'])->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->has('category')) {
            $query->where('category', $request->query('category'));
        }

        if ($request->has('rarity')) {
            $query->where('rarity', $request->query('rarity'));
        }

        $badges = $query->get()->map(function ($badge) {
            $data = $badge->toArray();
            $data['related_challenge_title'] = $badge->challenge?->title;
            $data['related_collection_name'] = $badge->collection?->name;
            return $data;
        });

        return response()->json([
            'success' => true,
            'message' => 'Data badges berhasil diambil',
            'total' => $badges->count(),
            'data' => $badges,
        ]);
    }

    /**
     * Store a newly created badge.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|in:challenge,collection,activity,streak,special_event',
            'rarity' => 'nullable|in:common,rare,epic,legendary',
            'unlock_requirement' => 'required|string|max:255',
            'related_challenge_id' => 'nullable|exists:challenges,id',
            'related_collection_id' => 'nullable|exists:collections,id',
            'status' => 'nullable|in:active,inactive',
            'badge_icon' => 'nullable',
        ]);

        $iconName = null;
        if ($request->hasFile('badge_icon')) {
            $file = $request->file('badge_icon');
            $iconName = time() . '-' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/badge_icons');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $iconName);
        } elseif ($request->filled('badge_icon') && is_string($request->input('badge_icon'))) {
            $iconName = $request->input('badge_icon');
        }

        $badge = Badge::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'badge_icon' => $iconName,
            'category' => $validated['category'] ?? 'challenge',
            'rarity' => $validated['rarity'] ?? 'common',
            'unlock_requirement' => $validated['unlock_requirement'],
            'related_challenge_id' => $validated['related_challenge_id'] ?? null,
            'related_collection_id' => $validated['related_collection_id'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Badge berhasil dibuat',
            'data' => $badge,
        ], 201);
    }

    /**
     * Display the specified badge.
     */
    public function show(string|int $id): JsonResponse
    {
        $badge = Badge::with(['challenge:id,title', 'collection:id,name'])->find($id);

        if (!$badge) {
            return response()->json([
                'success' => false,
                'message' => "Badge dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        $data = $badge->toArray();
        $data['related_challenge_title'] = $badge->challenge?->title;
        $data['related_collection_name'] = $badge->collection?->name;

        return response()->json([
            'success' => true,
            'message' => 'Detail badge berhasil diambil',
            'data' => $data,
        ]);
    }

    /**
     * Update the specified badge.
     */
    public function update(Request $request, string|int|null $id = null): JsonResponse
    {
        $id = $id ?? $request->input('id');
        $badge = Badge::find($id);

        if (!$badge) {
            return response()->json([
                'success' => false,
                'message' => "Badge dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|in:challenge,collection,activity,streak,special_event',
            'rarity' => 'nullable|in:common,rare,epic,legendary',
            'unlock_requirement' => 'sometimes|required|string|max:255',
            'related_challenge_id' => 'nullable|exists:challenges,id',
            'related_collection_id' => 'nullable|exists:collections,id',
            'status' => 'nullable|in:active,inactive',
            'badge_icon' => 'nullable',
        ]);

        if ($request->hasFile('badge_icon')) {
            $file = $request->file('badge_icon');
            $iconName = time() . '-' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/badge_icons');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            if ($badge->badge_icon && File::exists($destinationPath . '/' . $badge->badge_icon)) {
                File::delete($destinationPath . '/' . $badge->badge_icon);
            }

            $file->move($destinationPath, $iconName);
            $badge->badge_icon = $iconName;
        } elseif ($request->filled('badge_icon') && is_string($request->input('badge_icon'))) {
            $badge->badge_icon = $request->input('badge_icon');
        }

        if (isset($validated['name'])) $badge->name = $validated['name'];
        if ($request->has('description')) $badge->description = $request->input('description');
        if (isset($validated['category'])) $badge->category = $validated['category'];
        if (isset($validated['rarity'])) $badge->rarity = $validated['rarity'];
        if (isset($validated['unlock_requirement'])) $badge->unlock_requirement = $validated['unlock_requirement'];
        if ($request->has('related_challenge_id')) $badge->related_challenge_id = $validated['related_challenge_id'] ?? null;
        if ($request->has('related_collection_id')) $badge->related_collection_id = $validated['related_collection_id'] ?? null;
        if (isset($validated['status'])) $badge->status = $validated['status'];

        $badge->save();

        return response()->json([
            'success' => true,
            'message' => 'Badge berhasil diperbarui',
            'data' => $badge,
        ]);
    }

    /**
     * Remove the specified badge.
     */
    public function destroy(Request $request, string|int|null $id = null): JsonResponse
    {
        $id = $id ?? $request->input('id');
        $badge = Badge::find($id);

        if (!$badge) {
            return response()->json([
                'success' => false,
                'message' => "Badge dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        if ($badge->badge_icon) {
            $oldPath = public_path('uploads/badge_icons/' . $badge->badge_icon);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        $badge->delete();

        return response()->json([
            'success' => true,
            'message' => 'Badge berhasil dihapus',
        ]);
    }
}
