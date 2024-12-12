<?php

namespace App\Providers\Managers;

use App\Services\Exchange\ApiExchange\ApiExchangeService;
use App\Services\Exchange\ExchangeService;
use App\Services\Exchange\LocalExchange\LocalExchangeService;
use InvalidArgumentException;

class ExchangeServiceManager
{
    public function resolveDriver(): ExchangeService
    {
        $driver = config('exchange.driver');

        $driverMethod = 'create'.ucfirst($driver).'ExchangeService';

        if (! method_exists($this, $driverMethod)) {
            throw new InvalidArgumentException("Driver [{$driver}] is not supported.");
        }

        return $this->{$driverMethod}();
    }

    protected function createApiExchangeService(): ExchangeService
    {
        return new ApiExchangeService;
    }

    protected function createLocalExchangeService(): ExchangeService
    {
        return new LocalExchangeService;
    }
}
