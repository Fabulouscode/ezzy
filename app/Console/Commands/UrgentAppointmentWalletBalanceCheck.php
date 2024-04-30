<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UrgentAppointmentWalletBalanceCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'urgent-appointment:completed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'when user wallet balance is less than doctor fees appointment automatically complete';

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
        \App::call('App\Http\Controllers\Api\CronJobContrller@updateUrgentAppointmentComplete');
        return 0;
    }
}
