<?php

namespace App\Objects;

class UserExchangeDto
{
    public function __construct(private float $baseHourlyRate, private string $baseCurrency, private string $targetCurrency) {}

    public function getBaseHourlyRate(): float
    {
        return $this->baseHourlyRate;
    }

    public function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }

    public function getTargetCurrency(): string
    {
        return $this->targetCurrency;
    }
}
