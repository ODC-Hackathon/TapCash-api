<?php

namespace App\Http\Requests\api;

use App\Rules\api\CheckAccountPasswordRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserUpdateMemberRequest extends FormRequest
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
        $user = $this->user();
        return [
            'pincode'=>['required','numeric','min:3'],
            'amount'=>['required','numeric','min:0'],
            'password'=>['required',Password::min(8)
            ->mixedCase()
            ->letters()
            ->symbols()
            ->uncompromised(),
            new CheckAccountPasswordRule($user)
            ]
        ];
    }
}
