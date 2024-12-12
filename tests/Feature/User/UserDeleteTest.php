<?php

use App\Models\User;

test('user can be deleted', function () {
    $user = User::factory()->create();
    $userData = $user->toArray();

    $this->assertDatabaseHas('users', $userData);

    $response = $this->deleteJson(route('user.destroy', $user))->assertSuccessful()->json();

    $this->assertDatabaseMissing('users', $userData);
    expect($response['message'])->toBe('User deleted successfully.');
});

test('404 if user does not exist', function () {
    $user = User::factory()->create();
    $userData = $user->toArray();

    $this->assertDatabaseHas('users', $userData);

    $user->delete();

    $this->assertDatabaseMissing('users', $userData);

    $response = $this->deleteJson(route('user.destroy', $user))->assertStatus(404)->json();

    expect($response['message'])->toContain('No query results for model [App\Models\User]');
});
