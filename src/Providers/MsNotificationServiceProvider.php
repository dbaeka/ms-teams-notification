<?php

namespace Dbaeka\MsNotification\Providers;

use Dbaeka\MsNotification\Services\Client;
use Illuminate\Support\ServiceProvider;

class MsNotificationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (!app()->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../../config/ms_notification.php', 'ms_notification');
        }

        $this->app->register(EventServiceProvider::class);

        $this->app->singleton(Client::class, function () {
            return new Client(
                uri: config('ms_notification.ms_teams_webhook_url'),
                timeout: config('ms_notification.timeout'),
                retryTimes: config('ms_notification.retry_times'),
                retryMilliseconds: config('ms_notification.retry_milliseconds'),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/ms_notification.php' => config_path('ms_notification.php'),
            ], 'ms-notification-config');
        }
    }
}
