<?php

namespace App\Http\Requests\api\auth;

use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_name' => ['required', 'string'],
            'pincode' => ['required', 'numeric'],
            'type'=>['required','string'],
        ];
    }
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
        $attempeted = false;
        if(strcmp($this->type ,'user') ===0)
        {
            $user = User::where('user_name',$this->user_name)
            ->first();
            $attempeted = $this->check_authentication_pincode($user,$this->pincode,'api-users');

        }elseif(strcmp($this->type ,'family') === 0)
        {
            $family = FamilyMember::where('user_name',$this->user_name)
            ->first();
            $attempeted = $this->check_authentication_pincode($family,$this->pincode,'api-family');
        }
        if ($attempeted === false) {
            RateLimiter::hit($this->throttleKey());
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'username' => __('auth.failed')
                ]

            ]),400);
        }
        RateLimiter::clear($this->throttleKey());
    }

    public function check_authentication_pincode($user,$pincode,$guard)
    {
        if($user && Hash::check($pincode,$user->pincode))
        {
            Auth::guard($guard)->login($user);
            return true;
        }
        else
            return false;

    }
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('username')).'|'.$this->ip());
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()

        ]),400);
    }
}
