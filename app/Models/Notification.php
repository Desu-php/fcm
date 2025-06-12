<?php
declare(strict_types=1);

namespace App\Models;

use App\Enums\NotificationStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    protected $fillable = [
        'status',
        'notifiable_id',
        'notifiable_type',
        'data',
        'notification_job_id'
    ];

    protected function casts(): array
    {
        return [
            'status' => NotificationStatusEnum::class,
            'data' => 'json',
        ];
    }

    public function notificationJob(): BelongsTo
    {
        return $this->belongsTo(NotificationJob::class);
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'notifiable_id')->where('');
    }
}
