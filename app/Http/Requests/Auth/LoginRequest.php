<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
        $rules = [
            'password' => config('validation.password')
        ];

        if (request()->email) {
            $rules = array_merge($rules, [
                'email' => 'required|' . config('validation.email'),
                // 'password' => 'required|string|min:5'
            ]);
        }
        if (request()->name) {
            $rules = array_merge($rules, [
                'name' => 'required|' . config('validation.name')
            ]);
        }

        return $rules;
    }
}
