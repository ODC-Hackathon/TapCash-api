<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\api\AuthenticationController;
use App\Http\Controllers\api\CardController;
use App\Http\Controllers\api\FamilyController;
use App\Http\Controllers\api\NotificationController;
use App\Http\Controllers\api\PaymentController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\VerifyEmailController;
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
            // Route::get('/balance',function(Request $request){
            //     $user = User::find($request->user()->id);
            //     // $user->deposit(1000,['method'=>'test']);
            //     $transaction = Transaction::find(44);
            //     return $user->wallet->confirm($transaction);
            //     $last = User::find(6);
            //     // $blanaced = $user->transfer($last,100);
            //     // $test = new TransactionService();
            //     // return $test->getUserId();
            //     // $transactios= User::where('id',$user->id)->with(['transactions'=>function($query){
            //     //     $query
            //     //     ->select('type','amount','confirmed','payable_id','id');
            //     // }])->get();
            //     // return $transactios;
            //     // $transfers= User::where('id',$user->id)->with(['transfers'=>function($query) use ($last){
            //     //     $query->where('to_id',$last->wallet->id);
            //     // }])->get();
            //     return array('Vodafone Cash','Etisalat Cash','Orange Cash','Visa','Fawry');
            // });

        });

        Route::group(['middleware'=>['abilities:api-family','family']],function(){
            Route::post('family/pay-service',[FamilyController::class,'CreateService']);
            Route::get('family/transactions',[FamilyController::class,'get_Transactions']);
            Route::get('family/notifications',[NotificationController::class,'index']);
        });

    });
});



Route::middleware(['auth:sanctum','json.response'])->group(function ()
{

    Route::post('/logout', [AuthenticationController::class,'logout'])
    ->name('logout.api');

    Route::group(['middleware'=>'abilities:api-users'],function()
    {

        Route::post('/email/verify/resend', function (Request $request) {
            $request->user()->sendEmailVerificationNotification();
            return response()->json(['message'=>'email has been sent'],200);
        })->middleware(['throttle:3,1'])->name('verification.send');

    });
});

