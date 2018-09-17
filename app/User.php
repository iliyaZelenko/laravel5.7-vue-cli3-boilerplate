<?php

namespace App;

use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\VerifyEmail;

class User extends AuthenticatableForUser implements MustVerifyEmail
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password'
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * By name
     *
     * @param $query
     * @param $name
     * @return Builder
     */
    public function scopeOfName($query, $name)
    {
        return $query->where('name', $name);
    }

    /**
     * By email
     *
     * @param $query
     * @param $email
     * @return Builder
     */
    public function scopeOfEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }
}
