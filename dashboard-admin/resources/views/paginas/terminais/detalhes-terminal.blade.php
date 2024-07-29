@extends('template.header')

@section('title', "Terminal " . $terminal->nome)

@section('content')

    <div class="row">
        <div class="page-header pb-4">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Terminal <span class="text-gradient-01"> {{ $terminal->nome }}</span></h2>
                <div>{{ Breadcrumbs::render('app.terminais.view') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if($terminal->status == false)
                <div class="alert alert-danger mb-4" role="alert">
                    <i class="la la-flash mr-2"></i>
                    Este terminal está <strong>Inativo</strong>!
                </div>
            @else
                <div class="alert alert-secondary mb-4" role="alert">
                    <i class="la la-plug mr-2"></i>
                    URL do terminal: <a href="//{{ $url_terminais }}/{{ $terminal->codigo }}" target="_blank" class="text-dark font-weight-bold pl-2">
                        {{ $url_terminais }}/{{ $terminal->codigo }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="row" data-controller="TerminaisCtrl">
        <div class="col-12">
            <ul id="tab_terminal" class="nav nav-tabs nav-tab-header nav-tab-no-border">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#terminal">
                        <i class="la la-desktop la-2x align-middle mr-2"></i> Informações
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#users">
                        <i class="la la-users la-2x align-middle mr-2"></i> Usuários
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#status">
                        <i class="la la-line-chart la-2x align-middle mr-2"></i> Status terminal
                    </a>
                </li>
                @if(userIsAdmin())
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#vendas">
                            <i class="la la-shopping-cart la-2x align-middle mr-2"></i> Pedido realizados
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#comissao">
                            <i class="la la-money la-2x align-middle mr-2"></i> Comissionamento
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#debitos">
                            <i class="la la-flash la-2x align-middle mr-2"></i> Débitos terminal
                        </a>
                    </li>
                @endif
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="terminal">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form data-validate-ajax method="POST" action="{{ route('app.terminais.update') }}" class="form-horizontal">
                                <div class="row">
                                    <div class="col-xl-8 mt-4 mb-2">
                                        <div class="section-title mr-auto">
                                            <h3>01. Identificação do terminal</h3>
                                            <p class="mt-1">Nome e código imply do terminal</p>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 mt-3 mb-2 d-flex align-content-end">
                                        <div class="ml-auto">
                                            <p class="m-0 pull-left pr-3" style="padding-top: 0.43rem">Status do terminal</p>
                                            <label>
                                                <input name="disable" class="toggle-checkbox" type="checkbox" {{ ($terminal->status) ? "checked" : "" }}>
                                                <span><span></span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-4 mb-3">
                                        <label for="nome" class="form-control-label">Nome terminal</label>
                                        <input id="nome" type="text" class="form-control" placeholder="Ex. Laghetto Stilo" required value="{{ $terminal->nome }}"
                                               data-required data-min="5" title="Nome terminal" data-auto-capitalize name="nome" autocomplete="nope">
                                        <div class="invalid-feedback"></div>
                                        <small>Pode ser o nome do local onde ficará o terminal.</small>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="identificacao" class="form-control-label">Identificação &nbsp; <small>(Patrimônio do fabricante)</small></label>
                                        <input id="identificacao" type="text" class="form-control" placeholder="000000" required value="{{ $terminal->identificacao }}"
                                               data-required data-min="6" title="Identificação" name="identificacao" autocomplete="nope">
                                        <div class="invalid-feedback"></div>
                                        <small>O código é localizado na etiqueta atrás do terminal.</small>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="fornecedor_id" class="form-control-label">Parceiro do Terminal &nbsp; <small>(Fornecedor)</small></label>
                                        <select id="fornecedor_id" name="fornecedor_id" class="form-control boostrap-select-custom" required
                                                data-required title="Selecione um parceiro">
                                            @foreach($fornecedores as $fornecedor)
                                                @if($fornecedor->id == $terminal->fornecedor_id)
                                                    <option value="{{ $fornecedor->id }}" selected>{{ $fornecedor->nome_fantasia }}</option>
                                                @else
                                                    <option value="{{ $fornecedor->id }}">{{ $fornecedor->nome_fantasia }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <small>O terminal irá aparecer na área do fornecedor informado.</small>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="canal_venda_id" class="form-control-label">Canal de venda</label>
                                        <input id="canal_venda_id" type="text" class="form-control" value="{{ $canal_terminais->site }}" readonly>
                                        <div class="invalid-feedback"></div>
                                        <small>Canal de onde o terminal irá pegar os serviços.</small>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="destino_id" class="form-control-label">Destino principal</label>
                                        <select id="destino_id" name="destino_id" class="form-control boostrap-select-custom" required
                                                data-required title="Selecione um destino">
                                            @foreach($destinos as $destino)
                                                @if($destino->id == $terminal->destino_id)
                                                    <option value="{{ $destino->id }}" selected>{{ $destino->nome }}</option>
                                                @else
                                                    <option value="{{ $destino->id }}">{{ $destino->nome }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <small>Será o destino principal do terminal ao ser ligado.</small>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="fabricante" class="form-control-label">Fabricante do terminal</label>
                                        <select id="fabricante" name="fabricante" class="form-control boostrap-select-custom" required
                                                data-required title="Selecione um fabricante">
                                            @foreach($fabricantes as $id => $fabricante)
                                                @if($id == $terminal->fabricante)
                                                    <option value="{{ $id }}" selected>{{ $fabricante }}</option>
                                                @else
                                                    <option value="{{ $id }}">{{ $fabricante }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <small>Para identificar melhor o terminal.</small>
                                    </div>
                                </div>
                                <div class="ml-auto mt-0 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>02. Informações sobre o responsável</h3>
                                        <p class="mt-1">Nome da pessoa que ficará responsável pelo terminal</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-4 mb-3">
                                        <label for="nome_responsavel" class="form-control-label">Nome completo</label>
                                        <input id="nome_responsavel" type="text" class="form-control" placeholder="Nome e sobrenome" required value="{{ $terminal->nome_responsavel }}"
                                               data-required data-min="5" title="Nome completo" data-auto-capitalize name="nome_responsavel" autocomplete="nope">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="email_responsavel" class="form-control-label">E-mail contato</label>
                                        <input id="email_responsavel" type="email" class="form-control" placeholder="nome.sobrenome@email.com" required
                                               title="E-mail contato" data-required data-min="3" name="email_responsavel" autocomplete="nope" value="{{ $terminal->email_responsavel }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="telefone_responsavel" class="form-control-label">Telefone</label>
                                        <input id="telefone_responsavel" type="tel" class="form-control vanillaMask" placeholder="(99) 9 9999-9999" value="{{ $terminal->telefone_responsavel }}"
                                               required data-required title="Telefone" data-min="14" data-mask="phone" name="telefone_responsavel" autocomplete="nope">
                                    </div>
                                </div>
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>03. Localização do terminal</h3>
                                        <p class="mt-1">Informe o endereço onde ficará o terminal</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group row mb-0">
                                            <div class="col-xl-12 mb-4">
                                                <label for="search_google" class="form-control-label">Local onde está o terminal &nbsp; <small>(Nome do hotel ou endereço)</small></label>
                                                <input id="search_google" type="text" class="form-control" placeholder="Ex. Hotel Laghetto Toscana" required value="{{ $terminal->endereco_mapa }}"
                                                       data-required data-min="5" title="Local do terminal" data-auto-capitalize name="endereco_mapa" autocomplete="nope">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-xl-12 mb-4">
                                                <label for="endereco_terminal" class="form-control-label">Endereço</label>
                                                <input id="endereco_terminal" type="text" class="form-control" placeholder="Rua, avenida, travessa e etc." required
                                                       data-required data-min="5" title="Endereço" data-auto-capitalize name="endereco" autocomplete="nope" value="{{ $terminal->endereco }}">
                                                <div class="invalid-feedback"></div>
                                                <input type="hidden" name="nome_local" value="{{ $terminal->nome_local }}">
                                            </div>
                                            <div class="col-xl-6 mb-4">
                                                <label for="cidade" class="form-control-label">Cidade</label>
                                                <input id="cidade" type="text" required class="form-control" placeholder="Ex. Gramado, Canela e etc." value="{{ $terminal->cidade }}"
                                                       name="cidade" data-required title="Cidade" data-min="3" data-auto-capitalize autocomplete="nope">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-xl-6 mb-4">
                                                <label for="cep" class="form-control-label">CEP</label>
                                                <input id="cep" type="tel" required class="form-control vanillaMask" placeholder="00000-000" value="{{ $terminal->cep }}"
                                                       name="cep" data-required title="CEP" data-min="9" data-mask="cep" autocomplete="nope">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-xl-12">
                                                <label for="estado" class="form-control-label">Estado</label>
                                                <select id="estado" name="estado" class="form-control boostrap-select-custom" required
                                                        data-required title="Selecione um estado">
                                                    @foreach($estados as $uf => $estado)
                                                        @if($uf == $terminal->estado)
                                                            <option value="{{ $uf }}" selected data-uf="{{ $uf }}">{{ $estado }} - {{ $uf }}</option>
                                                        @else
                                                            <option value="{{ $uf }}" data-uf="{{ $uf }}">{{ $estado }} - {{ $uf }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div id="google-map" data-lat="{{ $terminal->latitude }}" data-lng="{{ $terminal->longitude }}"></div>
                                        <div id="infowindow-content">
                                            <strong id="place-name" class="title"></strong><br>
                                            <span id="place-address"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="hide">
                                    {{ method_field("PUT") }}
                                    <input type="hidden" name="terminal_id" value="{{ $terminal->id }}">
                                    <input type="hidden" name="geolocation" value="{{ $terminal->geolocation }}">
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="text-center">
                                    <button class="btn btn-gradient-01" type="submit">Atualizar cadastro <i class="la la-angle-right right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="users">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <div class="ml-auto mt-4 mb-4">
                                <div class="section-title mr-auto">
                                    <a href="//{{ $url_login_terminal }}" target="_blank" class="btn btn-outline-secondary pull-right">
                                        Acessar painel <i class="la la-external-link right"></i>
                                    </a>
                                    <h3>02. Usuários de acesso</h3>
                                    <p class="mt-1">Usuário que acessam as vendas do terminal</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-divider mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>E-mail de acesso</th>
                                        <th>Cadastrado</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Administração</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($terminal->usuarios->count() > 0)
                                        @foreach($terminal->usuarios as $usuario)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if($usuario->status)
                                                        <a href="{{ route('app.terminais.usuario.view', $usuario->id) }}" data-action="edit-user">
                                                            {{ $usuario->nome }}
                                                        </a>
                                                    @else
                                                        {{ $usuario->nome }}
                                                    @endif
                                                </td>
                                                <td>{{ $usuario->email }}</td>
                                                <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="text-center">
                                                    @if($usuario->status)
                                                        <span class="badge-text badge-text-small info">Ativo</span>
                                                    @else
                                                        <span class="badge-text badge-text-small danger">Inativo</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($usuario->status)
                                                        <a href="{{ route('app.terminais.usuario.view', $usuario->id) }}" data-action="edit-user" class="btn btn-outline-primary">
                                                            Editar <i class="la la-edit right"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('app.terminais.usuario.restore') }}" data-action="activate-user" data-id="{{ $usuario->id }}" class="btn btn-outline-light">
                                                            Ativar <i class="la la-level-up right"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center pt-4 pb-4">Sem usuários cadastrados</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="em-separator separator-dashed"></div>
                            <div class="text-center">
                                <button class="btn btn-gradient-01" data-target="#new-user" data-toggle="modal">
                                    Novo usuário <i class="la la-plus right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="status">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <div class="row">
                                <div class="col-xl-8 mt-4 mb-3">
                                    <div class="section-title mr-auto">
                                        <h3>Status do terminal</h3>
                                        <p class="mt-1">Histórico de conexões e informações.</p>
                                    </div>
                                </div>
                                <div class="col-xl-4 mt-4 mb-3 d-flex align-content-end">

                                </div>
                                <div class="col-xl-4 mb-1 text-dark info-terminal">
                                    <p>
                                        <i class="la la-lock"></i> IP privado &nbsp;
                                        <strong>{{ $ultima_conexao->payload['ip_privado'] ?? "Sem dados" }}</strong>
                                    </p>
                                    <p>
                                        <i class="la la-flag-o"></i> IP público &nbsp;
                                        <strong>{{ $ultima_conexao->payload['ip_publico'] ?? "Sem dados" }}</strong>
                                    </p>
                                </div>
                                <div class="col-xl-4 mb-1 info-terminal">
                                    <p>
                                        <i class="la la-print"></i> Impressora &nbsp;
                                        <strong>{{ $ultima_conexao->payload['impressora'] ?? "Sem dados" }}</strong>
                                    </p>
                                    <p>
                                        <i class="la la-newspaper-o"></i> Papel &nbsp;
                                        <strong>{{ $ultima_conexao->payload['papel'] ?? "Sem dados" }}</strong>
                                    </p>
                                </div>
                                <div class="col-xl-4 mb-1 info-terminal">
                                    <p>
                                        <i class="la la-gear"></i> Status terminal &nbsp;
                                        <strong>{{ $ultima_conexao->payload['manutencao'] ?? "Sem dados" }}</strong>
                                    </p>
                                    <p>
                                        <i class="la la-server"></i> Última conexão &nbsp;
                                        <strong>{{ isset($ultima_conexao->created_at) ? $ultima_conexao->created_at->format('d/m/Y H:i') : "Sem dados" }}</strong>
                                    </p>
                                </div>
                                <div class="col-xl-12 mb-0">
                                    <div class="em-separator separator-dashed mt-1 mb-2"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mt-4 mb-2">
                                    <div class="section-title mr-auto">
                                        <h3>Histórico de conexões</h3>
                                        <p class="mt-1">Últimas 30 conexões do terminal - A cada 1 hora</p>
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="table-responsive">
                                        <table class="table table-divider mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>URL Ativa no terminal</th>
                                                <th>IP público</th>
                                                <th>IP privado</th>
                                                <th class="text-center">Data e hora</th>
                                                <th class="text-center">Dados recebidos</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if($terminal->historicoConexao->count() > 0)
                                                @foreach($terminal->historicoConexao as $historico)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ str_limit($historico->payload['url'], 45) }}</td>
                                                        <td>{{ $historico->payload['ip_publico'] }}</td>
                                                        <td>{{ $historico->payload['ip_privado'] }}</td>
                                                        <td class="text-center">{{ $historico->created_at->format('d/m/Y H:i') }}</td>
                                                        <td class="text-center">
                                                            <a href="{{ route('app.terminais.historico.view', $historico->id) }}" data-action="view-history" class="btn btn-outline-secondary">
                                                                Ver dados <i class="la la-external-link right"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="6" class="text-center pt-4 pb-4">Sem conexões registradas</td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="vendas">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <div class="ml-auto mt-4 mb-4">
                                <div class="section-title mr-auto">
                                    <h3>Pedidos realizados</h3>
                                    <p class="mt-1">Listagem dos últimos 20 pedidos realizados no terminal</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-divider mb-3">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Reserva</th>
                                        <th>Serviço vendido</th>
                                        <th class="text-center">Total reserva</th>
                                        <th class="text-center">Valor comissão</th>
                                        <th class="text-center">Status reserva</th>
                                        <th class="text-center">Data da compra</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($terminal->comissaoTerminal->count() > 0)
                                        @foreach($terminal->comissaoTerminal as $comissao)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ route('app.reservas.view', $comissao->reservaPedido->voucher) }}" class="text-secondary" target="_blank">
                                                        #{{ $comissao->reservaPedido->voucher }}
                                                    </a>
                                                </td>
                                                <td class="text-primary">
                                                    <div class="d-inline text-truncate m-0">
                                                        <span class="text-truncate">({{ $comissao->reservaPedido->quantidade }}x) {{ $comissao->reservaPedido->servico->nome }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center text-primary">R$ {{ formataValor($comissao->reservaPedido->valor_total) }}</td>
                                                <td class="text-center">
                                                    <strong class="text-{{ $comissao->reservaPedido->cor_status }}">R$ {{ formataValor($comissao->comissao) }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge-text badge-text-small {{ $comissao->reservaPedido->cor_status }}">{{ $comissao->reservaPedido->status_reserva }}</span>
                                                </td>
                                                <td class="text-center text-primary">
                                                    {{ $comissao->reservaPedido->created_at->format('d/m/Y H:i') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center pt-4 pb-4">Não foi realizado nenhum pedido ainda.</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="comissao">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <div class="ml-auto mt-4 mb-4">
                                <div class="section-title mr-auto">
                                    <h3>Comissionamento</h3>
                                    <p class="mt-1">Valores de comissão à ser paga para o terminal</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-divider mb-3">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-center">Mês para pagamento</th>
                                        <th class="text-center">Qtd. de reservas</th>
                                        <th class="text-center">R$ Vendido</th>
                                        <th class="text-center">R$ Pago</th>
                                        <th class="text-center">R$ Pendente</th>
                                        <th class="text-center">R$ Cancelado</th>
                                        <th class="text-center">Mais detalhes</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($comissoes as $comissao)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('app.terminais.previsao.view', [$terminal->id, $comissao->data_previsao->format('m-Y')]) }}" data-action="view-previsao" class="text-secondary">
                                                    <strong>{{ mesPT($comissao->data_previsao->format('m')) }} de {{ $comissao->data_previsao->format('Y') }}</strong>
                                                </a>
                                            </td>
                                            <td class="text-center text-primary">{{ $comissao->reservas }} reserva(s)</td>
                                            <td class="text-center text-primary">R$ {{ formataValor($comissao->vendido) }}</td>
                                            <td class="text-center"><strong class="text-success">R$ {{ formataValor($comissao->pago) }}</strong></td>
                                            <td class="text-center"><strong class="text-warning">R$ {{ formataValor($comissao->pendente) }}</strong></td>
                                            <td class="text-center"><strong class="text-danger">R$ {{ formataValor($comissao->cancelado) }}</strong></td>
                                            <td class="text-center text-primary">
                                                <a href="{{ route('app.terminais.previsao.view', [$terminal->id, $comissao->data_previsao->format('m-Y')]) }}" data-action="view-previsao" class="btn btn-outline-secondary">
                                                    Ver reservas <i class="la la-expand right"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center pt-4 pb-4">Não foi realizado nenhum pedido ainda.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="debitos">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <div class="ml-auto mt-4 mb-4">
                                <div class="section-title mr-auto">
                                    <h3>Débitos terminal</h3>
                                    <p class="mt-1">Valores para serem debitados do terminal</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-divider mb-3">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Reserva</th>
                                        <th class="text-center">Valor comissão</th>
                                        <th class="text-center">Valor do débito</th>
                                        <th class="text-center">Status débito</th>
                                        <th class="text-center">Data lançamento</th>
                                        <th class="text-center">Data da compra</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($terminal->debitoComissao as $debito_comissao)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <a href="{{ route('app.reservas.view', $debito_comissao->comissaoTerminal->reservaPedido->voucher) }}" class="text-secondary">
                                                    #{{ $debito_comissao->comissaoTerminal->reservaPedido->voucher }}
                                                </a>
                                            </td>
                                            <td class="text-center text-primary"><strong class="text-success">+R$ {{ formataValor($debito_comissao->comissaoTerminal->comissao) }}</strong></td>
                                            <td class="text-center text-primary"><strong class="text-danger">-R$ {{ formataValor($debito_comissao->valor) }}</strong></td>
                                            <td class="text-center text-primary">
                                                <span class="badge-text badge-text-small {{ $debito_comissao->cor_status_debito }}">{{ $debito_comissao->status_debito }}</span>
                                            </td>
                                            <td class="text-center text-primary">{{ $debito_comissao->created_at->format('d/m/Y') }}</td>
                                            <td class="text-center text-primary">{{ $debito_comissao->comissaoTerminal->reservaPedido->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center pt-4 pb-4">Não foi realizado nenhum débito ainda.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para detalhes do historico de conexao do terminal --}}
    <div id="view-history" class="modal modal-top fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Detalhes conexão</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-0 d-flex align-items-center">
                        <div class="col-6">
                            <p><strong>Dados recebidos</strong></p>
                            <div class="text-dark info-terminal">
                                <p>
                                    <i class="la la-lock"></i> IP privado &nbsp;
                                    <strong data-text="ip_privado"></strong>
                                </p>
                                <p>
                                    <i class="la la-flag-o"></i> IP público &nbsp;
                                    <strong data-text="ip_publico"></strong>
                                </p>
                                <p>
                                    <i class="la la-gear"></i> Status terminal &nbsp;
                                    <strong data-text="manutencao"></strong>
                                </p>
                                <p>
                                    <i class="la la-newspaper-o"></i> Papel &nbsp;
                                    <strong data-text="papel"></strong>
                                </p>
                                <p>
                                    <i class="la la-print"></i> Impressora &nbsp;
                                    <strong data-text="impressora"></strong>
                                </p>
                                <p>
                                    <i class="la la-internet-explorer"></i> URL &nbsp;
                                    <strong data-text="url"></strong>
                                </p>
                                <p>
                                    <i class="la la-calendar"></i> Data e hora &nbsp;
                                    <strong data-text="date"></strong>
                                </p>
                            </div>
                        </div>
                        <div class="col-6">
                            <p><strong>Possível localização do terminal</strong></p>
                            <div id="mapa-termina-conexao"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para cadastrar novo usuario --}}
    <div id="new-user" class="modal modal-top fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Novo usuário terminal</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax method="POST" action="{{ route('app.terminais.usuario.store') }}" class="form-horizontal">
                        <div class="form-group row mb-0">
                            <div class="col-xl-12 mb-3">
                                <label for="nome_usuario" class="form-control-label">Nome completo</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-user"></i></span>
                                    <input id="nome_usuario" type="text" class="form-control" placeholder="Nome e sobrenome"
                                           title="Nome do usuário" name="nome" required data-required data-min="4" data-auto-capitalize>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="email_usuario" class="form-control-label">Email de acesso <small>&nbsp; (O e-mail deve ser único)</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-at"></i></span>
                                    <input id="email_usuario" type="email" class="form-control" placeholder="nome.sobrenome@email.com.br"
                                           title="Email de acesso" name="email" required data-required data-min="4">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-secondary mb-3 mt-2" role="alert">
                            <i class="la la-unlock mr-2"></i>
                            A senha de acesso será gerada automáticamente!
                        </div>
                        <div class="hide">
                            <input type="hidden" name="terminal_id" value="{{ $terminal->id }}">
                        </div>
                        <div class="em-separator separator-dashed mt-1"></div>
                        <div class="mt-3 text-center">
                            <button class="btn btn-success">Cadastrar usuário <i class="la la-angle-right right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para editar o usuario --}}
    <div id="edit-user" class="modal modal-top fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar usuário terminal</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax method="POST" action="{{ route('app.terminais.usuario.update') }}" class="form-horizontal">
                        <div class="form-group row mb-0">
                            <div class="col-xl-12 mb-3">
                                <label for="edit_nome_usuario" class="form-control-label">Nome completo</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-user"></i></span>
                                    <input id="edit_nome_usuario" type="text" class="form-control" placeholder="Nome e sobrenome"
                                           title="Nome do usuário" name="nome" required data-required data-min="4" data-auto-capitalize>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="edit_email_usuario" class="form-control-label">Email de acesso <small>&nbsp; (O e-mail deve ser único)</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-at"></i></span>
                                    <input id="edit_email_usuario" type="email" class="form-control" placeholder="nome.sobrenome@email.com.br"
                                           title="Email de acesso" name="email" required data-required data-min="4">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-1 mt-2 pl-4">
                                <div class="styled-checkbox">
                                    <input type="checkbox" name="new_password" id="new-password">
                                    <label for="new-password">Gerar nova senha de acesso</label>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-secondary mb-3 mt-2" role="alert">
                            <i class="la la-exclamation-circle mr-2"></i>
                            Marque a opção acima para gerar uma nova senha!
                        </div>
                        <div class="hide">
                            {{ method_field("PUT") }}
                            <input type="hidden" name="usuario_id">
                            <input type="hidden" name="desativar_usuario" value="off">
                        </div>
                        <div class="em-separator separator-dashed mt-1"></div>
                        <div class="mt-3">
                            <button type="button" data-action="desativar" class="btn btn-danger pull-left">Desativar <i class="la la-trash right"></i></button>
                            <button class="btn btn-success pull-right">Atualizar <i class="la la-refresh right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para detalhes da comissao --}}
    <div id="view-comissao" class="modal modal-top fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Detalhes comissão </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-0 d-flex align-items-center">
                        <div class="col-3">
                            <div class="text-dark info-terminal text-center">
                                <p>
                                    Número de reservas <br>
                                    <strong data-text="reservas"></strong>
                                </p>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="text-dark info-terminal text-center">
                                <p>
                                    Total vendido <br>
                                    <strong data-text="vendido"></strong>
                                </p>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="text-dark info-terminal text-center">
                                <p>
                                    R$ Pago <br>
                                    <strong class="text-success" data-text="comissao"></strong>
                                </p>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="text-dark info-terminal text-center">
                                <p>
                                    R$ Pendente <br>
                                    <strong class="text-warning" data-text="comissao_pendente"></strong>
                                </p>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="text-dark info-terminal text-center">
                                <p>
                                    R$ Cancelado <br>
                                    <strong class="text-danger" data-text="comissao_cancelada"></strong>
                                </p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-divider mb-3">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Reserva</th>
                                        <th>Serviço vendido</th>
                                        <th class="text-center">Total reserva</th>
                                        <th class="text-center">Comissão</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Data da compra</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env("GOOGLE_API_KEY") }}&libraries=places&callback=TerminaisCtrl.initMap" async defer></script>
    <script>
        window.maps = {
            nome_local: '{{ $terminal->nome_local }}',
            endereco: '{{ $terminal->endereco }}',
            estado: '{{ $terminal->estado }}',
            cidade: '{{ $terminal->cidade }}',
            cep: '{{ $terminal->cep }}',
        }
    </script>
@endsection
