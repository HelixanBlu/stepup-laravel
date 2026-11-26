<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\ChallengeParticipant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChallengeController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        $challenges = Challenge::where('status', '!=', 'draft')
            ->withCount('participants')
            ->with(['participants' => fn ($q) => $q->where('user_id', $userId)])
            ->orderByDesc('created_at')
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

        return view('user.challenges.index', compact('challenges'));
    }

    public function join(Request $request): JsonResponse
    {
        $request->validate([
            'challenge_id' => ['required', 'integer', 'exists:challenges,id'],
        ]);

        $userId = auth()->id();
        $challengeId = $request->input('challenge_id');

        $exists = ChallengeParticipant::where('challenge_id', $challengeId)
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Anda sudah bergabung dengan challenge ini.'], 409);
        }

        ChallengeParticipant::create([
            'challenge_id' => $challengeId,
            'user_id' => $userId,
            'current_progress' => 0,
            'completion_status' => 'in_progress',
        ]);

        return response()->json(['success' => true, 'message' => 'Berhasil bergabung dengan challenge!']);
    }

    public function logProgress(Request $request): JsonResponse
    {
        $request->validate([
            'challenge_id' => ['required', 'integer', 'exists:challenges,id'],
            'progress_value' => ['required', 'integer', 'min:1'],
        ]);

        $userId = auth()->id();
        $challengeId = $request->input('challenge_id');

        $participant = ChallengeParticipant::where('challenge_id', $challengeId)
            ->where('user_id', $userId)
            ->first();

        if (! $participant) {
            return response()->json(['success' => false, 'message' => 'Anda belum bergabung dengan challenge ini.'], 404);
        }

        if ($participant->completion_status === 'completed') {
            return response()->json(['success' => false, 'message' => 'Challenge sudah selesai.'], 422);
        }

        $challenge = Challenge::find($challengeId);
        $newProgress = $participant->current_progress + (int) $request->input('progress_value');
        $newProgress = min($newProgress, $challenge->goal_value);

        $participant->current_progress = $newProgress;

        $justCompleted = false;
        if ($newProgress >= $challenge->goal_value) {
            $participant->completion_status = 'completed';
            $justCompleted = true;

            // Award poin ke user
            if ($challenge->reward_points > 0) {
                auth()->user()->increment('poin', $challenge->reward_points);
            }
        }

        $participant->save();

        $progressPercent = round(($newProgress / $challenge->goal_value) * 100);

        return response()->json([
            'success' => true,
            'message' => $justCompleted
                ? "🎉 Challenge selesai! Anda mendapat {$challenge->reward_points} poin."
                : 'Progress berhasil diperbarui.',
            'data' => [
                'current_progress' => $newProgress,
                'goal_value' => $challenge->goal_value,
                'progress_percent' => $progressPercent,
                'completion_status' => $participant->completion_status,
                'reward_points' => $challenge->reward_points,
                'just_completed' => $justCompleted,
                'sisa_poin' => $justCompleted ? auth()->user()->fresh()->poin : null,
            ],
        ]);
    }
}
