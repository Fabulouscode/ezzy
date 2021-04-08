<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppointmentUpcomingNotification extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointment:upcoming';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Appointment Upcoming Notificatinon Send';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \App::call('App\Http\Controllers\Api\CronJobContrller@sendAppointmentUpcomingNotification');
        return 0;
    }
}
