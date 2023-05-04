<?php

use App\Http\Controllers\api\AuthenticationController;
use App\Http\Controllers\api\CardController;
use App\Http\Controllers\api\FamilyController;
use App\Http\Controllers\api\PaymentController;
use App\Http\Controllers\api\VerifyEmailController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
Route::group(['middleware' => ['cors', 'json.response']], function ()
{

    Route::post('/profiles',[AuthenticationController::class,'get_users'])->name('get.users');
    Route::post('/login', [AuthenticationController::class,'login'])->name('login.api');
    Route::post('/register',[AuthenticationController::class,'register'])->middleware('guest')->name('register.api');

    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:3,1'])
    ->name('verification.verify');

});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticationController::class,'logout'])->name('logout.api');

    Route::group(['middleware'=>'abilities:api-users'],function(){
        Route::post('/card/generate',[CardController::class,'generate'])->name('generate.card');
        Route::get('/card',[CardController::class,'get_card'])->name('get.card');
        Route::post('/email/verify/resend', function (Request $request) {
            $request->user()->sendEmailVerificationNotification();
            return response()->json(['message'=>'email has been sent'],200);
        })->middleware(['throttle:3,1'])->name('verification.send');

        Route::get('/payments/types',[PaymentController::class,'get_payment_types']);

    });

    Route::group(['middleware'=>'abilities:api-family'],function(){
            Route::get('/test/family',function(Request $request){
                return $request->user();
            });
    });

    Route::apiResource('user/family',FamilyController::class);


    Route::get('/test',function(Request $request){
        return Auth::check() == true ? 1 : 0;
    })->middleware(['abilities:api-users','verified']);

    Route::middleware('abilities:api-users')->get('/email/verified', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'verified' => $user->hasVerifiedEmail(),
        ]);
    });



});

