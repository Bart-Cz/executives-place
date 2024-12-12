<?php

use App\Enums\CurrencyEnum;
use App\Objects\UserExchangeDto;

test('User Exchange Data Transfer Object works', function () {
    $baseCurrency = CurrencyEnum::GBP->value;
    $targetCurrency = CurrencyEnum::USD->value;

    $userExchangeDto = new UserExchangeDto(15.22, $baseCurrency, $targetCurrency);

    expect($userExchangeDto->getBaseHourlyRate())->toBe(15.22)
        ->and($userExchangeDto->getBaseCurrency())->toBe($baseCurrency)
        ->and($userExchangeDto->getTargetCurrency())->toBe($targetCurrency);
});
