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

// //List all keys
// Route::get('/indicators', 'KeyController@index');

// //List one key
// Route::get('/indicators/{id}', 'KeyController@show');

// //Generate one key
// Route::post('/indicators', 'KeyController@store');


Route::group(['prefix' => '/indicators'], function() {

    Route::get('/','KeyController@index');
    Route::get('/{id}','KeyController@show');
    Route::post('/', 'KeyController@store');


    // ...
});