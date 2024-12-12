<?php

use App\Enums\CurrencyEnum;
use App\Models\User;
use App\Services\Exchange\LocalExchange\LocalExchangeService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    Config::set('exchange.driver', 'local');
});

test('user can be seen when the base currency provided', function () {
    $user = User::factory()->create([
        'currency' => CurrencyEnum::GBP->value,
    ]);

    $userData = [
        'currency' => CurrencyEnum::GBP->value,
    ];

    $response = $this->postJson(route('user.show', $user), $userData)->json();

    expect($response['data']['name'])->toBe($user->name)
        ->and($response['data']['currency'])->toBe($user->currency)
        ->and($response['data']['hourly_rate'])->toBe($user->hourly_rate);
});

test('user can be seen when different currency provided', function () {
    $baseCurrency = CurrencyEnum::GBP->value;
    $targetCurrency = CurrencyEnum::USD->value;

    $user = User::factory()->create([
        'currency' => $baseCurrency,
    ]);

    $userData = [
        'currency' => $targetCurrency,
    ];

    $exchangeRate = (new LocalExchangeService)->getRate($baseCurrency, $targetCurrency);

    $response = $this->postJson(route('user.show', $user), $userData)->json();

    expect($response['data']['name'])->toBe($user->name)
        ->and($response['data']['currency'])->toBe($targetCurrency)
        ->and($response['data']['hourly_rate'])->toBe(number_format(round($user->hourly_rate * $exchangeRate, 2), 2));
});

test('error when local driver and cannot fetch correct exchange rate from db', function () {
    // must be different currency base !== target
    // no exchange for below currencies
    DB::table('exchange_rates')
        ->where('base_currency', CurrencyEnum::USD->value)
        ->where('target_currency', CurrencyEnum::GBP->value)
        ->delete();

    $user = User::factory()->create([
        'currency' => CurrencyEnum::USD->value,
    ]);

    $userData = [
        'currency' => CurrencyEnum::GBP->value,
    ];

    $response = $this->postJson(route('user.show', $user), $userData)->json();

    expect($response['error'])->toBe('Failed to fetch exchange rates from the local driver.');
});
