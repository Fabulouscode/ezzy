<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppointmentElapsed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointment:elapsed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Appointment is past away no any user to start';

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
        // \App::call('App\Http\Controllers\Api\CronJobContrller@updateAppointmentElapsed');
        return 0;
    }
}
