<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Redemption;
use App\Models\Reward;
use App\Models\User;
use App\Services\RedemptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Dedicated endpoints for the JavaFX desktop client.
 * Mirrors the original /api/javafx/* native PHP endpoints.
 */
class JavafxController extends Controller
{
    public function __construct(private RedemptionService $redemptions) {}

    // ----------------------------------------------------------------
    // AUTH
    // ----------------------------------------------------------------

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'poin' => $user->poin,
                'foto_profile' => $user->foto_profile,
            ],
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'status' => 'active',
            'poin' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ], 201);
    }

    // ----------------------------------------------------------------
    // USERS
    // ----------------------------------------------------------------

    public function usersGet(): JsonResponse
    {
        $users = User::orderByDesc('id')->get(['id', 'name', 'email', 'role', 'status', 'poin', 'foto_profile', 'created_at']);

        return response()->json([
            'success' => true,
            'message' => 'Data users berhasil diambil',
            'data' => $users,
        ]);
    }

    public function usersUpdate(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $user = User::find($id);

        if (! $user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 404);
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

    public function usersDelete(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $user = User::find($id);

        if (! $user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 404);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User berhasil dihapus']);
    }

    // ----------------------------------------------------------------
    // COMMUNITY FEED
    // ----------------------------------------------------------------

    public function communityGet(): JsonResponse
    {
        $posts = Community::with('user:id,name,foto_profile')
            ->withCount('comments')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data community berhasil diambil',
            'data' => $posts,
        ]);
    }

    public function communityPost(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'deskripsi' => ['nullable', 'string'],
            'langkah' => ['nullable', 'integer', 'min:0'],
            'jarak' => ['nullable', 'numeric', 'min:0'],
            'kalori' => ['nullable', 'integer', 'min:0'],
        ]);

        $post = Community::create($validated);

        return response()->json(['success' => true, 'message' => 'Post berhasil dibuat', 'data' => $post], 201);
    }

    public function communityUpdate(Request $request): JsonResponse
    {
        $post = Community::find($request->input('id'));

        if (! $post) {
            return response()->json(['success' => false, 'message' => 'Post tidak ditemukan'], 404);
        }

        $post->update($request->only(['deskripsi', 'langkah', 'jarak', 'kalori']));

        return response()->json(['success' => true, 'message' => 'Post berhasil diperbarui', 'data' => $post->fresh()]);
    }

    public function communityDelete(Request $request): JsonResponse
    {
        $post = Community::find($request->input('id'));

        if (! $post) {
            return response()->json(['success' => false, 'message' => 'Post tidak ditemukan'], 404);
        }

        $post->delete();

        return response()->json(['success' => true, 'message' => 'Post berhasil dihapus']);
    }

    // ----------------------------------------------------------------
    // REWARDS
    // ----------------------------------------------------------------

    public function rewardsGet(): JsonResponse
    {
        $rewards = Reward::orderByDesc('id_reward')->get();

        return response()->json(['success' => true, 'message' => 'Data rewards berhasil diambil', 'data' => $rewards]);
    }

    public function rewardsPost(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name_reward' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'poin' => ['required', 'integer', 'min:0'],
            'stok' => ['nullable', 'integer', 'min:0'],
        ]);

        $reward = Reward::create($validated);

        return response()->json(['success' => true, 'message' => 'Reward berhasil ditambahkan', 'data' => $reward], 201);
    }

    public function rewardsUpdate(Request $request): JsonResponse
    {
        $reward = Reward::find($request->input('id_reward'));

        if (! $reward) {
            return response()->json(['success' => false, 'message' => 'Reward tidak ditemukan'], 404);
        }

        $reward->update($request->only(['name_reward', 'description', 'poin', 'stok']));

        return response()->json(['success' => true, 'message' => 'Reward berhasil diperbarui', 'data' => $reward->fresh()]);
    }

    public function rewardsDelete(Request $request): JsonResponse
    {
        $reward = Reward::find($request->input('id_reward'));

        if (! $reward) {
            return response()->json(['success' => false, 'message' => 'Reward tidak ditemukan'], 404);
        }

        $reward->delete();

        return response()->json(['success' => true, 'message' => 'Reward berhasil dihapus']);
    }

    public function rewardsRedeem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'reward_id' => ['required', 'integer', 'exists:rewards,id_reward'],
        ]);

        $user = User::find($validated['user_id']);
        $reward = Reward::find($validated['reward_id']);

        $result = $this->redemptions->redeem($user, $reward);

        if ($result['error'] === RedemptionService::ERROR_OUT_OF_STOCK) {
            return response()->json(['success' => false, 'message' => 'Stok reward habis'], 422);
        }

        if ($result['error'] === RedemptionService::ERROR_INSUFFICIENT_POINTS) {
            return response()->json(['success' => false, 'message' => 'Poin tidak mencukupi'], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Reward berhasil di-redeem',
            'data' => ['redemption' => $result['redemption'], 'sisa_poin' => $user->fresh()->poin],
        ], 201);
    }

    public function rewardsHistory(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $redemptions = Redemption::where('user_id', $userId)
            ->with('reward:id_reward,name_reward,gambar')
            ->orderByDesc('tanggal_redeem')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat redeem berhasil diambil',
            'data' => $redemptions,
        ]);
    }
}
