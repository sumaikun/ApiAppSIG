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
});*/


//usuarios
Route::get('users','Api\UserController@get');

Route::post('login','Api\AuthController@login');

Route::get('users/{id}','Api\UserController@getByid');

//actividad
Route::delete('activity/{id}','Api\ActivitiesController@deleteByid');

Route::get('activity/{id}','Api\ActivitiesController@getByid');

Route::post('activity','Api\ActivitiesController@create');

Route::put('activity/{id}','Api\ActivitiesController@update');

Route::get('activitiesbyUser/{user}/{date?}','Api\ActivitiesController@getByUser');

//lista actvidades
Route::get('listActivities','Api\ListActivitiesController@get');

Route::get('listActivities/{id}','Api\ListActivitiesController@getByid');

//lista empresas
Route::get('customers','Api\ListEnterprisesController@getCustomers');

Route::get('enterprises','Api\ListEnterprisesController@getEnterprises');

// recurso crud
Route::any("CrudResource","CrudController@dispatcher");
