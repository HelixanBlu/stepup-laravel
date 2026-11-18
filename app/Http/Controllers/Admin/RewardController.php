<?php

  namespace App\Http\Controllers\Admin;

  use App\Http\Controllers\Controller;
  use App\Models\Reward;
  use Illuminate\Http\RedirectResponse;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\File;
  use Illuminate\View\View;

  class RewardController extends Controller
  {
      public function index(): View
      {
          $rewards = Reward::orderBy('id_reward', 'desc')->get();

          return view('admin.rewards.index', compact('rewards'));
      }

      public function create(): View
      {
          return view('admin.rewards.create');
      }

      public function store(Request $request): RedirectResponse
      {
          $validated = $request->validate([
              'name_reward'  => 'required|string|max:255',
              'description'  => 'required|string',
              'poin'         => 'required|integer|min:0',
              'stok'         => 'nullable|integer|min:0',
              'gambar'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
          ]);

          $gambarName = null;

          if ($request->hasFile('gambar')) {
              $file       = $request->file('gambar');
              $gambarName = time() . '-' . $file->getClientOriginalName();
              $file->move(public_path('uploads'), $gambarName);
          }

          Reward::create([
              'name_reward' => $validated['name_reward'],
              'description' => $validated['description'],
              'poin'        => $validated['poin'],
              'stok'        => $validated['stok'] ?? 0,
              'gambar'      => $gambarName,
          ]);

          return redirect()->route('admin.rewards.index')
              ->with('success', 'Reward berhasil ditambahkan.');
      }

      public function edit(int $id): View
      {
          $reward = Reward::findOrFail($id);

          return view('admin.rewards.edit', compact('reward'));
      }

      public function update(Request $request, int $id): RedirectResponse
      {
          $reward = Reward::findOrFail($id);

          $validated = $request->validate([
              'name_reward' => 'required|string|max:255',
              'description' => 'required|string',
              'poin'        => 'required|integer|min:0',
              'stok'        => 'nullable|integer|min:0',
              'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
          ]);

          if ($request->hasFile('gambar')) {
              $oldPath = public_path('uploads/' . $reward->gambar);
              if ($reward->gambar && File::exists($oldPath)) {
                  File::delete($oldPath);
              }

              $file       = $request->file('gambar');
              $gambarName = time() . '-' . $file->getClientOriginalName();
              $file->move(public_path('uploads'), $gambarName);
              $reward->gambar = $gambarName;
          }

          $reward->name_reward = $validated['name_reward'];
          $reward->description = $validated['description'];
          $reward->poin        = $validated['poin'];
          $reward->stok        = $validated['stok'] ?? 0;
          $reward->save();

          return redirect()->route('admin.rewards.index')
              ->with('success', 'Reward berhasil diperbarui.');
      }

      public function destroy(int $id): RedirectResponse
      {
          $reward = Reward::findOrFail($id);

          if ($reward->gambar) {
              $oldPath = public_path('uploads/' . $reward->gambar);
              if (File::exists($oldPath)) {
                  File::delete($oldPath);
              }
          }

          $reward->delete();

          return redirect()->route('admin.rewards.index')
              ->with('success', 'Reward berhasil dihapus.');
      }
  }