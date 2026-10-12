<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Clients\FcmClient;
use App\DTO\FcmMessageDTO;
use App\Interfaces\FcmTransportInterface;
use App\Models\Device;
use App\Models\Notification;
use App\Notifications\Channels\FcmChannel;
use App\Notifications\PushNotification;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class FcmClientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.fcm.server_key', 'test-server-key');
        Http::preventStrayRequests();
    }

    public function test_existing_send_api_preserves_request_and_response(): void
    {
        Http::fake([
            'https://fcm.googleapis.com/fcm/send' => Http::response(['success' => 1]),
        ]);

        $data = ['title' => 'Hello', 'message' => 'World', 'custom' => 'value'];
        $response = (new FcmClient)->send('device-token', $data);

        $this->assertSame(['success' => 1], $response->json());
        Http::assertSent(fn (Request $request): bool => $request->method() === 'POST'
            && $request->url() === 'https://fcm.googleapis.com/fcm/send'
            && $request->hasHeader('Authorization', 'key=test-server-key')
            && $request->data() === ['to' => 'device-token', 'data' => $data]
        );
        Http::assertSentCount(1);
    }

    public function test_laravel_resolves_transport_and_sends_a_push_notification(): void
    {
        Http::fake([
            'https://fcm.googleapis.com/fcm/send' => Http::response(['success' => 1]),
        ]);

        $this->assertInstanceOf(FcmClient::class, $this->app->make(FcmTransportInterface::class));

        $notification = new PushNotification(new Notification([
            'data' => ['title' => 'Hello', 'message' => 'World'],
        ]));
        $device = new Device(['device_token' => 'device-token']);

        $this->app->make(FcmChannel::class)->send($device, $notification);

        Http::assertSent(fn (Request $request): bool => $request->data() === [
            'to' => 'device-token',
            'data' => ['title' => 'Hello', 'message' => 'World'],
        ]
        );
        Http::assertSentCount(1);
    }

    public function test_transport_uses_the_dto_serialization_contract(): void
    {
        Http::fake([
            'https://fcm.googleapis.com/fcm/send' => Http::response(['success' => 1]),
        ]);

        $message = new class('Hello', 'World') extends FcmMessageDTO
        {
            /** @return array<string, string> */
            public function toArray(): array
            {
                return [...parent::toArray(), 'custom' => 'value'];
            }
        };

        $this->app->make(FcmTransportInterface::class)->sendMessage('device-token', $message);

        Http::assertSent(fn (Request $request): bool => $request->data() === ['to' => 'device-token', 'data' => $message->toArray()]
        );
        Http::assertSentCount(1);
    }

    #[DataProvider('httpErrorStatuses')]
    public function test_http_errors_are_propagated(int $status, bool $useTypedTransport): void
    {
        Http::fake([
            'https://fcm.googleapis.com/fcm/send' => Http::response([], $status),
        ]);

        $this->expectException(RequestException::class);

        if ($useTypedTransport) {
            $this->app->make(FcmTransportInterface::class)->sendMessage(
                'device-token', new FcmMessageDTO('Hello', 'World'),
            );

            return;
        }

        (new FcmClient)->send('device-token', ['title' => 'Hello', 'message' => 'World']);
    }

    /** @return array<string, array{int, bool}> */
    public static function httpErrorStatuses(): array
    {
        return [
            'existing API client error' => [400, false],
            'existing API server error' => [500, false],
            'typed transport client error' => [400, true],
            'typed transport server error' => [500, true],
        ];
    }

    public function test_connection_errors_are_propagated(): void
    {
        Http::fake([
            'https://fcm.googleapis.com/fcm/send' => Http::failedConnection(),
        ]);

        $this->expectException(ConnectionException::class);

        (new FcmClient)->send('device-token', ['title' => 'Hello', 'message' => 'World']);
    }

    public function test_typed_transport_propagates_connection_errors(): void
    {
        Http::fake([
            'https://fcm.googleapis.com/fcm/send' => Http::failedConnection(),
        ]);

        $this->expectException(ConnectionException::class);

        $this->app->make(FcmTransportInterface::class)->sendMessage(
            'device-token', new FcmMessageDTO('Hello', 'World'),
        );
    }
}
