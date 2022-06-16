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

Route::group(['prefix' => 'providers'],function (){
    Route::controller(\App\Http\Controllers\ProvidersController::class)->group(function () {
        Route::post('all',              'index');
        Route::post('create',           'store');
        Route::post('show/{provider}',  'show');
        Route::post('update/{provider}','update');
        Route::post('delete/{provider}','delete');
    });
});

Route::group(['prefix' => 'messages'],function (){
    Route::controller(\App\Http\Controllers\MessagesController::class)->group(function () {
        Route::post('send',    'store');
    });
});
