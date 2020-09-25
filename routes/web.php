<?php

use Illuminate\Support\Facades\Route;
$ADMIN_PREFIX = "admin";

//Auth Route before login
Route::get('/','Admin\Auth\LoginController@getLogin')->name('admin_login');
Route::get('login','Admin\Auth\LoginController@getLogin')->name('admin_login');
Route::post('login', 'Admin\Auth\LoginController@postLogin')->name("check_admin_login");

//logout
Route::get('logout', 'Admin\Auth\LoginController@getLogout')->name("logout");

//after login routes
Route::group(['middleware' => 'admin_auth','prefix' => $ADMIN_PREFIX], function(){
	//Dashboard
	Route::get('/dashboard', 'Admin\DashboardController@dashboard')->name("admin-dashboard");
	Route::any('/dashboard/data','Admin\DashboardController@orderData')->name('orderData');

	Route::get('/change-toggle', 'Admin\LoginController@toggleChange')->name('change-toggle');

	//profile
	Route::get('/profile', 'Admin\DashboardController@myProfile')->name("admin-profile");
	Route::post('/profile/data', 'Admin\DashboardController@updateProfile')->name("admin-updateProfile");

	//users list
	Route::any('users/data','Admin\UserController@data')->name('users.data');
	Route::resource('users','Admin\UserController');
	Route::post('addmoney/{id}','Admin\UserController@addMoney')->name('addmoney');
	Route::any('users/wallet_history/{id}','Admin\UserController@wallethistory')->name('wallethistory');


	//products list
	Route::any('products/data','Admin\ProductsController@data')->name('products.data');
	Route::resource('products','Admin\ProductsController');
	Route::any('products/deleteImage/{id}','Admin\ProductsController@deleteImage')->name('products.deleteImage');
	//button status
    Route::get('changestatus/{id}/{status}','Admin\ProductsController@changeStatus')->name('changeStatus');
	//category  list
	Route::any('categories/data','Admin\CategoryController@data')->name('categories.data');
	Route::resource('categories','Admin\CategoryController');
	//orders  list
	Route::any('orders/data','Admin\OrdersController@data')->name('orders.data');
	Route::resource('orders','Admin\OrdersController');
	Route::any('orders/detail/{id}','Admin\OrdersController@orderDetail')->name('orders.detail');
	Route::post('orders/changeStatus/{id}','Admin\OrdersController@changeOrderStatus');
});

