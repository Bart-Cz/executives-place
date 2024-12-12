<?php

use App\Models\User;

// I will skip most of the validation testing, apart from the ones that I think are important

test('currency field is required', function () {
    $user = User::factory()->create();

    $response = $this->postJson(route('user.show', $user))->assertInvalid('currency')->json();

    expect($response['errors']['currency'][0])->toBe('The currency field is required.');
});

test('currency field must be of CurrencyEnum type value', function () {
    $user = User::factory()->create();

    $userData = [
        'currency' => 'dollar',
    ];

    $response = $this->postJson(route('user.show', $user), $userData)->assertInvalid('currency')->json();

    expect($response['errors']['currency'][0])->toBe('The selected currency is invalid.');
});
