<?php

return [
    'ms_teams_webhook_url' => env('MS_TEAMS_WEBHOOK_URL', ''),
    'timeout' => env('MS_TEAMS_WEBHOOK_TIMEOUT', 10),
    'retry_times' => env('MS_TEAMS_WEBHOOK_RETRY_TIMES', null),
    'retry_milliseconds' => env('MS_TEAMS_WEBHOOK_RETRY_MILLISECONDS', null),
];
