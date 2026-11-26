<?php

namespace App\Services;

use App\Models\Redemption;
use App\Models\Reward;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RedemptionService
{
    public const ERROR_OUT_OF_STOCK = 'out_of_stock';

    public const ERROR_INSUFFICIENT_POINTS = 'insufficient_points';

    /**
     * Redeem a reward for the user.
     *
     * @return array{redemption: Redemption|null, error: string|null}
     */
    public function redeem(User $user, Reward $reward): array
    {
        if ($reward->stok <= 0) {
            return ['redemption' => null, 'error' => self::ERROR_OUT_OF_STOCK];
        }

        if ($user->poin < $reward->poin) {
            return ['redemption' => null, 'error' => self::ERROR_INSUFFICIENT_POINTS];
        }

        $redemption = DB::transaction(function () use ($user, $reward): Redemption {
            $user->decrement('poin', $reward->poin);
            $reward->decrement('stok');

            return Redemption::create([
                'user_id' => $user->id,
                'reward_id' => $reward->id_reward,
                'poin_digunakan' => $reward->poin,
                'nama_reward' => $reward->name_reward,
            ]);
        });

        return ['redemption' => $redemption, 'error' => null];
    }
}
