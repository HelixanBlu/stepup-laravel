<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Redemption;
use App\Models\Reward;
use App\Services\RedemptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RewardController extends Controller
{
    public function __construct(private RedemptionService $redemptions) {}

    public function index(): View
    {
        $rewards = Reward::orderByDesc('id_reward')->get();
        $user = auth()->user();
        $history = Redemption::where('user_id', $user->id)
            ->with('reward:id_reward,name_reward,gambar')
            ->orderByDesc('tanggal_redeem')
            ->get();

        return view('user.rewards.index', compact('rewards', 'user', 'history'));
    }

    public function redeem(Request $request): JsonResponse
    {
        $request->validate([
            'reward_id' => ['required', 'integer', 'exists:rewards,id_reward'],
        ]);

        $user = auth()->user();
        $reward = Reward::findOrFail($request->input('reward_id'));

        $result = $this->redemptions->redeem($user, $reward);

        if ($result['error'] === RedemptionService::ERROR_OUT_OF_STOCK) {
            return response()->json(['success' => false, 'message' => 'Stok reward habis.'], 422);
        }

        if ($result['error'] === RedemptionService::ERROR_INSUFFICIENT_POINTS) {
            return response()->json(['success' => false, 'message' => 'Poin Anda tidak mencukupi.'], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Reward berhasil di-redeem!',
            'data' => [
                'sisa_poin' => $user->fresh()->poin,
                'sisa_stok' => $reward->fresh()->stok,
            ],
        ]);
    }

    public function history(): JsonResponse
    {
        $history = Redemption::where('user_id', auth()->id())
            ->orderByDesc('tanggal_redeem')
            ->get()
            ->map(fn ($r) => [
                'id' => $r->id,
                'nama_reward' => $r->nama_reward,
                'poin_digunakan' => $r->poin_digunakan,
                'tanggal_redeem' => $r->tanggal_redeem?->format('d M Y, H:i'),
            ]);

        return response()->json(['success' => true, 'data' => $history]);
    }
}
