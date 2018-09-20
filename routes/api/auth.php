<?php

Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function () {
    Route::middleware(['guest:api'])->group(function () {
        Route::post('signin', 'AuthController@signin'); // , [ 'as' => 'login', 'uses' =>
        Route::post('signup', 'AuthController@signup');

        Route::post('forgot-password-email', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('forgot-password-reset', 'ResetPasswordController@reset');
    });
//    , 'auth:api' 'jwt.refresh', jwt.auth
    Route::middleware(['auth:api'])->group(function () {
        Route::post('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
        // Email Verification Routes...
        Route::post('email/verify/{id}', 'VerificationController@verify')->name('verification.verify');
        Route::post('email/resend', 'VerificationController@resend')->name('verification.resend');
        Route::get('email/verify', 'VerificationController@show')->name('verification.notice'); // TODO походу не нужный
    });
    // no need auth:api middleware, because if token expired we need to refresh him
    Route::post('refresh', 'AuthController@refresh');
});

