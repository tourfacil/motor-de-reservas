<?php
use App\Exports\Terminais\RelatorioVendasTerminaisExport;
use TourFacil\Core\Models\ReservaPedido;
use TourFacil\Core\Services\Integracao\PWI\PWIAPI;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/teste', function() {

//     $api = new PWIAPI();




//     $produtos_skyglass = $api->consultarProdutos()['data'];
//     $produtos_skyglass = collect($produtos_skyglass);


//     $integracoes = $api->consultarVendas(Carbon::parse('2022-01-01')->format('Ymd'), Carbon::parse('2023-12-31')->format('Ymd'))['data']['vendas'];
//     dd($integracoes);
//     $dados = [];


//     foreach($integracoes as $integracao) {



//         $reserva = ReservaPedido::where('id', $integracao['idVendaOrigem'])->get();

//         $servico = $reserva->first()->servico->nome;

//         $texto_quantidade = "";
//         $texto_quantidade_skyglass = "";
//         $quantidade_tf = 0;
//         $quantidade_sky = 0;

//         foreach($reserva->first()->quantidadeReserva as $quantidade) {

//             $quantidade_tf += $quantidade->quantidade;

//             $value = number_format($quantidade->valor_net, 2, ',', '.');
//             $texto_quantidade .= "({$quantidade->quantidade} - {$quantidade->variacaoServico->nome} - (R$ {$value}) | \n";

//         }

//         foreach($integracao['itens'] as $item) {

//             $quantidade_sky += $item['qtde'];

//             $value = number_format($item['valor'], 2, ',', '.');
//             $name = $produtos_skyglass->where('id', $item['idProduto'])->first()['nome'];
//             $texto_quantidade_skyglass .= "({$item['qtde']}) - {$name} - (R$ {$value}) | \n";

//         }

//         $dados[] = [
//             'id_tourfacil' => $reserva->first()->id,
//             'voucher' => $reserva->first()->voucher,
//             'id_skyglass' => $integracao['id'],
//             'valor_tourfacil' => $reserva->first()->valor_net,
//             'valor_skyglass' => $integracao['total'],
//             'quantidade_integrado' => $reserva->count(),
//             'texto_quantidade' => $texto_quantidade,
//             'texto_quantidade_skyglass' => $texto_quantidade_skyglass,
//             'venda' => $reserva->first()->created_at->format('d/m/Y'),
//             'utilizacao' => $reserva->first()->agendaDataServico->data->format('d/m/Y'),
//             'status_tourfacil' => $reserva->first()->status,
//             'status_skyglass' => ($integracao['dataHoraCancelado'] == null) ? 'ATIVA' : 'CANCELADA',
//             'quantidade_tf' => $quantidade_tf,
//             'quantidade_sky' => $quantidade_sky,
//             'problema' => ($servico == 'Ingresso Skyglass' ? 'NAO' : 'SIM')
//         ];
//     }

//     $view_name = "teste";
//     $dados['dados'] = $dados;

//     return (new RelatorioVendasTerminaisExport($view_name, $dados))->download("Vendidos.xlsx");
// });

// Route::get('/exemplo-api', function () {


//     $data = [

//         'metodo_pagamento' => 'CREDITO',
//         'pagamento' => [
//             'cartao' => [
//                 'numero' => '4000000000000010',
//                 'nome' => 'Desenvolvimento Tour Fa',
//                 'cvv' => '123',
//                 'validade_ano' => '28',
//                 'validade_mes' => '12',
//                 'bandeira' => 'VISA',
//             ],
//             'parcelamento' => 1,
//         ],
//         'cliente' => [
//             'nome' => 'Desenvolvimento Tour Fa',
//             'email' => 'dev@tourfacil.com.br',
//             'cpf' => '13952721077',
//             'nascimento' => '01/01/2000',
//             'telefone' => '54978343428',
//             'rua' => 'Rua João Alfredo',
//             'numero' => '312',
//             'bairro' => 'Centro',
//             'cidade' => 'Gramado',
//             'estado' => 'RS',
//             'cep' => '95670-000'
//         ],
//         'servicos' => [
//             [
//                 'uuid' => '51b81af4-d8d6-11ea-804b-0242ac120006',
//                 'codigo_data' => 6185,
//                 'clientes' => [
//                     [
//                         'codigo_categoria' => 86,
//                         'nome' => 'Vinicius',
//                         'documento' => '13952721077',
//                         'nascimento' => '01/03/2000',
//                     ],
//                     [
//                         'codigo_categoria' => 86,
//                         'nome' => 'Guimarães',
//                         'documento' => '13952721077',
//                         'nascimento' => '01/03/2000',
//                     ],
//                     [
//                         'codigo_categoria' => 87,
//                         'nome' => 'Bardina',
//                         'documento' => '13952721077',
//                         'nascimento' => '01/03/2000',
//                     ],

//                 ],
//                 'campos_adicionais' => [
//                     [
//                         'codigo_campo_adicional' => 23,
//                         'campo' => 'Hotel',
//                     ],
//                     [
//                         'codigo_campo_adicional' => 23,
//                         'campo' => 'Hotel',
//                     ],
//                 ],
//             ],
//             [
//                 'uuid' => '51b81af4-d8d6-11ea-804b-0242ac120006',
//                 'codigo_data' => 6185,
//                 'clientes' => [
//                     [
//                         'codigo_categoria' => 86,
//                         'nome' => 'Vinicius',
//                         'documento' => '13952721077',
//                         'nascimento' => '01/03/2000',
//                     ],
//                     [
//                         'codigo_categoria' => 88,
//                         'nome' => 'Guimarães',
//                         'documento' => '13952721077',
//                         'nascimento' => '01/03/2000',
//                     ],

//                 ],
//                 'campos_adicionais' => [
//                     [
//                         'codigo_campo_adicional' => 23,
//                         'campo' => 'Hotel',
//                     ],
//                     [
//                         'codigo_campo_adicional' => 23,
//                         'campo' => 'Hotel',
//                     ],
//                 ],
//             ],
//         ],
//     ];

//     dd(json_encode($data));
// });

Route::get('/', 'WelcomeController@index')->name('app.welcome');

// Rotas para login
Auth::routes(['register' => false, 'verify' => false]);

// Rotas do painel
Route::group(['prefix' => '/painel', 'middleware' => ['auth']], function () {

    // Página inicial
    Route::get('/dashboard', 'PainelController@index')->name('app.dashboard');

    Route::get('/teste', 'PainelController@artisan');

    // Troca o canal da sessao do usuario
    Route::post("/trocar-canal-sessao", 'PainelController@trocarCanalSessao')->name('app.dashboard.trocar-canal');

    // Dados do usuário logado
    Route::group(['prefix' => '/meus-dados'], function () {

        // Dados do usuário
        Route::get('/', 'PainelController@meusDados')->name('app.meus-dados');

        // Atualização dos dados do usuário
        Route::put('/atualizar', 'PainelController@atualizarDados')->name('app.meus-dados.atualizar');
    });

    // Grupo de rotas referente a afiliados
    Route::group(['prefix' => 'afiliados', 'namespace' => 'Afiliado'], function() {

        // Listagem de afiliados
        Route::get('/', 'AfiliadoController@index')->name('app.afiliados.index');

        // Página para criar novos afiliados
        Route::get('/novo', 'AfiliadoController@create')->name('app.afiliados.create');

        Route::post('/store', 'AfiliadoController@store')->name('app.afiliados.store');

        Route::get('/editar/{afiliado_id}', 'AfiliadoController@edit')->name('app.afiliados.edit');

        Route::post('/editar', 'AfiliadoController@update')->name('app.afiliados.update');

        // Afiliados relatório
        Route::get('/relatorio', 'AfiliadoController@relatorioAfiliado')->name('app.relatorios.afiliados.index');

        Route::get('/relatorio-afiliados', 'AfiliadoController@relatorioAfiliados')->name('app.relatorios.afiliados.index2');

        Route::group(['prefix' => 'download'], function() {

            Route::get('xls', 'AfiliadoController@relatorioAfiliadoDownloadXLS')->name('app.relatorios.afiliados.download.xls');

            Route::get('pdf', 'AfiliadoController@relatorioAfiliadoDownloadPDF')->name('app.relatorios.afiliados.download.pdf');

            Route::get('/relatorio-afiliados-xls', 'AfiliadoController@relatorioAfiliadosDownloadXLS')->name('app.relatorios.afiliados.index2-xls');

            Route::get('/relatorio-afiliados-pdf', 'AfiliadoController@relatorioAfiliadosDownloadPDF')->name('app.relatorios.afiliados.index2-pdf');
        });

        Route::post('/atribuir-afiliado-reserva', 'AfiliadoController@atribuirAfiliadoReserva')->name('app.relatorios.afiliados.atribuir-afiliado-reserva');
    });

    Route::group(['prefix' => 'vendedores'], function() {
        Route::post('/atribuir-vendedor-reserva', 'VendedorController@atribuirVendedorReserva')->name('app.relatorios.afiliados.atribuir-vendedor-reserva');


        Route::get('/relatorio', 'VendedorController@relatorioVendedor')->name('app.relatorios.vendedores.index2');

        Route::get('/relatorio-vendedores', 'VendedorController@relatorioVendedores')->name('app.relatorios.vendedores.index');

    });

    // Grupo de rotas referente as vendas internas
    Route::group(['prefix' => 'vendas-internas', 'namespace' => 'VendasInternas'], function() {

        Route::get('/', 'VendaInternaController@index')->name('app.venda-interna.index');

        Route::get('/obter-servico-estilo-carrinho', 'VendaInternaController@obterServicoEstiloCarrinho')->name('app.venda-interna.obter.servico.estilo.carrinho');

        Route::post('/carrinho/adicionar', 'VendaInternaController@carrinhoAdicionar')->name('app.venda-interna.carrinho.adicionar');

        Route::get('/carrinho/remover/{index}', 'VendaInternaController@carrinhoRemover')->name('app.venda-interna.carrinho.remover');

        Route::get('/carrinho/finalizar', 'VendaInternaController@carrinhoFinalizar')->name('app.venda-interna.carrinho.finalizar');

        Route::get('/carrinho/gerar-link', 'VendaInternaController@gerarLink')->name('app.venda-interna.carrinho.gerar-link');

        Route::get('/carrinho/novo-link', 'VendaInternaController@novoLink')->name('app.venda-interna.carrinho.novo-link');

        Route::post('/consultar-email', 'VendaInternaController@consultarEmail')->name('app.venda-interna.consultar-email');

        Route::post('/set-cupom-desconto', 'VendaInternaController@setCupomDesconto')->name('app.venda-interna.set-cupom-desconto');

        Route::post('/unset-cupom-desconto', 'VendaInternaController@unsetCupomDesconto')->name('app.venda-interna.unset-cupom-desconto');
    });

    // Grupo de rotas para descontos como cupons e promoções
    Route::group(['prefix' => 'descontos'], function() {

        // Grupo de rotas para os cupons de desconto
        Route::group(['prefix' => 'cupom'], function() {

            // Rota para a index de cupons de desconto
            Route::get('/', 'CupomDescontoController@index')->name('app.descontos.cupom.index');

            // Rota para a view de criação de novos cupons de desconto
            Route::get('/novo', 'CupomDescontoController@create')->name('app.descontos.cupom.create');

            // Rota para salvar os novos cupons de desconto
            Route::post('/store', 'CupomDescontoController@store')->name('app.descontos.cupom.store');

            // Rota para a view de editar os cupons
            Route::get('/editar', 'CupomDescontoController@edit')->name('app.descontos.cupom.edit');

            // Rota para o post de salvar os novos dados dos produtos editados
            Route::post('/update', 'CupomDescontoController@update')->name('app.descontos.cupom.update');

            Route::get('/relatorio', 'CupomDescontoController@relatorio')->name('app.descontos.cupom.relatorio');
        });

        Route::group(['prefix' => 'desconto'], function() {

            Route::get('/', 'DescontoController@index')->name('app.descontos.desconto.index');

            Route::get('/novo', 'DescontoController@create')->name('app.descontos.desconto.create');

            Route::post('/store', 'DescontoController@store')->name('app.descontos.desconto.store');

            Route::get('/editar', 'DescontoController@edit')->name('app.descontos.desconto.edit');

            Route::post('/update', 'DescontoController@update')->name('app.descontos.desconto.update');

        });

    });

    // Alteração de senha do usuário logado
    Route::group(['prefix' => '/alterar-senha'], function () {

        // View para alteração
        Route::get('/', 'PainelController@viewAlterarSenha')->name('app.alterar-senha');

        // Atualização da senha
        Route::put('/atualizar-nova-senha', 'PainelController@alterarSenha')->name('app.alterar-senha.atualizar');
    });

    // Alteração de configurações do usuario logado
    Route::group(['prefix' => '/configuracoes'], function () {

        // View para alteração
        Route::get('/', 'PainelController@viewConfiguracoesUsuario')->name('app.user.configuracoes');

        // Atualizacao das configuracoes
        Route::put('/atualizar', 'PainelController@atualizarConfiguracoesUsuario')->name('app.user.configuracoes.atualizar');
    });

    // Canais de vendas
    Route::group(['prefix' => '/canais-de-venda'], function () {

        // Listagem dos canais de venda
        Route::get('/', 'CanalVendaController@index')->name('app.canal-venda');

        // View para cadastro de um novo canal de venda
        Route::get('/novo', 'CanalVendaController@create')->name('app.canal-venda.create');

        // Post para cadastrar novo canal
        Route::post('/cadastrar', 'CanalVendaController@store')->name('app.canal-venda.store');

        // View para detalhes do canal
        Route::get('/detalhes/{id}', 'CanalVendaController@view')->name('app.canal-venda.view');

        // PUT para atualização do canal
        Route::put('/atualizar', 'CanalVendaController@edit')->name('app.canal-venda.edit');

        // POST para limpar o cache da aplicacao
        Route::post('/limpar-cache', 'CanalVendaController@resetCache')->name('app.canal-venda.reset-cache');
    });

    // Rotas para fornecedores
    Route::group(['prefix' => '/fornecedores', 'namespace' => 'Fornecedores'], function () {

        // Listagem dos fornecedores
        Route::get('/', 'FornecedorController@index')->name('app.fornecedores.index');

        // Consulta CNPJ
        Route::get('/consulta-cnpj/{cnpj?}', 'FornecedorController@consultaCNPJ')->name('app.fornecedores.cnpj');

        // Cadastro do fornecedor
        Route::get('/novo', 'FornecedorController@create')->name('app.fornecedores.create');

        // View para detalhes do fornecedor
        Route::get('/detalhes/{id}', 'FornecedorController@view')->name('app.fornecedores.view');

        // Lista dos serviços do fornecedor em JSON
        Route::get('/servicos-json/{id?}', 'FornecedorController@viewServicosJson')->name('app.fornecedores.servicos.view-json');

        // POST Cadastro do fornecedor
        Route::post('/cadastrar-fornecedor', 'FornecedorController@store')->name('app.fornecedores.store');

        // PUT para atualizar os dados do fornecedor
        Route::put('/editar-fornecedor', 'FornecedorController@update')->name('app.fornecedores.update');

        // Post para cadastro dos dados bancários
        Route::post('/cadastrar-dados-bancarios', 'FornecedorController@storeBankDetails')->name('app.fornecedores.dados-bancarios.store');

        // PUT para atualizar os dados bancários do fornecedor
        Route::put('/editar-dados-bancarios', 'FornecedorController@updateBankDetails')->name('app.fornecedores.dados-bancarios.update');

        // Post para cadastro das regras e termos
        Route::post('/cadastrar-regras', 'FornecedorController@storeRules')->name("app.fornecedores.regras.store");

        // PUT para atualizar as regras e termos
        Route::put('/editar-regras', 'FornecedorController@updateRules')->name("app.fornecedores.regras.update");

        // Rotas para split de pagamentos do fornecedor
        Route::group(['prefix' => '/split'], function () {

            // Detalhes do split de pagamento
            Route::get('/detalhes/{id}', 'SplitFornecedorController@view')->name('app.fornecedores.splits.view');

            // POST Cadastro de split para o fornecedor
            Route::post('/cadastrar-split', 'SplitFornecedorController@store')->name('app.fornecedores.splits.store');

            // PUT para atualizar o split do fornecedor
            Route::put('/atualizar-split', 'SplitFornecedorController@update')->name('app.fornecedores.splits.update');
        });

        // Rotas para usuarios do fornecedor
        Route::group(['prefix' => '/usuarios'], function () {

            // GET para detalhes do usuario
            Route::get('/detalhes/{id}', 'UsuarioFornecedorController@view')->name('app.fornecedores.usuarios.view');

            // PUT para atualizar o usuairio
            Route::put('/atualizar-usuario', 'UsuarioFornecedorController@update')->name('app.fornecedores.usuarios.update');

            // PUT para reativar o usuairio
            Route::put('/reativar-usuario', 'UsuarioFornecedorController@restore')->name('app.fornecedores.usuarios.restore');

            // POST para cadastrar um novo usuario
            Route::post('/cadastrar-usuario', 'UsuarioFornecedorController@store')->name('app.fornecedores.usuarios.store');
        });
    });

    // Rotas para destinos
    Route::group(['prefix' => '/destinos', 'namespace' => 'Destinos'], function () {

        // Listagem dos destinos
        Route::get('/', 'DestinosController@index')->name('app.destinos.index');

        // Cadastro do novo destino
        Route::get('/novo', 'DestinosController@create')->name('app.destinos.create');

        // View para detalhes do destino
        Route::get('/detalhes/{id}', 'DestinosController@view')->name('app.destinos.view');

        // Detalhes do destino retornado em JSON
        Route::get('/detalhes-json/{id?}', 'DestinosController@viewJson')->name('app.destinos.view.json');

        // Lista dos serviços retornados em JSON
        Route::get('/servicos-json/{id?}', 'DestinosController@servicosJson')->name('app.destinos.servicos.json');

        // POST para cadastro do destino
        Route::post('/cadastrar-destino', 'DestinosController@store')->name('app.destinos.store');

        // POST para foto do destino
        Route::post('/foto-destino', 'DestinosController@uploadPhotoDestino')->name('app.destinos.foto');

        // PUT para edição do destino
        Route::put('/editar-destino', 'DestinosController@update')->name('app.destinos.update');

        // Rotas para os servicos na home destino
        Route::group(['prefix' => '/servicos-home'], function() {

            // Detalhes da secao na home
            Route::get('/detalhes/{home_destino_id}', 'ServicosHomeController@view')->name('app.destinos.servicos.view');

            // POST para cadastrar os servicos na home
            Route::post('/cadastrar', 'ServicosHomeController@store')->name('app.destinos.servicos.store');

            // PUT para atualizar a secao e os servicos da home
            Route::put('/atualizar', 'ServicosHomeController@update')->name('app.destinos.servicos.update');

            // DELETE para atualizar a secao e os servicos da home
            Route::delete('/desativar', 'ServicosHomeController@remove')->name('app.destinos.servicos.remove');

            // PUT para ativar a secao e os servicos
            Route::put('/reativar', 'ServicosHomeController@restore')->name('app.destinos.servicos.restore');
        });
    });

    // Rotas para categorias
    Route::group(['prefix' => '/categorias', 'namespace' => 'Categorias'], function () {

        // Listagem das categorias
        Route::get('/', 'CategoriasController@index')->name('app.categorias.index');

        // Cadastro da nova categoria
        Route::get('/novo', 'CategoriasController@create')->name('app.categorias.create');

        // View para detalhes da categoria
        Route::get('/detalhes/{id}', 'CategoriasController@view')->name('app.categorias.view');

        // Detalhes da categoria retornado em JSON
        Route::get('/detalhes-json/{id?}', 'CategoriasController@viewJson')->name('app.categorias.view.json');

        // POST para cadastro da categoria
        Route::post('/cadastrar-categoria', 'CategoriasController@store')->name('app.categorias.store');

        // Post para upload das fotos da categoria
        Route::post('/cadastrar-fotos', 'CategoriasController@uploadFotosCategoria')->name('app.categorias.foto');

        // PUT para atualizacao da categoria
        Route::put('/atualizar-categoria', 'CategoriasController@update')->name('app.categorias.update');

        // Post para atualizar as fotos da categoria
        Route::put('/atualizar-fotos', 'CategoriasController@updateFotoCategoria')->name('app.categorias.foto.update');

        // Secao categorias
        Route::group(['prefix' => '/secao-categoria'], function () {

            // View para detalhes da categoria
            Route::get('/detalhes/{id}', 'SecaoCategoriaController@view')->name('app.categorias.secao.view');

            // Post para cadastro de uma nova seção
            Route::post('/cadastrar-secao', 'SecaoCategoriaController@store')->name('app.categorias.secao.store');

            // PUT para atualização de uma seção
            Route::put('/atualizar-secao', 'SecaoCategoriaController@update')->name('app.categorias.secao.update');
        });
    });

    // Rotas para servicos
    Route::group(['prefix' => '/servicos', 'namespace' => 'Servicos'], function () {

        // Listagem dos serviços
        Route::get('/', 'ServicosController@index')->name('app.servicos.index');

        // Cadastro de novo serviço
        Route::get('/novo', 'ServicosController@create')->name('app.servicos.create');

        // Rota para detalhes do serviço
        Route::get('/detalhes/{id}', 'ServicosController@view')->name('app.servicos.view');

        // POST para cadastro da descrição do serviço
        Route::post('/descricao-servico', 'ServicosController@storeDescricao')->name('app.servicos.store.descricao');

        // View para servicos pendentes
        Route::get("/pendentes", "ServicosPendentesController@index")->name("app.servicos.pendentes.index");

        // PUT para atualizacap da descrição do serviço
        Route::put('/atualizar-descricao', 'ServicosController@updateDescricao')->name('app.servicos.update.descricao');

        // Post para cadastrar as regras do servico
        Route::post('/regras-voucher-servico', 'ServicosController@storeRegras')->name('app.servicos.store.regras');

        // PUT para atualizar as regras do servico
        Route::put('/atualizar-regras-voucher-servico', 'ServicosController@updateRegras')->name('app.servicos.update.regras');

        Route::get('/calendario', 'ServicosController@calendario')->name('app.servicos.calendario');

        // Categorias do servico
        Route::group(['prefix' => '/categoria'], function () {

            // Detalhes da categoria relacionada ao servico com as secoes
            Route::get('/detalhes/{servico_id}/{categoria_id}', 'ServicosController@viewCategoriaServico')->name('app.servicos.view.categoria');

            // Post para ligar a categoria ao serviço
            Route::post('/categoria-servico', 'ServicosController@storeCategoria')->name('app.servicos.store.categoria');

            // Atualiza as categorias no serviço
            Route::put('/atualizar', 'ServicosController@updateCategoriaServico')->name('app.servicos.update.categoria');
        });

        // Campos adicionais dos serviços
        Route::group(['prefix' => '/campos-adicionais'], function () {

            // Detalhes do campo adicional
            Route::get('/detalhes/{id}', 'CampoAdicionalController@view')->name('app.servicos.view.form');

            // Post para cadastro de um campo adicional
            Route::post('/novo', 'CampoAdicionalController@store')->name('app.servicos.store.form');

            // Detalhes do campo adicional
            Route::put('/atualizar', 'CampoAdicionalController@update')->name('app.servicos.update.form');

            // Post para reativar o campo adicional
            Route::post('/reativar-campo', 'CampoAdicionalController@reactivate')->name('app.servicos.active.form');
        });

        // Fotos do serviço
        Route::group(['prefix' => '/fotos'], function () {

            // Detalhes da foto
            Route::get('/detalhes/{id}', 'FotoServicoController@view')->name('app.servicos.view.fotos');

            // PUT para atualizar os dados das fotos e excluir
            Route::put('/atualizar', 'FotoServicoController@update')->name('app.servicos.update.fotos');

            // Post para cadastro de fotos
            Route::post('/cadastrar-fotos', 'FotoServicoController@store')->name('app.servicos.store.fotos');
        });

        // Rotas para variacoes do servico
        Route::group(['prefix' => '/variacoes'], function () {

            // Detalhes da foto
            Route::get('/detalhes/{id}', 'VariacaoServicoController@view')->name('app.servicos.view.variacao');

            // Post para cadastro da variacao
            Route::post('/cadastrar', 'VariacaoServicoController@store')->name('app.servicos.store.variacao');

            // PUT para atualizar os dados da variacao
            Route::put('/atualizar', 'VariacaoServicoController@update')->name('app.servicos.update.variacao');

            // PUT para atualizar o markup da variacao
            Route::put('/atualizar-markup', 'VariacaoServicoController@updateMarkup')->name('app.servicos.update.markup');

            // Post para reativar a variação no servico
            Route::post('/reativar', 'VariacaoServicoController@reactivate')->name('app.servicos.update.reactivate');
        });

        // Rotas para tags do servico
        Route::group(['prefix' => '/tags'], function () {

            // Rota para recuperar os icones
            Route::get('/icones', 'TagsServicoController@index')->name('app.servicos.tags.icones');

            Route::get('/icones2', 'TagsServicoInternoController@index')->name('app.servicos.tags.icones-2');

            // Rota para recupera os detalhes da tag
            Route::get('/detalhes/{tag_id}', 'TagsServicoController@view')->name('app.servicos.tags.view');

            Route::get('/detalhes2/{tag_id}', 'TagsServicoInternoController@view')->name('app.servicos.tags.view2');

            // POST para cadastro das tags
            Route::post('/cadastrar-tag', 'TagsServicoController@store')->name('app.servicos.tags.store');

            Route::post('/cadastrar-tag2', 'TagsServicoInternoController@store')->name('app.servicos.tags.store2');

            // PUT para atualizar os detalhes da tag
            Route::put('/atualizar-tag', 'TagsServicoController@update')->name('app.servicos.tags.update');

            // PUT para atualizar os detalhes da tag
            Route::put('/atualizar-tag2', 'TagsServicoInternoController@update')->name('app.servicos.tags.update2');
        });

        Route::group(['prefix' => '/avaliacoes'], function() {
            Route::get('/', 'AvaliacaoServicoController@index')->name('app.servicos.avaliacoes.index');

            Route::get('/novo', 'AvaliacaoServicoController@create')->name('app.servicos.avaliacoes.create');

            Route::post('/store', 'AvaliacaoServicoController@store')->name('app.servicos.avaliacoes.store');

            Route::get('/editar/{avaliacao_id}', 'AvaliacaoServicoController@edit')->name('app.servicos.avaliacoes.edit');

            Route::post('/update/{avaliacao_id}', 'AvaliacaoServicoController@update')->name('app.servicos.avaliacoes.update');
        });

        Route::group(['prefix' => '/regras-servico'], function() {

            Route::post('/store-ou-update', 'RegraServicoController@storeOuUpdate')->name('app.servicos.regras-servico.store-ou-update');

        });

        Route::group(['prefix' => '/faq'], function() {

            Route::post('/novo', 'FaqServicoController@store')->name('app.servicos.faq.store');

            Route::post('/update', 'FaqServicoController@update')->name('app.servicos.faq.update');

            Route::post('/delete', 'FaqServicoController@delete')->name('app.servicos.faq.delete');

        });
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

    // Grupo de rota para os terminais CDI
    Route::group(['prefix' => '/terminais', 'namespace' => 'Terminais'], function () {

        // Listagem dos terminais
        Route::get('/', 'TerminaisCdiController@index')->name('app.terminais.index');

        // View para cadastro de novo terminal
        Route::get('/novo', 'TerminaisCdiController@create')->name('app.terminais.create');

        // View para detalhes do terminal
        Route::get('/detalhes/{id}', 'TerminaisCdiController@view')->name('app.terminais.view');

        // Post para cadastrar novo terminal
        Route::post('/cadastrar-terminal', 'TerminaisCdiController@store')->name('app.terminais.store');

        // PUT para atualizar o terminal
        Route::put('/atualizar-terminal', 'TerminaisCdiController@update')->name('app.terminais.update');

        // Listagem das reservas de acordo com a provisao de pagamento
        Route::get('/detalhes-previsao/{terminal_id}/{mes}', 'TerminaisCdiController@viewPrevisaoMes')->name('app.terminais.previsao.view');

        // Detalhes do historico de conexao do terminal
        Route::get('/detalhe-historico/{historico_id}', 'TerminaisCdiController@viewHistorico')->name('app.terminais.historico.view');

        // Rotas para usuarios dos terminais
        Route::group(['prefix' => '/usuarios'], function () {

            // Get para detalhes do usuario
            Route::get('/detalhes/{id}', 'UsuarioTerminaisController@view')->name('app.terminais.usuario.view');

            // POST para cadastro de novo usuario
            Route::post('/novo', 'UsuarioTerminaisController@store')->name('app.terminais.usuario.store');

            // PUT para alterar usuario
            Route::put('/atualizar-usuario', 'UsuarioTerminaisController@update')->name('app.terminais.usuario.update');

            // PUT para reativar usuario
            Route::put('/reativar-usuario', 'UsuarioTerminaisController@restore')->name('app.terminais.usuario.restore');
        });

        // Rota para relatorio de vendas dos terminais
        Route::group(['prefix' => '/relatorios'], function () {

            // Lista de relatorios dos terminais
            Route::get('/', 'RelatorioVendaTerminaisController@index')->name('app.terminais.relatorios.index');

            // Download do relatorio
            Route::get('/download', 'RelatorioVendaTerminaisController@download')->name('app.terminais.relatorios.download');

            // Detalhes do relatorio
            Route::get('/detalhes/{terminal_id}', 'RelatorioVendaTerminaisController@view')->name('app.terminais.relatorios.view');

            // Detalhes do relatorio
            Route::get('/download-vendas-terminal/{terminal_id}', 'RelatorioVendaTerminaisController@downloadVendasTerminal')->name('app.terminais.relatorios.view.download');
        });

        // Rota para as comissões do terminal
        Route::group(['prefix' => '/pagamento-de-comissoes'], function () {

            // Listagem para pagamento das comissoes
            Route::get('/', 'ComissoesTerminaisController@index')->name('app.terminais.comissoes.index');

            // Listagem para pagamento das comissoes
            Route::get('/detalhes/{pagamento_id}', 'ComissoesTerminaisController@view')->name('app.terminais.comissoes.view');

            // PUT para efetuar o pagamento
            Route::put('/pagar-comissao', 'ComissoesTerminaisController@update')->name('app.terminais.comissoes.update');
        });
    });

    // Rota para pedidos
    Route::group(['prefix' => '/pedidos', 'namespace' => 'Pedidos'], function () {

        // Listagem dos pedidos
        Route::get('/', 'PedidosController@index')->name('app.pedidos.index');

        // Detalhes do pedido
        Route::get('/detalhes/{codigo_pedido?}', 'PedidosController@view')->name('app.pedidos.view');

        Route::post('/revisar-pix-pendentes', 'PedidosController@pixPendentes')->name('app.pedidos.pix-pendentes');

        // Rotas para reservas do pedido
        Route::group(['prefix' => '/reserva'], function () {

            // Detalhes da reserva
            Route::get('/detalhes/{voucher_id?}', 'ReservaController@view')->name('app.reservas.view');

            // Impressao do voucher
            Route::get('/imprimir/{voucher_id?}', 'ReservaController@print')->name('app.reservas.print');

            Route::get('/finalizar', 'PedidosController@informacaoFinalizacao')->name('app.reservas.finalizar');

            Route::post('/finalizar', 'PedidosController@informacaoFinalizacaoStore')->name('app.reservas.finalizar.store');

            // JSON com as datas disponiveis para alteracao da reserva
            Route::get('/calendario-reserva/{reserva_id}', 'ReservaController@calendarioReserva')->name('app.reservas.calendario.view');

            // PUT para atualizar os dados do acompanhante
            Route::put('/atualizar-acompanhante', 'AcompanhanteController@update')->name('app.reservas.acompanhante.update');

            // DELETE para remover um acompanhante da reserva
            Route::delete('/remover-acompanhante', 'AcompanhanteController@delete')->name('app.reservas.acompanhante.delete');

            // PUT para atualizar os dados adicionais da reserva
            Route::put('/atualizar-dados-adicionais', 'AdicionaisController@update')->name('app.reservas.adicionais.update');

            // PUT para atualizar a data de utilização da reserva
            Route::put('/atualizar-data-reserva', 'ReservaController@updateDataAgenda')->name('app.reservas.agenda.update');

            // DELETE para cancelar a reserva
            Route::delete('/cancelar-reserva', 'ReservaController@delete')->name('app.reservas.delete');

            // Grupo de rotas para quantidade reserva
            Route::group(['prefix' => '/quantidade-reserva'], function () {

                // Detalhes da quantidade reserva
                Route::get('/{quantidade_id}', 'QuantidadeReservaController@view')->name('app.reservas.quantidade.view');

                // PUT para atualizar a quantidade adquirida na reserva
                Route::put('/atualizar', 'QuantidadeReservaController@update')->name('app.reservas.quantidade.update');
            });
        });
    });

    // Rota para os colaboradores
    Route::group(['prefix' => '/colaboradores', 'namespace' => 'Colaboradores', 'middleware' => ['forUsersAdmin']], function () {

        // Listagem dos pedidos
        Route::get('/', 'ColaboradoresController@index')->name('app.colaboradores.index');

        // Detalhes do colaborador
        Route::get('/detalhes/{colaborador_id}', 'ColaboradoresController@view')->name('app.colaboradores.view');

        // POST para cadastrar um novo colaborador
        Route::post('/novo-colaborador', 'ColaboradoresController@store')->name('app.colaboradores.store');

        // PUT para editar os dados do colaborador
        Route::put('/editar-colaborador', 'ColaboradoresController@update')->name('app.colaboradores.update');

        // PUT para reativar o colaborador
        Route::put('/reativar-colaborador', 'ColaboradoresController@restore')->name('app.colaboradores.restore');
    });

    // Rota para gerenciar clientes
    Route::group(['prefix' => '/clientes', 'namespace' => 'Clientes'], function () {

        // Listagem dos clientes
        Route::get('/', 'ClientesController@index')->name('app.clientes.index');

        // Detalhe do cliente
        Route::get('/detalhes/{cliente_id?}', 'ClientesController@view')->name('app.clientes.view');

        // Pesquisa cliente
        Route::get('/pesquisa-cliente', 'ClientesController@search')->name('app.clientes.search');

        // Pesquisa cliente pelo TID ou cartão
        Route::get('/pesquisa-tid', 'ClientesController@searchTid')->name('app.clientes.search-tid');

        // Pesquisa cliente pelo codigo do pedido ou reserva
        Route::get('/pesquisa-pedido', 'ClientesController@searchCodPedido')->name('app.clientes.search-pedido');

        // Atualiza os dados do cliente
        Route::post('/atualizar-cliente', 'ClientesController@update')->name('app.clientes.update');

        // Desativa o cliente
        Route::delete('/desativar-cliente', 'ClientesController@delete')->name('app.clientes.delete');

        // Ativa o cliente
        Route::put('/ativar-cliente', 'ClientesController@restore')->name('app.clientes.restore');

        Route::post('/resetar-senha', 'ClientesController@resetarSenha')->name('app.clientes.resetar-senha');
    });

    // Rotas para os relatorios
    Route::group(['prefix' => '/relatorios', 'namespace' => 'Relatorios'], function () {

        // Relatorio das reservas autenticadas
        Route::get('/autenticados', 'AutenticadosController@index')->name('app.relatorios.autenticados.index');

        // Download relatorio das reservas autenticadas
        Route::get('/autenticados/download', 'AutenticadosController@download')->name('app.relatorios.autenticados.download');

        // Relatorio de ingressos vendidos
        Route::get('/vendidos', 'VendidosController@index')->name('app.relatorios.vendidos.index');

        Route::get('/vendidos2', 'VendidosController@index2')->name('app.relatorios.vendidos.index2');

        // Download relatorio de ingressos vendidos
        Route::get('/vendidos/download', 'VendidosController@download')->name('app.relatorios.vendidos.download');

        // Relatorio de fornecedores com vendas
        Route::get('/fornecedores', 'FornecedorController@index')->name('app.relatorios.fornecedores.index');

        // Download relatorio de fornecedores com vendas
        Route::get('/fornecedores/download', 'FornecedorController@download')->name('app.relatorios.fornecedores.download');

        // Relatorio de homelist
        Route::get('/homelist', 'HomelistController@index')->name('app.relatorios.homelist.index');

        Route::post('/conferencia-reserva/atualizar', 'ConferenciaReservaController@atualizarConferencia')->name('app.relatorios.conferencia-reserva.atualizar');

        Route::get('/mala-pronta', 'MalaProntaController@index')->name('app.relatorios.mala-pronta');

        Route::get('/disponibilidade-fornecedores', 'RelatoriosController@relatorioDisponibilidade')->name('app.relatorios.relatorio-disponibilidade');

        Route::get('/reserva-com-detalhes', 'RelatoriosController@relatorioReservaComDetalhes')->name('app.relatorios.relatorio-reserva-com-detalhes');
    });

    // Rotas para os banners
    Route::group(['prefix' => '/banners', 'namespace' => 'Banners'], function () {

        // Listagem dos banners
        Route::get('/{destino_id?}', 'BannersController@index')->name('app.banners.index');

        // Cadastro de um novo banner
        Route::get('/novo/{destino_id?}', 'BannersController@create')->name('app.banners.create');

        // Cadastro de um novo banner
        Route::get('/detalhes/{banner_id}', 'BannersController@view')->name('app.banners.view');

        // POST para cadastrar novo banner
        Route::post('/cadastrar-banner', 'BannersController@store')->name('app.banners.store');

        // PUT para atualizar banner
        Route::put('/atualizar-banner', 'BannersController@update')->name('app.banners.update');

        // PUT para atualizar banner
        Route::put('/alterar-status', 'BannersController@status')->name('app.banners.status');
    });

    // Rota para newsletters
    Route::group(['prefix' => '/newsletters', 'namespace' => 'Newsletter'], function () {

        // Listagem inicial
        Route::get('/', 'NewsletterController@index')->name('app.newsletter.index');

        // Download da lista de news
        Route::get('/download', 'NewsletterController@download')->name('app.newsletter.download');
    });

    Route::group(['prefix' => '/integracao'], function () {
         Route::post('integrar', 'IntegracaoController@integrarReserva')->name('app.integracao.integrar');
    });

    Route::group(['prefix' => '/faturas'], function () {

        Route::get('/', 'FaturaController@index')->name('app.faturas.index');

        Route::get('/fatura', 'FaturaController@show')->name('app.faturas.show');

        Route::get('/pendente-aprovacao', 'FaturaController@pendenteAprovacao')->name('app.faturas.pendente-aprovacao');

        Route::get('/pendente-pagamento', 'FaturaController@pendentePagamento')->name('app.faturas.pendente-pagamento');

        Route::get('/faturas-previstas', 'FaturaController@faturasPrevistas')->name('app.faturas.fatura-prevista');

        Route::get('/fatura-prevista', 'FaturaController@faturaPrevista')->name('app.faturas.fatura-prevista-individual');

        Route::post('/fatura/marcar-como-paga', 'FaturaController@setFaturaPaga')->name('app.faturas.set-fatura-paga');

    });

    Route::get('/dashboard-resumo', 'DashboardController@index');



    Route::get('/pwi-checkout', function () {

        $a = new PWIAPI();
        dd($a->checkoutAPI());

    });


});



