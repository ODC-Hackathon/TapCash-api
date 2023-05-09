<?php

namespace App\Http\Requests\api;

use App\Rules\PhonNumberRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserAccountData extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('api-users')->check() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $user = $this->route('user')->id;
        return [
            'email'=>['required',Rule::unique('users','email')->ignore($user)],
            'name'=>['required','min:3','string'],
            'password'=>['required',
                Password::min(8)->mixedCase()
                ->letters()
                ->symbols()
                ->uncompromised()
            ],
            'phone_number'=>['required',Rule::unique('users','phone_number')->ignore($user),new PhonNumberRule()]
        ];
    }
}
