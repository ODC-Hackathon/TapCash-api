<?php

use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\RestPasswordController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register'=>false,'login'=>false]);

// Route::get('password/reset', 'App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// Route::get('password/reset/{token}', [RestPasswordController::class,'showResetForm'])->name('password.reset');
// Route::post('password/reset', [RestPasswordController::class,'reset'])->name('password.update');

Route::get('/',function(){
    return view('welcome');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
