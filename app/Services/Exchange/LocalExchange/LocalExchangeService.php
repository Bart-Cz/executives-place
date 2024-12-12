<?php

namespace App\Services\Exchange\LocalExchange;

use App\Services\Exchange\ExchangeService;
use Illuminate\Support\Facades\DB;

class LocalExchangeService extends ExchangeService
{
    public function __construct() {}

    /**
     * @param string $baseCurrency
     * @param string $targetCurrency
     * @return float
     * @throws \Exception
     */
    public function getRate(string $baseCurrency, string $targetCurrency): float
    {
        // consider model, but query builder better for performance
        $rate = DB::table('exchange_rates')->where('base_currency', $baseCurrency)->where('target_currency', $targetCurrency)->value('rate');

        if (! is_null($rate)) {
            return $rate;
        }

        throw new \Exception('Failed to fetch exchange rates from the local driver.');
    }
}
