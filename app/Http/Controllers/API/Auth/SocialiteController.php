<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use Socialite;
use App\Traits\PassportToken;
use App\Traits\Socialite as SocialiteTrait;
use App\User;
// use Laravel\Socialite\Facades\Socialite;
use App\Traits\EmailVerification;
use App\SocialiteProvider;
use App\Http\Resources\UserResource;

class SocialiteController extends BaseController
{
    use PassportToken, SocialiteTrait, EmailVerification;

    // Права доступа
    protected $scopes = [
        'google' => ['email'],
        'facebook' => ['email'],
        'vkontakte' => ['email'],
        'instagram' => [],
        'reddit' => []
    ];

    /**
     * Дает ссылку для редиректа на соц сеть
     */
    public function getRedirectUrl(Request $request)
    {
        $providerName = $request->providerName;
        $url = Socialite::driver($providerName)
            ->scopes($this->scopes[$providerName])
            ->with(['state' => 'PUT_ANY_STRING_HERE']) // это для реддита
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        return $this->sendResponse(['redirectUrl' => $url]);
    }

    /**
     * Прикрепление соц акка
     *
     * @return Response
     */
    public function loggedInUserSaveSocAcc(Request $request)
    {
        try {
            $user = auth()->user();

            $this->userSaveSocAcc(
                $user,
                $request->userSoc,
                $request->providerName
            );

            return new UserResource($user);
        } catch (\Exception $e) { //
            // TODO свое Exception
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Открепление соц акка
     *
     * @return Response
     */
    public function loggedInUserDeleteSocAcc(Request $request)
    {
        $user = auth()->user();
        // если выбрал вход без пароля и почты/ника и если остается последняя возможность входа - через соц акк,
        // которй сейчас юзер пытается открепить также тут created_through_soc_acc будет истиной
        if ($user->socAccounts()->count() === 1) {
            if (!$user->password) {
                return $this->sendError('Это последний прикрепленный Вами аккаунт, чтобы открепить добавьте альтернативную возможность входа в настройках - через пароль.', 422);
            }
        }
        $user->socAccounts()->detach($request->id);

        return new UserResource($user);
    }


    /**
     * Возвращает пользователя после редиректа с страницы соц сети(до этого был редирект на саму страницу соц сети)
     * Используется code в query параметрах
     */
    public function getUserSocialite(Request $request)
    {
        $providerName = $request->providerName;
        // получение данных из соц-сети
        try {
            $userSoc = Socialite::driver($providerName)->stateless()->user();
            // logger(serialize($userSoc));
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseStatus = $response->getStatusCode();
            $responseBodyAsString = $response->getBody()->getContents();
            $errors = [
                401 => 'Код истек или указан не верно. Попробуйте еще раз.',
                400 => 'Код авторизации уже был использован. Попробуйте еще раз.'
            ];

            return $this->sendError($errors[$responseStatus] ?? $responseBodyAsString, $responseStatus);
        }

        $userSocResponse = $this->getUserSocResponse($userSoc, $providerName);

        // если гость, то попытка входа
        if (!$request->loggedIn) {
            // возможно этот акк соц сети уже привязан к кому-то(попытка найти привязки по данным из соц сети)
            $userForAuth = $this->userBySocAccountUid($userSocResponse['user']['uid']);

            // если не нашло по uid
            if (!$userForAuth && $userSocResponse['user']['email']) {
                // поиск по почте
                if ($userForAuth = User::whereHas('emails', function ($query) use ($userSocResponse) {
                    $query->where('email', $userSocResponse['user']['email']);
                })->first()) {
                    // акк с таким uid еще ни к кому не прикреплен то прикрепляет
                    $this->userSaveSocAcc(
                        $userForAuth,
                        $userSocResponse['user'],
                        $providerName
                    );
                }
            }

            // если не авторизован и не удалось получить $userForAuth(соц акк не привязан и нет одинаковой почты)
            if ($userForAuth) {
                // Инфо для авторизации
                $doAuthResponse = $this->getDoAuthResponse($userForAuth, $providerName);
                // ответ вместе с токеном и пользователем чтобы юзер на фронтенде становился авторизированным
                $userSocResponse = array_merge($userSocResponse, $doAuthResponse);
                // return $this->sendResponse(
                //     array_merge($userSocResponse, $doAuthResponse)
                // );
            }
        }

        // если авторизирован или не удается войти
        return $this->sendResponse($userSocResponse);
    }
}
