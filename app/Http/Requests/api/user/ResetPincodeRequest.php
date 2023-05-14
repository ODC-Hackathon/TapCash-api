<?php

namespace App\Http\Requests\api\user;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ResetPincodeRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'password'=>['required',
                    Password::min(8)->mixedCase()
                    ->letters()
                    ->symbols()
                    ->uncompromised()
                ],
            'user_name'=>['required','min:5','exists:users,user_name'],
            'token'=>['required'],
            'pincode'=>['required','numeric','digits:4']
        ];
    }

}
