<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => config('validation.reset_password_token'),
            'email' => 'required|' . config('validation.email'),
            'password' => config('validation.password')
//            'password_confirmation' => 'required|string|min:6|same:password'
        ];
    }
}
