<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $totalJoined = $user->challengeParticipants()->count();
        $totalCompleted = $user->completedChallenges()->count();
        $totalRedemptions = $user->redemptions()->count();

        $activeChallenges = Challenge::where('status', 'active')
            ->withCount('participants')
            ->with(['participants' => fn ($q) => $q->where('user_id', $user->id)])
            ->orderByDesc('created_at')
            ->limit(3)
            ->get()
            ->map(function ($challenge) {
                $participation = $challenge->participants->first();
                $challenge->is_joined = $participation !== null;
                $challenge->current_progress = $participation?->current_progress ?? 0;
                $challenge->completion_status = $participation?->completion_status ?? null;
                $challenge->progress_percent = $challenge->goal_value > 0
                    ? min(100, round(($challenge->current_progress / $challenge->goal_value) * 100))
                    : 0;

                return $challenge;
            });

        $recentRedemptions = $user->redemptions()
            ->orderByDesc('tanggal_redeem')
            ->limit(3)
            ->get();

        return view('user.dashboard', compact(
            'user',
            'totalJoined',
            'totalCompleted',
            'totalRedemptions',
            'activeChallenges',
            'recentRedemptions'
        ));
    }
}
