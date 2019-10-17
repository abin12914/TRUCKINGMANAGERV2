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

//voyager
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

//laravel auth
Auth::routes(['register' => false]);


//auth check == true
Route::group(['middleware' => ['auth.check', 'landlord.tenancy']], function () {
	Route::get('/dashboard', 'HomeController@index')->name('dashboard');

	Route::resources([
	    'accounts' 			=> 'AccountController',
	    'employees'			=> 'EmployeeController',
	    'trucks'			=> 'TruckController',
	    'expenses'			=> 'ExpenseController',
	    'sites'				=> 'SiteController',
	    'transportations'	=> 'TransportationController'
	]);

    //ajax urls
    Route::group(['middleware' => 'is.ajax'], function () {
        //transportation form
        Route::get('/last/transportation', 'TransportationController@getLastTransaction')->name('transportation.last');
        //purchase form
        Route::get('/purchase/details', 'PurchaseController@purchaseDetailsByCombo')->name('purchase.detail.combo');
        //sale form
        Route::get('/sale/details', 'SaleController@saleDetailsByCombo')->name('sale.detail.combo');
    });
});
