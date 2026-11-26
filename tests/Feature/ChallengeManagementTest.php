<?php

use App\Models\Challenge;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

function challengePayload(array $overrides = []): array
{
    return array_merge([
        'title' => 'Walk 10,000 Steps',
        'description' => 'Reach the daily step goal.',
        'challenge_type' => 'daily',
        'goal_type' => 'steps',
        'goal_value' => 10000,
        'reward_points' => 250,
        'badge_reward' => 'Step Starter',
        'start_date' => '2026-11-20',
        'end_date' => '2026-11-30',
        'status' => 'active',
    ], $overrides);
}

test('guests cannot access challenge management', function () {
    $this->get(route('admin.challenges.index'))
        ->assertRedirect(route('login'));
});

test('challenge management searches titles without a status filter', function () {
    $user = User::factory()->admin()->create();
    $matchingChallenge = Challenge::create(challengePayload());
    Challenge::create(challengePayload([
        'title' => 'Monthly Distance Goal',
        'challenge_type' => 'monthly',
        'status' => 'draft',
    ]));

    DB::table('challenge_participants')->insert([
        'challenge_id' => $matchingChallenge->id,
        'user_id' => $user->id,
        'current_progress' => 2500,
        'completion_status' => 'in_progress',
        'joined_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('admin.challenges.index', ['search' => 'Walk']))
        ->assertOk()
        ->assertSee('Walk 10,000 Steps')
        ->assertDontSee('Monthly Distance Goal')
        ->assertSee('1 participant');
});

test('authenticated users can create update with preserved banner and delete challenges', function () {
    Storage::fake('public');
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->post(route('admin.challenges.store'), challengePayload([
            'banner_image' => UploadedFile::fake()->createWithContent(
                'challenge.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQIHWP4z8DwHwAFgAI/ScL7TAAAAABJRU5ErkJggg=='),
            ),
        ]))
        ->assertRedirect(route('admin.challenges.index'));

    $challenge = Challenge::firstOrFail();
    $originalBanner = $challenge->banner_image;

    expect($originalBanner)->not->toBeNull();
    Storage::disk('public')->assertExists($originalBanner);
    $this->actingAs($user)
        ->get(route('admin.challenges.index'))
        ->assertOk()
        ->assertSee(Storage::disk('public')->url($originalBanner));
    $this->assertDatabaseHas('challenges', [
        'id' => $challenge->id,
        'title' => 'Walk 10,000 Steps',
        'status' => 'active',
    ]);

    $this->actingAs($user)
        ->put(route('admin.challenges.update', $challenge), challengePayload([
            'title' => 'Walk 12,000 Steps',
        ]))
        ->assertRedirect(route('admin.challenges.index'));

    $challenge->refresh();

    expect($challenge->banner_image)->toBe($originalBanner);

    DB::table('challenge_participants')->insert([
        'challenge_id' => $challenge->id,
        'user_id' => $user->id,
        'current_progress' => 0,
        'completion_status' => 'in_progress',
        'joined_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $collectionId = DB::table('collections')->insertGetId([
        'name' => 'Starter Collection',
        'difficulty' => 'easy',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    DB::table('collection_challenges')->insert([
        'collection_id' => $collectionId,
        'challenge_id' => $challenge->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($user)
        ->delete(route('admin.challenges.destroy', $challenge))
        ->assertRedirect(route('admin.challenges.index'));

    $this->assertDatabaseMissing('challenges', ['id' => $challenge->id]);
    $this->assertDatabaseMissing('challenge_participants', ['challenge_id' => $challenge->id]);
    $this->assertDatabaseMissing('collection_challenges', ['challenge_id' => $challenge->id]);
    Storage::disk('public')->assertMissing($originalBanner);
});

test('challenge dates and values are validated', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->from(route('admin.challenges.create'))
        ->post(route('admin.challenges.store'), challengePayload([
            'goal_value' => -1,
            'reward_points' => -1,
            'end_date' => '2026-11-19',
        ]))
        ->assertRedirect(route('admin.challenges.create'))
        ->assertSessionHasErrors(['goal_value', 'reward_points', 'end_date']);
});
