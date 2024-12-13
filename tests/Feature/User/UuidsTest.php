<?php

use App\Enums\CurrencyEnum;
use App\Models\User;

test('user cannot be shown by id -> uuid binding for less visibility of our ids', function () {
    // the same currency -> user returned without calling any exchange driver
    $user = User::factory()->create([
        'currency' => CurrencyEnum::GBP->value,
    ]);

    // id provided
    $this->getJson('/api/user/'.$user->id.'?currency=gbp')->assertNotFound()->json();

    // uuid provided
    $response2 = $this->getJson('/api/user/'.$user->uuid.'?currency=gbp')->assertSuccessful()->json();

    expect($response2['data']['name'])->toBe($user->name)
        ->and($response2['data']['uuid'])->toBe($user->uuid)
        ->and($response2['data']['currency'])->toBe($user->currency)
        ->and($response2['data']['hourly_rate'])->toBe($user->hourly_rate)
        ->and($response2['data'])->not->toHaveKey('id');
});
