<?php

use App\Http\Controllers\api\AuthenticationController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['cors', 'json.response']], function () {

});
Route::post('/login', [AuthenticationController::class,'login'])->middleware('guest')->name('login.api');
Route::post('/register',[AuthenticationController::class,'register'])->name('register.api')->middleware('guest');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticationController::class,'logout'])->name('logout.api');


    Route::get('/test',function(){
        $last = User::find(5);
        return Auth::user()->transfer($last,10);
    });
});
