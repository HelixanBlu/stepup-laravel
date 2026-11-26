<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Redemption;
use App\Models\Reward;
use App\Models\User;
use App\Services\RedemptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RedemptionController extends Controller
{
    public function __construct(private RedemptionService $redemptions) {}

    /**
     * Redeem a reward for a user.
     */
    public function redeem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'reward_id' => ['required', 'integer', 'exists:rewards,id_reward'],
        ]);

        $user = User::find($validated['user_id']);
        $reward = Reward::find($validated['reward_id']);

        $result = $this->redemptions->redeem($user, $reward);

        if ($result['error'] === RedemptionService::ERROR_OUT_OF_STOCK) {
            return response()->json([
                'success' => false,
                'message' => 'Stok reward habis',
            ], 422);
        }

        if ($result['error'] === RedemptionService::ERROR_INSUFFICIENT_POINTS) {
            return response()->json([
                'success' => false,
                'message' => 'Poin tidak mencukupi',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Reward berhasil di-redeem',
            'data' => [
                'redemption' => $result['redemption'],
                'sisa_poin' => $user->fresh()->poin,
            ],
        ], 201);
    }

    /**
     * History of redemptions for a user.
     */
    public function history(int $userId): JsonResponse
    {
        $user = User::find($userId);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => "User dengan ID {$userId} tidak ditemukan",
            ], 404);
        }

        $redemptions = Redemption::where('user_id', $userId)
            ->with('reward:id_reward,name_reward,gambar')
            ->orderByDesc('tanggal_redeem')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat redeem berhasil diambil',
            'total' => $redemptions->count(),
            'data' => $redemptions,
        ]);
    }
}
