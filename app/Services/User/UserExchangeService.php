<?php

namespace App\Services\User;

use App\Models\User;
use App\Objects\UserExchangeDto;
use App\Services\Exchange\ExchangeService;

class UserExchangeService
{
    /**
     * @param ExchangeService $exchangeService
     */
    public function __construct(protected ExchangeService $exchangeService) {}

    /**
     * @param User $user
     * @param UserExchangeDto $userExchangeDto
     * @return User
     */
    public function getRefactoredUserWithProvidedCurrency(User $user, UserExchangeDto $userExchangeDto): User
    {
        $targetCurrency = $userExchangeDto->getTargetCurrency();

        $user->currency = $targetCurrency;

        $user->hourly_rate = $this->exchangeService->convertRate(
            $userExchangeDto->getBaseHourlyRate(),
            $userExchangeDto->getBaseCurrency(),
            $targetCurrency
        );

        return $user;
    }
}
