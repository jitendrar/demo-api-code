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


Route::group(['middleware' => ['api_language_switcher']], function(){

	Route::get('version', 'API\AuthController@getversion');
	Route::post('register', 'API\AuthController@register');
	Route::post('login', 'API\AuthController@login');
	Route::post('userverify', 'API\AuthController@userverifyotp');
	Route::post('resendotp', 'API\AuthController@otpresend');
	Route::post('otpverify', 'API\AuthController@verifyotp');
	Route::post('resetpassword', 'API\AuthController@passwordreset');
	Route::apiResource('category', 'API\CategoryController');
	Route::post('listproducts', 'API\ProductController@listproductsbycategory');
	
	Route::get('products/{id}', 'API\ProductController@productdetails');
	Route::get('listtimeslot', 'API\OrderController@listoftimeslot');
	Route::get('aboutus', 'API\CommonController@getaboutus');
	Route::get('contactus', 'API\CommonController@getcontactus');
	Route::post('logout', 'API\AuthController@logout');
	Route::post('addcart', 'API\CartDetailController@addcartitem');
	Route::post('updateaddcart', 'API\CartDetailController@updatecartitem');
	Route::post('listcart', 'API\CartDetailController@listcartitem');
	Route::post('cartitemcount', 'API\CartDetailController@cartitemcount');
	Route::post('generatetimeslot', 'API\CartDetailController@generatetimeslot');
	Route::apiResource('listoffers', 'API\OfferController');
	Route::get('referraldesc', 'API\CommonController@referraldesc');
	Route::get('paymentoptions', 'API\CommonController@paymentoptions');
	Route::post('referralinfo', 'API\CommonController@referralinfo');
	Route::post('changeuserlng', 'API\AuthController@changeuserlng');

	/*Version 1.0.1 API changes*/
	Route::post('productslist', 'API\ProductController@productslistbycategory');
	Route::post('userlogin', 'API\AuthController@userlogin');
	Route::post('usersotpverify', 'API\AuthController@verifyotpusers');

	Route::group(['middleware' => ['auth:api']], function () {
		Route::post('sendnewphoneotp', 'API\AuthController@sendnewphoneotp');
		Route::apiResource('address', 'API\AddressController');
		Route::post('addressbyuser', 'API\AddressController@listaddressbyuser');
		Route::post('addressselected', 'API\AddressController@addressselectincart');
		Route::post('createorder', 'API\OrderController@createorder');
		Route::post('getorder', 'API\OrderController@getorderbyuser');
		Route::apiResource('order', 'API\OrderController');
		Route::post('transactionhistory', 'API\OrderController@transactionwallethistory');
		Route::post('mywalletbalance', 'API\OrderController@mywalletbalance');
		Route::post('repeatorder', 'API\OrderController@repeatorder');
		Route::post('profileupdate', 'API\AuthController@updateProfile');
		Route::post('updateuserprofile', 'API\AuthController@updateuserprofile');

		/*Version 1.0.1 API changes*/
		Route::post('addtocart', 'API\CartDetailController@addtocartitem');
	});
});


