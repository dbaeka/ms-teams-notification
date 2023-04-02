<?php

namespace Dbaeka\MsNotification\Providers;

use Dbaeka\MsNotification\Events\OrderStatusChanged;
use Dbaeka\MsNotification\Listeners\NotifyOrderStatusUpdate;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderStatusChanged::class => [
            NotifyOrderStatusUpdate::class,
        ]
    ];
}
