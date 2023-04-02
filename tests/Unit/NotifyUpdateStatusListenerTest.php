<?php

namespace Dbaeka\MsNotification\Tests\Unit;

;

use Dbaeka\MsNotification\Events\OrderStatusChanged;
use Dbaeka\MsNotification\Listeners\NotifyOrderStatusUpdate;
use Dbaeka\MsNotification\Services\Client;
use Dbaeka\MsNotification\Services\CreateMessage;
use Dbaeka\MsNotification\Services\StatusUpdateData;
use Dbaeka\MsNotification\Tests\TestCase;
use Mockery;

class NotifyUpdateStatusListenerTest extends TestCase
{
    public function testHandleSuccessfully(): void
    {
        $data = new StatusUpdateData(
            order_uuid: fake()->uuid(),
            old_order_status: fake()->word(),
            new_order_status: fake()->word(),
            updated_time: now()->toImmutable()
        );
        $event = new OrderStatusChanged($data);

        $this->mock(Client::class)
            ->shouldReceive('sendMessage')
            ->andReturnTrue()
            ->once();

        $this->mock(CreateMessage::class)
            ->shouldReceive('execute')
            ->andReturn(['message' => 'Hello World'])
            ->once();

        /** @var NotifyOrderStatusUpdate $listener */
        $listener = app(NotifyOrderStatusUpdate::class);
        $listener->handle($event);
    }

    public function testHandleFailed(): void
    {
        $data = new StatusUpdateData(
            order_uuid: fake()->uuid(),
            old_order_status: fake()->word(),
            new_order_status: fake()->word(),
            updated_time: now()->toImmutable()
        );
        $event = new OrderStatusChanged($data);

        $this->mock(Client::class)
            ->shouldReceive('sendMessage')
            ->andReturnFalse()
            ->once();

        $this->mock(CreateMessage::class)
            ->shouldReceive('execute')
            ->andReturn(['message' => 'Hello World'])
            ->once();

        $client = app(Client::class);
        $listener = Mockery::mock(NotifyOrderStatusUpdate::class, [$client])->makePartial();
        $listener->shouldReceive('release')->once();
        $listener->handle($event);
    }
}
