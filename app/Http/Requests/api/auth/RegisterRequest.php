<?php

namespace App\Http\Requests\api\auth;

use App\Rules\PhonNumberRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255|min:6',
            'email' => 'required|string|email|max:255|unique:users',
            'user_name'=>['required','string','unique:users,user_name','min:6'],
            'phone_number'=>['required',new PhonNumberRule(),'unique:users,phone_number'],
            'password' => ['required',
            Password::min(8)
            ->mixedCase()
            ->letters()
            ->symbols()
            ->uncompromised()
            ],
            'SSN'=>['required','min:13','numeric'],
            'pincode'=>['required','numeric','min:3']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([

            'success'   => false,

            'message'   => 'Validation errors',

            'data'      => $validator->errors()

        ]),400);
    }
}
