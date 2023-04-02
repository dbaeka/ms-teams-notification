<?php

namespace Dbaeka\MsNotification\Events;

use Dbaeka\MsNotification\Services\StatusUpdateData;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public StatusUpdateData $data
    ) {
    }
}
