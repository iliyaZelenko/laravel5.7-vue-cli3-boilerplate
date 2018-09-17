<?php

Route::middleware(['auth:api'])->group(function () {
    Route::apiResources([
        'users' => 'UsersController',
    ]);
});

