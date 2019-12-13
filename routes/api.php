<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//auth
Route::group(['middleware'=>["jwt.auth"],"prefix"=>"v2"],function(){
   Route::get('/usuarios',"UserController@index"); //logout
   
});

Route::group(['middleware'=>[],"prefix"=>"v1"],function(){
    Route::post('/auth/refresh',"TokensController@refreshToken");
    Route::get('/auth/logout',"TokensController@logout"); //logout
    Route::post('/auth/login',"TokensController@login");
   
});