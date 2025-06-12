<?php

namespace App\Enums;

enum NotificationStatusEnum: string
{
    case Pending = 'pending';
    case Sent = 'sent';
    case Delivered = 'delivered';
    case Failed = 'failed';
}
