<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::orderByDesc('id')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'poin' => $user->poin,
                'foto_profile' => $user->foto_profile,
                'created_at' => $user->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data users berhasil diambil',
            'total' => $users->count(),
            'data' => $users,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => "User dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail user berhasil diambil',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'poin' => $user->poin,
                'foto_profile' => $user->foto_profile,
                'created_at' => $user->created_at,
            ],
        ]);
    }

    public function update(Request $request, int|string|null $id = null): JsonResponse
    {
        $id = $id ?? $request->input('id');
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => "User dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'unique:users,email,'.$id],
            'role' => ['sometimes', 'in:user,admin'],
            'status' => ['sometimes', 'in:active,inactive'],
            'poin' => ['sometimes', 'integer', 'min:0'],
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->input('password'));
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui',
            'data' => $user->fresh(),
        ]);
    }

    public function destroy(Request $request, int|string|null $id = null): JsonResponse
    {
        $id = $id ?? $request->input('id');
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => "User dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus',
        ]);
    }
}
