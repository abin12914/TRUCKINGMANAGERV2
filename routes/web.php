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

//Route::redirect('/', '/login');
Route::get('/', 'HomeController@index')->name('home');
//temp
// Route::get('/signout', function() {
//     \Auth::logout();
// })->name('signout');

//voyager
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

//laravel auth
Auth::routes(['register' => false]);

//auth check == true
Route::group(['middleware' => ['auth.check', 'landlord.tenancy']], function () {
    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
	Route::get('/profile/edit', 'UserController@profileEdit')->name('user.profile.edit');
    Route::post('/profile/update', 'UserController@profileUpdate')->name('user.profile.update');

    Route::get('/certificates', 'TruckController@certificates')->name('trucks.certificates');
    Route::get('/certificates/renew/{truckId}', 'ExpenseController@certEdit')->name('expense.certificate.renew');
    Route::post('/certificates/renew', 'ExpenseController@certUpdate')->name('expense.certificate.renew.action');
    Route::get('/fuel/refill/{truckId}', 'ExpenseController@fuelRefillEdit')->name('expense.fuel.refill');
    Route::post('/fuel/refill', 'ExpenseController@fuelRefillUpdate')->name('expense.fuel.refill.action');

	Route::resources([
	    'accounts' 			=> 'AccountController',
	    'employees'			=> 'EmployeeController',
	    'trucks'			=> 'TruckController',
	    'expenses'			=> 'ExpenseController',
	    'sites'				=> 'SiteController',
	    'transportations'	=> 'TransportationController',
        'supply'            => 'SupplyTransportationController',
        'vouchers'          => 'VoucherController'
	]);

    //reports
    Route::get('/reports/account-statement', 'ReportController@accountStatement')->name('reports.account-statement');
    Route::get('/reports/credit-statement', 'ReportController@creditStatement')->name('reports.credit-statement');
    Route::get('/reports/profit-loss-statement', 'ReportController@profitLossStatement')->name('reports.profit-loss-statement');

    //ajax urls
    Route::group(['middleware' => 'is.ajax'], function () {
        //last transportation via ajax
        Route::get('/last/transportation', 'TransportationController@getLastTransaction')->name('transportation.last');
        //last purchase via ajax
        Route::get('/last/purchase', 'PurchaseController@getLastTransaction')->name('purchase.last');
        //last sale via ajax
        Route::get('last/sale', 'SaleController@getLastTransaction')->name('sale.last');
    });
});

Route::fallback(function () {
    return view('errors.404');
});
