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
        if ($model instanceof  \App\Models\Category) {
            if ($type == 'Added') {
                $this->title = 'Category Added';
                $this->description = 'New Category id :'.$model->id.' created';
            }
            if ($type == 'Update') {
                $this->title = 'Category Updated';
                $this->description = 'Details of Category id :'.$model->id.' updates';
            }
            if ($type == 'Deleted') {
                $this->title = 'Category Deleted';
                $this->description = 'Category id :'.$model->id.' Deleted Following Id : '.$oldValues;
            }

            $this->admin_id = $admin_id;
            $this->last_ip_address = $ip;
            $this->last_user_agent = $agent;
            $this->oldValues = (!empty($oldValues))?json_encode($oldValues):null;
            $this->newValues = (!empty($newValues))?json_encode($newValues):null;
        }
        if ($model instanceof  \App\Models\Services) {
            if ($type == 'Added') {
                $this->title = 'Service Added';
                $this->description = 'New Service id :'.$model->id.' created';
            }
            if ($type == 'Update') {
                $this->title = 'Service Updated';
                $this->description = 'Details of Service id :'.$model->id.' updates';
            }
            if ($type == 'Deleted') {
                $this->title = 'Service Deleted';
                $this->description = 'Service id :'.$model->id.' Deleted Following Id : '.$oldValues;
            }

            $this->admin_id = $admin_id;
            $this->last_ip_address = $ip;
            $this->last_user_agent = $agent;
            $this->oldValues = (!empty($oldValues))?json_encode($oldValues):null;
            $this->newValues = (!empty($newValues))?json_encode($newValues):null;
        }
        if ($model instanceof  \App\Models\Voucher_code) {
            if ($type == 'Added') {
                $this->title = 'Voucher code Added';
                $this->description = 'New Voucher code id :'.$model->id.' created';
            }
            if ($type == 'Update') {
                $this->title = 'Voucher code Updated';
                $this->description = 'Details of Voucher code id :'.$model->id.' updates';
            }
            if ($type == 'Deleted') {
                $this->title = 'Voucher code Deleted';
                $this->description = 'Voucher code id :'.$model->id.' Deleted Following Id : '.$oldValues;
            }

            $this->admin_id = $admin_id;
            $this->last_ip_address = $ip;
            $this->last_user_agent = $agent;
            $this->oldValues = (!empty($oldValues))?json_encode($oldValues):null;
            $this->newValues = (!empty($newValues))?json_encode($newValues):null;
        }
        if ($model instanceof  \App\Models\Medical_item) {
            if ($type == 'Added') {
                $this->title = 'Medical item Added';
                $this->description = 'New Medical item id :'.$model->id.' created';
            }
            if ($type == 'Update') {
                $this->title = 'Medical item Updated';
                $this->description = 'Details of Medical item id :'.$model->id.' updates';
            }
            if ($type == 'Deleted') {
                $this->title = 'Medical item Deleted';
                $this->description = 'Medical item id :'.$model->id.' Deleted Following Id : '.$oldValues;
            }

            $this->admin_id = $admin_id;
            $this->last_ip_address = $ip;
            $this->last_user_agent = $agent;
            $this->oldValues = (!empty($oldValues))?json_encode($oldValues):null;
            $this->newValues = (!empty($newValues))?json_encode($newValues):null;
        }
        if ($model instanceof  \App\Models\Medicine_category) {
            if ($type == 'Added') {
                $this->title = 'Medicine category Added';
                $this->description = 'New Medicine category id :'.$model->id.' created';
            }
            if ($type == 'Update') {
                $this->title = 'Medicine category Updated';
                $this->description = 'Details of Medicine category id :'.$model->id.' updates';
            }
            if ($type == 'Deleted') {
                $this->title = 'Medicine category Deleted';
                $this->description = 'Medicine category id :'.$model->id.' Deleted Following Id : '.$oldValues;
            }

            $this->admin_id = $admin_id;
            $this->last_ip_address = $ip;
            $this->last_user_agent = $agent;
            $this->oldValues = (!empty($oldValues))?json_encode($oldValues):null;
            $this->newValues = (!empty($newValues))?json_encode($newValues):null;
        }
        if ($model instanceof  \App\Models\Medical_category) {
            if ($type == 'Added') {
                $this->title = 'Medical category Added';
                $this->description = 'New Medical category id :'.$model->id.' created';
            }
            if ($type == 'Update') {
                $this->title = 'Medical category Updated';
                $this->description = 'Details of Medical category id :'.$model->id.' updates';
            }
            if ($type == 'Deleted') {
                $this->title = 'Medical category Deleted';
                $this->description = 'Medical category id :'.$model->id.' Deleted Following Id : '.$oldValues;
            }

            $this->admin_id = $admin_id;
            $this->last_ip_address = $ip;
            $this->last_user_agent = $agent;
            $this->oldValues = (!empty($oldValues))?json_encode($oldValues):null;
            $this->newValues = (!empty($newValues))?json_encode($newValues):null;
        }
        if ($model instanceof  \App\Models\Medicine_details) {
            if ($type == 'Added') {
                $this->title = 'Medicine details Added';
                $this->description = 'New Medicine details id :'.$model->id.' created';
            }
            if ($type == 'Update') {
                $this->title = 'Medicine details Updated';
                $this->description = 'Details of Medicine details id :'.$model->id.' updates';
            }
            if ($type == 'Deleted') {
                $this->title = 'Medicine details Deleted';
                $this->description = 'Medicine details id :'.$model->id.' Deleted Following Id : '.$oldValues;
            }

            $this->admin_id = $admin_id;
            $this->last_ip_address = $ip;
            $this->last_user_agent = $agent;
            $this->oldValues = (!empty($oldValues))?json_encode($oldValues):null;
            $this->newValues = (!empty($newValues))?json_encode($newValues):null;
        }
        if ($model instanceof  \App\Models\ContactDetails) {
            if ($type == 'Deleted') {
                $this->title = 'Contact details Deleted';
                $this->description = 'Contact details id :'.$model->id.' Deleted Following Id : '.$oldValues;
            }

            $this->admin_id = $admin_id;
            $this->last_ip_address = $ip;
            $this->last_user_agent = $agent;
            $this->oldValues = (!empty($oldValues))?json_encode($oldValues):null;
            $this->newValues = (!empty($newValues))?json_encode($newValues):null;
        }
        if ($model instanceof  \App\Models\Support_request) {
            if ($type == 'Update') {
                $this->title = 'Support request Updated';
                $this->description = 'Details of Support request id :'.$model->id.' updates';
            }
            if ($type == 'Deleted') {
                $this->title = 'Support request Deleted';
                $this->description = 'Support request id :'.$model->id.' Deleted Following Id : '.$oldValues;
            }

            $this->admin_id = $admin_id;
            $this->last_ip_address = $ip;
            $this->last_user_agent = $agent;
            $this->oldValues = (!empty($oldValues))?json_encode($oldValues):null;
            $this->newValues = (!empty($newValues))?json_encode($newValues):null;
        }
        if ($model instanceof  \App\Models\AdminNotification) {
            if ($type == 'Added') {
                $this->title = 'Notification Added';
                $this->description = 'New Notification id :'.$model->id.' created';
            }
            if ($type == 'Update') {
                $this->title = 'Notification Updated';
                $this->description = 'Details of Notification id :'.$model->id.' updates';
            }
            if ($type == 'Deleted') {
                $this->title = 'Notification Deleted';
                $this->description = 'Notification id :'.$model->id.' Deleted Following Id : '.$oldValues;
            }

            $this->admin_id = $admin_id;
            $this->last_ip_address = $ip;
            $this->last_user_agent = $agent;
            $this->oldValues = (!empty($oldValues))?json_encode($oldValues):null;
            $this->newValues = (!empty($newValues))?json_encode($newValues):null;
        }
        if ($model instanceof  \App\Models\Role) {
            if ($type == 'Added') {
                $this->title = 'Role Added';
                $this->description = 'New Role id :'.$model->id.' created';
            }
            if ($type == 'Update') {
                $this->title = 'Role Updated';
                $this->description = 'Details of Role id :'.$model->id.' updates';
            }
            if ($type == 'Deleted') {
                $this->title = 'Role Deleted';
                $this->description = 'Role id :'.$model->id.' Deleted Following Id : '.$oldValues;
            }

            $this->admin_id = $admin_id;
            $this->last_ip_address = $ip;
            $this->last_user_agent = $agent;
            $this->oldValues = (!empty($oldValues))?json_encode($oldValues):null;
            $this->newValues = (!empty($newValues))?json_encode($newValues):null;
        }
        if ($model instanceof  \App\Models\AppVersion) {
            if ($type == 'Update') {
                $this->title = 'App Version Updated';
                $this->description = 'Details of App Version id :'.$model->id.' updates';
            }

            $this->admin_id = $admin_id;
            $this->last_ip_address = $ip;
            $this->last_user_agent = $agent;
            $this->oldValues = (!empty($oldValues))?json_encode($oldValues):null;
            $this->newValues = (!empty($newValues))?json_encode($newValues):null;
        }
        if ($model instanceof  \App\Models\Appointment) {
            if ($type == 'Update') {
                $this->title = 'Appointment cancelled';
                $this->description = 'Details of Appointment id :'.$model->id.' updates';
            }

            if ($type == 'Deleted') {
                $this->title = 'Appointment Deleted';
                $this->description = 'Appointment id :'.$model->id.' Deleted Following Id : '.$oldValues;
            }

            $this->admin_id = $admin_id;
            $this->last_ip_address = $ip;
            $this->last_user_agent = $agent;
            $this->oldValues = (!empty($oldValues))?json_encode($oldValues):null;
            $this->newValues = (!empty($newValues))?json_encode($newValues):null;
        }
        if ($model instanceof  \App\Models\User) {
            if ($type == 'Update') {
                $this->title = 'User Updated';
                $this->description = 'Details of User id :'.$model->id.' updates';
            }
            if ($type == 'Deleted') {
                $this->title = 'User Deleted';
                $this->description = 'User id :'.$model->id.' Deleted Following Id : '.$oldValues;
            }

            $this->admin_id = $admin_id;
            $this->last_ip_address = $ip;
            $this->last_user_agent = $agent;
            $this->oldValues = (!empty($oldValues))?json_encode($oldValues):null;
            $this->newValues = (!empty($newValues))?json_encode($newValues):null;
        }
        if ($model instanceof  \App\Models\Static_pages) {
            if ($type == 'Added') {
                $this->title = 'Static pages Added';
                $this->description = 'New Static pages id :'.$model->id.' created';
            }
            if ($type == 'Update') {
                $this->title = 'Static pages Updated';
                $this->description = 'Details of Static pages id :'.$model->id.' updates';
            }
            if ($type == 'Deleted') {
                $this->title = 'Static pages Deleted';
                $this->description = 'Static pages id :'.$model->id.' Deleted Following Id : '.$oldValues;
            }

            $this->admin_id = $admin_id;
            $this->last_ip_address = $ip;
            $this->last_user_agent = $agent;
            $this->oldValues = (!empty($oldValues))?json_encode($oldValues):null;
            $this->newValues = (!empty($newValues))?json_encode($newValues):null;
        }
        if ($model instanceof  \App\Models\Manage_fees) {
            if ($type == 'Update') {
                $this->title = 'Manage fees updated';
                $this->description = 'Details of Manage fees id :'.$model->id.' updates';
            }

            $this->admin_id = $admin_id;
            $this->last_ip_address = $ip;
            $this->last_user_agent = $agent;
            $this->oldValues = (!empty($oldValues))?json_encode($oldValues):null;
            $this->newValues = (!empty($newValues))?json_encode($newValues):null;
        }
        if ($model instanceof  \App\Models\Permission) {
            if ($type == 'Added') {
                $this->title = 'Permission Added';
                $this->description = 'New Permission id :'.$model->id.' created';
            }
            if ($type == 'Update') {
                $this->title = 'Permission Updated';
                $this->description = 'Details of Permission id :'.$model->id.' updates';
            }
            if ($type == 'Deleted') {
                $this->title = 'Permission Deleted';
                $this->description = 'Permission id :'.$model->id.' Deleted Following Id : '.$oldValues;
            }

            $this->admin_id = $admin_id;
            $this->last_ip_address = $ip;
            $this->last_user_agent = $agent;
            $this->oldValues = (!empty($oldValues))?json_encode($oldValues):null;
            $this->newValues = (!empty($newValues))?json_encode($newValues):null;
        }
        if ($model instanceof  \App\Models\Permission_category) {
            if ($type == 'Added') {
                $this->title = 'Permission category Added';
                $this->description = 'New Permission category id :'.$model->id.' created';
            }
            if ($type == 'Update') {
                $this->title = 'Permission category Updated';
                $this->description = 'Details of Permission category id :'.$model->id.' updates';
            }
            if ($type == 'Deleted') {
                $this->title = 'Permission category Deleted';
                $this->description = 'Permission category id :'.$model->id.' Deleted Following Id : '.$oldValues;
            }

            $this->admin_id = $admin_id;
            $this->last_ip_address = $ip;
            $this->last_user_agent = $agent;
            $this->oldValues = (!empty($oldValues))?json_encode($oldValues):null;
            $this->newValues = (!empty($newValues))?json_encode($newValues):null;
        }
        if ($model instanceof  \App\Models\Sevice_usage) {
            if ($type == 'Added') {
                $this->title = 'Service Usage Added';
                $this->description = 'New Service Usage id :'.$model->id.' created';
            }
            if ($type == 'Update') {
                $this->title = 'Service Usage Updated';
                $this->description = 'Details of Service Usage id :'.$model->id.' updates';
            }
            if ($type == 'Deleted') {
                $this->title = 'Service Usage Deleted';
                $this->description = 'Service Usage id :'.$model->id.' Deleted Following Id : '.$oldValues;
            }

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
