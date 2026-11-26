<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Community;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CommunityController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = Community::with('user:id,name,foto_profile')
            ->withCount('comments')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($post) {
                $data = $post->toArray();
                $data['nama_user'] = $post->user?->name ?? 'User';
                $data['foto_profile'] = $post->user?->foto_profile;

                return $data;
            });

        return response()->json([
            'success' => true,
            'message' => 'Data community berhasil diambil',
            'total' => $posts->count(),
            'data' => $posts,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'deskripsi' => ['nullable', 'string'],
            'langkah' => ['nullable', 'integer', 'min:0'],
            'jarak' => ['nullable', 'numeric', 'min:0'],
            'kalori' => ['nullable', 'integer', 'min:0'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $fileName = time().'-'.$file->getClientOriginalName();
            $file->move(public_path('uploads/community'), $fileName);
            $validated['gambar'] = $fileName;
        }

        $post = Community::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Post berhasil dibuat',
            'data' => $post,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $post = Community::with(['user:id,name,foto_profile', 'comments'])->find($id);

        if (! $post) {
            return response()->json([
                'success' => false,
                'message' => "Post dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail post berhasil diambil',
            'data' => $post,
        ]);
    }

    public function update(Request $request, int|string|null $id = null): JsonResponse
    {
        $id = $id ?? $request->input('id');
        $post = Community::find($id);

        if (! $post) {
            return response()->json([
                'success' => false,
                'message' => "Post dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        $validated = $request->validate([
            'deskripsi' => ['nullable', 'string'],
            'langkah' => ['nullable', 'integer', 'min:0'],
            'jarak' => ['nullable', 'numeric', 'min:0'],
            'kalori' => ['nullable', 'integer', 'min:0'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $fileName = time().'-'.$file->getClientOriginalName();
            $uploadPath = public_path('uploads/community');

            if ($post->gambar && File::exists($uploadPath.'/'.$post->gambar)) {
                File::delete($uploadPath.'/'.$post->gambar);
            }

            $file->move($uploadPath, $fileName);
            $validated['gambar'] = $fileName;
        }

        $post->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Post berhasil diperbarui',
            'data' => $post->fresh(),
        ]);
    }

    public function destroy(Request $request, int|string|null $id = null): JsonResponse
    {
        $id = $id ?? $request->input('id');
        $post = Community::find($id);

        if (! $post) {
            return response()->json([
                'success' => false,
                'message' => "Post dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        if ($post->gambar) {
            $path = public_path('uploads/community/'.$post->gambar);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post berhasil dihapus',
        ]);
    }

    public function getComments(int $communityId): JsonResponse
    {
        $comments = Comment::where('community_id', $communityId)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil diambil',
            'total' => $comments->count(),
            'data' => $comments,
        ]);
    }

    public function postComment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'community_id' => ['required', 'integer', 'exists:community,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'nama_user' => ['nullable', 'string', 'max:100'],
            'komentar' => ['required', 'string'],
        ]);

        $validated['nama_user'] = $validated['nama_user'] ?? 'User';

        $comment = Comment::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil ditambahkan',
            'data' => $comment,
        ], 201);
    }

    public function deleteComment(Request $request, int|string|null $id = null): JsonResponse
    {
        $id = $id ?? $request->input('id');
        $comment = Comment::find($id);

        if (! $comment) {
            return response()->json([
                'success' => false,
                'message' => "Komentar dengan ID {$id} tidak ditemukan",
            ], 404);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus',
        ]);
    }
}
