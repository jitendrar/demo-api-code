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

Route::post('login', 'Api\AuthController@login');
Route::post('register', 'Api\AuthController@register');
Route::post('userverify', 'Api\AuthController@userverifyotp');

Route::group(['middleware' => ['auth:api']], function () {
	Route::apiResource('category', 'Api\CategoryController');
	Route::apiResource('products', 'Api\ProductController');
});

