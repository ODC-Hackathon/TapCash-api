<?php

namespace App\Http\Requests\api\auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
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
            'password' => ['required', 'string'],
            'type'=>['required','string'],
        ];
    }
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
        $credentials = $this->only('user_name', 'password');
        $member_token = $this->boolean('remember');
        $attempeted = false;
        if($this->type == 'user')
        {
            $attempeted = Auth::guard('api-users')->attempt($credentials,$member_token);
        }elseif($this->type == 'family')
        {
            $attempeted = Auth::guard('api-family')->attempt($credentials,$member_token);
        }
        if ($attempeted == false) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'username' => __('auth.failed'),
            ]);
        }
        RateLimiter::clear($this->throttleKey());
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
            'success'   => false,

            'message'   => 'Validation errors',

            'data'      => $validator->errors()

        ]),400);
    }
}
