<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportController;
use App\Http\Controllers\ProductController;

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
Route::post('login',[PassportController::class,'login']);
Route::post('register',[PassportController::class,'register']);

Route::middleware('auth:api')->group(function (){
    Route::get('product', [ProductController::class, 'index']);
    Route::post('product', [ProductController::class, 'store']);
    Route::put('product/{id}', [ProductController::class, 'update']);
    Route::delete('product/{product}', [ProductController::class, 'destroy']);
    Route::post('/import',[ProductController::class,'import']);
});



 

