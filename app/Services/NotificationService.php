<?php
declare(strict_types=1);

namespace App\Services;

use App\Enums\NotificationStatusEnum;
use App\Models\Notification;

class NotificationService
{
    public function failed(Notification $notification): void
    {
        $this->updateStatus($notification, NotificationStatusEnum::Failed);
    }

    public function delivered(Notification $notification): void
    {
        $this->updateStatus($notification, NotificationStatusEnum::Delivered);
    }

    public function updateStatus(Notification $notification, NotificationStatusEnum $status): void
    {
        $notification->update([
            'status' => $status
        ]);
    }
}
