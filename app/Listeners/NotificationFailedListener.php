<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Interfaces\LoggableNotificationInterface;
use App\Services\NotificationService;
use Illuminate\Notifications\Events\NotificationFailed;

readonly class NotificationFailedListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private NotificationService $notificationService,
    )
    {

    }

    /**
     * Handle the event.
     */
    public function handle(NotificationFailed $event): void
    {
        if ($event->notification instanceof LoggableNotificationInterface) {
            $this->notificationService->failed($event->notification->getModel());
        }
    }
}
