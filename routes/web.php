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

        Route::post('/admin/login', 'LoginController@adminLogin');
        Route::post('/admin/register', 'RegisterController@createAdmin');

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
       
        // Users routes
        Route::resource('user', 'UserController');
        Route::post('user/change_status', 'UserController@changeStatus');
        Route::post('user/data', 'UserController@getDatatable');
    
        // user listing using provider
        Route::get('{provider}/users', 'UserController@index');
        Route::get('{provider}/users/pending', 'UserController@getPending');
        Route::get('users/patients', 'UserController@index');

        // Appointment routes        
        Route::get('appointment/{status}', 'AppointmentController@getAppointments');
        Route::resource('appointment', 'AppointmentController');
    
    });
    
        
});