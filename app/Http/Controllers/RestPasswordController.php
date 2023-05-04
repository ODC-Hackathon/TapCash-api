<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
class RestPasswordController extends Controller
{
    //
    use ResetsPasswords;

     /**
     * Display the password reset view for the given token.
     *
     * @param  string|null  $token
     * @return \Illuminate\View\View
     */
    public function showResetForm($token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => request('email')]
        );
    }



    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {

        $request->validate($this->rules(), $this->validationErrorMessages());

        $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return redirect($this->redirectPath())
                    ->with('status', trans('passwords.reset'));
    }

    public function resetPassword($user,$password)
    {
        $user->update([
            'password' => $password
        ]);
    }
    public function broker()
    {
        return \Illuminate\Support\Facades\Password::broker();
    }

    protected function rules()
    {
        return [
            '_token' => 'required',
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required',
            Password::min(8)
            ->mixedCase()
            ->letters()
            ->symbols()
            ->uncompromised()
            ],
        ];
    }
}
