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



Route::namespace('App\Http\Controllers\Api')->middleware('decrypt_req')->group(function(){

    // without auth
    
    // HCP MainTypes
    Route::get('/hcp/main_types', 'CategoryController@getHCPMainTypes');
    
    // HCP SubTypes
    Route::get('/hcp/sub_types/{id}', 'CategoryController@getHCPSubTypes');

    Route::post('/login', 'UserAuthController@login');
    Route::post('/register', 'UserAuthController@saveRegister');
    Route::post('/forget/password', 'UserAuthController@forgetPassword');

    Route::middleware('auth:api')->group(function(){
        Route::post('/logout', 'UserAuthController@userLogout');
        Route::post('/otp/resend', 'UserAuthController@resendSMS');
        Route::post('/otp/verify', 'UserAuthController@verifyOTP');
        Route::post('/reset/password', 'UserAuthController@recoverPassword');
    });
    
    
    // middleware add (with auth)
    Route::middleware('auth:api')->group(function(){
       
        // Dashoard
        Route::get('/', 'DashboardController@index');

        // User request
        Route::prefix('user')->group(function(){
            Route::get('/profile', 'UserController@getUserDetails');
            Route::get('/get/profile/{id}', 'UserController@getUserbyIdDetails');
            Route::get('/get/card_nuber/{card_num}', 'UserController@getUserbyCardNumberDetails');
            Route::post('/profile/add', 'UserController@addUserDetails');
            Route::post('/document/upload', 'UserController@uploadDocumentFile');
            Route::get('/change/status/{status}', 'UserController@changeUserStatus');


             // bank details
            Route::prefix('bank_details')->group(function(){
                Route::get('', 'UserController@getUserBankDetails');
                Route::post('/add', 'UserController@addUserBankDetails');
                Route::post('/update', 'UserController@updateUserBankDetails');
                Route::get('/get/{id}', 'UserController@getByIdUserBankDetails');
                Route::get('/delete/{id}', 'UserController@deleteUserBankDetails');
            });
            
            // available times
            Route::prefix('available_times')->group(function(){
                Route::get('', 'UserController@getUserAvailableTimes');
                Route::post('/add', 'UserController@addUserAvailableTimes');
                Route::post('/update', 'UserController@updateUserAvailableTimes');
                Route::get('/get/{id}', 'UserController@getByIdUserAvailableTimes');
                Route::get('/delete/{id}', 'UserController@deleteUserAvailableTimes');
            });
            
            // eductaion details
            Route::prefix('eductaion_details')->group(function(){
                Route::get('', 'UserController@getUserEducationDetails');
                Route::post('/add', 'UserController@addUserEducationDetails');
                Route::post('/update', 'UserController@updateUserEducationDetails');
                Route::get('/get/{id}', 'UserController@getByIdUserEducationDetails');
                Route::get('/delete/{id}', 'UserController@deleteUserEducationDetails');
            });
            
            // experiance details
            Route::prefix('experiance_details')->group(function(){
                Route::get('', 'UserController@getUserExperianceDetails');
                Route::post('/add', 'UserController@addUserExperianceDetails');
                Route::post('/update', 'UserController@updateUserExperianceDetails');
                Route::get('/get/{id}', 'UserController@getByIdUserExperianceDetails');
                Route::get('/delete/{id}', 'UserController@deleteUserExperianceDetails');
            }); 
        });
        
       
        // healthcare request
        Route::prefix('healthcare')->group(function(){            
            Route::post('/top/list', 'UserController@getTopHealthcareProviders');
            Route::post('/list', 'UserController@getHealthcareProviders');

            // appointment
            Route::prefix('appointment')->group(function(){            
                Route::post('/pending', 'AppointmentController@getPendingAppointment');
                Route::post('/upcoming', 'AppointmentController@getUpcomingAppointment');
                Route::post('/cancelled', 'AppointmentController@getCancelledAppointment');
                Route::post('/completed', 'AppointmentController@getCompletedAppointment');
                Route::post('/add', 'AppointmentController@addAppointment');
                Route::post('/change/status', 'AppointmentController@changeStatusAppointment');
            });
        });


        
        // pharmacy request
        Route::prefix('pharmacy')->group(function(){                        
            Route::get('/categories/get', 'ShopMedicineDetailsController@getMedicineCategories');
            Route::get('/subcategories/get/{cate_id}', 'ShopMedicineDetailsController@getMedicineSubcategories');
            Route::get('/product/get/{sub_id}', 'ShopMedicineDetailsController@getMedicineDetails'); 


            // shop request
            Route::prefix('shop')->group(function(){  
                Route::post('/product/add', 'ShopMedicineDetailsController@addShopProduct');
                Route::post('/product/list', 'ShopMedicineDetailsController@getShopProduct');            
                Route::get('/product/{id}', 'ShopMedicineDetailsController@getShopProductInfo');    
                Route::post('/review/add', 'ShopMedicineDetailsController@addShopPharmacyReview');    
            });

             // cart request
            Route::prefix('cart')->group(function(){  
                Route::post('/add', 'ShoppingCartController@addToCart');
                Route::get('/update/add/{id}', 'ShoppingCartController@updateToCartAddition');       
                Route::get('/update/sub/{id}', 'ShoppingCartController@updateToCartSubtraction');       
                Route::get('/list', 'ShoppingCartController@getUserCart');            
                Route::get('/get/{id}', 'ShoppingCartController@getToCart');       
                Route::get('/remove/{id}', 'ShoppingCartController@removeToCart');       
                Route::get('/clear', 'ShoppingCartController@clearUserCart');       
                Route::get('/shop/clear/{shop_id}', 'ShoppingCartController@clearShopCart');       
                Route::post('/checkout', 'ShoppingCartController@saveCartCheckout');       
            });


            // favorite request
            Route::prefix('favorite')->group(function(){  
                Route::post('/remove', 'ShoppingCartController@removeFavoriteMedicine'); 
                Route::post('/add', 'ShoppingCartController@addFavoriteMedicine'); 
                Route::post('/list', 'ShoppingCartController@getFavoriteMedicine'); 
            });


            // order request
            Route::prefix('order')->group(function(){  
                Route::post('/history', 'OrderController@getOrderHistory'); 
                Route::get('/get/{order_id}', 'OrderController@getOrderProduct'); 
                Route::get('/invoice/{order_id}', 'OrderController@generateInvoice'); 
                Route::post('/change/status', 'OrderController@changeOrderStatus'); 

                Route::post('/completed', 'OrderController@getCompletedOrder'); 
                Route::post('/cancelled', 'OrderController@getCancelledOrder'); 
                Route::post('/active', 'OrderController@getActiveOrder'); 
            });


        });
       

     
        // laboratories request
        Route::prefix('laboratories')->group(function(){    

        });
  
    

        
        // support request
        Route::prefix('support_request')->group(function(){            
            Route::post('', 'SupportRequestController@getSupportRequest');
            Route::post('/add', 'SupportRequestController@addSupportReques');
            Route::get('/get/{id}', 'SupportRequestController@getSupportRequestInfo');
        });
  





    });

        
});