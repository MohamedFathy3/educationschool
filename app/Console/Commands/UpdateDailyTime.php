<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;

class UpdateDailyTime extends Command
{
    protected $signature = 'tickets:update-daily-time';
    protected $description = 'Update daily_time for all tickets';

    public function handle()
    {
        $tickets = Ticket::all();
        foreach ($tickets as $ticket) {
            if ($ticket->created_at) {
                $dailyTime = now()->diffInMinutes($ticket->created_at);
                $ticket->update(['daily_time' => $dailyTime]);
                if ($dailyTime > $ticket->ata_time) {
                    $ticket->update(['daily_status' => true]);
                }
            }
        }
        $this->info('Daily time updated for all tickets.');
    }
}
