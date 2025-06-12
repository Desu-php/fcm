<?php
declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Notification;

interface LoggableNotificationInterface
{
    public function getModel(): Notification;
}
