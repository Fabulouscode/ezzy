<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});
Route::get('/admin', function () {
    return redirect('login');
});
Route::get('/admin/login', function () {
    return redirect('login');
});


// Auth::routes();

Route::namespace('App\Http\Controllers')->group(function(){

    Route::namespace('Auth')->group(function(){
        Route::get('/login', 'LoginController@showAdminLoginForm')->name('login');
        // Route::get('/register', 'RegisterController@showAdminRegisterForm')->name('register');
        Route::get('/lockscreen', 'LockScreenController@showAdminLockScreenForm')->name('lockscreen');

        Route::post('/admin/login', 'LoginController@adminLogin');
        // Route::post('/admin/register', 'RegisterController@createAdmin');
        Route::post('/admin/lockscreen', 'LockScreenController@adminLockscreen');

        Route::middleware('auth:admin')->group(function(){
            Route::post('/logout', 'LoginController@logout')->name('logout');
        });
    });

    Route::view('/support_chat', 'admin.support_request.chat');
    
    Route::namespace('Admin')->middleware(['auth:admin','lock'])->group(function(){
       
        // Main Dashoard
        Route::get('/dashboard', 'DashboardController@index');
        Route::post('/chart/revenue', 'DashboardController@getRevenueChartdata');
        Route::post('/chart/income', 'DashboardController@getIncomeChartdata');
        Route::post('/chart/earning', 'DashboardController@getEarningdata');

        // permission No access 
        Route::get('/permission_not_access', function(){
            return view('errors.permission_access');
        })->name('permission_not_access');
        
        // upload image 
        Route::post('image/upload', 'FileUploadController@fileUploadStorage');
        Route::post('image/remove', 'FileUploadController@fileRemoveStorage');

        // Child Dashoard
        Route::get('{provider?}/dashboard', 'DashboardController@index')->middleware('role-permission:{provider}-dashboard');
        
        // Category routes(Provider)
        Route::resource('category', 'CategoryController')->middleware('role-permission-resource:hcp_type-list,hcp_type-add,hcp_type-edit,hcp_type-delete');
       
        // Admin User routes        
        Route::resource('admin/users', 'AdminController')->middleware('role-permission-resource:admin-list,admin-add,admin-edit,admin-delete');
       
        // User review routes        
        Route::resource('user/review', 'UserReviewController');  

        // Users routes
        Route::post('user/change_status', 'UserController@changeStatus');
        Route::post('user/data', 'UserController@getDatatable');        
        Route::post('user/transaction/data', 'UserController@getTransactionDatatable');        
        Route::post('user/wallet_balance', 'UserController@getWalletBalance');        
        Route::post('user/medicine/data', 'UserController@showMedicineDetails');
        Route::post('user/services/data', 'UserController@showHCPService');
 
        Route::get('customer/patient', 'UserController@index')->middleware('role-permission:patients-list');
        Route::get('customer/patient/{id?}', 'UserController@showPatient')->middleware('role-permission:patients-list');
        Route::get('customer/patient/edit/{id?}', 'UserController@editPatient')->middleware('role-permission:patients-edit');
        Route::get('customer/patient/account/payment/{id?}', 'UserController@showPatientTransaction')->middleware('role-permission:patients-list');
        
        Route::get('{provider?}/user', 'UserController@index')->middleware('role-permission:{provider}-list');
        Route::get('{provider?}/user/pending', 'UserController@getPending')->middleware('role-permission:{provider}-list');
        Route::get('{provider?}/user/{id?}', 'UserController@show')->middleware('role-permission:{provider}-list');
        Route::get('{provider?}/user/edit/{id?}', 'UserController@editUser')->middleware('role-permission:{provider}-edit');
        Route::get('{provider?}/user/account/payment/{id?}', 'UserController@showTransaction')->middleware('role-permission:{provider}-transaction');
        Route::get('{provider?}/user/services/{id?}', 'UserController@showHCPService')->middleware('role-permission:{provider}-services');        
        Route::get('pharmacy/user/medicine/{id?}', 'UserController@showMedicineDetails')->middleware('role-permission:pharmacy-services');
        Route::get('{provider?}/user/info/{id?}', 'UserController@showUserInfoDetails')->middleware('role-permission:{provider}-list');
        Route::resource('user', 'UserController');

        // Medicine Category routes
        Route::resource('medicine/categories', 'MedicineCategoryController')->middleware('role-permission-resource:medicine_category-list,medicine_category-add,medicine_category-edit,medicine_category-delete');
       
        // Medicine Subcategory routes
        Route::resource('medicine/subcategories', 'MedicineSubcategoryController')->middleware('role-permission-resource:medicine_subcategory-list,medicine_subcategory-add,medicine_subcategory-edit,medicine_subcategory-delete');
       
        // Medicine Details routes
        Route::post('medicine/details/import', 'MedicineDetailsController@importMedicine');
        Route::resource('medicine/details', 'MedicineDetailsController')->middleware('role-permission-resource:medicine_details-list,medicine_details-add,medicine_details-edit,medicine_details-delete');
       
        // pharmacy order Details routes        
        Route::get('pharmacy/order/invoice/{id?}', 'OrderController@getInvoice')->middleware('role-permission:order-invoice');
        Route::get('pharmacy/order/reviews', 'OrderController@getOrderReviews')->middleware('role-permission:order-review');
        Route::resource('pharmacy/order', 'OrderController')->middleware('role-permission-resource:order-list');        
     
        // payout routes         
        Route::get('payout/pending', 'PayoutAmountController@getPendingPayout');
        Route::post('payout/data', 'PayoutAmountController@getPayouts');
        Route::post('payout/status', 'PayoutAmountController@savePayoutsInprocess');        
        Route::post('payout/paid', 'PayoutAmountController@savePayoutTransaction');
        Route::get('payout/export', 'PayoutAmountController@getPayoutExport');
        Route::get('payout/transaction/{id?}', 'PayoutAmountController@getPayoutHistory');
        Route::resource('payout', 'PayoutAmountController');
     
        // static pages routes 
        Route::resource('voucher_code', 'VoucherCodeController')->middleware('role-permission-resource:voucher_code-list,voucher_code-add,voucher_code-edit,voucher_code-delete');
       
        // static pages routes 
        Route::resource('static_pages', 'StaticPagesController')->middleware('role-permission-resource:static_page-list,static_page-add,static_page-edit,static_page-delete');
 
        // manage fees routes 
        Route::resource('manage_fees', 'ManageFeesController')->middleware('role-permission-resource:fees-list,fees-add,fees-edit,fees-delete');
 
        // manage fees routes 
        Route::resource('service_usage', 'ServiceUsageController');
 
        // medical category  routes  
        Route::resource('medical_category', 'MedicalCategoryController')->middleware('role-permission-resource:medical_category-list,medical_category-add,medical_category-edit,medical_category-delete');
       
        // medical item  routes 
        Route::resource('medical_item', 'MedicalItemController')->middleware('role-permission-resource:medical_item-list,medical_item-add,medical_item-edit,medical_item-delete');
 
        // permission category routes 
        Route::resource('permission_category', 'PermissionCategoryController')->middleware('role-permission-resource:permission_category-list,permission_category-add,permission_category-edit,permission_category-delete');
 
        // permission routes 
        Route::resource('permission', 'PermissionController')->middleware('role-permission-resource:permission-list,permission-add,permission-edit,permission-delete');
      
        // role routes 
        Route::resource('role', 'RoleController')->middleware('role-permission-resource:role-list,role-add,role-edit,role-delete');

        // admin send notification routes 
        Route::resource('notifications', 'AdminNotificationController')->middleware('role-permission-resource:notification-list,notification-add,notification-edit,notification-delete');
 
        // services routes
        Route::resource('services', 'ServicesController')->middleware('role-permission-resource:services-list,services-add,services-edit,services-delete');
       
        // Appointment routes        
        Route::get('appointment/reviews', 'AppointmentController@getAppointmentReviews')->middleware('role-permission:appointments-review');
        Route::get('appointment/upcoming', 'AppointmentController@getUpcomingAppointments')->middleware('role-permission:appointments-list');
        Route::get('appointment/cancel', 'AppointmentController@getCancelAppointments')->middleware('role-permission:appointments-list');
        Route::get('appointment/invoice/{id?}', 'AppointmentController@getInvoice')->middleware('role-permission:appointments-invoice');        
        Route::resource('appointment', 'AppointmentController')->middleware('role-permission-resource:appointments-list');

        // Support request  routes       
        Route::post('support_request/chat_msg/add', 'SupportRequestController@addChatMessages')->middleware('role-permission:support_ticket-edit');
        Route::get('support_request/chat_msg/{id?}', 'SupportRequestController@getChatMessages')->middleware('role-permission:support_ticket-edit');
        Route::get('support_request/close_request/{id?}', 'SupportRequestController@closeSupportRequest')->middleware('role-permission:support_ticket-edit');
        Route::resource('support_request', 'SupportRequestController')->middleware('role-permission-resource:support_ticket-list,support_ticket-add,support_ticket-edit,support_ticket-delete');        
    
    });


    Route::namespace('Admin')->group(function(){
       Route::get('/card_generate', 'DashboardController@genrateCardNumber');
    });
    
        
});