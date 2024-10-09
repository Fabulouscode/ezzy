<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\AppSetting;
use App\Models\User;
use App\Models\User_transaction;

class UserRegistrationBonusQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 3;
    private $user_id;
    /**
     * Create a new job instance.
     * 
     * @return void
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
        $this->onQueue('userRegistrationBonus');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('UserRegistrationBonusQueue start');
        $currentDatetime = Carbon::now()->format('Y-m-d H:i:s');
        $bonusAmount = AppSetting::where('key_name','bonus_amount')->first();
        $transactionMsg = "Welcome to EzzyCare!  You've just been gifted ₦".$bonusAmount->value_txt." to explore and enjoy on the platform. Dive in and have fun!";
        $add_transaction = [
            'user_id'=> $this->user_id,
            'transaction_date'=> $currentDatetime,
            'amount'=> $bonusAmount->value_txt,                        
            'mode_of_payment'=> '0',
            'transaction_type'=> '0',
            'wallet_transaction'=> '1',
            'payout_status'=> '0',
            'status'=> '0',
            'transaction_msg'=> $transactionMsg,
            'online_transaction_pay'=>'0',
            'is_admin'=>1,
        ];

        try {
            User_transaction::create($add_transaction);
            app('App\Http\Controllers\Api\UserController')->userWalletUpdate($this->user_id);
            User::where('id', $this->user_id)->update(['welcome_bonus', Carbon::now()]);
            Log::info('Wallet balance add Successfully');            
            return true;
        } catch (\Exception $e) {
            Log::info('Wallet balance Exception error');           
            Log::info($e);      
            return true;
        }
        Log::info('UserRegistrationBonusQueue end');
    }
}
