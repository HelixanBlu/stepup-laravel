<?php

use App\Http\Controllers\Api\BadgeController;
use App\Http\Controllers\Api\CollectionController;
use App\Http\Controllers\Api\RewardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Legacy Native PHP Endpoint Aliases (webpro-stepup compatibility)
| Must precede wildcard routes so 'get' / 'post' are not treated as {id}
|--------------------------------------------------------------------------
*/
Route::prefix('rewards')->group(function () {
    Route::get('/get', [RewardController::class, 'index']);
    Route::post('/post', [RewardController::class, 'store']);
    Route::post('/update', [RewardController::class, 'update']);
    Route::post('/delete', [RewardController::class, 'destroy']);
});

Route::prefix('collections')->group(function () {
    Route::get('/get', [CollectionController::class, 'index']);
    Route::post('/post', [CollectionController::class, 'store']);
    Route::post('/update', [CollectionController::class, 'update']);
    Route::post('/delete', [CollectionController::class, 'destroy']);
});

Route::prefix('badges')->group(function () {
    Route::get('/get', [BadgeController::class, 'index']);
    Route::post('/post', [BadgeController::class, 'store']);
    Route::post('/update', [BadgeController::class, 'update']);
    Route::post('/delete', [BadgeController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| RESTful API Resource Routes (Rewards, Collections, Badges)
|--------------------------------------------------------------------------
*/
Route::apiResource('rewards', RewardController::class)->whereNumber('reward');
Route::apiResource('collections', CollectionController::class)->whereNumber('collection');
Route::apiResource('badges', BadgeController::class)->whereNumber('badge');
