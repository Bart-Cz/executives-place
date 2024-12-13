<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->userData = $this->user->toArray();
});

test('user can be deleted', function () {
    $this->assertDatabaseHas('users', $this->userData);

    $response = $this->deleteJson(route('user.destroy', $this->user))->assertSuccessful()->json();

    $this->assertDatabaseMissing('users', $this->userData);
    expect($response['message'])->toBe('User deleted successfully.');
});

test('404 if user does not exist', function () {
    $this->assertDatabaseHas('users', $this->userData);

    $this->user->delete();

    $this->assertDatabaseMissing('users', $this->userData);

    $response = $this->deleteJson(route('user.destroy', $this->user))->assertStatus(404)->json();

    expect($response['message'])->toContain('No query results for model [App\Models\User]');
});
