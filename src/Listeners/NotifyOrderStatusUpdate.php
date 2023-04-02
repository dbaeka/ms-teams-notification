<?php

namespace Dbaeka\MsNotification\Listeners;

use DateTime;
use Dbaeka\MsNotification\Events\OrderStatusChanged;
use Dbaeka\MsNotification\Services\Client;
use Dbaeka\MsNotification\Services\CreateMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\ThrottlesExceptions;
use Illuminate\Support\Facades\Log;
use Throwable;

class NotifyOrderStatusUpdate implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private readonly Client $api_service
    ) {
    }

    public function handle(OrderStatusChanged $event): void
    {
        $message = app(CreateMessage::class)->execute($event->data);
        $success = $this->api_service->sendMessage($message);
        if (!$success) {
            $this->release(60);
        }
    }


    /**
     * Handle a job failure.
     * @codeCoverageIgnore
     */
    public function failed(OrderStatusChanged $event, Throwable $exception): void
    {
        Log::error("Failed to send order status update for uuid: {$event->data->order_uuid}. 
        Error: {$exception->getMessage()}");
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     * @codeCoverageIgnore
     */
    public function middleware(): array
    {
        return [(new ThrottlesExceptions(10, 5))->backoff(1)];
    }

    /**
     * Determine the time at which the job should timeout.
     * @codeCoverageIgnore
     */
    public function retryUntil(): DateTime
    {
        return now()->addMinutes(10);
    }
}
