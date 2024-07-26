<?php

/*
|--------------------------------------------------------------------------
| Marketplace Routes
|--------------------------------------------------------------------------
*/

// routes/web.php
Route::get('/env-test', function () {
    return [
        'APP_NAME' => env('APP_NAME'),
        'APP_ENV' => env('APP_ENV'),
        'CANAL_VENDA_ID' => env('CANAL_VENDA_ID'),
        'TEST_VARIABLE' => env('TEST_VARIABLE')
    ];
});


Route::group(['namespace' => 'Ecommerce'], function () {

    Route::group(['prefix' => '/admin-ecommerce-api'], function() {

        Route::post('/enviar-email-cliente-fornecedor', 'AdminEcommerceAPIController@solicitarEnvioDeEmailAposVendaInterna')->name('ecommerce.email-venda-interna');

    });

    // Rota para limpar o cache da aplicação
    Route::get(\TourFacil\Core\Enum\CanaisVendaEnum::URL_CACHE_CLEAR, 'HomeController@cacheClear');

    //Route::get("/cancel", "HomeController@cancelGetnet");

    // Valida e verifica se o email ja existe na base de clientes
    Route::post("/verificar-email", "HomeController@verificarEmail")->name("ecommerce.verificar-email");

    // Rota para cadastro na newsltter
    Route::post("/newsletter", "HomeController@newsletter")->name("ecommerce.newsletter.store");

    // Detalhes do destino utilizado na home
    Route::get("/detalhes-destino/{destino_slug?}", "HomeController@view")->name("ecommerce.destino.view");

    // Grupo de rotas para trackers
    Route::group(['prefix' => '/trackers'], function () {

        // Feed de servicos para o Facebook
        Route::get('/facebook-csv', 'TrackersController@facebookCsv');

        // Feed de servicos para o Merchant Center
        Route::get('/google-csv', 'TrackersController@googleCsv');

        // Feed de servicos para o Google Adwords
        Route::get('/google-adwords', 'TrackersController@googleAdwords');
    });

    // Rotas para a central de ajuda
    Route::group(['prefix' => '/'], function () {

        // Termos e condicoes
        Route::get('/termos-e-condicoes', 'CentralAjudaController@termos')->name('ecommerce.ajuda.termos');

        // Politica de privacidade
        Route::get('/politica-de-privacidade', 'CentralAjudaController@politicaPrivacidade')->name('ecommerce.ajuda.privacidade');

        // Politica de cancelamento
        Route::get('/politica-de-cancelamento', 'CentralAjudaController@politicaCancelamento')->name('ecommerce.ajuda.cancelamento');
    });

    // Rota para recuperar dados sobre o serviço
    Route::group(['prefix' => '/servico'], function () {

        // Datas para o calendario
        Route::get('/calendario/{uuid}', 'ServicoController@calendario')->name('ecommerce.servico.calendario');

        // Dados sobre o servico
        Route::get('/informacoes/{uuid}', 'ServicoController@viewJson')->name('ecommerce.servico.view.json');
    });

    // Rotas para carrinho de compras
    Route::group(['prefix' => '/carrinho-de-compras'], function () {

        // Carrinho de compras
        Route::get('/', 'CarrinhoController@index')->name('ecommerce.carrinho.index');

        // Página de pagamento
        Route::get('/pagamento', 'PagamentoController@index')->name('ecommerce.carrinho.pagamento');

        // Página de pagamento
        Route::post('/pagamento', 'PagamentoController@index')->name('ecommerce.carrinho.forma-pagamento');

        // Página de pagamento
        Route::post('/pagamento', 'PagamentoController@index')->name('ecommerce.carrinho.forma-pagamento');

        // Página de resultado do pagamento
        Route::get('/pagamento-realizado', 'PagamentoController@pagamentoRealizado')->name('ecommerce.carrinho.pagamento.sucesso');

        // Página de pagamento nao autorizado
        Route::get('/pagamento-nao-autorizado', 'PagamentoController@pagamentoNaoRealizado')->name('ecommerce.carrinho.pagamento.falha');

        // POST para efetuar pagamento
        Route::post('/efetuar-pagamento', 'PagamentoController@efetuarPagamento')->name('ecommerce.carrinho.efetuar-pagamento');

        // POST para gerar o pedido
        Route::post('/gerar-pedido', 'PagamentoController@gerarPedido')->name('ecommerce.carrinho.gerar-pedido');

        // POST para retornar se o pix ja foi pago
        Route::post('/consultar-pagamento-pix', 'PagamentoController@consultarPagamentoPix')->name('ecommerce.carrinho.consultar-pagamento-pix');

        // POST para retornar pix
        Route::post('/gerar-qrcode-pagamento-pix', 'PagamentoController@gerarQrcodePagamentoPix')->name('ecommerce.carrinho.gerar-qrcode-pagamento-pix');

        // Post para remover o pix da sessão (Exemplo: Trocar meio de pagamento)
        Route::post('/cancelar-pix-sessao', 'PagamentoController@cancelarPixSessao')->name('ecommerce.carrinho.cancelar-pix-sessao');

        // Rota para adicionar servico no carrinho
        Route::post('/adicionar', 'CarrinhoController@add')->name('ecommerce.carrinho.add');

        // Rota para remover servico no carrinho
        Route::delete('/remover/{servico_uuid}', 'CarrinhoController@remove')->name('ecommerce.carrinho.remove');

        // Rota para limpar o carrinho de compras
        Route::delete('/limpar', 'CarrinhoController@zerarCarrinho')->name('ecommerce.carrinho.clear');

        // POST para retornar o form como json
        Route::post('/form-parse', 'CarrinhoController@formParse')->name('ecommerce.carrinho.form-parse');

        // Rota para receber links de venda interna
        Route::get('/link/{uuid}', 'CarrinhoController@linkVenda')->name('ecommerce.carrinho.link-venda');

        Route::post('/cliente/sessao', 'PagamentoController@clienteSessao')->name('ecommerce.carrinho.cliente.sessao');
    });

    // Rota para pesquisa
    Route::get("/pesquisar", "PesquisaController@pesquisar")->name("ecommerce.servico.search");

    // Rota para pesquisa via AJAX
    Route::get("/search", "PesquisaController@searchAjax")->name("ecommerce.servico.search-ajax");

    // Página inicial
    Route::get("/", "HomeController@index")->name("ecommerce.index");

    // Rota dos destinos
    Route::get("/{destino_slug}", "DestinoController@index")->name("ecommerce.destino.index");

    // Rota dos destinos
    Route::get("/{destino_slug}/{categoria_slug}", "CategoriaController@index")->name("ecommerce.categoria.index");

    // Rota para detalhes do servico
    Route::get("/{destino_slug}/{categoria_slug}/{servicos_slug}", "ServicoController@view")->name("ecommerce.servico.view");

    // Rota de grupos para os cupons de desconto
    Route::group(['prefix' => '/cupom-desconto'], function() {

        Route::post('/adicionar', 'CupomDescontoController@adicionarCupom')->name('ecommerce.cupom.adicionar');

        Route::post('/remover', 'CupomDescontoController@removerCupom')->name('ecommerce.cupom.remover');

    });
});
