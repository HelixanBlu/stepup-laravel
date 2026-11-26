<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CommunityController extends Controller
{
    public function index(Request $request): View
    {
        $posts = Community::query()
            ->with('user:id,name')
            ->withCount('comments')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('deskripsi', 'like', '%'.$request->string('search')->trim().'%');
            })
            ->orderByDesc('id')
            ->get();

        return view('admin.community.index', compact('posts'));
    }

    public function destroy(Community $community): RedirectResponse
    {
        if ($community->gambar) {
            Storage::disk('public')->delete($community->gambar);
        }

        $community->delete();

        return redirect()->route('admin.community.index')
            ->with('success', 'Post community berhasil dihapus.');
    }
}
