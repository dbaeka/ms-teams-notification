<?php

namespace Dbaeka\MsNotification\Tests\Unit;

use Dbaeka\MsNotification\Services\Client;
use Dbaeka\MsNotification\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class ClientTest extends TestCase
{
    public function testSendMessageSuccessfully(): void
    {
        $service = app(Client::class);
        Http::fake([
            '*' => Http::response('')
        ]);
        $data = [
            "message" => "Hello World"
        ];
        $success = $service->sendMessage($data);
        $this->assertTrue($success);
    }

    public function testSendMessageFail(): void
    {
        $service = app(Client::class);
        Http::fake([
            '*' => Http::response('', 400)
        ]);
        $data = [
            "message" => "Hello World",
        ];
        $success = $service->sendMessage($data);
        $this->assertFalse($success);
    }

    public function testSendMessageExceptionNoRetry(): void
    {
        config([
            'ms_notification.retry_times' => 3,
            'ms_notification.retry_milliseconds' => 0
        ]);
        $service = app(Client::class);
        $data = [
            "message" => "Hello World"
        ];
        Http::fake([
            '*' => Http::response('', 400)
        ]);
        $success = $service->sendMessage($data);
        Http::assertSentCount(1);
        $this->assertFalse($success);
    }

    public function testSendMessageRetry(): void
    {
        config([
            'ms_notification.retry_times' => 3,
            'ms_notification.retry_milliseconds' => 0
        ]);
        $service = app(Client::class);
        $data = [
            "message" => "Hello World"
        ];
        Http::fake([
            '*' => Http::response('', 429)
        ]);
        $success = $service->sendMessage($data);
        Http::assertSentCount(3);
        $this->assertFalse($success);
    }
}
