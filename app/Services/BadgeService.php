<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\ChallengeParticipant;
use App\Models\User;
use App\Models\UserBadge;
use App\Models\UserCollection;

class BadgeService
{
    /**
     * Check all active badges and award any that the user now qualifies for.
     * Returns the list of newly awarded badges.
     *
     * @return Badge[]
     */
    public function checkAndAward(User $user): array
    {
        $userId = $user->id;

        $earnedBadgeIds = UserBadge::where('user_id', $userId)->pluck('badge_id')->toArray();

        $totalCompletedChallenges = $user->completedChallenges()->count();

        $totalCompletedCollections = $user->userCollections()
            ->where('status', 'completed')
            ->count();

        $totalSteps = $user->challengeParticipants()
            ->join('challenges', 'challenge_participants.challenge_id', '=', 'challenges.id')
            ->where('challenge_participants.completion_status', 'completed')
            ->where('challenges.goal_type', 'steps')
            ->sum('challenges.goal_value');

        $streakDays = $user->challengeParticipants()
            ->selectRaw('COUNT(DISTINCT CAST(joined_at AS DATE)) as streak_days')
            ->value('streak_days') ?? 0;

        $activeBadges = Badge::where('status', 'active')->get();
        $newlyEarned = [];

        foreach ($activeBadges as $badge) {
            if (in_array($badge->id, $earnedBadgeIds)) {
                continue;
            }

            $shouldAward = $this->shouldAward(
                $badge,
                $userId,
                $totalCompletedChallenges,
                $totalCompletedCollections,
                $totalSteps,
                $streakDays
            );

            if ($shouldAward) {
                $inserted = UserBadge::insertOrIgnore([
                    'user_id' => $userId,
                    'badge_id' => $badge->id,
                    'earned_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($inserted) {
                    $newlyEarned[] = $badge;
                }
            }
        }

        return $newlyEarned;
    }

    private function shouldAward(
        Badge $badge,
        int $userId,
        int $totalCompletedChallenges,
        int $totalCompletedCollections,
        int $totalSteps,
        int $streakDays
    ): bool {
        if (! empty($badge->related_challenge_id)) {
            return ChallengeParticipant::where('user_id', $userId)
                ->where('challenge_id', $badge->related_challenge_id)
                ->where('completion_status', 'completed')
                ->exists();
        }

        if (! empty($badge->related_collection_id)) {
            return UserCollection::where('user_id', $userId)
                ->where('collection_id', $badge->related_collection_id)
                ->where('status', 'completed')
                ->exists();
        }

        $req = strtolower(trim($badge->unlock_requirement ?? ''));

        if (str_contains($req, 'complete 1 challenge')) {
            return $totalCompletedChallenges >= 1;
        }

        if (str_contains($req, 'complete 10 challenges')) {
            return $totalCompletedChallenges >= 10;
        }

        if (str_contains($req, 'walk 10,000 steps')) {
            return $totalSteps >= 10000;
        }

        if (str_contains($req, 'complete 3 collections')) {
            return $totalCompletedCollections >= 3;
        }

        if (str_contains($req, 'streak')) {
            preg_match('/\d+/', $req, $matches);
            $reqDays = isset($matches[0]) ? (int) $matches[0] : 7;

            return $streakDays >= $reqDays;
        }

        return false;
    }
}
