<?php
namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Traits\AuthTokenResponses;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    use AuthTokenResponses, ThrottlesLogins;
    /**
     * Регистрация
     */
    public function signup(SignupRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        event(new Registered($user));

        return $this->tokenDataAndUser($user);
//        return $this->sendResponse(NULL, 'Successfully sign up!', 201);
    }

    /**
     * sign up and return token
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function signin(LoginRequest $request)
    {
        $credentials = array_filter(request(['name', 'email']));

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            // this can throw a ValidationException
            $this->sendLockoutResponse($request);

        }

        $error = null;
        // user by email or name(example)
        if ((isset($credentials['email']) && $user = User::ofEmail($credentials['email'])->first())
        || (isset($credentials['name']) && $user = User::ofName($credentials['name'])->first())
        ) {
            // check password
            if ($request->password && !Hash::check($request->password, $user->password)) {
                logger($request->password);
                logger($user->password);
                $error = 'Wrong password';
            }
        } else {
            $error = isset($credentials['email']) ? 'Wrong email' : 'Wrong name';
        }


        // if error
        if ($error) {
            // login attempts ++
            $this->incrementLoginAttempts($request);
            return $this->sendError($error, 401);
        }

        // login attempts = 0
        $this->clearLoginAttempts($request);

        // token and user response
        return $this->tokenDataAndUser($user);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return \Illuminate\Http\JsonResponse [string] message
     */
    public function logout()
    {
        $user = auth()->user();
        auth()->logout();
        event(new Logout('api', $user));

        return $this->sendResponse(NULL, 'Successfully logged out');
    }

    /**
     * Get the authenticated User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse [json] user object
     */
    public function user(Request $request)
    {
        return $this->sendResponse([
            'user' => $this->userData()
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function refresh()
    {
        try {
            $token = auth()->refresh();

            return response()->json($this->tokenData($token));
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            //  it mean that token already refreshed
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                return $this->sendResponse([
                    'status' => 'tokenAlreadyRefreshed'
                ]);
            }
            // if refresh token expired
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->sendResponse([
                    'status' => 'refreshTokenExpired'
                ]);
            }

            throw $e;
        }
    }

    /**
     * username for Throttles
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }
}
