<?php

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
Route::group(['middleware' => ['api'], 'namespace' => '\App\Http\Controllers\Api'], function () {
    Route::resource('users', 'UsersController');

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login');
    });

    Route::group(['middleware' => ['auth']], function () {
        Route::resource('transactions', 'TransactionsController');
        Route::get('wallets/{address}/transactions', 'WalletsController@transactions');
        Route::resource('wallets', 'WalletsController');
    });
});
