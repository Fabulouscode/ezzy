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


    Route::namespace('Admin')->middleware('auth:admin')->group(function(){
       
       // Main Dashoard
       Route::get('/', 'DashboardController@index');
        
       // Child Dashoard
       Route::get('{provider}/dashboard', 'DashboardController@index');
        
        // Category routes(Provider)
        Route::resource('category', 'CategoryController');
       
        // User review routes        
        Route::get('user/reviews/{user_id}', 'UserReviewController@getReviewView');
        Route::resource('user/review', 'UserReviewController');  

        // Users routes
        Route::resource('user', 'UserController');
        Route::post('user/change_status', 'UserController@changeStatus');
        Route::post('user/data', 'UserController@getDatatable');
        Route::get('{provider}/users', 'UserController@index');
        Route::get('{provider}/users/pending', 'UserController@getPending');
        Route::get('{provider}/user/{id}', 'UserController@show');

        // Medicine Category routes
        Route::resource('medicine/categories', 'MedicineCategoryController');
       
        // Medicine Subcategory routes
        Route::resource('medicine/subcategories', 'MedicineSubcategoryController');
       
        // Medicine Details routes
        Route::resource('medicine_details', 'MedicineDetailsController');
      
        // Appointment routes        
        Route::resource('appointment', 'AppointmentController');        
        Route::get('appointments/{status}', 'AppointmentController@getAppointments');
       
        // Support request  routes        
        Route::resource('support_request', 'SupportRequestController');        
    
    });


    Route::namespace('Admin')->group(function(){
       Route::get('/card_generate', 'DashboardController@genrateCardNumber');
    });
    
        
});