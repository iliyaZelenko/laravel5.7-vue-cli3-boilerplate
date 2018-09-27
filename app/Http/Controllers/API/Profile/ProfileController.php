<?php
namespace App\Http\Controllers\API\Profile;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Auth\Events\PasswordReset;
//use App\Traits\Avatar;
use App\Http\Requests\Profile\Current\SetPasswordRequest;
use App\Http\Requests\Profile\Current\SetUserDataRequest;
use App\Http\Requests\Profile\Current\SaveAvatarRequest;
use App\Http\Resources\UserResource;

class ProfileController extends BaseController
{
//    use Avatar;

    /**
     * Change password
     * @param SetPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setPassword(SetPasswordRequest $request)
    {
        $user = $request->user();

        $currentPassword = $request->currentPassword;
        $newPassword = $request->newPassword;

        if (Hash::check($newPassword, $user->password)) {
            return $this->sendError('You had such a password!', 422);
        }

        if (!Hash::check($currentPassword, $user->password)) {
            return $this->sendError('Invalid current password', 422);
        }

        $hashedNewPassword = Hash::make($newPassword);
        $user->password = $hashedNewPassword;
        $user->save();

        return $this->sendResponse([
            'user' => new UserResource($user),
            'message' => 'Password changed successfully!'
        ]);
    }

    /**
     * Set user data
     * @param SetUserDataRequest $request
     * @return UserResource
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

    /**
     * Save avatar
     * @param SaveAvatarRequest $request
     * @return UserResource
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
