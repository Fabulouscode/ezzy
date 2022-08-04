<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\AdminActivity;

class AdminActivityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 3;
    private $admin_id, $title, $description, $last_ip_address, $last_user_agent,$oldValues,$newValues;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model, $type, $ip = null, $agent = null,$admin_id = null,$oldValues = null,$newValues = null)
    {
        if ($model instanceof  \App\Models\Admin) {
            if ($type == 'Login') {
                $this->title = 'Sign in success';
                $this->description = 'Successful sign in from IP ' . $ip;
            }
            if ($type == 'Logout') {
                $this->title = 'Sign out success';
                $this->description = 'Successful sign out from IP ' . $ip;
            }
            if ($type == 'AdminLock') {
                $this->title = 'Admin Lock screen';
                $this->description = 'Successful Lock screen from IP ' . $ip;
            }
            if ($type == 'AdminUnlock') {
                $this->title = 'Admin Unlock screen';
                $this->description = 'Successful Unlock screen from IP ' . $ip;
            }
            if ($type == 'Added') {
                $this->title = 'Admin Added';
                $this->description = 'New Admin user with admin id :'.$model->id.' created';
            }
            if ($type == 'Update') {
                $this->title = 'Admin Updated';
                $this->description = 'Details of Admin id :'.$model->id.' updates';
            }
            if ($type == 'Deleted') {
                $this->title = 'Admin Deleted';
                $this->description = 'Admin id :'.$model->id.' Deleted Following Id : '.$oldValues;
            }
            //\Log::info(["Old Values"=>$oldValues,"New values"=>$newValues]);
            $this->admin_id = $admin_id;
            $this->last_ip_address = $ip;
            $this->last_user_agent = $agent;
            $this->oldValues = (!empty($oldValues))?json_encode($oldValues):null;
            $this->newValues = (!empty($newValues))?json_encode($newValues):null;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        AdminActivity::create([
            'admin_id'   =>  $this->admin_id,
            'title'         =>  $this->title,
            'description'   =>  $this->description,
            'last_user_agent' => $this->last_user_agent,
            'last_ip_address' => $this->last_ip_address,
            'old_values' => $this->oldValues,
            'new_values' => $this->newValues,
        ]);
    }
}
