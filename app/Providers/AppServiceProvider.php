<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin;
use App\Models\Category;
use App\Models\ContactDetails;
use App\Models\Medical_category;
use App\Models\Medical_item;
use App\Models\Medicine_category;
use App\Models\Medicine_details;
use App\Models\AdminNotification;
use App\Models\Appointment;
use App\Models\AppVersion;
use App\Models\Manage_fees;
use App\Models\Permission;
use App\Models\Permission_category;
use App\Models\Services;
use App\Models\Support_request;
use App\Models\Voucher_code;
use App\Models\Role;
use App\Models\Sevice_usage;
use App\Models\Static_pages;
use App\Models\User;
use App\Observers\AdminObserver;
use App\Observers\CategoryObserver;
use App\Observers\ContactDetailsObserver;
use App\Observers\MedicalCategoryObserver;
use App\Observers\MedicalItemObserver;
use App\Observers\MedicineCategoryObserver;
use App\Observers\MedicineDetailsObserver;
use App\Observers\AdminNotificationObserver;
use App\Observers\AppointmentObserver;
use App\Observers\AppVersionObserver;
use App\Observers\ManageFeesObserver;
use App\Observers\PermissionCategoryObserver;
use App\Observers\PermissionObserver;
use App\Observers\RoleObserver;
use App\Observers\ServicesObserver;
use App\Observers\SeviceUsageObserver;
use App\Observers\StaticPagesObserver;
use App\Observers\SupportRequestObserver;
use App\Observers\UserObserver;
use App\Observers\VoucherCodeObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->environment() != 'local'){
           \URL::forceScheme('https');
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        if($this->app->environment() != 'local'){
           $this->app['request']->server->set('HTTPS', true);
           \URL::forceScheme('https');
        }

        Admin::observe(AdminObserver::class);
        Category::observe(CategoryObserver::class);
        Services::observe(ServicesObserver::class);
        Voucher_code::observe(VoucherCodeObserver::class);
        Medical_item::observe(MedicalItemObserver::class);
        Medicine_category::observe(MedicineCategoryObserver::class);
        Medical_category::observe(MedicalCategoryObserver::class);
        Medicine_details::observe(MedicineDetailsObserver::class);
        ContactDetails::observe(ContactDetailsObserver::class);
        Support_request::observe(SupportRequestObserver::class);
        AdminNotification::observe(AdminNotificationObserver::class);
        Role::observe(RoleObserver::class);
        AppVersion::observe(AppVersionObserver::class);
        Appointment::observe(AppointmentObserver::class);
        User::observe(UserObserver::class);
        Static_pages::observe(StaticPagesObserver::class);
        Manage_fees::observe(ManageFeesObserver::class);
        Permission::observe(PermissionObserver::class);
        Permission_category::observe(PermissionCategoryObserver::class);
        Sevice_usage::observe(SeviceUsageObserver::class);
    }
}
