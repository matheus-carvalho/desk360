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

Route::get('/contacts', 'ContactController@get');
Route::get('/contacts.getById', 'ContactController@getById');
Route::post('/contacts', 'ContactController@store');
Route::put('/contacts', 'ContactController@update');
Route::delete('/contacts', 'ContactController@destroy');
Route::delete('/tels', 'ContactController@destroyTel');
