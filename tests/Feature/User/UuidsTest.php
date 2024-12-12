<?php

use App\Enums\CurrencyEnum;
use App\Models\User;

test('user cannot be shown by id -> uuid binding for less visibility of our ids', function () {
    // the same currency -> user returned without calling any exchange driver
    $user = User::factory()->create([
        'currency' => CurrencyEnum::GBP->value,
    ]);

    $userData = [
        'currency' => CurrencyEnum::GBP->value,
    ];

    // id provided
    $this->postJson('/api/user/'.$user->id, $userData)->assertNotFound()->json();

    // uuid provided
    $response2 = $this->postJson('/api/user/'.$user->uuid, $userData)->assertSuccessful()->json();

    expect($response2['data']['name'])->toBe($user->name)
        ->and($response2['data']['uuid'])->toBe($user->uuid)
        ->and($response2['data']['currency'])->toBe($user->currency)
        ->and($response2['data']['hourly_rate'])->toBe($user->hourly_rate)
        ->and($response2['data'])->not->toHaveKey('id');
});
