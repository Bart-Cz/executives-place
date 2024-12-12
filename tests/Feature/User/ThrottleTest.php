<?php

use App\Models\User;
use Illuminate\Support\Facades\Config;

// can be skipped as resources consuming
test('throttle on api endpoints', function () {
    // Cache is turned off, so to turn it on here -> for throttle
    Config::set('cache.default', 'array');

    $user = User::factory()->create();
    $userData = User::factory()->make()->toArray();

    // throttle set to 20 -> first 20 are successful
    for ($i = 0; $i < 20; $i++) {
        $this->putJson(route('user.update', $user), $userData)->assertSuccessful();
    }

    // 21st should 429
    $this->putJson(route('user.update', $user), $userData)->assertTooManyRequests();
});
