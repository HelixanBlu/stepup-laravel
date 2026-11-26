<?php

use App\Models\Reward;
use App\Models\User;

function rewardForRedemption(array $overrides = []): Reward
{
    return Reward::create(array_merge([
        'name_reward' => 'Voucher Makan Sehat',
        'description' => 'Voucher makan siang.',
        'poin' => 100,
        'stok' => 3,
    ], $overrides));
}

test('redeeming a reward deducts points and stock and records the redemption', function () {
    $user = User::factory()->create(['poin' => 250]);
    $reward = rewardForRedemption();

    $this->postJson('/api/rewards/redeem', [
        'user_id' => $user->id,
        'reward_id' => $reward->id_reward,
    ])
        ->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.sisa_poin', 150);

    expect($user->fresh()->poin)->toBe(150)
        ->and($reward->fresh()->stok)->toBe(2);

    $this->assertDatabaseHas('redemptions', [
        'user_id' => $user->id,
        'reward_id' => $reward->id_reward,
        'poin_digunakan' => 100,
        'nama_reward' => 'Voucher Makan Sehat',
    ]);
});

test('a reward without stock cannot be redeemed', function () {
    $user = User::factory()->create(['poin' => 250]);
    $reward = rewardForRedemption(['stok' => 0]);

    $this->postJson('/api/rewards/redeem', [
        'user_id' => $user->id,
        'reward_id' => $reward->id_reward,
    ])
        ->assertStatus(422)
        ->assertJsonPath('success', false)
        ->assertJsonPath('message', 'Stok reward habis');

    expect($user->fresh()->poin)->toBe(250)
        ->and($reward->fresh()->stok)->toBe(0);

    $this->assertDatabaseCount('redemptions', 0);
});

test('a reward cannot be redeemed without enough points', function () {
    $user = User::factory()->create(['poin' => 50]);
    $reward = rewardForRedemption();

    $this->postJson('/api/rewards/redeem', [
        'user_id' => $user->id,
        'reward_id' => $reward->id_reward,
    ])
        ->assertStatus(422)
        ->assertJsonPath('success', false)
        ->assertJsonPath('message', 'Poin tidak mencukupi');

    expect($user->fresh()->poin)->toBe(50)
        ->and($reward->fresh()->stok)->toBe(3);

    $this->assertDatabaseCount('redemptions', 0);
});

test('the user reward page redeems with the same rules', function () {
    $user = User::factory()->create(['poin' => 250]);
    $reward = rewardForRedemption();

    $this->actingAs($user)
        ->postJson(route('user.rewards.redeem'), ['reward_id' => $reward->id_reward])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.sisa_poin', 150)
        ->assertJsonPath('data.sisa_stok', 2);

    expect($reward->fresh()->stok)->toBe(2);
});
