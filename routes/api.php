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
    Route::post('/patient/register', 'UserAuthController@saveRegisterwithMobile');
    Route::post('/patient/register_mobile', 'UserAuthController@saveRegisterPatient');
    Route::post('/forget/password', 'UserAuthController@forgetPassword');

    Route::middleware('auth:api')->group(function(){
        Route::post('/logout', 'UserAuthController@userLogout');
        Route::post('/otp/resend', 'UserAuthController@resendSMS');
        Route::post('/otp/verify', 'UserAuthController@verifyOTP');
        Route::post('/reset/password', 'UserAuthController@recoverPassword');
    });
    
    
    // middleware add (with auth)
    Route::middleware('auth:api')->group(function(){
       
         // notification checking
        Route::get('/send_notification', 'DashboardController@sendingNotification');

        // User request
        Route::prefix('user')->group(function(){
            
            Route::get('/dashboard', 'DashboardController@getDashboardDetails');
            Route::get('/hcp/types/{id}', 'DashboardController@getHealthCareTypes');
            Route::post('/payment/history', 'DashboardController@getPaymentHistory');

            Route::get('/profile', 'UserController@getUserDetails');
            Route::get('/get/profile/{id}', 'UserController@getUserbyIdDetails');
            Route::get('/get/card_nuber/{card_num}', 'UserController@getUserbyCardNumberDetails');
            Route::get('/change/status/{status}', 'UserController@changeUserStatus');

            //user profile details
            Route::post('/profile/add', 'UserProfileController@addUserDetails');
            Route::post('/document/upload', 'UserProfileController@uploadDocumentFile');


             // bank details
            Route::prefix('bank_details')->group(function(){
                Route::get('', 'UserProfileController@getUserBankDetails');
                Route::post('/add', 'UserProfileController@addUserBankDetails');
                Route::post('/update', 'UserProfileController@updateUserBankDetails');
                Route::get('/get/{id}', 'UserProfileController@getByIdUserBankDetails');
                Route::get('/delete/{id}', 'UserProfileController@deleteUserBankDetails');
            });
            
            // available times
            Route::prefix('available_times')->group(function(){
                Route::get('', 'UserProfileController@getUserAvailableTimes');
                Route::post('/add', 'UserProfileController@addUserAvailableTimes');
                Route::post('/update', 'UserProfileController@updateUserAvailableTimes');
                Route::get('/get/{id}', 'UserProfileController@getByIdUserAvailableTimes');
                Route::get('/delete/{id}', 'UserProfileController@deleteUserAvailableTimes');
            });
            
            // eductaion details
            Route::prefix('eductaion_details')->group(function(){
                Route::get('', 'UserProfileController@getUserEducationDetails');
                Route::post('/add', 'UserProfileController@addUserEducationDetails');
                Route::post('/update', 'UserProfileController@updateUserEducationDetails');
                Route::get('/get/{id}', 'UserProfileController@getByIdUserEducationDetails');
                Route::get('/delete/{id}', 'UserProfileController@deleteUserEducationDetails');
            });
            
            // experiance details
            Route::prefix('experiance_details')->group(function(){
                Route::get('', 'UserProfileController@getUserExperianceDetails');
                Route::post('/add', 'UserProfileController@addUserExperianceDetails');
                Route::post('/update', 'UserProfileController@updateUserExperianceDetails');
                Route::get('/get/{id}', 'UserProfileController@getByIdUserExperianceDetails');
                Route::get('/delete/{id}', 'UserProfileController@deleteUserExperianceDetails');
            }); 
 
            // service details
            Route::prefix('service')->group(function(){
                Route::get('', 'UserServiceDetailsController@getUserServiceDetails');
                Route::post('/add', 'UserServiceDetailsController@addUserServiceDetails');
                Route::post('/update', 'UserServiceDetailsController@updateUserServiceDetails');
                Route::get('/get/{id}', 'UserServiceDetailsController@getByIdUserServiceDetails');
                Route::get('/delete/{id}', 'UserServiceDetailsController@deleteUserServiceDetails');
            }); 


        });
        
       
        // healthcare request
        Route::prefix('healthcare')->group(function(){            
            Route::post('/top/list', 'UserController@getTopHealthcareProviders');
            Route::post('/list', 'UserController@getHealthcareProviders');
            Route::post('/urgent', 'UserController@getHealthcareProvidersUrgent');

            // appointment
            Route::prefix('appointment')->group(function(){     
                Route::get('/request', 'AppointmentController@getRequestAppointment');       
                Route::post('/pending', 'AppointmentController@getPendingAppointment');
                Route::post('/upcoming', 'AppointmentController@getUpcomingAppointment');
                Route::post('/cancelled', 'AppointmentController@getCancelledAppointment');
                Route::post('/completed', 'AppointmentController@getCompletedAppointment');
                Route::post('/add', 'AppointmentController@addAppointment');
                Route::post('/change/status', 'AppointmentController@changeAppointmentStatus');
                Route::post('/reschedule', 'AppointmentController@rescheduleAppointment');
                Route::post('/change/completed', 'AppointmentController@completedAppointment');                
                Route::post('/review/add', 'AppointmentController@addAppointmentReview');    
                Route::post('/bill_pay', 'TransactionController@appointmentBillPay');
            });
        });

         // services 
        Route::prefix('services')->group(function(){ 
            Route::get('/get/{service_type}', 'UserServiceDetailsController@getServiceDetails');
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
                Route::get('/tracking/{order_id}', 'OrderController@getOrderTracking'); 

                Route::post('/completed', 'OrderController@getCompletedOrder'); 
                Route::post('/cancelled', 'OrderController@getCancelledOrder'); 
                Route::post('/active', 'OrderController@getActiveOrder'); 
                Route::post('/review/add', 'OrderController@addOrderPharmacyReview');    
            });


        });
       

     
        // laboratories request
        Route::prefix('laboratories')->group(function(){    
            Route::post('/top/list', 'UserController@getTopHealthcareProviders');
            Route::post('/list', 'UserController@getHealthcareProviders');

            // appointment
            Route::prefix('appointment')->group(function(){     
                
                Route::post('/add', 'AppointmentController@addLaboratoryAppointment');

                Route::get('/request', 'AppointmentController@getRequestAppointment');       
                Route::post('/pending', 'AppointmentController@getPendingAppointment');
                Route::post('/upcoming', 'AppointmentController@getUpcomingAppointment');
                Route::post('/cancelled', 'AppointmentController@getCancelledAppointment');
                Route::post('/completed', 'AppointmentController@getCompletedAppointment');
                Route::post('/change/status', 'AppointmentController@changeAppointmentStatus');
                Route::post('/reschedule', 'AppointmentController@rescheduleAppointment');
            });
        });
  
    

        
        // support request
        Route::prefix('support_request')->group(function(){            
            Route::post('', 'SupportRequestController@getSupportRequest');
            Route::post('/add', 'SupportRequestController@addSupportReques');
            Route::get('/get/{id}', 'SupportRequestController@getSupportRequestInfo');
        });
  





    });

        
});