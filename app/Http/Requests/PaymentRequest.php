<?php

namespace App\Http\Requests;

use App\Rules\PhonNumberRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('api-users')->check() == true ? true : false ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => ['required'],
            'card_no'=>['required_if:type,Visa','size:16'],
            'cvv'=>['required_if:type,Visa','size:3'],
            'expiration_date'=>['required_if:type,Visa','date'],
            'phone_number'=>
            [
                'required_unless:type,Request,Visa',new PhonNumberRule(),
            ],
            'user_name' =>['required_if:type,Request','exists:users,user_name'],
            'amount'=>['required','numeric','min:1'],
        ];
    }
}
