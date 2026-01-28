<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
|
| Here you may define all of the scheduled tasks for your application.
| These tasks are run via the Laravel scheduler.
|
*/

// Clean up old activity logs daily at 2:00 AM
// Removes logs older than configured retention period (default: 90 days)
Schedule::command('activitylog:clean')
    ->daily()
    ->at('02:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/activity-cleanup.log'));
