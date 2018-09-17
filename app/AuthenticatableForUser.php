<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Tymon\JWTAuth\Contracts\JWTSubject;

class AuthenticatableForUser extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    // the added:
    JWTSubject
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     * https://github.com/tymondesigns/jwt-auth/issues/1316#issuecomment-330018396
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     * https://github.com/tymondesigns/jwt-auth/issues/1316#issuecomment-330018396
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
