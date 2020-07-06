<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//wallet
Route::post('wallet/client', 'walletController@addClients')->name('addClients');
Route::post('wallet/addWallet', 'walletController@addWallet')->name('addWallet');
Route::put('wallet/recharge', 'walletController@rechargeWallet')->name('rechargeWallet');
Route::post('wallet/payments', 'walletController@paymentsWallet')->name('paymentsWallet');

