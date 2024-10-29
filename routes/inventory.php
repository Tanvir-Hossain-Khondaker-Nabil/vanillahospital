<?php



Route::group(['middleware' => 'language'], function () {


    Route::middleware('tenant')->group(function() {


    });
});
