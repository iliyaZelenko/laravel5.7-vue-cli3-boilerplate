<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordEmailRequest extends FormRequest
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
        $rules = [];
        if (request()->email) {
            $rules['email'] = 'required|' . config('validation.email');
        }
        if (request()->nickname) {
            $rules['nickname'] = 'required|' . config('validation.nickname');;
        }

        return $rules;
    }
}
