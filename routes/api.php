<?php

use App\Http\Controllers\Api\BadgeController;
use App\Http\Controllers\Api\ChallengeController;
use App\Http\Controllers\Api\CollectionController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\JavafxController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RedemptionController;
use App\Http\Controllers\Api\RewardController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Legacy Native PHP Endpoint Aliases (webpro-stepup compatibility)
| Only kept for resources without a RESTful equivalent (profile).
|--------------------------------------------------------------------------
*/

// Profile
Route::prefix('profile')->group(function () {
    Route::get('/get/{id}', [ProfileController::class, 'show']);
    Route::post('/update/{id}', [ProfileController::class, 'update']);
    Route::post('/update_password/{id}', [ProfileController::class, 'updatePassword']);
});

/*
|--------------------------------------------------------------------------
| RESTful API Resources
|--------------------------------------------------------------------------
*/
Route::apiResource('rewards', RewardController::class)->whereNumber('reward');
Route::apiResource('collections', CollectionController::class)->whereNumber('collection');
Route::apiResource('badges', BadgeController::class)->whereNumber('badge');
Route::apiResource('challenges', ChallengeController::class)->whereNumber('challenge');
Route::apiResource('users', UserController::class)->whereNumber('user')->except(['store']);
Route::apiResource('community', CommunityController::class)->whereNumber('community');

// Challenge extra actions
Route::post('challenges/{challenge}/join', [ChallengeController::class, 'join'])->whereNumber('challenge');
Route::post('challenges/{challenge}/log_progress', [ChallengeController::class, 'logProgress'])->whereNumber('challenge');

// Collection extra actions
Route::post('collections/{collection}/join', [CollectionController::class, 'join'])->whereNumber('collection');
Route::post('collections/{collection}/add_challenges', [CollectionController::class, 'addChallenges'])->whereNumber('collection');
Route::delete('collections/{collection}/challenges/{challenge}', [CollectionController::class, 'removeChallenge'])->whereNumber(['collection', 'challenge']);

// Community comments
Route::get('community/{community}/comments', [CommunityController::class, 'getComments'])->whereNumber('community');
Route::post('community/{community}/comments', [CommunityController::class, 'postComment'])->whereNumber('community');
Route::delete('community/comments/{comment}', [CommunityController::class, 'deleteComment']);

// Redemptions
Route::post('rewards/redeem', [RedemptionController::class, 'redeem']);
Route::get('users/{user}/redemptions', [RedemptionController::class, 'history'])->whereNumber('user');

/*
|--------------------------------------------------------------------------
| JavaFX dedicated endpoints
|--------------------------------------------------------------------------
*/
Route::prefix('javafx')->group(function () {
    Route::post('/auth_login', [JavafxController::class, 'login']);
    Route::post('/auth_register', [JavafxController::class, 'register']);

    Route::get('/users_get', [JavafxController::class, 'usersGet']);
    Route::post('/users_update', [JavafxController::class, 'usersUpdate']);
    Route::post('/users_delete', [JavafxController::class, 'usersDelete']);

    Route::get('/community_feed_get', [JavafxController::class, 'communityGet']);
    Route::post('/community_feed_post', [JavafxController::class, 'communityPost']);
    Route::post('/community_feed_update', [JavafxController::class, 'communityUpdate']);
    Route::post('/community_feed_delete', [JavafxController::class, 'communityDelete']);

    Route::get('/rewards_get', [JavafxController::class, 'rewardsGet']);
    Route::post('/rewards_post', [JavafxController::class, 'rewardsPost']);
    Route::post('/rewards_update', [JavafxController::class, 'rewardsUpdate']);
    Route::post('/rewards_delete', [JavafxController::class, 'rewardsDelete']);
    Route::post('/rewards_redeem', [JavafxController::class, 'rewardsRedeem']);
    Route::get('/rewards_history', [JavafxController::class, 'rewardsHistory']);
});
