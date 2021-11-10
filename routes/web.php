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

Route::match(['GET', 'POST'], 'remote', 'RemoteController@index');

Route::group(['namespace' => 'Web'], function()
{
    

    Route::get('/', 'HomeController@index');
    Route::match(['GET', 'POST'], 'guest-book', 'HomeController@guestBook');

    // Route::group(['prefix' => 'products'], function()
    // {
    //     Route::get('/', 'ProductController@index');
    //     Route::get('{category}', 'ProductController@categories');
    //     Route::get('{category}/{slug}', 'ProductController@show');
    // });

    // //page
    // Route::get('{p}.html', 'PageController@detail');
    // Route::group(['prefix' => 'article'], function()
    // {
    //     Route::get('/{category}', 'ArticleController@index');
    //     Route::get('/{category}/{slug}.html', 'ArticleController@show');
    // });
    // Route::match(['GET', 'POST'], 'contact', 'ContactController@index');

});