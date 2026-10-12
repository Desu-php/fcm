<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\DTO\FcmMessageDTO;
use App\Interfaces\FcmNotifiableInterface;
use App\Interfaces\FcmNotificationInterface;
use App\Interfaces\FcmTransportInterface;
use App\Notifications\Channels\FcmChannel;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class FcmChannelTest extends TestCase
{
    public function test_channel_passes_recipient_and_message_to_transport(): void
    {
        $message = new FcmMessageDTO('Hello', 'World');
        $transport = $this->createMock(FcmTransportInterface::class);
        $transport->expects($this->once())
            ->method('sendMessage')
            ->with('device-token', $this->identicalTo($message));

        (new FcmChannel($transport))->send(
            $this->notifiable(),
            $this->notification($message),
        );
    }

    public function test_channel_propagates_the_original_transport_failure(): void
    {
        $message = new FcmMessageDTO('Hello', 'World');
        $failure = new RuntimeException('Transport unavailable');
        $transport = $this->createMock(FcmTransportInterface::class);
        $transport->expects($this->once())
            ->method('sendMessage')
            ->with('device-token', $this->identicalTo($message))
            ->willThrowException($failure);

        try {
            (new FcmChannel($transport))->send(
                $this->notifiable(),
                $this->notification($message),
            );
        } catch (RuntimeException $caughtFailure) {
            $this->assertSame($failure, $caughtFailure);

            return;
        }

        $this->fail('The channel must propagate transport failures.');
    }

    private function notifiable(): FcmNotifiableInterface
    {
        $notifiable = $this->createStub(FcmNotifiableInterface::class);
        $notifiable->method('routeNotificationForFcm')->willReturn('device-token');

        return $notifiable;
    }

    private function notification(FcmMessageDTO $message): FcmNotificationInterface
    {
        $notification = $this->createStub(FcmNotificationInterface::class);
        $notification->method('toFcm')->willReturn($message);

        return $notification;
    }
}
