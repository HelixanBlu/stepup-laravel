<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

test('socials keeps posting and commenting available without badges or collections routes', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('user.community.index'))
        ->assertOk()
        ->assertSee('Socials')
        ->assertSee('Komentar');

    expect(Route::has('admin.badges.index'))->toBeFalse()
        ->and(Route::has('admin.collections.index'))->toBeFalse()
        ->and(Route::has('api.badges.index'))->toBeFalse()
        ->and(Route::has('api.collections.index'))->toBeFalse();
});
