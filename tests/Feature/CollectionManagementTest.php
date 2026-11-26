<?php

use App\Models\Challenge;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function collectionChallenge(array $overrides = []): Challenge
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

test('guests cannot access collection management', function () {
    $this->get(route('admin.collections.index'))
        ->assertRedirect(route('login'));
});

test('collection management searches names, counts challenges, and shows details', function () {
    $user = User::factory()->admin()->create();
    $challenge = collectionChallenge();
    $collection = Collection::create([
        'name' => 'Daily Fitness',
        'description' => 'Short everyday challenges.',
    ]);
    $collection->challenges()->attach($challenge);
    Collection::create(['name' => 'Weekend Activities']);

    $this->actingAs($user)
        ->get(route('admin.collections.index', ['search' => 'Daily']))
        ->assertOk()
        ->assertSee('Daily Fitness')
        ->assertDontSee('Weekend Activities')
        ->assertSee('1 challenge');

    $this->actingAs($user)
        ->get(route('admin.collections.show', $collection))
        ->assertOk()
        ->assertSee('Daily Fitness')
        ->assertSee('Walk 10,000 Steps')
        ->assertSee('10,000 steps')
        ->assertSee('250 poin');
});

test('authenticated users can create update associations and delete a collection without deleting challenges', function () {
    Storage::fake('public');
    $user = User::factory()->admin()->create();
    $firstChallenge = collectionChallenge();
    $secondChallenge = collectionChallenge(['title' => 'Distance Builder', 'goal_type' => 'distance']);

    $this->actingAs($user)
        ->post(route('admin.collections.store'), [
            'name' => 'Starter Program',
            'description' => 'A balanced starting set.',
            'cover_image' => UploadedFile::fake()->createWithContent(
                'collection.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQIHWP4z8DwHwAFgAI/ScL7TAAAAABJRU5ErkJggg=='),
            ),
            'challenge_ids' => [$firstChallenge->id, $secondChallenge->id],
        ])
        ->assertRedirect(route('admin.collections.index'));

    $collection = Collection::firstOrFail();
    $originalImage = $collection->cover_image;

    expect($originalImage)->not->toBeNull();
    Storage::disk('public')->assertExists($originalImage);
    $this->assertDatabaseCount('collection_challenges', 2);

    $this->actingAs($user)
        ->put(route('admin.collections.update', $collection), [
            'name' => 'Updated Starter Program',
            'description' => 'A refined starting set.',
            'challenge_ids' => [$secondChallenge->id],
        ])
        ->assertRedirect(route('admin.collections.index'));

    $collection->refresh();

    expect($collection->cover_image)->toBe($originalImage);
    $this->assertDatabaseHas('collection_challenges', [
        'collection_id' => $collection->id,
        'challenge_id' => $secondChallenge->id,
    ]);
    $this->assertDatabaseMissing('collection_challenges', [
        'collection_id' => $collection->id,
        'challenge_id' => $firstChallenge->id,
    ]);

    $this->actingAs($user)
        ->put(route('admin.collections.update', $collection), [
            'name' => 'Updated Starter Program',
            'description' => 'A refined starting set.',
            'cover_image' => UploadedFile::fake()->createWithContent(
                'replacement.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQIHWP4z8DwHwAFgAI/ScL7TAAAAABJRU5ErkJggg=='),
            ),
            'challenge_ids' => [$secondChallenge->id],
        ])
        ->assertRedirect(route('admin.collections.index'));

    $replacementImage = $collection->fresh()->cover_image;

    expect($replacementImage)->not->toBe($originalImage);
    Storage::disk('public')->assertMissing($originalImage);
    Storage::disk('public')->assertExists($replacementImage);

    $this->actingAs($user)
        ->delete(route('admin.collections.destroy', $collection))
        ->assertRedirect(route('admin.collections.index'));

    $this->assertDatabaseMissing('collections', ['id' => $collection->id]);
    $this->assertDatabaseMissing('collection_challenges', ['collection_id' => $collection->id]);
    $this->assertDatabaseHas('challenges', ['id' => $secondChallenge->id]);
    Storage::disk('public')->assertMissing($replacementImage);
});

test('collection names and selected challenges are validated', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->from(route('admin.collections.create'))
        ->post(route('admin.collections.store'), [
            'name' => '',
            'challenge_ids' => [999999],
        ])
        ->assertRedirect(route('admin.collections.create'))
        ->assertSessionHasErrors(['name', 'challenge_ids.0']);
});
