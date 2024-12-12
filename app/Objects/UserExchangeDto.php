<?php

namespace App\Objects;

class UserExchangeDto
{
    /**
     * @param float $baseHourlyRate
     * @param string $baseCurrency
     * @param string $targetCurrency
     */
    public function __construct(private float $baseHourlyRate, private string $baseCurrency, private string $targetCurrency) {}

    /**
     * @return float
     */
    public function getBaseHourlyRate(): float
    {
        return $this->baseHourlyRate;
    }

    /**
     * @return string
     */
    public function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }

    /**
     * @return string
     */
    public function getTargetCurrency(): string
    {
        return $this->targetCurrency;
    }
}
