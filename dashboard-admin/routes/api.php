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

Route::group(['prefix' => '/api-expanda/v1', 'namespace' => 'APIExpanda', 'middleware' => 'api.expanda'], function () {

    $name_prefix = 'api.api-expanda.v1.';

    Route::get('/produtos', 'ServicosControllerExpanda@servicos')->name($name_prefix.'produtos');

    Route::get('/produto', 'ServicosControllerExpanda@servico')->name($name_prefix.'produto');

    Route::get('/disponibilidade', 'ServicosControllerExpanda@disponibilidade')->name($name_prefix.'disponibilidade');

    Route::post('/efetuar-venda', 'PagamentoControllerExpanda@efetuarVenda')->name($name_prefix . 'efetuar-venda');
});
