<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class RewardController extends Controller
{
    /**
     * Display a listing of rewards.
     */
    public function index(): JsonResponse
    {
        $rewards = Reward::orderBy('id_reward', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data rewards berhasil diambil',
            'total' => $rewards->count(),
            'data' => $rewards,
        ]);
    }

    /**
     * Store a newly created reward.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name_reward' => 'required|string|max:255',
            'description' => 'required|string',
            'poin' => 'required|integer|min:0',
            'stok' => 'nullable|integer|min:0',
            'gambar' => 'nullable',
        ]);

        $stok = $request->input('stok', 0);
        $gambarName = null;

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $gambarName = time() . '-' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $gambarName);
        } elseif ($request->filled('gambar') && is_string($request->input('gambar'))) {
            $gambarName = $request->input('gambar');
        }

        $reward = Reward::create([
            'name_reward' => $validated['name_reward'],
            'description' => $validated['description'],
            'poin' => $validated['poin'],
            'stok' => $stok,
            'gambar' => $gambarName,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reward berhasil ditambahkan',
            'data' => $reward,
        ], 201);
    }

    /**
     * Display the specified reward.
     */
    public function show(string|int $id): JsonResponse
    {
        $reward = Reward::find($id);

        if (!$reward) {
            return response()->json([
                'success' => false,
                'message' => "Reward dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail reward berhasil diambil',
            'data' => $reward,
        ]);
    }

    /**
     * Update the specified reward.
     */
    public function update(Request $request, string|int|null $id = null): JsonResponse
    {
        $id = $id ?? $request->input('id_reward') ?? $request->input('id');
        $reward = Reward::find($id);

        if (!$reward) {
            return response()->json([
                'success' => false,
                'message' => "Reward dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        $validated = $request->validate([
            'name_reward' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'poin' => 'sometimes|required|integer|min:0',
            'stok' => 'nullable|integer|min:0',
            'gambar' => 'nullable',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $gambarName = time() . '-' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            // Remove old image if exists
            if ($reward->gambar && File::exists($destinationPath . '/' . $reward->gambar)) {
                File::delete($destinationPath . '/' . $reward->gambar);
            }

            $file->move($destinationPath, $gambarName);
            $reward->gambar = $gambarName;
        } elseif ($request->filled('gambar') && is_string($request->input('gambar'))) {
            $reward->gambar = $request->input('gambar');
        }

        if (isset($validated['name_reward'])) $reward->name_reward = $validated['name_reward'];
        if (isset($validated['description'])) $reward->description = $validated['description'];
        if (isset($validated['poin'])) $reward->poin = $validated['poin'];
        if ($request->has('stok')) $reward->stok = $request->input('stok', 0);

        $reward->save();

        return response()->json([
            'success' => true,
            'message' => 'Reward berhasil diperbarui',
            'data' => $reward,
        ]);
    }

    /**
     * Remove the specified reward.
     */
    public function destroy(Request $request, string|int|null $id = null): JsonResponse
    {
        $id = $id ?? $request->input('id_reward') ?? $request->input('id');
        $reward = Reward::find($id);

        if (!$reward) {
            return response()->json([
                'success' => false,
                'message' => "Reward dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        if ($reward->gambar) {
            $oldPath = public_path('uploads/' . $reward->gambar);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        $reward->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reward berhasil dihapus',
        ]);
    }
}
