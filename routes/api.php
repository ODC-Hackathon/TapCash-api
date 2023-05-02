<?php

use App\Http\Controllers\api\CategoryContoller;
use App\Http\Controllers\api\Sub_CategoryController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/check',function(){
    return Auth::check() == false? 0 : 1;
});

Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::group(['middleware'=>['auth:api','auth:sanctum'] ],function(){
        Route::get('/test',function(){
            return  Auth::user()->balance;
        });

        Route::get('/categories',[CategoryContoller::class,'getCategories']);
        Route::get('/subcategories',[Sub_CategoryController::class,'get_sub_categories']);
    });
});


Route::middleware(['auth:api'])->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('/register', [RegisteredUserController::class, 'store'])
//                 ->middleware('guest')
//                 ->name('register');
require __DIR__.'/auth.php';
