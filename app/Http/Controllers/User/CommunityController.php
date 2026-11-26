<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Community;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class CommunityController extends Controller
{
    public function index(): View
    {
        return view('user.community.index');
    }

    public function getPosts(): JsonResponse
    {
        $posts = Community::with('user:id,name,foto_profile')
            ->withCount('comments')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'deskripsi' => $post->deskripsi,
                    'gambar' => $post->gambar,
                    'langkah' => $post->langkah,
                    'jarak' => $post->jarak,
                    'kalori' => $post->kalori,
                    'comments_count' => $post->comments_count,
                    'nama_user' => $post->user?->name ?? 'User',
                    'foto_profile' => $post->user?->foto_profile,
                    'user_id' => $post->user_id,
                    'created_at' => $post->created_at?->diffForHumans(),
                    'is_mine' => $post->user_id === auth()->id(),
                ];
            });

        return response()->json(['success' => true, 'data' => $posts]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'deskripsi' => ['nullable', 'string', 'max:500'],
            'langkah' => ['nullable', 'integer', 'min:0'],
            'jarak' => ['nullable', 'numeric', 'min:0'],
            'kalori' => ['nullable', 'integer', 'min:0'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $gambarName = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $gambarName = time().'-'.$file->getClientOriginalName();
            $uploadPath = public_path('uploads/community');

            if (! File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file->move($uploadPath, $gambarName);
        }

        $post = Community::create([
            'user_id' => auth()->id(),
            'deskripsi' => $request->input('deskripsi'),
            'gambar' => $gambarName,
            'langkah' => $request->input('langkah', 0),
            'jarak' => $request->input('jarak', 0),
            'kalori' => $request->input('kalori', 0),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Postingan berhasil dibuat!',
            'data' => [
                'id' => $post->id,
                'deskripsi' => $post->deskripsi,
                'gambar' => $post->gambar,
                'langkah' => $post->langkah,
                'jarak' => $post->jarak,
                'kalori' => $post->kalori,
                'comments_count' => 0,
                'nama_user' => auth()->user()->name,
                'user_id' => auth()->id(),
                'created_at' => $post->created_at?->diffForHumans(),
                'is_mine' => true,
            ],
        ], 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $post = Community::find($id);

        if (! $post) {
            return response()->json(['success' => false, 'message' => 'Post tidak ditemukan.'], 404);
        }

        if ($post->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Anda tidak berhak menghapus post ini.'], 403);
        }

        if ($post->gambar) {
            $path = public_path('uploads/community/'.$post->gambar);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $post->delete();

        return response()->json(['success' => true, 'message' => 'Post berhasil dihapus.']);
    }

    public function getComments(int $communityId): JsonResponse
    {
        $comments = Comment::where('community_id', $communityId)
            ->orderBy('created_at')
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'nama_user' => $c->nama_user,
                'komentar' => $c->komentar,
                'created_at' => $c->created_at?->diffForHumans(),
                'is_mine' => $c->user_id === auth()->id(),
            ]);

        return response()->json(['success' => true, 'data' => $comments]);
    }

    public function postComment(Request $request): JsonResponse
    {
        $request->validate([
            'community_id' => ['required', 'integer', 'exists:community,id'],
            'komentar' => ['required', 'string', 'max:500'],
        ]);

        $comment = Comment::create([
            'community_id' => $request->input('community_id'),
            'user_id' => auth()->id(),
            'nama_user' => auth()->user()->name,
            'komentar' => $request->input('komentar'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil ditambahkan.',
            'data' => [
                'id' => $comment->id,
                'nama_user' => $comment->nama_user,
                'komentar' => $comment->komentar,
                'created_at' => $comment->created_at?->diffForHumans(),
                'is_mine' => true,
            ],
        ], 201);
    }

    public function leaderboard(): JsonResponse
    {
        $users = User::where('role', 'user')
            ->where('status', 'active')
            ->orderByDesc('poin')
            ->limit(10)
            ->get(['id', 'name', 'poin', 'foto_profile']);

        return response()->json(['success' => true, 'data' => $users]);
    }
}
