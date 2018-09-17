<?php
namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use App\User;
use App\Email;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Auth\ForgotPasswordEmailRequest;

class ForgotPasswordController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
     */
    use SendsPasswordResetEmails;

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(ForgotPasswordEmailRequest $request)
    {
        $resetUrl = $request->input('resetUrl');
        $credentials = array_filter($request->only(['email', 'nickname']));

        if (isset($credentials['email'])) {
            // $user = User::whereHas('emails', function ($query) use ($credentials) {
            //     $query->where('email', $credentials['email']);
            // })->first();
            $email = Email::ofEmail($credentials['email'])->first();
            $user = $email->user()->first(); // ->first()
        } else if (isset($credentials['nickname'])) {
            $user = User::where('nickname', $credentials['nickname'])->first();
            // TODO правильнее конечно возможно было сделать выбор между публичными адресами и главным, но пока так
            if ($user) { $email = $user->mainEmail; }
        }


        if (!$user) {
            $input = isset($credentials['email']) ? 'почте' : 'нику';
            return $this->sendError(trans('passwords.user', ['input' => $input]), 404);
        }
        $emailForMail = $email->email;
        // ставит через какую почту сбрасывать пароль
        $user->setEmailForResetPassword($emailForMail);
        $token = $this->broker()->createToken($user);
        $resetUrlWithToken = "$resetUrl/$token/{$emailForMail}";

        Mail::to([
            'email' => $user->emails()->ofEmail($emailForMail)->first()
        ])->send(new ForgotPassword($resetUrlWithToken));

        return $this->sendResponse(NULL, trans('passwords.sent'));
    }
}
