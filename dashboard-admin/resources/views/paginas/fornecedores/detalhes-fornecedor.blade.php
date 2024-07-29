@extends('template.header')

@section('title', $fornecedor->nome_fantasia)

@section('content')

    @php($count = 1)

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Detalhes fornecedor</h2>
                <div>{{ Breadcrumbs::render('app.fornecedores.view') }}</div>
            </div>
        </div>
    </div>

    <div class="row" data-controller="FornecedorCtrl" data-cnpj="{{ route('app.fornecedores.cnpj') }}">
        <div class="col-12">
            <ul id="tab_fornecedor" class="nav nav-tabs nav-tab-header nav-tab-no-border tabs-mobile">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#cadastro">
                        <i class="la la-suitcase la-2x align-middle mr-2"></i> Ficha de cadastro
                    </a>
                </li>
                @IF($fornecedor->dadosBancarios != null)
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#financeiro">
                            <i class="la la-credit-card la-2x align-middle mr-2"></i> Dados financeiros
                        </a>
                    </li>
                @ENDIF
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#regras">
                        <i class="la la-code-fork la-2x align-middle"></i> Regras e termos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#split">
                        <i class="la la-random la-2x align-middle mr-2"></i> Split pagamentos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#users">
                        <i class="la la-users la-2x align-middle"></i> Usuários
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="cadastro">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form data-validate-ajax method="POST" action="{{ route('app.fornecedores.update') }}" class="form-horizontal">
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>0{{ $count++ }}. Dados da empresa</h3>
                                        <p class="mt-1">Informações jurídicas do parceiro</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-3 mb-3">
                                        <label for="cnpj" class="form-control-label">CNPJ</label>
                                        <input id="cnpj" type="tel" class="form-control vanillaMask" placeholder="00.000.000/0000-00" required
                                               data-required data-min="18" data-mask="cnpj" title="CNPJ" name="cnpj" value="{{ $fornecedor->cnpj }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="razao_social" class="form-control-label">Razão social</label>
                                        <input id="razao_social" type="text" class="form-control" placeholder="Razão social da empresa" required
                                               data-required data-min="3" title="Razão social" data-auto-capitalize name="razao_social" value="{{ $fornecedor->razao_social }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-5 mb-3">
                                        <label for="nome_fantasia" class="form-control-label">Nome fantasia</label>
                                        <input id="nome_fantasia" type="text" class="form-control" placeholder="Nome fantasia da empresa" required
                                               data-required data-min="3" title="Nome fantasia" data-auto-capitalize name="nome_fantasia" value="{{ $fornecedor->nome_fantasia }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-3 mb-3">
                                        <label for="responsavel" class="form-control-label">Pessoa responsável</label>
                                        <input id="responsavel" type="text" class="form-control" placeholder="Nome completo" required
                                               data-required data-min="3" data-auto-capitalize title="Pessoa responsável" name="responsavel" value="{{ $fornecedor->responsavel }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="email_responsavel" class="form-control-label">E-mail para contato</label>
                                        <input id="email_responsavel" type="email" class="form-control" placeholder="nome.sobrenome@email.com" required
                                               data-required data-min="3" title="E-mail para contato" name="email_responsavel" value="{{ $fornecedor->email_responsavel }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-5 mb-3">
                                        <label for="telefone_responsavel" class="form-control-label">Telefone responsável</label>
                                        <input id="telefone_responsavel" type="tel" class="form-control vanillaMask" placeholder="(00) 0 0000-0000" required
                                               data-required data-min="14" title="Telefone responsável" data-mask="phone" name="telefone_responsavel" value="{{ $fornecedor->telefone_responsavel }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="em-separator separator-dashed mt-4 mb-5"></div>
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>0{{ $count++ }}. Dados de endereço</h3>
                                        <p class="mt-1">Informações sobre o endereço do parceiro</p>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col-xl-4 mb-3">
                                        <label for="cep" class="form-control-label">CEP</label>
                                        <input id="cep" type="tel" class="form-control vanillaMask" placeholder="00000-000" required
                                               data-required data-min="9" data-mask="cep" title="CEP" name="cep" value="{{ $fornecedor->cep }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-8 mb-3">
                                        <label for="endereco" class="form-control-label">Endereço completo</label>
                                        <input id="endereco" type="text" class="form-control" placeholder="Rua, avenida, travessa e etc." required
                                               data-required data-min="3" title="Endereço" data-auto-capitalize name="endereco" value="{{ $fornecedor->endereco }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col-xl-4 mb-3">
                                        <label for="bairro" class="form-control-label">Bairro</label>
                                        <input id="bairro" type="text" class="form-control" placeholder="Centro" required
                                               data-required data-min="3" title="Bairro" data-auto-capitalize name="bairro" value="{{ $fornecedor->bairro }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="cidade" class="form-control-label">Cidade</label>
                                        <input id="cidade" type="text" class="form-control" placeholder="Gramado, Canela e etc." required
                                               data-required data-min="3" title="Cidade" data-auto-capitalize name="cidade" value="{{ $fornecedor->cidade }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="estado" class="form-control-label">Estado</label>
                                        <input id="estado" type="text" class="form-control" placeholder="Rio Grande do Sul" required
                                               data-required data-min="3" title="Estado" data-auto-capitalize name="estado" value="{{ $fornecedor->estado }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="em-separator separator-dashed mt-4 mb-5"></div>
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>0{{ $count++ }}. Dados de contato</h3>
                                        <p class="mt-1">Informações sobre contatos do parceiro</p>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col-xl-4 mb-3">
                                        <label for="email" class="form-control-label">E-mails</label>
                                        <input id="email" type="text" class="form-control" placeholder="nome.sobrenome@email.com" required
                                               data-required data-min="3" title="E-mails" name="email" value="{{ $fornecedor->email }}">
                                        <small>Para multiplus e-mails separe por vírgula</small>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="telefone" class="form-control-label">Telefones</label>
                                        <input id="telefone" type="text" class="form-control" placeholder="(00) 0 0000-0000" required
                                               data-required data-min="5" title="Telefone" name="telefone" value="{{ $fornecedor->telefone }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="site" class="form-control-label">URL site</label>
                                        <input id="site" type="text" class="form-control" placeholder="https://www.site.com.br" title="URL site"
                                               name="site" value="{{ $fornecedor->site }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="hide">
                                    {{ method_field("PUT") }}
                                    <input type="hidden" name="fornecedor_id" value="{{ $fornecedor->id }}">
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="text-center">
                                    <button class="btn btn-gradient-01" type="submit">Atualizar cadastro <i class="la la-refresh right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @IF($fornecedor->dadosBancarios != null)
                    <div class="tab-pane" id="financeiro">
                        <div class="widget has-shadow">
                            <div class="widget-body pl-lg-5 pr-lg-5">
                                <form data-validate-ajax method="POST" action="{{ route('app.fornecedores.dados-bancarios.update') }}" class="form-horizontal">
                                    <div class="ml-auto mt-4 mb-4">
                                        <div class="section-title mr-auto">
                                            <h3>0{{ $count++ }}. Dados financeiros</h3>
                                            <p class="mt-1">Informações bancárias do parceiro</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xl-4 mb-3">
                                            <label for="banco" class="form-control-label">Banco</label>
                                            <select id="banco" name="banco" class="form-control boostrap-select-custom" required
                                                    data-required title="Selecione um banco" data-placeholder="Procurar banco">
                                                @foreach($bancos as $banco)
                                                    @if($fornecedor->dadosBancarios->banco == $banco['code'])
                                                        <option value="{{ $banco['code'] }}" selected>{{ $banco['code'] . " - " . $banco['name'] }}</option>
                                                    @else
                                                        <option value="{{ $banco['code'] }}">{{ $banco['code'] . " - " . $banco['name'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-xl-2 mb-3">
                                            <label for="agencia" class="form-control-label">Agência</label>
                                            <input id="agencia" type="tel" class="form-control" placeholder="0000xx" required
                                                data-required data-min="3" title="Número agência" name="agencia" value="{{ $fornecedor->dadosBancarios->agencia }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-xl-3 mb-3">
                                            <label for="conta" class="form-control-label">Conta bancária</label>
                                            <input id="conta" type="tel" class="form-control" placeholder="0000000-xx" required
                                                data-required data-min="3" title="Agência bancária" name="conta" value="{{ $fornecedor->dadosBancarios->conta }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-xl-3 mb-3">
                                            <label for="tipo_conta" class="form-control-label">Tipo da conta</label>
                                            <select id="tipo_conta" name="tipo_conta" class="form-control boostrap-select-custom" required
                                                    data-required title="Selecione um tipo">
                                                @foreach($tipo_contas as $value => $tipo_conta)
                                                    @if($fornecedor->dadosBancarios->tipo_conta == $value)
                                                        <option value="{{ $value }}" selected>{{ $tipo_conta }}</option>
                                                    @else
                                                        <option value="{{ $value }}">{{ $tipo_conta }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xl-3 mb-3">
                                            <label for="tipo_fatura" class="form-control-label">Tipo da fatura</label>
                                            <select id="tipo_fatura" name="tipo_fatura" class="form-control boostrap-select-custom" required
                                                    data-required title="Selecione um tipo">
                                                @foreach($tipo_faturas as $index => $tipo_fatura)
                                                    <option {{ ($fornecedor->tipo_fatura == $index) ? 'selected' : ''}} value="{{ $index }}"> {{ $tipo_fatura }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-xl-3 mb-3">
                                            <label for="tipo_periodo_fatura" class="form-control-label">Tipo de período da fatura</label>
                                            <select id="tipo_periodo_fatura" name="tipo_periodo_fatura" class="form-control boostrap-select-custom" required
                                                    data-required title="Selecione um tipo">
                                                @foreach($tipo_periodo_faturas as $index => $tipo_periodo_fatura)
                                                    <option {{ ($fornecedor->tipo_periodo_fatura == $index) ? 'selected' : ''}} value="{{ $index }}"> {{ $tipo_periodo_fatura }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xl-12 mb-4">
                                            <label for="observacoes" class="form-control-label">Observações bancárias <small>&nbsp;(Não é obrigatório)</small></label>
                                            <textarea id="observacoes" name="observacoes" class="form-control not-resize" rows="3"
                                                    placeholder="Ex. nome de quem está a conta">{{ $fornecedor->dadosBancarios->observacoes }}</textarea>
                                        </div>
                                        <div class="col-xl-12">
                                            <p class="mb-1">*As informações bancárias são somente armazenar os dados para futuros pagamentos ao fornecedor.</p>
                                        </div>
                                    </div>
                                    <div class="hide">
                                        {{ method_field("PUT") }}
                                        <input type="hidden" name="fornecedor_id" value="{{ $fornecedor->id }}">
                                    </div>
                                    <div class="em-separator separator-dashed"></div>
                                    <div class="text-center">
                                        <button class="btn btn-gradient-01" type="submit">Atualizar dados <i class="la la-refresh right"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @ENDIF
                <div class="tab-pane" id="regras">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form data-validate-ajax method="POST" action="{{ route('app.fornecedores.regras.update') }}" class="form-horizontal">
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>0{{ $count++ }}. Regras e termos</h3>
                                        <p class="mt-1">Regras e termos genéricas do parceiro</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-12">
                                        <label for="termos" class="form-control-label">Condições dos serviços <small>&nbsp;(Não é obrigatório)</small></label>
                                        <div class="position-relative">
                                            <textarea id="termos" name="termos" class="form-control not-resize simple-editor"
                                                      placeholder="Ex. é necessário levar documento com foto em todos os serviços">{{ $fornecedor->termos }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <p class="mb-1">*As regras e termos irão aparecer na página de serviço e no voucher do cliente!</p>
                                    </div>
                                </div>
                                <div class="hide">
                                    {{ method_field("PUT") }}
                                    <input type="hidden" name="fornecedor_id" value="{{ $fornecedor->id }}">
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="text-center">
                                    <button class="btn btn-gradient-01" type="submit">Atualizar regras <i class="la la-refresh right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="split">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <div class="ml-auto mt-4 mb-5">
                                <div class="section-title mr-auto">
                                    <h3>0{{ $count++ }}. Split de pagamentos</h3>
                                    <p class="mt-1">Tokens para split de pagamentos para o parceiro</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-border-bottom mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Token</th>
                                        <th>Canal de venda</th>
                                        <th class="text-center">Cadastrado</th>
                                        <th class="text-center">Administração</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($fornecedor->splits as $split)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <a href="{{ route('app.fornecedores.splits.view', $split->id) }}" data-action="edit-split">{{ $split->token }}</a>
                                            </td>
                                            <td class="text-primary">{{ $split->canalVenda->site }}</td>
                                            <td class="text-center">{{ $split->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('app.fornecedores.splits.view', $split->id) }}" data-action="edit-split" class="btn btn-outline-primary">
                                                    Editar <i class="la la-edit right"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center pt-4 pb-4">Nenhum split (token) cadastrado</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="em-separator separator-dashed"></div>
                            <div class="text-center">
                                @if($canais_venda_split->count())
                                    <button class="btn btn-gradient-01" data-target="#new-split" data-toggle="modal">
                                        Cadastrar split <i class="la la-plus right"></i>
                                    </button>
                                @else
                                    <strong class="text-primary">Este fornecedor já possui split para todos os canais de venda.</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="users">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <div class="ml-auto mt-4 mb-5">
                                <div class="section-title mr-auto">
                                    <h3>0{{ $count++ }}. Usuários de acesso</h3>
                                    <p class="mt-1">Lista de usuários que possuem acesso ao painel do fornecedor.</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-border-bottom mb-0">
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
                                    @forelse($fornecedor->usuarios as $usuario)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if($usuario->status)
                                                    <a href="{{ route('app.fornecedores.usuarios.view', $usuario->id) }}" data-action="edit-user">
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
                                                    <a href="{{ route('app.fornecedores.usuarios.view', $usuario->id) }}" data-action="edit-user" class="btn btn-outline-primary">
                                                        Editar <i class="la la-edit right"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('app.fornecedores.usuarios.restore') }}" data-action="activate-user" data-id="{{ $usuario->id }}" class="btn btn-outline-light">
                                                        Ativar <i class="la la-level-up right"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center pt-4 pb-4">Nenhum usuário cadastrado</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="em-separator separator-dashed"></div>
                            <div class="text-center">
                                <button class="btn btn-gradient-01" data-target="#new-user" data-toggle="modal">
                                    Cadastrar usuário <i class="la la-plus right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para cadastrar um split --}}
    <div id="new-split" class="modal modal-top fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Novo split de pagamentos</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax method="POST" action="{{ route('app.fornecedores.splits.store') }}" class="form-horizontal">
                        <div class="form-group row mb-0">
                            <div class="col-xl-12 mb-3">
                                <label for="canal_venda_id_new" class="form-control-label">Para qual canal de venda?</label>
                                <select id="canal_venda_id_new" name="canal_venda_id" class="form-control boostrap-select-custom" required
                                        data-required title="Selecione um canal de venda">
                                    @foreach($canais_venda_split as $canal_venda)
                                        <option value="{{ $canal_venda->id }}">{{ $canal_venda->site }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="token_new" class="form-control-label">Token de acesso</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-key"></i></span>
                                    <input id="token_new" type="text" class="form-control" placeholder="MPA-5432432"
                                           title="Token de acesso" name="token" required data-required data-min="4">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="hide">
                            <input type="hidden" name="fornecedor_id" value="{{ $fornecedor->id }}">
                        </div>
                        <div class="em-separator separator-dashed mt-1"></div>
                        <div class="mt-3 text-center">
                            <button class="btn btn-success">Salvar token <i class="la la-save right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para edit um split --}}
    <div id="edit-split" class="modal modal-top fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar split de pagamentos</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax method="POST" action="{{ route('app.fornecedores.splits.update') }}" class="form-horizontal">
                        <div class="form-group row mb-0">
                            <div class="col-xl-12 mb-3">
                                <label for="canal_venda_id_edit" class="form-control-label">Canal de venda</label>
                                <input id="canal_venda_id_edit" type="text" class="form-control" readonly disabled>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="token_edit" class="form-control-label">Token de acesso</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-key"></i></span>
                                    <input id="token_edit" type="text" class="form-control" placeholder="MPA-5432432"
                                           title="Token de acesso" name="token" required data-required data-min="4">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="hide">
                            {{ method_field("PUT") }}
                            <input type="hidden" name="split_pagamento_id">
                        </div>
                        <div class="em-separator separator-dashed mt-1"></div>
                        <div class="mt-3 text-center">
                            <button class="btn btn-success">Atualizar token <i class="la la-refresh right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para cadastrar novo usuario --}}
    <div id="new-user" class="modal modal-top fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Novo usuário</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax method="POST" action="{{ route('app.fornecedores.usuarios.store') }}" class="form-horizontal">
                        <div class="form-group row mb-0">
                            <div class="col-xl-12 mb-3">
                                <label for="nome" class="form-control-label">Nome completo</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-user"></i></span>
                                    <input id="nome" type="text" class="form-control" placeholder="Nome e sobrenome"
                                           title="Nome do usuário" name="nome" required data-required data-min="4" data-auto-capitalize>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="email-user" class="form-control-label">Email de acesso <small>&nbsp; (O e-mail deve ser único)</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-at"></i></span>
                                    <input id="email-user" type="email" class="form-control" placeholder="nome.sobrenome@email.com.br"
                                           title="Email de acesso" name="email" required data-required data-min="4">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="password" class="form-control-label">Senha de acesso <small>&nbsp; (Min 3 caracteres)</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-unlock"></i></span>
                                    <input id="password" type="text" class="form-control" placeholder="******"
                                           title="Senha de acesso" name="password" required data-required data-min="3">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="level" class="form-control-label">Nível de acesso</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-balance-scale"></i></span>
                                    <select id="level" name="level" class="form-control boostrap-select-custom" required
                                            data-required title="Selecione um nível">
                                        @foreach($niveis_usuarios as $key => $nivel)
                                            <option value="{{ $key }}">{{ $nivel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="em-separator separator-dashed mt-1"></div>
                        <div class="hide">
                            <input type="hidden" name="fornecedor_id" value="{{ $fornecedor->id }}">
                        </div>
                        <div class="mt-3 text-center">
                            <button class="btn btn-success">Cadastrar usuário <i class="la la-angle-right right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para editar usuario --}}
    <div id="edit-user" class="modal modal-top fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar usuário</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax method="POST" action="{{ route('app.fornecedores.usuarios.update') }}" class="form-horizontal">
                        <div class="form-group row mb-0">
                            <div class="col-xl-12 mb-3">
                                <label for="nome-edit" class="form-control-label">Nome completo</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-user"></i></span>
                                    <input id="nome-edit" type="text" class="form-control" placeholder="Nome e sobrenome"
                                           title="Nome do usuário" name="nome" required data-required data-min="4" data-auto-capitalize>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="email-edit" class="form-control-label">Email de acesso <small>&nbsp; (O e-mail deve ser único)</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-at"></i></span>
                                    <input id="email-edit" type="email" class="form-control" placeholder="nome.sobrenome@email.com.br"
                                           title="Email de acesso" name="email" required data-required data-min="4">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="level-edit" class="form-control-label">Nível de acesso</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-balance-scale"></i></span>
                                    <select id="level-edit" name="level" class="form-control boostrap-select-custom" required
                                            data-required title="Selecione um nível">
                                        @foreach($niveis_usuarios as $key => $nivel)
                                            <option value="{{ $key }}">{{ $nivel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="password-edit" class="form-control-label">Nova senha de acesso <small>&nbsp; (Min 3 caracteres)</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-unlock"></i></span>
                                    <input id="password-edit" type="text" class="form-control" placeholder="******"
                                           title="Nova senha de acesso" name="password" data-min="3">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="em-separator separator-dashed mt-1"></div>
                        <div class="hide">
                            {{ method_field("PUT") }}
                            <input type="hidden" name="usuario_id">
                            <input type="hidden" name="desativar_usuario" value="off">
                        </div>
                        <div class="mt-3">
                            <button type="button" data-action="desativar" class="btn btn-danger pull-left">Desativar <i class="la la-trash right"></i></button>
                            <button class="btn btn-success pull-right">Atualizar <i class="la la-refresh right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
