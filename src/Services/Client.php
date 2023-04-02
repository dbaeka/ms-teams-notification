<?php

namespace Dbaeka\MsNotification\Services;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class Client
{
    public function __construct(
        protected string   $uri,
        protected int      $timeout = 10,
        protected null|int $retryTimes = null,
        protected null|int $retryMilliseconds = null,
    ) {
    }

    /**
     * @param array<string, mixed> $message
     */
    public function sendMessage(array $message): bool
    {
        $request = Http::acceptJson()->timeout(
            seconds: $this->timeout,
        );

        $request = $this->handleRetrySetup($request);

        $response = $request->post(url: $this->uri, data: $message);
        if (!$response->successful()) {
            return false;
        }
        return true;
    }

    private function handleRetrySetup(PendingRequest $request): PendingRequest
    {
        if (!is_null($this->retryTimes) && !is_null($this->retryMilliseconds)) {
            $request->retry(
                times: $this->retryTimes,
                sleepMilliseconds: $this->retryMilliseconds,
                when: function (Exception $exception): bool {
                    if ($exception instanceof ConnectionException ||
                        ($exception instanceof RequestException && $exception->response->status() === 429)
                    ) {
                        return true;
                    }
                    return false;
                },
                throw: false
            );
        }
        return $request;
    }
}
