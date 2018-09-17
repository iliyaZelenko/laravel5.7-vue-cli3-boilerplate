<?php

Route::group(['namespace' => 'Profile', 'prefix' => 'profile'], function () {
    // Текущий пользователь
    Route::group(['prefix' => 'current', 'middleware' => ['auth:api']], function () {
        Route::post('set-password', 'ProfileController@setPassword');
        Route::post('set-user-data', 'ProfileController@setUserData');
        Route::post('set-avatar', 'ProfileController@setAvatar');

        Route::post('save-email', 'ProfileController@saveEmail');
        Route::post('delete-email', 'ProfileController@deleteEmail');
        Route::post('set-main-email', 'ProfileController@setMainEmail');
        Route::post('change-public-email', 'ProfileController@changePublicEmail');

        Route::post('save-phone', 'ProfileController@savePhone');
        Route::post('delete-phone', 'ProfileController@deletePhone');
        Route::post('set-main-phone', 'ProfileController@setMainPhone');
        Route::post('change-public-phone', 'ProfileController@changePublicPhone');

        Route::post('get-passwords-history', 'ProfileController@getPasswordsHistory');
    });

    // Другой пользователь
    Route::group(['prefix' => 'other'], function () {
        Route::post('get-user', 'OtherProfileController@getUser');
    });
});
