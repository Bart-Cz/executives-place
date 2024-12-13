<?php

namespace App\Providers;

use App\Providers\Managers\ExchangeServiceManager;
use App\Services\Exchange\ExchangeService;
use Illuminate\Support\ServiceProvider;

class ExchangeServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ExchangeService::class, function () {
            return (new ExchangeServiceManager)->resolveDriver();
        });
    }
}
