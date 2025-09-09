<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgetPasswordController;
use App\Http\Controllers\Api\Auth\RestPasswordController;

use Illuminate\Support\Facades\Route;













/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group(['middleware'=>['userLangApi']],function(){
  
    Route::group(['prefix'=>'auth'],function(){
        Route::post('login',[AuthController::class,'login']);
        Route::post('logout',[AuthController::class,'logout'])->middleware('auth-api');
        Route::post('forget/password',[ForgetPasswordController::class,'ForgetPassword']);
        Route::post('rest/password',[RestPasswordController::class,'RestPassword']);
    });
  

});