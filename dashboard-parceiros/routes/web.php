<?php

// Redirect to login
Route::get("/", "AppController@index");

// Rotas para login
Auth::routes(['register' => false, 'verify' => false]);

// Rotas do validador antigo
//Route::group(['prefix' => '/validador-antigo', 'namespace' => 'Validador'], function () {
//
//    // Validador de ticket
//    Route::get('/', 'ValidadorController@index')->name('app.validador.old.index');
//
//    // Detalhes do ticket
//    Route::get('/detalhes', 'ValidadorController@view')->name('app.validador.old.view');
//
//    // Detalhes do ticket
//    Route::put('/validar-voucher', 'ValidadorController@autenticarVoucher')->name('app.validador.old.validar');
//});

// Rotas do painel
Route::group(['prefix' => '/painel', 'namespace' => 'Painel', 'middleware' => ['auth']], function () {

    // Pagina inicial do dashboard
    Route::get("/dashboard", "PainelController@dashboard")->name("app.dashboard");

    // Validador de reservas
    Route::get('/validador', 'ValidadorController@index')->name('app.validador.index');

    // Listagem dos serviços
    Route::get('/servicos', 'ServicosController@index')->name("app.servicos.index");

    // Pagina de meus dados
    Route::group(['prefix' => '/meus-dados'], function () {

        // Dados cadastrais do usuario logado
        Route::get("/", "UsuarioController@index")->name("app.meus-dados.index");

        // View para alterar a senha do usuario
        Route::get("/alterar-senha", "UsuarioController@viewAlterarSenha")->name("app.meus-dados.senha.index");

        // PUT para atualizar os dados cadastrais do usuario logado
        Route::put("/atualizar-dados", "UsuarioController@update")->name("app.meus-dados.update");

        // PUT para alterar a senha do usuario
        Route::put("/atualizar-senha", "UsuarioController@updateSenha")->name("app.meus-dados.senha.update");
    });

    // Rotas para reservas do fornecedor
    Route::group(['prefix' => '/reservas'], function () {

        // Listagem das vendas realizadas do fornecedor
        Route::get("/", 'ReservasController@index')->name("app.reservas.index");

        // Detalhes da reserva
        Route::get('/detalhes/{voucher_id?}', 'ReservasController@view')->name("app.reservas.view");

        // Pesquisa reserva
        Route::get('/pesquisa-reserva', 'ReservasController@searchReserva')->name('app.reservas.search');

        // Pesquisa cliente
        Route::get('/pesquisa-cliente', 'ReservasController@searchCliente')->name('app.reservas.search.cliente');

        // POST para validar a reserva
        Route::post("/validar-reserva", 'ReservasController@validar')->name("app.reservas.validar");
    });

    // Rotas para relatorios do fornecedor
    Route::group(['prefix' => '/relatorios', 'namespace' => 'Relatorios'], function () {

        // Listagem das reservas autenticadas pelo fornecedor
        Route::get("/autenticados", 'AutenticadosController@index')->name("app.relatorios.autenticados");

        // Download das reservas autenticadas pelo fornecedor
        Route::get("/autenticados/download", 'AutenticadosController@download')->name("app.relatorios.autenticados.download");

        // Listagem das vendas realizadas pelo fornecedor
        Route::get("/vendidos", 'VendidosController@index')->name("app.relatorios.vendidos");

        // Download das vendas realizadas pelo fornecedor
        Route::get("/vendidos/download", 'VendidosController@index')->name("app.relatorios.vendidos.download");

        Route::get('/homelist', 'HomelistController@index')->name('app.relatorios.homelist.index');
    });

    // Grupo de rotas para a agenda
    Route::group(['prefix' => "/agenda", 'namespace' => 'Servicos'], function () {

        // Listagem das agendas
        Route::get('/', 'AgendaServicoController@index')->name("app.agenda.index");

        // View para cadastro de uma nova agenda
        Route::get('/novo', 'AgendaServicoController@create')->name("app.agenda.create");

        // View para detalhes da agenda
        Route::get('/detalhes/{agenda_id}', 'AgendaServicoController@view')->name('app.agenda.view');

        // Post para cadastrar uma nova agenda
        Route::post('/cadastrar-agenda', 'AgendaServicoController@store')->name('app.agenda.store');

        // Put para atualizar as configuracoes da agenda
        Route::put('/atualizar', 'AgendaServicoController@update')->name('app.agenda.update');

        // Rotas para as datas do calendario
        Route::group(['prefix' => '/datas'], function () {

            // Datas para o calendario administrativo
            Route::get('/calendario/{agenda_id}', 'DatasAgendaController@datasCalendario')->name('app.agenda.datas.calendario');

            // Recupera as informacoes da data
            Route::get("/detalhes/{data_id}", "DatasAgendaController@view")->name("app.agenda.datas.view");

            // Post para criar novas datas
            Route::post('/cadastrar-datas', 'DatasAgendaController@store')->name('app.agenda.datas.store');

            // Put para alterar os dados das datas
            Route::put('/alterar-datas', 'DatasAgendaController@update')->name('app.agenda.datas.update');

            // Put para atualizar uma unica data
            Route::put('/atualizar-data', 'DatasAgendaController@updateOne')->name('app.agenda.datas.update-one');

            // Delete para remover datas da agenda
            Route::delete('/remover-datas', 'DatasAgendaController@remove')->name('app.agenda.datas.remove');
        });

        // Rotas para as substituicoes na agenda
        Route::group(['prefix' => '/substituicoes'], function () {

            // GET para detalhes da substituicao
            Route::get('/detalhes/{agenda_id}', 'SubstituicoesAgendaController@view')->name('app.agenda.substituicao.view');

            // POST para cadastrar uma substituicao
            Route::post('/cadastrar', 'SubstituicoesAgendaController@store')->name('app.agenda.substituicao.store');

            // PUT para editar uma substituicao
            Route::put('/atualizar-substituicao', 'SubstituicoesAgendaController@update')->name('app.agenda.substituicao.update');
        });
    });

    Route::group(['prefix' => '/faturas'], function() {
        Route::get('/', 'FaturaController@index')->name('app.faturas.index');

        Route::get('/fatura', 'FaturaController@show')->name('app.faturas.show');

        Route::get('/faturas-previstas', 'FaturaController@faturasPrevistas')->name('app.faturas.fatura-prevista');

        Route::get('/fatura-prevista', 'FaturaController@faturaPrevista')->name('app.faturas.fatura-prevista-individual');
    });

});
