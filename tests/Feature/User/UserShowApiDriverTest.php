<?php

use App\Models\User;
use App\Enums\CurrencyEnum;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('exchange.driver', 'api');
    Http::fake();
});

test('user can be seen when the base currency provided', function () {
    $user = User::factory()->create([
        'currency' => CurrencyEnum::GBP->value,
    ]);

    $userData = [
        $user,
        'currency' => CurrencyEnum::GBP->value,
    ];

    $response = $this->getJson(route('user.show', $userData))->json();

    expect($response['data']['name'])->toBe($user->name)
        ->and($response['data']['currency'])->toBe($user->currency)
        ->and($response['data']['hourly_rate'])->toBe($user->hourly_rate);
});

test('Http request is sent to exchange rate provider api', function () {
    $baseCurrency = CurrencyEnum::GBP->value;
    $targetCurrency = CurrencyEnum::USD->value;

    $user = User::factory()->create([
        'currency' => $baseCurrency,
    ]);

    $userData = [
        $user,
        'currency' => $targetCurrency,
    ];

    $this->getJson(route('user.show', $userData))->json();

    Http::assertSent(function ($request) use ($baseCurrency, $targetCurrency) {
        return $request->url() == config('services.exchangeratesapi.base_url')
            .'/v1/latest?access_key='.config('services.exchangeratesapi.api_key')
            .'&base='.strtoupper($baseCurrency)
            .'&symbols='
            .strtoupper($targetCurrency);
    });
});

test('error when api driver and cannot fetch correct exchange rate', function () {
    // must be different currency base !== target
    $user = User::factory()->create([
        'currency' => CurrencyEnum::USD->value,
    ]);

    $userData = [
        $user,
        'currency' => CurrencyEnum::GBP->value,
    ];

    $response = $this->getJson(route('user.show', $userData))->json();

    expect($response['error'])->toBe('Failed to fetch exchange rates from the API.');
});
