<?php

/*
|--------------------------------------------------------------------------
| Cliente Auth Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => '/cliente', 'namespace' => 'Auth'], function () {

    // Página de fazer login
    Route::get('/login', 'LoginController@showLoginForm')->name('ecommerce.cliente.login');

    // URL para recuperar a senha via e-mail
    Route::get('/redefinir-senha/{token}', 'ResetPasswordController@showResetForm')->name('ecommerce.cliente.alterar-senha');

    // Post para realizar login
    Route::post('/auth-login', 'LoginController@login')->name('ecommerce.cliente.post-login');

    // Post para fazer cadastro
    Route::post('/novo-cadastro', 'RegisterController@register')->name('ecommerce.cliente.store');

    // Post para fazer logout
    Route::post('/auth-logout', 'LoginController@logout')->name('ecommerce.cliente.logout');

    // Post para recuperar a senha de login
    Route::post('/recuperar-senha', 'ForgotPasswordController@sendResetLinkEmail')->name('ecommerce.cliente.recuperar-senha');

    // Post para cadastrar a nova senha de login
    Route::post('/cadastrar-senha', 'ResetPasswordController@reset')->name('ecommerce.cliente.cadastrar-senha');
});

/*
|--------------------------------------------------------------------------
| Cliente Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => '/cliente', 'namespace' => 'Cliente', 'middleware' => ['auth']], function () {

    // Rotas para meus dados cliente
    Route::group(['prefix' => '/meus-dados'], function () {

        // Listagem dos pedidos
        Route::get('/', 'MeusDadosController@index')->name('ecommerce.cliente.meus-dados.index');

        // PUT para atualizar os dados do cliente
        Route::put('/atualizar-dados', 'MeusDadosController@update')->name('ecommerce.cliente.meus-dados.update');
    });

    // Rota para pedidos do cliente
    Route::group(['prefix' => '/pedidos-realizados'], function() {

        // Listagem dos pedidos
        Route::get('/', 'PedidosController@index')->name('ecommerce.cliente.pedidos.index');

        // Detalhes do pedido
        Route::get('/detalhes/{codigo_pedido}', 'PedidosController@view')->name('ecommerce.cliente.pedidos.view');

        // Impressao do voucher da reserva
        Route::get('/imprimir/{voucher}', 'PedidosController@print')->name('ecommerce.cliente.pedidos.print');

        Route::get('/informacao-finalizacao', 'PedidosController@informacaoFinalizacao')->name('ecommerce.cliente.pedidos.informacao-finalizacao');

        Route::post('/informacao-finalizacao', 'PedidosController@informacaoFinalizacaoStore')->name('ecommerce.cliente.pedidos.informacao-finalizacao-store');
    });
});
