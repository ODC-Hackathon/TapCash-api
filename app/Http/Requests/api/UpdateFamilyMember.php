<?php

namespace App\Http\Requests\api;

use App\Rules\api\CheckAccountPasswordRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UpdateFamilyMember extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('api-family')->check() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $memberID = $this->route('family')->id;
        return [
            'phone_number'=>['required',Rule::unique('family_members','phone_number')->ignore($memberID)],
            'name' => ['required','string','min:3'],
            'password' => ['required', Password::min(8)
            ->mixedCase()
            ->letters()
            ->symbols()
            ->uncompromised(), new CheckAccountPasswordRule($memberID)],
            'pincode'=>['required','numeric','min:3'
            ]
        ];
    }
}
