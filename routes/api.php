<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\api\AuthenticationController;
use App\Http\Controllers\api\CardController;
use App\Http\Controllers\api\FamilyController;
use App\Http\Controllers\api\NotificationController;
use App\Http\Controllers\api\PaymentController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\VerifyEmailController;
use App\Models\Account;
use App\Models\User;
use App\Services\Wallet\TransactionService;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
Route::group(['middleware' => ['cors','json.response']], function ()
{
    Route::post('/profiles',[AccountController::class,'profiles'])
    ->middleware('account.verify')
    ->name('get.profiles');

    Route::post('/register',[AuthenticationController::class,'register'])
    ->middleware('guest')
    ->name('register.api');

    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['throttle:3,1'])
    ->name('verification.verify');

    Route::group(['middleware' => ['auth:sanctum','account.verify']],function(){

        Route::group(['middleware'=>['abilities:accounts'] ],function(){
            Route::post('/login', [AuthenticationController::class,'login'])
            ->name('login.api');
        });

        Route::group(['middleware'=>['abilities:api-users','users']],function(){
            Route::post('/card/generate',[CardController::class,'create'])
            ->name('generate.card');

            Route::get('/card',[CardController::class,'get_card'])->name('get.card');

            Route::get('/payments/types',[PaymentController::class,'get_payment_methods']);

            Route::get('users/',[UserController::class,'get_users']);
            Route::post('/add-money',[PaymentController::class,'AddMoney']);
            Route::post('/payment/confirm',[PaymentController::class,'ConfirmPayment']);
            Route::get('/user/notifcations',[NotificationController::class,'index'])->name('user.notifications');
            Route::post('/user/send-money',[PaymentController::class,'SendMoney']);
            Route::post('/user/create-family-member',[UserController::class,'storeFamilyMember']);
            Route::post('/user/pay-service',[UserController::class,'createservice']);

            Route::get('user/transactions',[UserController::class,'get_Transactions']);
            Route::get('family/{member:user_name}/transactions',[UserController::class,'get_member_transaction']);
            Route::get('/user/balance',[UserController::class,'getBalance']);
            Route::get('/user',[UserController::class,'get_UserData']);
            Route::put('/user/{user:id}',[UserController::class,'update_user_accountData']);
            Route::put('user/family/{family:user_name}',[UserController::class,'update_memberData']);
            Route::get('user/family/{family:user_name}',[UserController::class,'get_MemberData']);

        });

        Route::group(['middleware'=>['abilities:api-family','family']],function(){
            Route::post('family/pay-service',[FamilyController::class,'CreateService']);
            Route::get('family/transactions',[FamilyController::class,'get_Transactions']);
            Route::get('family/notifications',[NotificationController::class,'index']);
            Route::put('/family/{family:user_name}',[FamilyController::class,'update']);
        });

    });
});



Route::middleware(['auth:sanctum','json.response'])->group(function ()
{

    Route::post('/logout', [AuthenticationController::class,'logout'])
    ->name('logout.api');
});

Route::post('/email/verify/resend', function (Request $request){
    return $request->all();
    $account = Account::find($request->id);
    $account->sendEmailVerificationNotification();
    return response()->json(['data'=>['message'=>'email has been sent']],200);
})->middleware(['throttle:3,1'])->name('api.verification.send');
