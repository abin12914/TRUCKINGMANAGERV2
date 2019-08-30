<?php

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

Route::redirect('/', '/login');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes(['register' => false]);

Route::get('/dashboard', 'HomeController@index')->name('dashboard');

Route::group(['middleware' => 'auth.check'], function () {
    //common routes
    /*Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');*/
    Route::get('/user/profile', 'HomeController@profileView')->name('user.profile');
    Route::post('/user/profile', 'HomeController@profileUpdate')->name('user.profile.action');

    Route::group(['middleware' => ['user.role:0']], function () {
        //company
        Route::resource('company', 'companyController');
    });
    //user routes
    Route::group(['middleware' => ['user.role:0,1,2']], function () {

        //account
        Route::resource('account', 'AccountController');
	});
});