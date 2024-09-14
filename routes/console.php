<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
// use Illuminate\Console\Scheduling\Schedule;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

Schedule::command('fetch:companies')->everyMinute()->runInBackground();
// Schedule::command('fetch:companies --queue')->everyFiveMinutes();