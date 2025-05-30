<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        // ...other commands
        \App\Console\Commands\ImportOldCrmData::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // ...scheduling code
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}