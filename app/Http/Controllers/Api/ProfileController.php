<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(int $id): JsonResponse
    {
        $user = User::withCount([
            'challengeParticipants as total_challenges_joined',
            'completedChallenges as total_challenges_completed',
            'badges as total_badges',
            'redemptions as total_redemptions',
        ])->find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => "User dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diambil',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'poin' => $user->poin,
                'foto_profile' => $user->foto_profile,
                'total_challenges_joined' => $user->total_challenges_joined,
                'total_challenges_completed' => $user->total_challenges_completed,
                'total_badges' => $user->total_badges,
                'total_redemptions' => $user->total_redemptions,
            ],
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
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
            'foto_profile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('foto_profile')) {
            $file = $request->file('foto_profile');
            $fileName = time().'-'.$file->getClientOriginalName();
            $uploadPath = public_path('uploads/profiles');

            if (! File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            if ($user->foto_profile && File::exists($uploadPath.'/'.$user->foto_profile)) {
                File::delete($uploadPath.'/'.$user->foto_profile);
            }

            $file->move($uploadPath, $fileName);
            $validated['foto_profile'] = $fileName;
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user->fresh(),
        ]);
    }

    public function updatePassword(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => "User dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($request->input('current_password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama tidak sesuai',
            ], 422);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah',
        ]);
    }
}
