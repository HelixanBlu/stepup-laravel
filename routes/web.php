<?php

use App\Http\Controllers\Admin\BadgeController as AdminBadgeController;
use App\Http\Controllers\Admin\ChallengeController as AdminChallengeController;
use App\Http\Controllers\Admin\CollectionController as AdminCollectionController;
use App\Http\Controllers\Admin\RewardController as AdminRewardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('rewards', AdminRewardController::class)->except(['show']);
    Route::resource('badges', AdminBadgeController::class)->except(['show']);
    Route::resource('challenges', AdminChallengeController::class)->except(['show']);
    Route::resource('collections', AdminCollectionController::class);
});

require __DIR__.'/auth.php';
