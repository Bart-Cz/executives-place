<?php

use App\Models\User;

test('new user can be stored', function () {
    $userData = User::factory()->make()->toArray();

    $response = $this->postJson(route('user.store'), $userData)->assertSuccessful();

    $this->assertDatabaseHas('users', $userData);
    foreach ($userData as $key => $value) {
        expect($response['data'][$key])->toBe($value);
    }
});

// I will skip most of the validation testing, apart from the ones that I think are important

test('fields are required', function () {
    $userData = User::factory()->make()->toArray();

    $userDataCopy = $userData;

    foreach ($userData as $key => $value) {
        $data = $userDataCopy;
        unset($data[$key]);
        $this->postJson(route('user.store'), $data)->assertInvalid($key);
    }
});

test('email must be unique', function () {
    $user1 = User::factory()->create();
    $userData = User::factory()->make([
        'email' => $user1->email,
    ])->toArray();

    $response = $this->postJson(route('user.store'), $userData)->assertInvalid('email')->json();

    expect($response['errors']['email'][0])->toBe('The email has already been taken.');
});
