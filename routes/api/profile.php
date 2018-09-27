<?php

Route::group(['namespace' => 'Profile', 'prefix' => 'profile'], function () {
    // Current user
    Route::group(['prefix' => 'current', 'middleware' => ['auth:api']], function () {
        Route::post('set-password', 'ProfileController@setPassword');
    });
});
