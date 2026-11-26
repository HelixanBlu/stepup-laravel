<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\ChallengeParticipant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ChallengeController extends Controller
{
    public function index(): JsonResponse
    {
        $challenges = Challenge::withCount([
            'participants',
            'participants as completed_count' => fn ($q) => $q->where('completion_status', 'completed'),
        ])->orderByDesc('created_at')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data challenges berhasil diambil',
            'total' => $challenges->count(),
            'data' => $challenges,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'challenge_type' => ['required', 'string', 'max:50'],
            'goal_type' => ['required', 'string', 'max:50'],
            'goal_value' => ['required', 'integer', 'min:1'],
            'reward_points' => ['nullable', 'integer', 'min:0'],
            'badge_reward' => ['nullable', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'string', 'max:50'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $fileName = time().'-'.$file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $validated['banner_image'] = $fileName;
        }

        $challenge = Challenge::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Challenge berhasil dibuat',
            'data' => $challenge,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $challenge = Challenge::withCount([
            'participants',
            'participants as completed_count' => fn ($q) => $q->where('completion_status', 'completed'),
        ])->find($id);

        if (! $challenge) {
            return response()->json([
                'success' => false,
                'message' => "Challenge dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail challenge berhasil diambil',
            'data' => $challenge,
        ]);
    }

    public function update(Request $request, int|string|null $id = null): JsonResponse
    {
        $id = $id ?? $request->input('id');
        $challenge = Challenge::find($id);

        if (! $challenge) {
            return response()->json([
                'success' => false,
                'message' => "Challenge dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'challenge_type' => ['sometimes', 'required', 'string', 'max:50'],
            'goal_type' => ['sometimes', 'required', 'string', 'max:50'],
            'goal_value' => ['sometimes', 'required', 'integer', 'min:1'],
            'reward_points' => ['nullable', 'integer', 'min:0'],
            'badge_reward' => ['nullable', 'string', 'max:100'],
            'start_date' => ['sometimes', 'required', 'date'],
            'end_date' => ['sometimes', 'required', 'date'],
            'status' => ['nullable', 'string', 'max:50'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $fileName = time().'-'.$file->getClientOriginalName();
            $uploadPath = public_path('uploads');

            if ($challenge->banner_image && File::exists($uploadPath.'/'.$challenge->banner_image)) {
                File::delete($uploadPath.'/'.$challenge->banner_image);
            }

            $file->move($uploadPath, $fileName);
            $validated['banner_image'] = $fileName;
        }

        $challenge->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Challenge berhasil diperbarui',
            'data' => $challenge->fresh(),
        ]);
    }

    public function destroy(Request $request, int|string|null $id = null): JsonResponse
    {
        $id = $id ?? $request->input('id');
        $challenge = Challenge::find($id);

        if (! $challenge) {
            return response()->json([
                'success' => false,
                'message' => "Challenge dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        if ($challenge->banner_image) {
            $path = public_path('uploads/'.$challenge->banner_image);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $challenge->delete();

        return response()->json([
            'success' => true,
            'message' => 'Challenge berhasil dihapus',
        ]);
    }

    /**
     * User joins a challenge.
     */
    public function join(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'challenge_id' => ['required', 'integer', 'exists:challenges,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $exists = ChallengeParticipant::where('challenge_id', $validated['challenge_id'])
            ->where('user_id', $validated['user_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'User sudah bergabung dengan challenge ini',
            ], 409);
        }

        $participant = ChallengeParticipant::create([
            'challenge_id' => $validated['challenge_id'],
            'user_id' => $validated['user_id'],
            'current_progress' => 0,
            'completion_status' => 'in_progress',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil bergabung dengan challenge',
            'data' => $participant,
        ], 201);
    }

    /**
     * Log progress on a challenge participation.
     */
    public function logProgress(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'challenge_id' => ['required', 'integer', 'exists:challenges,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'progress' => ['required', 'integer', 'min:0'],
        ]);

        $participant = ChallengeParticipant::where('challenge_id', $validated['challenge_id'])
            ->where('user_id', $validated['user_id'])
            ->first();

        if (! $participant) {
            return response()->json([
                'success' => false,
                'message' => 'User belum bergabung dengan challenge ini',
            ], 404);
        }

        $challenge = Challenge::find($validated['challenge_id']);
        $participant->current_progress = $validated['progress'];

        if ($participant->current_progress >= $challenge->goal_value) {
            $participant->current_progress = $challenge->goal_value;
            $participant->completion_status = 'completed';
        }

        $participant->save();

        return response()->json([
            'success' => true,
            'message' => 'Progress berhasil diperbarui',
            'data' => $participant,
        ]);
    }
}
