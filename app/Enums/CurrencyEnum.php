<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum CurrencyEnum: string
{
    use EnumTrait;

    case USD = 'usd';
    case EUR = 'eur';
    case GBP = 'gbp';

    /**
     * @return string
     */
    public function symbol(): string
    {
        return match ($this) {
            self::USD => '$',
            self::EUR => '€',
            self::GBP => '£',
        };
    }
}
