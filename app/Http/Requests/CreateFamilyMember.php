<?php

namespace App\Http\Requests;

use App\Rules\PhonNumberRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class CreateFamilyMember extends FormRequest
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
        return [
            'name'=>['required','string','min:3'],
            'phone_number' => ['required',new PhonNumberRule(),'unique:family_members,phone_number'],
            'pincode'=>['required','numeric','min:3'],
            'user_name'=>['required','unique:family_members,user_name','min:5'],
            'age'=>['required','numeric','min:10','max:16'],
            'percentage'=>['required','numeric','min:0'],
            'sponser'=>['required','exists:users,user_name'],
            'permissions'=>['required','json']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()

        ]),400);
    }
}
