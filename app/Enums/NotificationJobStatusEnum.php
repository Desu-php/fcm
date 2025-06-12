<?php

namespace App\Enums;

enum NotificationJobStatusEnum: string
{
    case Pending = 'pending';
    case Working = 'working';
    case Failed = 'failed';
    case Completed = 'completed';
}
