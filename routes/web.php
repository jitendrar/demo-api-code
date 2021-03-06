<?php

use Illuminate\Support\Facades\Route;

Route::domain(''.env('SITE_URL'))->group(function () {
	Route::get('/','HomeController@index')->name('home_page');
	Route::any('storecontactus','HomeController@storeContactUsForm')->name('front.storecontactus');
	Route::get('/privacy-policy','HomeController@privacypolicy');
	Route::get('/terms-of-use','HomeController@termsofuse');
});

Route::domain('admin.'.env('SITE_URL'))->group(function () {
	//Auth Route before login
	$ADMIN_PREFIX = "admin";

	Route::get('/debug/{id}','HomeController@debug')->name('debug');
	
	Route::get('login','Admin\Auth\LoginController@getLogin')->name('admin_login');
	Route::post('login', 'Admin\Auth\LoginController@postLogin')->name("check_admin_login");

	Route::get('/','Admin\Auth\LoginController@getLogin')->name('admin_login');

	//logout
	Route::get('logout', 'Admin\Auth\LoginController@getLogout')->name("logout");

	//after login routes
	Route::group(['middleware' => 'admin_auth','prefix' => $ADMIN_PREFIX], function(){
		//Dashboard
		Route::get('/dashboard', 'Admin\DashboardController@dashboard')->name("admin-dashboard");
		/*Route::any('/dashboard/data','Admin\DashboardController@orderData')->name('orderData');*/
		Route::any('/dashboard/data','Admin\OrdersController@data')->name('orderData');

		Route::get('/change-toggle', 'Admin\LoginController@toggleChange')->name('change-toggle');

		//profile
		Route::get('/profile', 'Admin\DashboardController@myProfile')->name("admin-profile");
		Route::post('/profile/data', 'Admin\DashboardController@updateProfile')->name("admin-updateProfile");

		//users list
		Route::any('users/data','Admin\UserController@data')->name('users.data');
		Route::resource('users','Admin\UserController');
		Route::post('addmoney/{id}','Admin\UserController@addMoney')->name('addmoney');
		Route::any('users/wallet_history/{id}','Admin\UserController@wallethistory')->name('wallethistory');
		Route::post('users/getusersdetails','Admin\UserController@getusersdetails');
		
		//delivery users
		Route::any('delivery-users/data','Admin\DeliveryUserController@data')->name('delivery-users.data');
		Route::resource('delivery-users','Admin\DeliveryUserController');

		//products list
		Route::any('products/latestindex','Admin\ProductsController@latestindex')->name('products.latestindex');
		Route::any('products/latestdata','Admin\ProductsController@latestdata')->name('products.latestdata');

		Route::any('products/data','Admin\ProductsController@data')->name('products.data');
		Route::get('products/sorting','Admin\ProductsController@sorting')->name('products.sorting');
		Route::any('products/getsortdata','Admin\ProductsController@getsortdata')->name('products.getsortdata');
		Route::post('products/sortingupdate','Admin\ProductsController@sortingupdate')->name('products.sortingupdate');
		Route::resource('products','Admin\ProductsController');
		Route::any('products/deleteImage/{id}','Admin\ProductsController@deleteImage')->name('products.deleteImage');
		Route::post('products/getproductlist','Admin\ProductsController@getproductlist');
		Route::post('products/getproductdetails','Admin\ProductsController@getproductdetails');


		//Offers List
		Route::get('change-offer-status/{id}/{status}','Admin\OffersController@changeOfferStatus')->name('changeOfferStatus');
		Route::any('offers/data','Admin\OffersController@data')->name('offers.data');
		Route::resource('offers','Admin\OffersController');

		//button status
	    Route::get('changestatus/{id}/{status}','Admin\ProductsController@changeStatus')->name('changeStatus');
		//category  list
		Route::any('categories/data','Admin\CategoryController@data')->name('categories.data');
		Route::resource('categories','Admin\CategoryController');
		//orders  list
		Route::any('orders/data','Admin\OrdersController@data')->name('orders.data');
		Route::any('orders/summary','Admin\OrdersController@summary')->name('orders.summary');
		Route::resource('orders','Admin\OrdersController');
		Route::any('orders/detail/{id}','Admin\OrdersController@orderDetail')->name('orders.detail');
		Route::post('orders/changeStatus/{id}','Admin\OrdersController@changeOrderStatus');
		Route::any('orders/assign-delivery-boy/{id}','Admin\OrdersController@assignDeliveryBoy')->name('assign-driver');
		Route::any('changeQty/{id}','Admin\OrdersController@changeQtyData');
		Route::any('deleteProduct/{id}','Admin\OrdersController@deleteProduct')->name('deleteProduct');
		Route::post('orders/add-new-product/{id}','Admin\OrdersController@addProduct');
		Route::any('orders/add-money-from-order/{id}','Admin\OrdersController@addmoneyfromorder')->name('add-money-from-order');

		//activity types
		Route::any('admin-action/data','Admin\AdminActionController@data')->name('admin-action.data');
		Route::resource('admin-action','Admin\AdminActionController');
		//activity logs
		Route::any('admin-activity-logs/data','Admin\AdminActivityLogsController@data')->name('admin-activity-logs.data');
		Route::any('admin-activity-logs/logDetail/{id}','Admin\AdminActivityLogsController@logDetail')->name('log.detail');
		Route::resource('admin-activity-logs','Admin\AdminActivityLogsController');

		Route::any('billings/data','Admin\BillingController@data')->name('billings.data');
		Route::resource('billings','Admin\BillingController');

		//List Cart Details
		Route::any('cart/data','Admin\CartController@data')->name('cart.data');
		Route::resource('cart','Admin\CartController');
		Route::any('cart/summary','Admin\CartController@summary')->name('cart.summary');
		Route::any('cart/detail/{id}','Admin\CartController@cartDetail')->name('cart.detail');
		Route::any('cart/placeorder/{id}','Admin\CartController@placeOrder')->name('cart.placeorder');

		Route::any('dailyreports/data','Admin\ReportController@data')->name('dailyreports.data');
		Route::resource('dailyreports','Admin\ReportController');

	});
});