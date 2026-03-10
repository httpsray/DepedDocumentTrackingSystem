<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Auto-archive unprocessed documents daily at midnight
Schedule::command('documents:archive-unprocessed')->daily();

// Auto-complete for_pickup documents after 3 days (recipient didn't confirm)
Schedule::command('documents:auto-complete-pickup')->daily();
