<?php

namespace App\Services\Exchange\ApiExchange;

use App\Services\Exchange\ExchangeService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiExchangeService extends ExchangeService
{
    protected string $baseUrl;

    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.exchangeratesapi.base_url');
        $this->apiKey = config('services.exchangeratesapi.api_key');
    }

    /**
     * @param string $baseCurrency
     * @param string $targetCurrency
     * @return float
     * @throws \Exception
     */
    public function getRate(string $baseCurrency, string $targetCurrency): float
    {
        $endpoint = $this->baseUrl.'/v1/latest';

        $symbol = strtoupper($targetCurrency);

        $params = [
            'access_key' => $this->apiKey,
            'base' => strtoupper($baseCurrency),
            'symbols' => $symbol,
        ];

        $response = $this->request('GET', $endpoint, $params);

        if (isset($response['rates'][$symbol])) {
            return $response['rates'][$symbol];
        }

        throw new \Exception('Failed to fetch exchange rates from the API.');
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array<string, mixed> $params
     * @return null|array<string, mixed>
     * @throws \Exception
     */
    protected function request(string $method, string $endpoint, array $params = []): ?array
    {
        // try/catch for not exposing anything when exception thrown by api call (e.g. Guzzle issue)
        try {
            $response = Http::withQueryParameters($params)
                ->{$method}($endpoint);
        } catch (\Exception $exception) {
            // potentially sentry or other monitoring tool
            Log::channel('api_calls')->error($exception->getMessage());

            throw new \Exception('API request failed.');
        }

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('API request unsuccessful.');
    }
}
