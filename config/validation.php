<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation rules
    |--------------------------------------------------------------------------
    |
    |
    */

    'email' => 'email',
    'name' => 'string|min:3|max:20',
    'password' => 'required|string|min:5',
    'reset_password_token' => 'required'
];
