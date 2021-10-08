<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register',[AuthController::class,'register']);
Route::post('verify-email/{id}',[AuthController::class,'verifyEmail']);
Route::post('login-page',[AuthController::class,'loginPage']);


Route::middleware('auth:api')->group(function () {
    //posts and favourite posts
    Route::post('article-create',[ArticleController::class,'articleCreate']);
    Route::post('article-update/{id}',[ArticleController::class,'articleUpdate']);
    Route::post('article-delete/{id}',[ArticleController::class,'articleDelete']);
    Route::get('profile',[AuthController::class,'profile']);
});
