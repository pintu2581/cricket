<?php

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

Route::post('auth-key', [\App\Http\Controllers\TokenGenerateController::class,'getToken']);
//Route::get('top-tournament', [\App\Http\Controllers\::class,'getToken']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
