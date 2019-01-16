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

//List Persons
Route::get('persons','PersonController@index');
//List Person
Route::get('person/{id}','PersonController@show');
// Store
Route::post('person','PersonController@store');
// Update
Route::put('person','PersonController@store');
// Delete
Route::delete('person/{id}','PersonController@destroy');