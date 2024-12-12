<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExchangeRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rates = [
            ['base_currency' => 'GBP', 'target_currency' => 'USD', 'rate' => 1.3],
            ['base_currency' => 'GBP', 'target_currency' => 'EUR', 'rate' => 1.1],
            ['base_currency' => 'EUR', 'target_currency' => 'GBP', 'rate' => 0.9],
            ['base_currency' => 'EUR', 'target_currency' => 'USD', 'rate' => 1.2],
            ['base_currency' => 'USD', 'target_currency' => 'GBP', 'rate' => 0.7],
            ['base_currency' => 'USD', 'target_currency' => 'EUR', 'rate' => 0.8],
        ];

        foreach ($rates as &$row) {
            $row['created_at'] = now();
            $row['updated_at'] = now();
        }

        DB::table('exchange_rates')->insert($rates);
    }
}
