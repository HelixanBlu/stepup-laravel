<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CollectionController extends Controller
{
    /**
     * Display a listing of collections.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Collection::withCount('challenges')->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        $collections = $query->get()->map(function ($col) {
            $data = $col->toArray();
            $data['total_challenges'] = $col->challenges_count;
            return $data;
        });

        return response()->json([
            'success' => true,
            'message' => 'Data collections berhasil diambil',
            'total' => $collections->count(),
            'data' => $collections,
        ]);
    }

    /**
     * Store a newly created collection.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'estimated_duration' => 'required|string|max:100',
            'status' => 'nullable|in:active,inactive',
            'cover_image' => 'nullable',
        ]);

        $coverName = null;
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $coverName = time() . '-' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $coverName);
        } elseif ($request->filled('cover_image') && is_string($request->input('cover_image'))) {
            $coverName = $request->input('cover_image');
        }

        $collection = Collection::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'cover_image' => $coverName,
            'difficulty' => $validated['difficulty'] ?? 'easy',
            'estimated_duration' => $validated['estimated_duration'],
            'status' => $validated['status'] ?? 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Koleksi berhasil dibuat',
            'data' => $collection,
        ], 201);
    }

    /**
     * Display the specified collection.
     */
    public function show(string|int $id): JsonResponse
    {
        $collection = Collection::with('challenges')->find($id);

        if (!$collection) {
            return response()->json([
                'success' => false,
                'message' => "Koleksi dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        $data = $collection->toArray();
        $data['total_challenges'] = $collection->challenges->count();

        return response()->json([
            'success' => true,
            'message' => 'Detail koleksi berhasil diambil',
            'data' => $data,
        ]);
    }

    /**
     * Update the specified collection.
     */
    public function update(Request $request, string|int|null $id = null): JsonResponse
    {
        $id = $id ?? $request->input('id');
        $collection = Collection::find($id);

        if (!$collection) {
            return response()->json([
                'success' => false,
                'message' => "Koleksi dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'estimated_duration' => 'sometimes|required|string|max:100',
            'status' => 'nullable|in:active,inactive',
            'cover_image' => 'nullable',
        ]);

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $coverName = time() . '-' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            if ($collection->cover_image && File::exists($destinationPath . '/' . $collection->cover_image)) {
                File::delete($destinationPath . '/' . $collection->cover_image);
            }

            $file->move($destinationPath, $coverName);
            $collection->cover_image = $coverName;
        } elseif ($request->filled('cover_image') && is_string($request->input('cover_image'))) {
            $collection->cover_image = $request->input('cover_image');
        }

        if (isset($validated['name'])) $collection->name = $validated['name'];
        if ($request->has('description')) $collection->description = $request->input('description');
        if (isset($validated['difficulty'])) $collection->difficulty = $validated['difficulty'];
        if (isset($validated['estimated_duration'])) $collection->estimated_duration = $validated['estimated_duration'];
        if (isset($validated['status'])) $collection->status = $validated['status'];

        $collection->save();

        return response()->json([
            'success' => true,
            'message' => 'Koleksi berhasil diperbarui',
            'data' => $collection,
        ]);
    }

    /**
     * Remove the specified collection.
     */
    public function destroy(Request $request, string|int|null $id = null): JsonResponse
    {
        $id = $id ?? $request->input('id');
        $collection = Collection::find($id);

        if (!$collection) {
            return response()->json([
                'success' => false,
                'message' => "Koleksi dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        if ($collection->cover_image) {
            $oldPath = public_path('uploads/' . $collection->cover_image);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        $collection->delete();

        return response()->json([
            'success' => true,
            'message' => 'Koleksi berhasil dihapus',
        ]);
    }
}
