<?php

namespace Dbaeka\MsNotification\Tests\Unit;

;

use Dbaeka\MsNotification\Events\OrderStatusChanged;
use Dbaeka\MsNotification\Listeners\NotifyOrderStatusUpdate;
use Dbaeka\MsNotification\Services\StatusUpdateData;
use Dbaeka\MsNotification\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class OrderStatusChangeEventTest extends TestCase
{
    public function testEventDispatched(): void
    {
        Event::fake();
        $data = new StatusUpdateData(
            order_uuid: fake()->uuid(),
            old_order_status: fake()->word(),
            new_order_status: fake()->word(),
            updated_time: now()->toImmutable()
        );
        OrderStatusChanged::dispatch($data);
        Event::assertDispatched(
            OrderStatusChanged::class,
            function (OrderStatusChanged $event) use ($data): bool {
                $this->assertSame($data, $event->data);
                return true;
            }
        );

        Event::assertListening(
            OrderStatusChanged::class,
            NotifyOrderStatusUpdate::class
        );
    }
}
