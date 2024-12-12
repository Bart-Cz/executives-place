<?php

namespace App\Traits;

trait EnumTrait
{
    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        $cases = static::cases();

        return array_column(
            $cases,
            /** @phpstan-ignore-next-line */
            $cases[0] instanceof \BackedEnum ? 'value' : 'name'
        );
    }
}
