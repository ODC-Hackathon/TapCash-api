<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    //
    public function __invoke(Request $request)
    {

        $account = Account::find(User::find($request->route('id'))->account->id);

        if ($account->hasVerifiedEmail()) {
            return 'success';
        }

        if ($account->markEmailAsVerified()) {
            event(new Verified($account));
        }

        return 'success';
    }
}
