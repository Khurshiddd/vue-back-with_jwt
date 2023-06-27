<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('register',[AuthController::class,'register']);
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);

});
Route::get('index',[PostController::class,'index']);
Route::post('store',[PostController::class,'store'])->middleware('jwt.auth');
Route::post('show/{post}',[PostController::class,'show'])->middleware('jwt.auth');
Route::put('update/{post}',[PostController::class,'update'])->middleware('jwt.auth');
Route::delete('destroy/{post}',[PostController::class,'destroy'])->middleware('jwt.auth');
