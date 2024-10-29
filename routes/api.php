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


Route::post('/login', 'Api\AuthApiController@login');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:api'], 'namespace' => 'Api' ], function (){


    Route::get('/logout', 'AuthApiController@logout');

    Route::get('/get-areas', 'AreaApiController@get_areas');
    Route::get('/get-thanas', 'AreaApiController@get_thanas');
    Route::get('/get-districts', 'AreaApiController@get_districts');
    Route::get('/get-divisions', 'AreaApiController@get_divisions');
    Route::get('/get-regions', 'AreaApiController@get_regions');


    Route::get('/get-stores', 'StoreApiController@index');
    Route::get('/stores-info/{id}', 'StoreApiController@edit');
    Route::post('/save-store', 'StoreApiController@store');
    Route::post('/update-store', 'StoreApiController@update');


    Route::post('/visit-store', 'StoreApiController@storeVisit');


    Route::get('/list-requisition', 'SaleRequisitionApiController@index');
    Route::get('/create-requisition', 'SaleRequisitionApiController@create');
    Route::post('/save-requisition', 'SaleRequisitionApiController@store');
    Route::get('/show-requisition/{id}', 'SaleRequisitionApiController@show');
    Route::get('/edit-requisition/{id}', 'SaleRequisitionApiController@edit');
    Route::post('/update-requisition/{id}', 'SaleRequisitionApiController@update');



    Route::get('/report/sale-requisition', 'ReportApiController@sale_requisition_report');



});


