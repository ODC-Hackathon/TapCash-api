<?php

namespace App\Http\Requests;

use App\Rules\PhonNumberRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreFamilyRequest extends FormRequest
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
            //

            'name'=>['required','string','min:3'],
            // 'sponsor_id'=>['required','exists:users,id'],
            'phone_number'=>['required',new PhonNumberRule()],
            'password'=>['required','min:8','confirmed'],
            'percentage'=>['required','numeric'],
            'user_name'=>['required','unique:family_members,user_name']
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
