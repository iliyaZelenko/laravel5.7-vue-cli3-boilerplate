<?php
namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\User;
use UnexpectedValueException;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Traits\AuthTokenResponses;

class ResetPasswordController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
     */

    /**
     * Constant representing a successfully sent reminder.
     *
     * @var string
    */
    const RESET_LINK_SENT = 'passwords.sent';
    /**
     * Constant representing a successfully reset password.
     *
     * @var string
    */
    const PASSWORD_RESET = 'passwords.reset';
    /**
     * Constant representing the user not found response.
     *
     * @var string
    */
    const INVALID_USER = 'passwords.user';
    /**
     * Constant representing an invalid password.
     *
     * @var string
    */
    const INVALID_PASSWORD = 'passwords.password';
    /**
     * Constant representing an invalid token.
     *
     * @var string
    */
    const INVALID_TOKEN = 'passwords.token';


    use ResetsPasswords, AuthTokenResponses;

    /**
     * Reset the given user's password.
     *
     * @param ResetPasswordRequest $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function reset(ResetPasswordRequest $request)
    {
        $credentials = $this->credentials($request);

        // нужно передать свой валидатор, а то в исходном коде laravel сделали по глупому еще валидацию, зачем если есть валидация в запросе?! https://github.com/laravel/framework/blob/5.5/src/Illuminate/Auth/Passwords/PasswordBroker.php#L168
        // $this->broker()->validator(function () { return true; });
        $response = $this->validateReset($credentials);
        // $response = $this->broker()->validateReset($credentials);

        if ($response instanceof CanResetPasswordContract) {
            $user = $response;
        } else {
            switch ($response) {
                case static::INVALID_USER:
                    return $this->sendError(trans('passwords.user'), 404);
                case static::INVALID_PASSWORD:
                    return $this->sendError(trans('passwords.password'), 422);
                case static::INVALID_TOKEN:
                    return $this->sendError(trans('passwords.token'), 422);
            }
        }

        $this->resetPassword($user, $credentials['password']);
        $this->broker()->getRepository()->delete($user);


//        return $this->sendResponse(NULL, trans('passwords.reset'));
        return $this->tokenDataAndUser($user);
    }


    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->save();

//        RememberToken is removed from user db!
//        $user->setRememberToken(Str::random(60));

        event(new PasswordReset($user));

//        $this->guard()->login($user);
    }


    /**
     * User by credentials
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\CanResetPassword|null
     *
     * @throws \UnexpectedValueException
     */
    public function getUser(array $credentials)
    {
        // $passwordHashed = Hash::make($credentials['password']);
        $user = User::where('email', $credentials['email'])->first();

        if ($user && !$user instanceof CanResetPasswordContract) {
            throw new UnexpectedValueException('User must implement CanResetPassword interface.');
        }

        return $user;
    }


    /**
     * Валидация нового пароля
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validateNewPassword(array $credentials)
    {
        return true;
    }

    /**
     * Validate a password reset for the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\CanResetPassword|string
     */
    protected function validateReset(array $credentials)
    {
        if (is_null($user = $this->getUser($credentials))) {
            return static::INVALID_USER;
        }
        if (!$this->validateNewPassword($credentials)) {
            return static::INVALID_PASSWORD;
        }
        // if (! $this->broker()->getRepository()->exists($user, $credentials['token'])) {
        //     return static::INVALID_TOKEN;
        // }
        if (!$this->broker()->tokenExists($user, $credentials['token'])) {
            return static::INVALID_TOKEN;
        }

        return $user;
    }


    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $cred = $request->only(
            'email', 'password', 'token' // 'password_confirmation'
        );
        $cred['password_confirmation'] = $cred['password'];

        return $cred;
    }
}
