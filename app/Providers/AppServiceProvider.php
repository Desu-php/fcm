<?php

declare(strict_types=1);

namespace App\Providers;

use App\Clients\FcmClient;
use App\Interfaces\FcmTransportInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FcmTransportInterface::class, FcmClient::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
