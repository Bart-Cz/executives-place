<?php

use App\Enums\CurrencyEnum;
use App\Services\Exchange\ApiExchange\ApiExchangeService;
use App\Services\Exchange\LocalExchange\LocalExchangeService;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake();

    $this->baseCurrency = CurrencyEnum::GBP->value;
    $this->targetCurrency = CurrencyEnum::USD->value;
});

test('convertRate method works correctly for ApiExchangeService', function () {
    $apiExchangeServiceMock = Mockery::mock(ApiExchangeService::class)->makePartial();
    $apiExchangeServiceMock->shouldReceive('getRate')
        ->with($this->baseCurrency, $this->targetCurrency)
        ->andReturn(1.35);

    $result1 = $apiExchangeServiceMock->convertRate(10.2, $this->baseCurrency, $this->targetCurrency);
    $result2 = $apiExchangeServiceMock->convertRate(0, $this->baseCurrency, $this->targetCurrency);
    $result3 = $apiExchangeServiceMock->convertRate(-3, $this->baseCurrency, $this->targetCurrency);

    $this->assertEquals(13.77, $result1);
    $this->assertEquals(0, $result2);
    $this->assertEquals(0, $result3);
});

test('convertRate method works correctly for LocalExchangeService', function () {
    $apiExchangeServiceMock = Mockery::mock(LocalExchangeService::class)->makePartial();
    $apiExchangeServiceMock->shouldReceive('getRate')
        ->with($this->baseCurrency, $this->targetCurrency)
        ->andReturn(1.2225);

    $result1 = $apiExchangeServiceMock->convertRate(10.2, $this->baseCurrency, $this->targetCurrency);
    $result2 = $apiExchangeServiceMock->convertRate(0, $this->baseCurrency, $this->targetCurrency);
    $result3 = $apiExchangeServiceMock->convertRate(-3, $this->baseCurrency, $this->targetCurrency);

    $this->assertEquals(12.47, $result1);
    $this->assertEquals(0, $result2);
    $this->assertEquals(0, $result3);
});

test('convertRate method still works correctly for ApiExchangeService if xch rate is 0', function () {
    $apiExchangeServiceMock = Mockery::mock(ApiExchangeService::class)->makePartial();
    $apiExchangeServiceMock->shouldReceive('getRate')
        ->with($this->baseCurrency, $this->targetCurrency)
        ->andReturn(0);

    $result1 = $apiExchangeServiceMock->convertRate(10.2, $this->baseCurrency, $this->targetCurrency);

    $this->assertEquals(0, $result1);
});

test('convertRate method still works correctly for LocalExchangeService if xch rate is 0', function () {
    $apiExchangeServiceMock = Mockery::mock(LocalExchangeService::class)->makePartial();
    $apiExchangeServiceMock->shouldReceive('getRate')
        ->with($this->baseCurrency, $this->targetCurrency)
        ->andReturn(0);

    $result1 = $apiExchangeServiceMock->convertRate(10.2, $this->baseCurrency, $this->targetCurrency);

    $this->assertEquals(0, $result1);
});
