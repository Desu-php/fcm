<?php

namespace App\Console\Commands;

use App\DTO\FcmMessageDTO;
use App\Enums\NotificationJobStatusEnum;
use App\Enums\NotificationStatusEnum;
use App\Models\Device;
use App\Models\Notification;
use App\Models\NotificationJob;
use App\Notifications\PushNotification;
use Illuminate\Console\Command;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class NotificationJobCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        NotificationJob::query()
            ->where('scheduled_at', '>=', now())
            ->pending()
            ->each(function (NotificationJob $job) {
                DB::transaction(function () use ($job) {
                    $job->update([
                        'status' => NotificationJobStatusEnum::Working
                    ]);

                    $this->sendNotifications($job);

                    $job->update([
                        'status' => NotificationJobStatusEnum::Completed
                    ]);
                });
            });
    }

    private function sendNotifications(NotificationJob $job): void
    {
        Device::query()
            ->select('devices.*')
            ->leftJoin('notifications as n',
                fn(JoinClause $join) => $join->on('n.notifiable_id', 'devices.id')
                    ->where('n.notifiable_type', Device::class)
                    ->where('notification_job_id', $job->id)
            )
            ->whereNull('n.id')
            ->each(function (Device $device) use ($job) {
                $notification = Notification::create([
                    'notifiable_id' => $device->id,
                    'notifiable_type' => Device::class,
                    'data' => new FcmMessageDTO(
                        title: $job->title,
                        message: $job->body
                    ),
                    'status' => NotificationStatusEnum::Sent,
                    'notification_job_id' => $job->id
                ]);

                $device->notify(new PushNotification($notification));
            });
    }
}
