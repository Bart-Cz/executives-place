<?php

namespace App\Providers;

use App\Providers\Managers\ExchangeServiceManager;
use App\Services\Exchange\ExchangeService;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class ExchangeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ExchangeService::class, function () {
            return (new ExchangeServiceManager)->resolveDriver();
        });
    }
}
