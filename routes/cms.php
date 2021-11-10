<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cms Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//AUTH
Route::match(['GET', 'POST'], '/forgot', 'AuthController@forgot');
Route::match(['GET', 'POST'], '/reset', 'AuthController@reset');
Route::get('accept-invitation', 'AuthController@acceptInvitation');

//SSO GOOGLE
Route::group(['prefix' => 'auth-google'], function()
{ 
    Route::get('redirect', 'AuthController@googleRedirect');
    Route::get('callback', 'AuthController@googleCallback');
});


Route::group(['namespace' => 'Cms'], function()
{
    // AUTH
    Route::match(['GET', 'POST'], '/login', 'AuthController@login')->name('login');
    Route::get('logout', 'AuthController@logout');

    //SIGNED IN
    Route::group(['middleware' => 'authed'], function()
    {
        Route::get('/', 'DashboardController@index');
        Route::get('notif', 'NotifController@index');
        Route::match(['GET', 'POST'], 'profile', 'ProfileController@index');

        //UPDATE
        Route::group(['namespace'=>'Update'], function()
        { 
            Route::resource('guest-book', 'GuestBookController');
            Route::get('city', 'GuestBookController@getCity');
        });

        //SYSTEM
        Route::group(['namespace'=>'System'], function()
        { 
            Route::resource('role', 'RoleController');
            Route::resource('user', 'UserController');
            Route::resource('menu', 'MenuController');
            Route::resource('help', 'HelpController');
        });

        Route::get('send-notif', function(){
            \Notification::send(\Auth::user(), 
                new \App\Notifications\TestNotif()
            );
        });
    });
});

