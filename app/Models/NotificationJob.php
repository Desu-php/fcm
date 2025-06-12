<?php
declare(strict_types=1);

namespace App\Models;

use App\Enums\NotificationJobStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NotificationJob extends Model
{
    protected $fillable = [
        'title',
        'body',
        'scheduled_at',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'status' => NotificationJobStatusEnum::class,
        ];
    }

    public function scopePending(Builder $builder): void
    {
        $builder->where('status', NotificationJobStatusEnum::Pending);
    }
}
