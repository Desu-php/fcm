<?php
declare(strict_types=1);

namespace App\Notifications;

use App\DTO\FcmMessageDTO;
use App\Interfaces\FcmNotificationInterface;
use App\Interfaces\LoggableNotificationInterface;
use App\Notifications\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Notification as ModelNotification;

class PushNotification extends Notification implements ShouldQueue, LoggableNotificationInterface, FcmNotificationInterface
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private readonly ModelNotification $notification,
    )
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [FcmChannel::class];
    }


    public function getModel(): ModelNotification
    {
        return $this->notification;
    }

    public function toFcm(): FcmMessageDTO
    {
        return new FcmMessageDTO(
            title: $this->notification->data['title'],
            message: $this->notification->data['message'],
        );
    }
}
