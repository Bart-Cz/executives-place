<?php

use App\Services\Exchange\ApiExchange\ApiExchangeService;
use App\Services\Exchange\LocalExchange\LocalExchangeService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

test('it is using Cache', function () {
    Http::fake();
    Cache::spy();

    $service = new ApiExchangeService;

    $service->convertRate(10, 'USD', 'EUR');

    Cache::shouldHaveReceived('remember')
        ->once()
        ->with('exchange_rate_USD_EUR', \Mockery::any(), \Mockery::any());
});

test('it caches the rate', function () {
    Config::set('exchange.driver', 'local');

    // Cache is turned off, so to turn it on here
    Config::set('cache.default', 'array');

    // local driver used for the testing
    $service = new LocalExchangeService;

    $service->convertRate(10, 'EUR', 'USD');

    $this->assertTrue(Cache::has('exchange_rate_EUR_USD'));
});
