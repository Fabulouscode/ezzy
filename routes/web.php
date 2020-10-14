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
        Route::get('/', 'HomeController@index');
        Route::get('/datatable', function () {
            return view('datatable');
        });
        Route::resource('category', 'CategoryController');
        Route::resource('user', 'UserController');
    });
    
        
});