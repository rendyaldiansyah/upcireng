<?php

use App\Services\DailyRecapService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('orders:send-daily-recap {--date=}', function () {
    $date = $this->option('date')
        ? Carbon::parse($this->option('date'), 'Asia/Jakarta')
        : now('Asia/Jakarta');

    $message = app(DailyRecapService::class)->send($date);

    $this->info($message);
})->purpose('Send daily recap notification for UP Cireng');

Schedule::command('orders:send-daily-recap')
    ->dailyAt(env('DAILY_RECAP_TIME', '23:05'))
    ->timezone('Asia/Jakarta');
