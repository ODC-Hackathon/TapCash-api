<?php

use App\Http\Controllers\api\AuthenticationController;
use App\Http\Controllers\api\CardController;
use App\Http\Controllers\api\FamilyController;
use App\Http\Controllers\api\PaymentController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::post('/login', [AuthenticationController::class,'login'])->middleware('guest')->name('login.api');
    Route::post('/register',[AuthenticationController::class,'register'])->name('register.api')->middleware('guest');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthenticationController::class,'logout'])->name('logout.api');

        Route::group(['middleware'=>'abilities:api-users'],function(){
            Route::post('/card/generate',[CardController::class,'generate'])->name('generate.card');
            Route::get('/card',[CardController::class,'get_card'])->name('get.card');

        });
        Route::apiResource('user/family',FamilyController::class);

        Route::get('/payments/types',[PaymentController::class,'get_payment_types']);
        
        Route::get('/test',function(Request $request){
            $user = User::findOrfail(4);
            return  $request->user()->transfer($user,1000);
        });
    });
});

