<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::namespace('App\Http\Controllers\Api')->group(function(){

    // without auth
    
    // HCP MainTypes
    Route::get('/hcp/main_types', 'CategoryController@getHCPMainTypes');
    
    // HCP SubTypes
    Route::get('/hcp/sub_types/{id}', 'CategoryController@getHCPSubTypes');

    Route::post('/login', 'UserAuthController@login');
    Route::post('/register', 'UserAuthController@saveRegister');
    Route::post('/forget/password', 'UserAuthController@forgetPassword');

    Route::middleware('auth:api')->group(function(){
        Route::post('/logout', 'UserAuthController@getLogout');
        Route::post('/otp/resend', 'UserAuthController@resendSMS');
        Route::post('/otp/verify', 'UserAuthController@verifyOTP');
        Route::post('/reset/password', 'UserAuthController@recoverPassword');
    });
    
    
    // middleware add (with auth)
    Route::middleware('auth:api')->group(function(){
       
       // Dashoard
       Route::get('/', 'DashboardController@index');

       // User Profile
       Route::get('/user/profile', 'UserController@getUserDetails');
        
        // bank details
        Route::prefix('user/bank_details')->group(function(){
            Route::get('', 'UserController@getUserBankDetails');
            Route::post('/add', 'UserController@addUserBankDetails');
            Route::post('/update', 'UserController@updateUserBankDetails');
            Route::get('/get/{id}', 'UserController@getByIdUserBankDetails');
            Route::get('/delete/{id}', 'UserController@deleteUserBankDetails');
        });
        
        // available times
        Route::prefix('user/available_times')->group(function(){
            Route::get('', 'UserController@getUserAvailableTimes');
            Route::post('/add', 'UserController@addUserAvailableTimes');
            Route::post('/update', 'UserController@updateUserAvailableTimes');
            Route::get('/get/{id}', 'UserController@getByIdUserAvailableTimes');
            Route::get('/delete/{id}', 'UserController@deleteUserAvailableTimes');
        });
        
        // eductaion details
        Route::prefix('user/eductaion_details')->group(function(){
            Route::get('', 'UserController@getUserEducationDetails');
            Route::post('/add', 'UserController@addUserEducationDetails');
            Route::post('/update', 'UserController@updateUserEducationDetails');
            Route::get('/get/{id}', 'UserController@getByIdUserEducationDetails');
            Route::get('/delete/{id}', 'UserController@deleteUserEducationDetails');
        });
        
        // experiance details
        Route::prefix('user/experiance_details')->group(function(){
            Route::get('', 'UserController@getUserExperianceDetails');
            Route::post('/add', 'UserController@addUserExperianceDetails');
            Route::post('/update', 'UserController@updateUserExperianceDetails');
            Route::get('/get/{id}', 'UserController@getByIdUserExperianceDetails');
            Route::get('/delete/{id}', 'UserController@deleteUserExperianceDetails');
        }); 
        
        // appointment
        Route::prefix('appointment')->group(function(){            
            Route::post('/pending', 'AppointmentController@getPendingAppointment');
            Route::post('/upcoming', 'AppointmentController@getUpcomingAppointment');
            Route::post('/cancelled', 'AppointmentController@getCancelledAppointment');
            Route::post('/completed', 'AppointmentController@getCompletedAppointment');
        });



    });

        
});