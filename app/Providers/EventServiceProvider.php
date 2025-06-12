<?php
declare(strict_types=1);

namespace App\Providers;

use App\Listeners\NotificationFailedListener;
use App\Listeners\NotificationSentListener;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(
            NotificationFailed::class,
            NotificationFailedListener::class,
        );

        Event::listen(
            NotificationSent::class,
            NotificationSentListener::class,
        );
    }
}
