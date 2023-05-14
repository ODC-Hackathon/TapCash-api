<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\RestPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register'=>false,'login'=>false]);

// Route::get('password/reset', 'App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

Route::get('pincode/reset/{token}',[AccountController::class,'show_reset_form'])->name('send.pincode.reset');
Route::post('pincode/reset',[AccountController::class,'resetPincode'])->name('reset.pincode');



// Route::post('password/reset', [RestPasswordController::class,'reset'])->name('password.update');

// Route::get('/',function(){
//     return view('welcome');
// });
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/register',function(){
//     return view('auth.register');
// });
