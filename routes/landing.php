<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 10/20/2022
 * Time: 4:43 PM
 */


//
Route::group(['middleware' => 'language'], function () {



    // Landing pages routes
    Route::get('/pricing', ['as' => 'pricing', 'uses' => 'LandingController@pricing']);
    Route::get('/feature', ['as' => 'feature', 'uses' => 'LandingController@feature']);
    Route::get('/partner', ['as' => 'partner', 'uses' => 'LandingController@partner']);

    Route::get('/contact', ['as' => 'contact', 'uses' => 'LandingController@contact']);
    Route::post('/send-contact', ['as' => 'store-contact', 'uses' => 'LandingController@send_contact']);


    // End landing pages routes

    Route::resource('system', 'DatabaseController');

    Route::get('language/{type}', ['as' => 'lang', 'uses' => 'LanguageController@setLang']);



    if (config('view.default_login')){
        Route::get('/', function () {
            return redirect()->route('login');
        });
    }else{
        Route::get('/', ['as' => 'index', 'uses' => 'LandingController@index']);
    }
});
