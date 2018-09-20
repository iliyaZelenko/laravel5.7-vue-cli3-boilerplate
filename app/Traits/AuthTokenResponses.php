<?php

namespace App\Traits;

use App\Http\Resources\UserResource;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use App\User;
use \Illuminate\Contracts\Auth\CanResetPassword;

trait AuthTokenResponses
{
    protected function tokenData($token)
    {
        // setToken $token
        $payload = auth()->setToken($token)->getPayload()->toArray();
        // Возврашает ответ с токеном
        return [
            'accessToken' => $token,
            'expiresIn' => $payload['exp'],
            'issuedAt' => $payload['iat'],
            'refreshTokenExpiresIn' => Carbon::createFromTimestamp($payload['iat'])
                ->addMinutes(config('jwt.refresh_ttl'))
                ->getTimestamp()
//            'token_type' => 'Bearer'
        ];
    }

    /**
     * @param User $user
     * @return array
     */
    protected function tokenDataAndUser(User $user)
    {
        // login user
        if ((!auth()->user() && $user) || (auth()->user() && auth()->id() !== $user->id)) {
            auth()->login($user);
            event(new Login('api', $user, false)); // false - its remember
        }

        $token = auth()->fromUser(auth()->user());

        return [
            'tokenInfo' => $this->tokenData($token),
            'user' => $this->userData()
        ];
    }

    protected function userData()
    {
        return new UserResource(auth()->user());
    }
}
