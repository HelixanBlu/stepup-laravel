<?php

use App\Http\Controllers\Admin\ChallengeController as AdminChallengeController;
use App\Http\Controllers\Admin\CommunityController as AdminCommunityController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RewardController as AdminRewardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\ChallengeController as UserChallengeController;
use App\Http\Controllers\User\CommunityController as UserCommunityController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\RewardController as UserRewardController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    return view('welcome');
});

// Profile (Breeze default)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =====================================================================
// USER ROUTES — semua role (user & admin), hanya perlu auth + verified
// =====================================================================
Route::middleware(['auth', 'verified'])->prefix('user')->name('user.')->group(function () {

    // Dashboard user — ringkasan personal
    Route::get('dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Rewards
    Route::get('rewards', [UserRewardController::class, 'index'])->name('rewards.index');
    Route::post('rewards/redeem', [UserRewardController::class, 'redeem'])->name('rewards.redeem');
    Route::get('rewards/history', [UserRewardController::class, 'history'])->name('rewards.history');

    // Challenges
    Route::get('challenges', [UserChallengeController::class, 'index'])->name('challenges.index');
    Route::post('challenges/join', [UserChallengeController::class, 'join'])->name('challenges.join');
    Route::post('challenges/log_progress', [UserChallengeController::class, 'logProgress'])->name('challenges.log_progress');

    // Community
    Route::get('community', [UserCommunityController::class, 'index'])->name('community.index');
    Route::get('community/posts', [UserCommunityController::class, 'getPosts'])->name('community.posts');
    Route::post('community', [UserCommunityController::class, 'store'])->name('community.store');
    Route::delete('community/{id}', [UserCommunityController::class, 'destroy'])->name('community.destroy');
    Route::get('community/{id}/comments', [UserCommunityController::class, 'getComments'])->name('community.comments');
    Route::post('community/comment', [UserCommunityController::class, 'postComment'])->name('community.comment');
    Route::get('community/leaderboard', [UserCommunityController::class, 'leaderboard'])->name('community.leaderboard');
});

// =====================================================================
// ADMIN ROUTES — hanya role admin
// =====================================================================
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard admin — ringkasan statistik platform
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('rewards', AdminRewardController::class)->except(['show']);
    Route::resource('challenges', AdminChallengeController::class)->except(['show']);

    // Users management
    Route::resource('users', AdminUserController::class)->except(['create', 'store', 'show']);

    // Community moderation
    Route::get('community', [AdminCommunityController::class, 'index'])->name('community.index');
    Route::delete('community/{community}', [AdminCommunityController::class, 'destroy'])->name('community.destroy');
});

require __DIR__.'/auth.php';
