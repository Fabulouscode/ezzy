<?php

namespace App\Console;

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
        Commands\AppointmentUpcomingNotification::class,
        Commands\AppointmentExtendNotification::class,
        Commands\AppointmentElapsed::class,
        Commands\AppointmentCancel::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('appointment:extend')->everyMinute();   
        $schedule->command('appointment:upcoming')->everyMinute();  
        // $schedule->command('appointment:elapsed')->everyMinute();  
        $schedule->command('appointment:cancel')->dailyAt('3:00');   
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
