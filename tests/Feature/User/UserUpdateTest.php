<?php

use App\Models\User;

test('user can be updated', function () {
    $user = User::factory()->create([
        'name' => 'Test User',
    ]);
    $userData = User::factory()->make([
        'name' => 'John Doe',
    ])->toArray();

    expect($user->name)->toBe('Test User');

    $response = $this->putJson(route('user.update', $user), $userData)->assertSuccessful();

    $this->assertDatabaseHas('users', $userData);
    foreach ($userData as $key => $value) {
        expect($response['data'][$key])->toBe($value);
    }

    expect($user->refresh()->name)->toBe('John Doe');
});

// I will skip most of the validation testing, apart from the ones that I think are important

test('fields are required', function () {
    $user = User::factory()->create([
        'name' => 'Test User',
    ]);
    $userData = User::factory()->make()->toArray();
    $userDataCopy = $userData;
    // tested separately
    unset($userDataCopy['email']);
    unset($userData['email']);

    foreach ($userData as $key => $value) {
        $data = $userDataCopy;
        unset($data[$key]);
        $this->putJson(route('user.update', $user), $data)->assertInvalid($key);
    }
});

test('email must be unique', function () {
    $user1 = User::factory()->create([
        'email' => 'extremelydifficultemail@example.com',
    ]);
    $user2 = User::factory()->create();

    $userData = User::factory()->make([
        'email' => $user1->email,
    ])->toArray();

    $response = $this->putJson(route('user.update', $user2), $userData)->assertStatus(422)->json();

    expect($response['error']['email'][0])->toBe('The email has already been taken.');
});

test('email must be unique, but for the same user does not throw error', function () {
    $user1 = User::factory()->create([
        'email' => 'extremelydifficultemail@example.com',
    ]);

    $userData = User::factory()->make([
        'name' => 'Different Name',
        'email' => $user1->email,
    ])->toArray();

    $response = $this->putJson(route('user.update', $user1), $userData)->assertSuccessful();

    expect($response['data']['email'])->toBe('extremelydifficultemail@example.com')
        ->and($user1->email)->toBe('extremelydifficultemail@example.com');
});
