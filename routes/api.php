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
   Route::any('/send/offline/push', 'OfflineNotificationController@offlineNotificationSend');
   Route::get('/notification/check', 'DashboardController@checkNotification');
   //Route::post('/interswitch/initialize_transaction', 'PaymentController@initializeTransactionInterswitch');
  
   Route::get('/check/request_timeout', 'CategoryController@getTimeoutCheck');
   Route::post('/contact/form_submit', 'UserController@addContactDetails');
   Route::get('/app-setting', 'AppSettingController@getAppSetting');
   
});

Route::namespace('App\Http\Controllers\Api')->middleware('auth:api')->group(function(){
    Route::post('/user/document/upload', 'UserProfileController@uploadDocumentFile');
    Route::post('/user/file/upload', 'UserProfileController@uploadImageFile');
    Route::post('/pharmacy/product/import', 'ShopMedicineDetailsController@importMedicineDetails'); 
});

Route::namespace('App\Http\Controllers\Api')->group(function(){
    Route::post('/user/live/location/update', 'UserProfileController@updateUserLiveLocation');
});

Route::namespace('App\Http\Controllers\Api')->middleware('decrypt_req')->group(function(){

    // without auth
    
    //app version check
    Route::get('/app_version/android/check/{version}', 'AppVersionController@checkAndroidVersion');
    Route::get('/app_version/ios/check/{version}', 'AppVersionController@checkIOSVersion');
    Route::get('/app_version/get', 'AppVersionController@getAppVersion');

    // HCP MainTypes
    Route::get('/hcp/main_types', 'CategoryController@getHCPMainTypes');
    
    // HCP SubTypes
    Route::get('/hcp/sub_types/{id?}', 'CategoryController@getHCPSubTypes');

    Route::post('/login', 'UserAuthController@login');
    Route::post('/register_mobile', 'UserAuthController@saveRegisterwithMobile')->middleware('throttle:RegisterThrottle');
    Route::post('/forget/password', 'UserAuthController@forgetPassword');
    Route::post('/social/login', 'UserAuthController@socialLogin');    
    Route::post('/otp/resend', 'UserAuthController@resendSMS');


    Route::middleware('auth:api')->group(function(){
        Route::get('/logout', 'UserAuthController@userLogout');
        Route::post('/otp/verify', 'UserAuthController@verifyOTP');
        Route::post('/reset/password', 'UserAuthController@recoverPassword');
        Route::post('/register', 'UserAuthController@saveRegister')->middleware('throttle:RegisterThrottle');  
        Route::post('/patient/register', 'UserAuthController@saveRegisterPatient')->middleware('throttle:RegisterThrottle');
        Route::post('/password/change', 'UserAuthController@userChangePassword');
    });
    
    
    // middleware add (with auth)
    Route::middleware(['auth:api','user_block'])->group(function(){

         // notification checking
        Route::get('/send_notification', 'DashboardController@sendingNotification');
        
         // call notification send
        Route::post('/call/notification/send', 'UserController@callNotificationSend');

        // twilio video call
        Route::prefix('twilio/video')->group(function() {
            Route::get('join/{roomName}', 'TwilioController@joinRoom');
            Route::get('create/{user_id?}', 'TwilioController@createRoom');
        });
 
        // webrtc video call
        Route::prefix('webrtc/video')->group(function() {
            Route::post('join', 'WebRtcController@joinRoom');
            Route::get('create/{user_id?}', 'WebRtcController@createRoom');
        });

        // Agora video call
        Route::prefix('agora/video')->group(function() {
            // Route::post('join', 'AgoraRtcController@joinRoom');
            Route::get('create/{user_id?}', 'AgoraRtcController@createRoom');
        });
         
        // User request
        Route::prefix('user')->group(function(){
            
            // every 1 min. run url
            Route::post('/current/location/update', 'UserProfileController@updateUserCurrentLocation');

            Route::get('/dashboard', 'DashboardController@getDashboardDetails');
            Route::get('/cart_item/count', 'ShoppingCartController@getShopingCartItemCount');

            Route::get('/hcp/types/{id?}', 'DashboardController@getHealthCareTypes');
            Route::post('/hcp/payment/history', 'DashboardController@getHCPPaymentHistory');
            Route::post('/payment/history', 'DashboardController@getPaymentHistory');
            Route::get('/payment/history/info/{id?}', 'DashboardController@getPaymentHistoryInfo');
            Route::post('/payout/history', 'DashboardController@getPayoutAmountHistory');

            Route::get('/check/chat_module/{id?}', 'DashboardController@checkUserChatModule');

            Route::get('/profile', 'UserController@getUserDetails');
            Route::get('/get/profile/location/{id?}', 'UserController@getUserbyIdLocationDetails');
            Route::get('/get/profile/{id?}', 'UserController@getUserbyIdDetails');
            Route::get('/get/card_nuber/{card_num?}', 'UserController@getUserbyCardNumberDetails');
            Route::get('/get/appointment/history/{card_num?}', 'UserController@getUserAppointmentHistory');
            Route::get('/change/status/{status?}', 'UserController@changeUserStatus');

            Route::post('/calendar/available_times', 'UserProfileController@getByUserCalendarAvailableTimes');
            Route::post('/calendar/busy_times', 'UserProfileController@getByUserCalendarBusyTimes');
            Route::get('/review/get', 'UserController@getUserReviewList');
         
            //user profile details
            Route::post('/profile/add', 'UserProfileController@addUserDetails');
            Route::get('/appointment/tracking', 'AppointmentController@getAllTrackingAppointment');

             // location
            Route::prefix('location')->group(function(){
                Route::get('/country', 'UserProfileController@getCountrys');
                Route::get('/state/{country_id?}', 'UserProfileController@getStateByCountryId');
                Route::get('/city/{state_id?}', 'UserProfileController@getCityByStateId');
            });

             // bank details
            Route::prefix('bank_details')->group(function(){
                Route::get('', 'UserProfileController@getUserBankDetails');
                Route::post('/card/add', 'UserProfileController@addUserCardDetails');
                Route::post('/card/update', 'UserProfileController@updateUserCardDetails');
                Route::post('/add', 'UserProfileController@addUserBankDetails');
                Route::post('/update', 'UserProfileController@updateUserBankDetails');
                Route::get('/get/{id?}', 'UserProfileController@getByIdUserBankDetails');
                Route::get('/primary/{id?}', 'UserProfileController@updatePrimaryUserBankDetails');
                Route::delete('/delete/{id?}', 'UserProfileController@deleteUserBankDetails');
            });
            
            // available times
            Route::prefix('available_times')->group(function(){
                Route::get('', 'UserProfileController@getUserAvailableTimes');
                Route::post('/add', 'UserProfileController@addUserAvailableTimes');
                Route::post('/multiple_add', 'UserProfileController@multipleAddUserAvailableTimes');
                Route::post('/update', 'UserProfileController@updateUserAvailableTimes');
                Route::get('/get/{id?}', 'UserProfileController@getByIdUserAvailableTimes');
                Route::delete('/delete/{id?}', 'UserProfileController@deleteUserAvailableTimes');
            });
            
            // eductaion details
            Route::prefix('eductaion_details')->group(function(){
                Route::get('', 'UserProfileController@getUserEducationDetails');
                Route::post('/add', 'UserProfileController@addUserEducationDetails');
                Route::post('/update', 'UserProfileController@updateUserEducationDetails');
                Route::get('/get/{id?}', 'UserProfileController@getByIdUserEducationDetails');
                Route::delete('/delete/{id?}', 'UserProfileController@deleteUserEducationDetails');
            });
            
            // experiance details
            Route::prefix('experiance_details')->group(function(){
                Route::get('', 'UserProfileController@getUserExperianceDetails');
                Route::post('/add', 'UserProfileController@addUserExperianceDetails');
                Route::post('/update', 'UserProfileController@updateUserExperianceDetails');
                Route::get('/get/{id?}', 'UserProfileController@getByIdUserExperianceDetails');
                Route::delete('/delete/{id?}', 'UserProfileController@deleteUserExperianceDetails');
            }); 
       
            // location details
            Route::prefix('location_details')->group(function(){
                Route::get('', 'UserProfileController@getUserLocationDetails');
                Route::post('/add', 'UserProfileController@addUserLocationDetails');
                Route::post('/update', 'UserProfileController@updateUserLocationDetails');
                Route::get('/get/{id?}', 'UserProfileController@getByIdUserLocationDetails');
                Route::get('/primary/{id?}', 'UserProfileController@updatePrimaryUserLocationDetails');
                Route::delete('/delete/{id?}', 'UserProfileController@deleteUserLocationDetails');
            }); 
 
            // service details
            Route::prefix('service')->group(function(){
                Route::get('', 'UserServiceDetailsController@getUserServiceDetails');
                Route::post('/add', 'UserServiceDetailsController@addUserServiceDetails');
                Route::post('/update', 'UserServiceDetailsController@updateUserServiceDetails');
                Route::get('/get/{id?}', 'UserServiceDetailsController@getByIdUserServiceDetails');
                Route::delete('/delete/{id?}', 'UserServiceDetailsController@deleteUserServiceDetails');
            }); 
     
            // notification details
            Route::prefix('notification')->group(function(){
                Route::get('/change/status/{status?}', 'NotificationController@changeNotificationStatus');
                Route::post('', 'NotificationController@getNotificationDetails');
                Route::get('/get/{id?}', 'NotificationController@getByIdNotificationDetails');
                Route::get('/read/{id?}', 'NotificationController@readNotificationDetails');
                Route::delete('/delete/{id?}', 'NotificationController@deleteNotificationDetails');
            }); 
  
            // voucher code details
            Route::prefix('voucher_code')->group(function(){
                Route::post('', 'VoucherCodeController@getVoucherCodeDetails');
                Route::get('/get/{id?}', 'VoucherCodeController@getByIdVoucherCodeDetails');
            }); 

             // lab report
            Route::prefix('lab_report')->group(function(){
                Route::post('', 'MedicalController@getLabReportDetails');
                Route::post('/add', 'MedicalController@addLabReportDetails');
                Route::post('/update', 'MedicalController@updateLabReportDetails');
                Route::get('/get/{id?}', 'MedicalController@getByIdLabReportDetails');
                Route::delete('/delete/{id?}', 'MedicalController@deleteLabReportDetails');
            });
        
            // medical details
            Route::prefix('medical_detail')->group(function(){
                Route::get('/list', 'MedicalController@getMedicalCategory');
                Route::get('/get/{id?}', 'MedicalController@getMedicalItemUsingCatID');
            });
            
            // wallet request
            Route::prefix('wallet')->group(function(){
                Route::post('/add', 'WalletController@addWalletBalance');
                Route::post('/deduction', 'WalletController@deductionWalletBalance');
                Route::get('/get/balance', 'WalletController@getWalletBalance');
            });
        
            // payout request
            Route::prefix('payout')->group(function(){
                Route::post('/list', 'DashboardController@getPayoutHistory');
                Route::get('/get', 'DashboardController@getPayoutCompleteAndPending');
            });

        });
        


        // healthcare request
        Route::prefix('healthcare')->group(function(){            
            Route::post('/top/list', 'UserController@getHealthcareProvidersTop');
            Route::post('/list', 'UserController@getHealthcareProviders');
            Route::post('/erecommendation/list', 'ChatController@getERecommendationProviders');
            Route::post('/urgent', 'UserController@getHealthcareProvidersUrgent');

            // appointment
            Route::prefix('appointment')->group(function(){     
                Route::get('/request', 'AppointmentController@getRequestAppointment');       
                Route::post('/all', 'AppointmentController@getAllAppointment');
                Route::post('/pending', 'AppointmentController@getPendingAppointment');
                Route::post('/upcoming', 'AppointmentController@getUpcomingAppointment');
                Route::post('/cancelled', 'AppointmentController@getCancelledAppointment');
                Route::post('/completed', 'AppointmentController@getCompletedAppointment');
                Route::post('/active', 'AppointmentController@getActiveAppointment');
                Route::post('/add', 'AppointmentController@addAppointment');
                Route::post('/add_extend_time', 'AppointmentController@addAppointmentTimeExtend');
                Route::post('/urgent/add', 'AppointmentController@addUrgentAppointment');
                Route::post('/change/status', 'AppointmentController@changeAppointmentStatus');
                Route::post('/urgent/accept', 'AppointmentController@acceptAppointment');
                Route::post('/reschedule', 'AppointmentController@rescheduleAppointment');
                Route::post('/change/completed', 'AppointmentController@completedAppointment');                
                Route::post('/review/add', 'AppointmentController@addAppointmentReview');    
                Route::post('/bill_pay', 'TransactionController@appointmentBillPay');
                Route::post('/status/bill_pay', 'TransactionController@appointmentBillPaymentStatus');
                Route::get('/check/current/{userId?}', 'AppointmentController@checkUserAppointment'); 
                Route::get('/get/{appointment_id?}', 'AppointmentController@getAppointmentById'); 
                Route::get('/invoice/{appointment_id?}', 'AppointmentController@generateInvoice'); 
            });
        });

         // services 
        Route::prefix('services')->group(function(){             
            Route::get('/get/list', 'UserServiceDetailsController@getServices');
            Route::get('/get/{service_type?}', 'UserServiceDetailsController@getServiceDetails');
            Route::get('/edignostics/get/list', 'UserServiceDetailsController@geteDignosticsServices');
        });
        
        // pharmacy request
        Route::prefix('pharmacy')->group(function(){     
            Route::post('/top/list', 'UserController@getHealthcareProvidersTop');
            Route::post('/list', 'UserController@getHealthcareProviders');
            Route::post('/eprescibe/list', 'ChatController@getERecommendationProviders');
            Route::post('/eprescibe/save', 'ChatController@saveEPrescibe');
            Route::get('/eprescibe/get/{id?}', 'ChatController@getEPrescibeChat');
            Route::post('/treatment/save', 'ChatController@saveTreatmentPlan');
            Route::get('/treatment/get/{id?}', 'ChatController@getTreatmentPlanChat');
            Route::post('/treatment/item/get', 'ChatController@getTreatmentPlanSuggestItem');
            Route::post('/treatment/bill/pay', 'TransactionController@treatmentPlanBillPay');
            Route::get('/treatment/invoice/{id?}', 'ChatController@generateInvoiceTreatmentPlan');
            
            Route::get('/categories/get', 'ShopMedicineDetailsController@getMedicineCategories');
            Route::get('/subcategories/get/{cate_id?}', 'ShopMedicineDetailsController@getMedicineSubcategories');
            Route::get('/product/get/{sub_id?}', 'ShopMedicineDetailsController@getMedicineDetails'); 
            Route::post('/product/get', 'ShopMedicineDetailsController@getMedicineDetailsWithSearch'); 
            Route::get('/product/export', 'ShopMedicineDetailsController@exportMedicineDetails'); 

         

            // shop request
            Route::prefix('shop')->group(function(){  
                Route::post('/product/add', 'ShopMedicineDetailsController@addShopProduct');
                Route::post('/product/edit', 'ShopMedicineDetailsController@editShopProduct');
                Route::post('/product/list', 'ShopMedicineDetailsController@getShopProduct');                        
                Route::post('/product/delete', 'ShopMedicineDetailsController@deleteShopProduct');    
                Route::post('/product/filter', 'ShopMedicineDetailsController@getShopProductWithSearch');     
                Route::get('/product/{id?}', 'ShopMedicineDetailsController@getShopProductInfo');    
            });

             // cart request
            Route::prefix('cart')->group(function(){  
                Route::post('/add', 'ShoppingCartController@addToCart');
                Route::get('/update/add/{id?}', 'ShoppingCartController@updateToCartAddition');       
                Route::get('/update/sub/{id?}', 'ShoppingCartController@updateToCartSubtraction');       
                Route::get('/list', 'ShoppingCartController@getUserCart');            
                Route::get('/get/{id?}', 'ShoppingCartController@getToCart');       
                Route::get('/remove/{id?}', 'ShoppingCartController@removeToCart');       
                Route::get('/clear', 'ShoppingCartController@clearUserCart');       
                Route::get('/shop/clear/{shop_id?}', 'ShoppingCartController@clearShopCart');          
                Route::get('/treatment/plan/{plan_id?}', 'ShoppingCartController@addToCartUsingTreatmentPlan');          
                Route::get('/eprescibe/plan/{plan_id?}', 'ShoppingCartController@addToCartUsingePrescibePlan');          
            });

            // favorite request
            Route::prefix('favorite')->group(function(){  
                Route::post('/remove', 'ShopMedicineDetailsController@removeFavoriteMedicine'); 
                Route::post('/add', 'ShopMedicineDetailsController@addFavoriteMedicine'); 
                Route::post('/list', 'ShopMedicineDetailsController@getFavoriteMedicine'); 
            });


            // order request
            Route::prefix('order')->group(function(){  
                Route::post('/history', 'OrderController@getOrderHistory'); 
                Route::get('/reorder/{order_id?}', 'OrderController@saveReorder'); 
                Route::get('/get/{order_id?}', 'OrderController@getOrderProduct'); 
                Route::get('/invoice/{order_id?}', 'OrderController@generateInvoice'); 
                Route::post('/change/status', 'OrderController@changeOrderStatus'); 
                Route::get('/tracking/{order_id?}', 'OrderController@getOrderTracking'); 
                Route::post('/change/tracking/status', 'OrderController@changeOrderTrackingStatus'); 

                Route::get('/pending', 'OrderController@getPendingOrder'); 
                Route::post('/completed', 'OrderController@getCompletedOrder'); 
                Route::post('/cancelled', 'OrderController@getCancelledOrder'); 
                Route::post('/active', 'OrderController@getActiveOrder'); 
                Route::post('/review/add', 'OrderController@addOrderPharmacyReview');   
                
                
                Route::post('/checkout', 'OrderController@saveCartCheckout');       
                Route::post('/bill_pay', 'TransactionController@orderPharmacyBillPay');    
                Route::post('/otp/verify', 'OrderController@verifyOTP');    
                Route::get('/otp/resend/{order_id?}', 'OrderController@resendSMS');    
            });


        });
       

     
        // laboratories request
        Route::prefix('laboratories')->group(function(){    
            Route::post('/top/list', 'UserController@getHealthcareProvidersTop');
            Route::post('/list', 'UserController@getHealthcareProviders');
            Route::get('/edignostics/services/{id?}', 'ChatController@getServices');
            Route::post('/edignostics/list', 'ChatController@getEDignosticsProviders');
            Route::post('/edignostics/save', 'ChatController@saveEDignostics');
            Route::get('/edignostics/get/{id?}', 'ChatController@getEDignosticsChat');

            // appointment
            Route::prefix('appointment')->group(function(){     
                
                // Route::post('/add', 'AppointmentController@addLaboratoryAppointment');

                Route::get('/request', 'AppointmentController@getRequestAppointment');     
                Route::post('/all', 'AppointmentController@getAllAppointment');  
                Route::post('/pending', 'AppointmentController@getPendingAppointment');
                Route::post('/upcoming', 'AppointmentController@getUpcomingAppointment');
                Route::post('/cancelled', 'AppointmentController@getCancelledAppointment');
                Route::post('/completed', 'AppointmentController@getCompletedAppointment');
                Route::post('/active', 'AppointmentController@getActiveAppointment');
                Route::post('/change/status', 'AppointmentController@changeAppointmentStatus');
                Route::post('/change/completed', 'AppointmentController@completedAppointment');  
                Route::post('/reschedule', 'AppointmentController@rescheduleAppointment');
                Route::post('/bill_pay', 'TransactionController@appointmentBillPay');
                Route::post('/status/bill_pay', 'TransactionController@appointmentBillPaymentStatus');
                Route::get('/get/{appointment_id?}', 'AppointmentController@getAppointmentById'); 
                Route::get('/invoice/{appointment_id?}', 'AppointmentController@generateInvoice'); 
            });
        });
  
    
        // interswitch request
        Route::prefix('interswitch')->group(function(){                        
            Route::post('/transaction/initialize', 'PaymentController@makeInterSwitchPaymentRequest');
            Route::post('/transaction/completed', 'WalletController@addWalletBalanceInterSwitch');
        });
        
        // support request
        Route::prefix('support_request')->group(function(){            
            Route::post('', 'SupportRequestController@getSupportRequest');
            Route::post('/add', 'SupportRequestController@addSupportRequest');
            Route::post('/add/message', 'SupportRequestController@addSupportMessage');
            Route::get('/get/{id?}', 'SupportRequestController@getSupportRequestInfo');
            Route::post('/get/message', 'SupportRequestController@getSupportRequestMessages');
        });
  
        // paystack request
        Route::prefix('paystack')->group(function(){                        
            Route::post('/transaction/initialize', 'PaymentController@makePaymentRequest');
            Route::get('/transaction/verify/{refrence?}', 'PaymentController@handleGatewayCallback');
          
            // Route::get('/payment/callback', 'PaymentController@handleGatewayCallback');
            // Route::get('/payment/get', 'PaymentController@createCustomer');
        });
  
        Route::get('/video/appointment/check/{client_id?}', 'AppointmentController@getAppointmentProgressByUserId');
        Route::get('/video/appointment/check/wallet/{client_id?}', 'AppointmentController@checkAppointmentClientWallet');


        // ejabbred request
        Route::prefix('ejabbred')->group(function(){    
            Route::post('chat-history', 'EjabbredChatController@chat_history');
            Route::get('chat-users', 'EjabbredChatController@users');
            Route::post('chat-history-clear', 'EjabbredChatController@chatHistoryClear');
        });

    });

        
});