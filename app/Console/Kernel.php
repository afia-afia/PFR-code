<?php

namespace App\Console;

use App\Jobs\StatusChangeDetection;
use App\Jobs\TrapSnmpJob;
use App\Jobs\CorrelationQuery;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->job(new CorrelationQuery())->everyMinute()->runInBackground();
        $schedule->job(new StatusChangeDetection())->everyFiveMinutes()->runInBackground(); //everyFiveMinutes
        $schedule->job(new TrapSnmpJob())->everyFiveMinutes()->runInBackground()->withoutOverlapping();
        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

