<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring la inspiring quote');

Schedule::command('app:monitor-course-demand')->hourly();
Schedule::command('app:send-course-demand-report')->dailyAt('08:00');
