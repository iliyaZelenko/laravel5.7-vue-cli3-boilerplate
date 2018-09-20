<?php
namespace App\Http\Controllers\API\Profile;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use App\Traits\EmailVerification;
use App\Traits\Avatar;
use App\Http\Requests\Profile\Current\SetPasswordRequest;
use App\Http\Requests\Profile\Current\SetUserDataRequest;
use App\Http\Requests\Profile\Current\SaveEmailRequest;
use App\Http\Requests\Profile\Current\SavePhoneRequest;
use App\Http\Requests\Profile\Current\SaveAvatarRequest;
use Image;
use File;
use App\Http\Resources\UserResource;
use App\Email;
use App\Phone;
use App\UserPasswordHistroy;

class ProfileController extends BaseController
{
    use EmailVerification, Avatar;

    /**
     * Меняет пароль currentPassword newPassword
     */
    public function setPassword(SetPasswordRequest $request)
    {
        $user = auth()->user();

        if ($request->password) {
            $user->password = Hash::make($request->password);
            $user->save();

            return new UserResource($user);
        }

        $currentPassword = $request->currentPassword;
        $newPassword = $request->newPassword;

        $passwordHistory = $user->passwordsHistory()->get();
        $oldSamePassword = null;

        // приходится сравнивать вот так, но всеравно никто не меняет пароль по сто раз
        $passwordHistory->each(function ($item) use ($newPassword, &$oldSamePassword) {
            if (Hash::check($newPassword, $item->password)) {
                $oldSamePassword = $item;
                return false;
            }
        });

        if ($oldSamePassword) {
            return $this->sendError("У Вас был такой пароль!(добавляли в $oldSamePassword->created_at)", 422);
        }

        if (Hash::check($currentPassword, $user->password)) {
            $hashedNewPassword = Hash::make($newPassword);

            $user->password = $hashedNewPassword;
            $user->save();

            $user->passwordsHistory()->save(new UserPasswordHistroy([
                'password' => $hashedNewPassword
            ]));
        } else {
            return $this->sendError('Не верный текущий пароль', 422);
        }

        return new UserResource($user);
    }

    /**
     * Меняет пароль
     */
    public function setUserData(SetUserDataRequest $request)
    {
        $user = auth()->user();

        $fields = collect($request->all())->keyBy(function ($value, $key) {
            return snake_case($key);
        })->all();

        $user->fill($fields)->save();
        // 'first_name' => $request->firstName,
        // 'last_name' => $request->lastName,
        // 'gender' => $request->gender,
        // 'birthday' => $request->birthday,
        // 'timezone' => $request->timezone,
        // 'country' => $request->country

        return new UserResource($user);
    }

    /** image
     * Сохраняет аватарку
     */
    public function setAvatar(SaveAvatarRequest $request) {
        $user = auth()->user();
        $cropInfo = json_decode($request->cropInfo, true);
        $file = $request->file('file');

        $img = Image::make($file)->crop(
            $cropInfo['width'],
            $cropInfo['height'],
            $cropInfo['x'],
            $cropInfo['y']
        );
        $avatar = $this->setUserAvatar($user, $img);


        return new UserResource($user);
    }
}
