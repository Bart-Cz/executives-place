<?php

namespace App\Services\Exchange;

use Illuminate\Support\Facades\Cache;

abstract class ExchangeService
{
    public function convertRate(float $amount, string $baseCurrency, string $targetCurrency): float
    {
        if ($baseCurrency === $targetCurrency) {
            return $amount;
        }

        // should not be needed as we validate db inserts min:0
        if ($amount <= 0) {
            return 0;
        }

        $cacheKey = "exchange_rate_{$baseCurrency}_{$targetCurrency}";

        // consider cache when drivers switched - possibly different cache key for each driver
        $newRate = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($baseCurrency, $targetCurrency) {
            return $this->getRate($baseCurrency, $targetCurrency);
        });

        return round($amount * $newRate, 2);
    }

    abstract public function getRate(string $baseCurrency, string $targetCurrency): float;
}
