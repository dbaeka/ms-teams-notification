<?php

namespace Dbaeka\MsNotification\Tests\Unit;

;

use Dbaeka\MsNotification\Services\CreateMessage;
use Dbaeka\MsNotification\Services\StatusUpdateData;
use Dbaeka\MsNotification\Tests\TestCase;

class CreateMessageTest extends TestCase
{
    public function testCreateMessage(): void
    {
        $data = new StatusUpdateData(
            order_uuid: fake()->uuid(),
            old_order_status: fake()->word(),
            new_order_status: fake()->word(),
            updated_time: now()->toImmutable()
        );

        $action = app(CreateMessage::class);
        $result = $action->execute($data);
        $this->assertIsArray($result);
        /** @var string $result */
        $result = json_encode($result);
        $this->assertStringContainsString($data->order_uuid, $result);
        $this->assertStringContainsString($data->new_order_status, $result);
        $this->assertStringContainsString($data->old_order_status, $result);
        $this->assertStringContainsString($data->updated_time, $result);
    }
}
