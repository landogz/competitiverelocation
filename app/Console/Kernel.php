<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('agents:sync')
                ->daily()
                ->at('00:00')
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/agents-sync.log'));

        // Check for available jobs daily at 9 AM
        $schedule->command('jobs:check-available')
                ->dailyAt('09:00')
                ->timezone('America/New_York');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
