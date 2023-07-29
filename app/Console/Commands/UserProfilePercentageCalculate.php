<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UserProfilePercentageCalculate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:profile-percentage-calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User Profile Percentage Calculate Every 5 minutes run';

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
        $users = User::whereNotNull('category_id')->whereNotNull('mobile_no')->whereNotNull('email')->where('status','!=','0')->get();
        foreach ($users as $key => $value) {
            User::where('id', $value->id)->update(['completed_percentage' => $value->profile_completed_progress]);
        }
        return 0;
    }
}
