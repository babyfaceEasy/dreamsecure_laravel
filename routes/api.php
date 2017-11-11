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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/kunle', 'ClientController@registerClient');
Route::get('/test-send-mail', 'ClientController@testMail');
*/

Route::group(array( 'prefix' => 'users' ),function(){
    Route::post('register', 'ClientController@registerClient');
    Route::post('activate', 'ClientController@activateClient');
    Route::post('login', 'ClientController@loginClient');
    Route::post('getdetails', 'ClientController@getClientDetails');
    Route::post('updatedetails', 'ClientController@updateClientDetails');
});//end of user->prefix

Route::prefix('data')->group(function(){
    Route::post('post', 'DataController@create');
});

