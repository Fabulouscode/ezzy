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

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

Route::namespace('App\Http\Controllers')->group(function(){

     Route::namespace('Auth')->group(function(){
        Route::get('/login', 'LoginController@showAdminLoginForm')->name('login');
        Route::get('/register', 'RegisterController@showAdminRegisterForm')->name('register');
        Route::get('/lockscreen', 'LockScreenController@showAdminLockScreenForm')->name('lockscreen');

        Route::post('/admin/login', 'LoginController@adminLogin');
        Route::post('/admin/register', 'RegisterController@createAdmin');
        Route::post('/admin/lockscreen', 'LockScreenController@adminLockscreen');

        Route::middleware('auth:admin')->group(function(){
            Route::post('/logout', 'LoginController@logout')->name('logout');
        });
    });


    Route::namespace('Admin')->middleware(['auth:admin','lock'])->group(function(){
       
        // Main Dashoard
        Route::get('/', 'DashboardController@index');
        
        // upload image 
        Route::post('image/upload', 'FileUploadController@fileUploadStorage');
        Route::post('image/remove', 'FileUploadController@fileRemoveStorage');

        // Child Dashoard
        Route::get('{provider}/dashboard', 'DashboardController@index');
        
        // Category routes(Provider)
        Route::resource('category', 'CategoryController');
       
        // Admin User routes        
        Route::resource('admin/users', 'AdminController');  
       
        // User review routes        
        Route::resource('user/review', 'UserReviewController');  

        // Users routes
        Route::post('user/change_status', 'UserController@changeStatus');
        Route::post('user/data', 'UserController@getDatatable');
        Route::get('{provider}/user', 'UserController@index');
        Route::get('{provider}/user/pending', 'UserController@getPending');
        Route::get('{provider}/user/{id}', 'UserController@show');
        Route::get('{provider}/user/transaction/{id}', 'UserController@showTransaction');
        Route::post('{provider}/user/transaction/data', 'UserController@getTransactionDatatable');
        Route::resource('user', 'UserController');

        // Medicine Category routes
        Route::resource('medicine/categories', 'MedicineCategoryController');
       
        // Medicine Subcategory routes
        Route::resource('medicine/subcategories', 'MedicineSubcategoryController');
       
        // Medicine Details routes
        Route::resource('medicine/details', 'MedicineDetailsController');
       
        // pharmacy order Details routes        
        Route::get('pharmacy/order/invoice/{id}', 'OrderController@getInvoice');
        Route::get('pharmacy/order/reviews', 'OrderController@getOrderReviews');
        Route::resource('pharmacy/order', 'OrderController');        
     
        // static pages routes 
        Route::resource('static_pages', 'StaticPagesController');
 
        // services routes
        Route::resource('services', 'ServicesController');
       
        // Appointment routes        
        Route::get('appointment/reviews', 'AppointmentController@getAppointmentReviews');
        Route::get('appointment/completed', 'AppointmentController@getCompletedAppointments');
        Route::get('appointment/cancel', 'AppointmentController@getCancelAppointments');
        Route::get('appointment/invoice/{id}', 'AppointmentController@getInvoice');        
        Route::resource('appointment', 'AppointmentController');

        // Support request  routes        
        Route::resource('support_request', 'SupportRequestController');        
    
    });


    Route::namespace('Admin')->group(function(){
       Route::get('/card_generate', 'DashboardController@genrateCardNumber');
    });
    
        
});