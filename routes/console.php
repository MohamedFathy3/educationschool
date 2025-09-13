<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule as Scheduler;
use App\Models\Ticket;

Scheduler::command('tickets:update-daily-time')
    ->everyMinute();

    Scheduler::call(function () {
        $tickets = Ticket::where('status', 'pending')->get();

        foreach ($tickets as $ticket) {
            if ($ticket->created_at) {
                // حساب الفرق بالدقائق وجعل الرقم موجبًا دائمًا
                $dailyTime = abs(\Carbon\Carbon::parse($ticket->created_at)->diffInMinutes(now()));

                // تحديث daily_time بدون أي قيمة سالبة
                $ticket->update([
                    'daily_time' => $dailyTime,
                    'daily_status' => $dailyTime >= 30, // يتحول إلى true عندما يصل إلى 30 دقيقة أو أكثر
                ]);
            }
        }
    })->everyMinute(); // تشغيل الجدولة كل دقيقة


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
