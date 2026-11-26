<?php

use App\Models\Badge;
use App\Models\Challenge;
use App\Models\Collection;
use App\Models\User;
use Database\Seeders\BadgeSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

function badgeChallenge(array $overrides = []): Challenge
{
    return Challenge::create(array_merge([
        'title' => 'Walk 10,000 Steps',
        'description' => 'Reach the daily step goal.',
        'challenge_type' => 'daily',
        'goal_type' => 'steps',
        'goal_value' => 10000,
        'reward_points' => 250,
        'start_date' => '2026-11-20',
        'end_date' => '2026-11-30',
        'status' => 'active',
    ], $overrides));
}

function badgePayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Step Starter',
        'description' => 'Complete your first walking activity.',
        'category' => 'activity',
        'rarity' => 'common',
        'unlock_requirement' => 'Complete one walking activity.',
        'status' => 'active',
    ], $overrides);
}

test('guests cannot access badge management', function () {
    $this->get(route('admin.badges.index'))
        ->assertRedirect(route('login'));
});

test('badge management searches and filters badges with their relationships and earners', function () {
    $user = User::factory()->admin()->create();
    $earner = User::factory()->create();
    $challenge = badgeChallenge();
    $collection = Collection::create(['name' => 'Daily Fitness']);
    $matchingBadge = Badge::create(badgePayload([
        'name' => 'Morning Mover',
        'category' => 'challenge',
        'rarity' => 'rare',
        'related_challenge_id' => $challenge->id,
        'related_collection_id' => $collection->id,
    ]));
    $matchingBadge->users()->attach($earner->id, ['earned_at' => now()]);
    Badge::create(badgePayload([
        'name' => 'Legend Walker',
        'category' => 'special_event',
        'rarity' => 'legendary',
        'status' => 'inactive',
    ]));

    $this->actingAs($user)
        ->get(route('admin.badges.index', [
            'search' => 'Morning',
            'category' => 'challenge',
            'rarity' => 'rare',
            'status' => 'active',
        ]))
        ->assertOk()
        ->assertSee('Morning Mover')
        ->assertSee('Walk 10,000 Steps')
        ->assertSee('Daily Fitness')
        ->assertSee('1 user earned')
        ->assertDontSee('Legend Walker');
});

test('authenticated users can create update with preserved and replacement icon and delete badges', function () {
    Storage::fake('public');
    $user = User::factory()->admin()->create();
    $earner = User::factory()->create();
    $challenge = badgeChallenge();
    $collection = Collection::create(['name' => 'Starter Collection']);

    $this->actingAs($user)
        ->post(route('admin.badges.store'), badgePayload([
            'badge_icon' => UploadedFile::fake()->createWithContent(
                'badge.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQIHWP4z8DwHwAFgAI/ScL7TAAAAABJRU5ErkJggg=='),
            ),
            'related_challenge_id' => $challenge->id,
            'related_collection_id' => $collection->id,
        ]))
        ->assertRedirect(route('admin.badges.index'));

    $badge = Badge::firstOrFail();
    $originalIcon = $badge->badge_icon;

    expect($originalIcon)->not->toBeNull();
    Storage::disk('public')->assertExists($originalIcon);
    $this->assertDatabaseHas('badges', [
        'id' => $badge->id,
        'name' => 'Step Starter',
        'related_challenge_id' => $challenge->id,
        'related_collection_id' => $collection->id,
    ]);

    $this->actingAs($user)
        ->put(route('admin.badges.update', $badge), badgePayload([
            'name' => 'Updated Step Starter',
            'related_challenge_id' => '',
            'related_collection_id' => '',
        ]))
        ->assertRedirect(route('admin.badges.index'));

    $badge->refresh();

    expect($badge->badge_icon)->toBe($originalIcon);
    expect($badge->related_challenge_id)->toBeNull();
    expect($badge->related_collection_id)->toBeNull();

    $this->actingAs($user)
        ->put(route('admin.badges.update', $badge), badgePayload([
            'name' => 'Updated Step Starter',
            'badge_icon' => UploadedFile::fake()->createWithContent(
                'replacement.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQIHWP4z8DwHwAFgAI/ScL7TAAAAABJRU5ErkJggg=='),
            ),
        ]))
        ->assertRedirect(route('admin.badges.index'));

    $replacementIcon = $badge->fresh()->badge_icon;

    expect($replacementIcon)->not->toBe($originalIcon);
    Storage::disk('public')->assertMissing($originalIcon);
    Storage::disk('public')->assertExists($replacementIcon);

    DB::table('user_badges')->insert([
        'user_id' => $earner->id,
        'badge_id' => $badge->id,
        'earned_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($user)
        ->delete(route('admin.badges.destroy', $badge))
        ->assertRedirect(route('admin.badges.index'));

    $this->assertDatabaseMissing('badges', ['id' => $badge->id]);
    $this->assertDatabaseMissing('user_badges', ['badge_id' => $badge->id]);
    Storage::disk('public')->assertMissing($replacementIcon);
});

test('badge fields and optional relationships are validated', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->from(route('admin.badges.create'))
        ->post(route('admin.badges.store'), badgePayload([
            'name' => '',
            'category' => 'invalid',
            'rarity' => 'invalid',
            'status' => 'invalid',
            'related_challenge_id' => 999999,
            'related_collection_id' => 999999,
        ]))
        ->assertRedirect(route('admin.badges.create'))
        ->assertSessionHasErrors([
            'name',
            'category',
            'rarity',
            'status',
            'related_challenge_id',
            'related_collection_id',
        ]);
});

test('badge seeder can be run twice without duplicating badges', function () {
    (new BadgeSeeder)->run();
    (new BadgeSeeder)->run();

    expect(Badge::count())->toBe(6);
});
