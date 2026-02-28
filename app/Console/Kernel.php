<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SendMedicineReminderNotifications;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        SendMedicineReminderNotifications::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // හරියටම minute-by-minute check
        $schedule->command('reminders:send')->everyMinute();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
