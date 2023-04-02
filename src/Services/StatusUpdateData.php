<?php

namespace Dbaeka\MsNotification\Services;

use Carbon\CarbonImmutable;

readonly class StatusUpdateData
{
    public function __construct(
        public string          $order_uuid,
        public string          $old_order_status,
        public string          $new_order_status,
        public CarbonImmutable $updated_time
    ) {
    }
}
