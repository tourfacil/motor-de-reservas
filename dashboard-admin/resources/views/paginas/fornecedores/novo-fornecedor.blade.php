@extends('template.header')

@section('title', 'Novo fornecedor')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Novo fornecedor</h2>
                <div>{{ Breadcrumbs::render('app.fornecedores.create') }}</div>
            </div>
        </div>
    </div>

    <div class="row" data-controller="FornecedorCtrl" data-cnpj="{{ route('app.fornecedores.cnpj') }}">
        <div class="col-12">
            <ul id="tab_fornecedor" class="nav nav-tabs nav-tab-header nav-tab-no-border">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#cadastro">
                        <i class="la la-suitcase la-2x align-middle mr-2"></i> Ficha de cadastro
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" data-toggle="tab" href="#financeiro">
                        <i class="la la-credit-card la-2x align-middle mr-2"></i> Dados financeiros
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" data-toggle="tab" href="#regras">
                        <i class="la la-code-fork la-2x align-middle"></i> Regras e termos
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="cadastro">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form name="ficha-cadastro" method="POST" action="{{ route('app.fornecedores.store') }}" class="form-horizontal">
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>01. Dados da empresa</h3>
                                        <p class="mt-1">Informações jurídicas do parceiro</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-3 mb-3">
                                        <label for="cnpj" class="form-control-label">CNPJ</label>
                                        <input id="cnpj" type="tel" class="form-control vanillaMask search_cnpj" placeholder="00.000.000/0000-00" required
                                               data-required data-min="18" data-mask="cnpj" title="CNPJ" name="cnpj">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="razao_social" class="form-control-label">Razão social</label>
                                        <input id="razao_social" type="text" class="form-control" placeholder="Razão social da empresa" required
                                               data-required data-min="3" title="Razão social" data-auto-capitalize name="razao_social">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-5 mb-3">
                                        <label for="nome_fantasia" class="form-control-label">Nome fantasia</label>
                                        <input id="nome_fantasia" type="text" class="form-control" placeholder="Nome fantasia da empresa" required
                                               data-required data-min="3" title="Nome fantasia" data-auto-capitalize name="nome_fantasia">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-3 mb-3">
                                        <label for="responsavel" class="form-control-label">Pessoa responsável</label>
                                        <input id="responsavel" type="text" class="form-control" placeholder="Nome completo" required
                                               data-required data-min="3" data-auto-capitalize title="Pessoa responsável" name="responsavel">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="email_responsavel" class="form-control-label">E-mail para contato</label>
                                        <input id="email_responsavel" type="email" class="form-control" placeholder="nome.sobrenome@email.com" required
                                               data-required data-min="3" title="E-mail para contato" name="email_responsavel">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-5 mb-3">
                                        <label for="telefone_responsavel" class="form-control-label">Telefone responsável</label>
                                        <input id="telefone_responsavel" type="tel" class="form-control vanillaMask" placeholder="(00) 0 0000-0000" required
                                               data-required data-min="14" title="Telefone responsável" data-mask="phone" name="telefone_responsavel">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="em-separator separator-dashed mt-4 mb-5"></div>
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>02. Dados de endereço</h3>
                                        <p class="mt-1">Informações sobre o endereço do parceiro</p>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col-xl-4 mb-3">
                                        <label for="cep" class="form-control-label">CEP</label>
                                        <input id="cep" type="tel" class="form-control vanillaMask" placeholder="00000-000" required
                                               data-required data-min="9" data-mask="cep" title="CEP" name="cep">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-8 mb-3">
                                        <label for="endereco" class="form-control-label">Endereço completo</label>
                                        <input id="endereco" type="text" class="form-control" placeholder="Rua, avenida, travessa e etc." required
                                               data-required data-min="3" title="Endereço" data-auto-capitalize name="endereco">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col-xl-4 mb-3">
                                        <label for="bairro" class="form-control-label">Bairro</label>
                                        <input id="bairro" type="text" class="form-control" placeholder="Centro" required
                                               data-required data-min="3" title="Bairro" data-auto-capitalize name="bairro">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="cidade" class="form-control-label">Cidade</label>
                                        <input id="cidade" type="text" class="form-control" placeholder="Gramado, Canela e etc." required
                                               data-required data-min="3" title="Cidade" data-auto-capitalize name="cidade">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="estado" class="form-control-label">Estado</label>
                                        <input id="estado" type="text" class="form-control" placeholder="Rio Grande do Sul" required
                                               data-required data-min="3" title="Estado" data-auto-capitalize name="estado">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="em-separator separator-dashed mt-4 mb-5"></div>
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>03. Dados de contato</h3>
                                        <p class="mt-1">Informações sobre contatos do parceiro</p>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col-xl-4 mb-3">
                                        <label for="email" class="form-control-label">E-mails para confirmação</label>
                                        <input id="email" type="text" class="form-control" placeholder="nome.sobrenome@email.com" required
                                               data-required data-min="3" title="E-mails" name="email">
                                        <small>Para multiplus e-mails separe por vírgula</small>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="telefone" class="form-control-label">Telefone no voucher</label>
                                        <input id="telefone" type="text" class="form-control" placeholder="(00) 0 0000-0000" required
                                               data-required data-min="14" title="Telefone" name="telefone">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="site" class="form-control-label">URL site</label>
                                        <input id="site" type="text" class="form-control" placeholder="https://www.site.com.br" title="URL site" name="site">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="text-center">
                                    <button class="btn btn-gradient-01" type="submit">Próximo passo <i class="la la-angle-right right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="financeiro">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form name="financeiro-cadastro" method="POST" action="{{ route('app.fornecedores.dados-bancarios.store') }}" class="form-horizontal">
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>04. Dados financeiros</h3>
                                        <p class="mt-1">Informações bancárias do parceiro</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-4 mb-3">
                                        <label for="banco" class="form-control-label">Banco</label>
                                        <select id="banco" name="banco" class="form-control boostrap-select-custom" required
                                                data-required title="Selecione um banco" data-placeholder="Procurar banco">
                                            @foreach($bancos as $banco)
                                                <option value="{{ $banco['code'] }}">{{ $banco['code'] . " - " . $banco['name'] }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-2 mb-3">
                                        <label for="agencia" class="form-control-label">Agência</label>
                                        <input id="agencia" type="tel" class="form-control" placeholder="0000xx" required
                                               data-required data-min="3" title="Número agência" name="agencia">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-3 mb-3">
                                        <label for="conta" class="form-control-label">Conta bancária</label>
                                        <input id="conta" type="tel" class="form-control" placeholder="0000000-xx" required
                                               data-required data-min="3" title="Agência bancária" name="conta">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-3 mb-3">
                                        <label for="tipo_conta" class="form-control-label">Tipo da conta</label>
                                        <select id="tipo_conta" name="tipo_conta" class="form-control boostrap-select-custom" required
                                                data-required title="Selecione um tipo">
                                            @foreach($tipo_contas as $value => $tipo_conta)
                                                <option value="{{ $value }}">{{ $tipo_conta }}</option>
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
                                                <option value="{{ $index }}"> {{ $tipo_fatura }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-3 mb-3">
                                        <label for="tipo_periodo_fatura" class="form-control-label">Tipo de período da fatura</label>
                                        <select id="tipo_periodo_fatura" name="tipo_periodo_fatura" class="form-control boostrap-select-custom" required
                                                data-required title="Selecione um tipo">
                                            @foreach($tipo_periodo_faturas as $index => $tipo_periodo_fatura)
                                                <option value="{{ $index }}"> {{ $tipo_periodo_fatura }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-12 mb-4">
                                        <label for="observacoes" class="form-control-label">Observações bancárias <small>&nbsp;(Não é obrigatório)</small></label>
                                        <textarea id="observacoes" name="observacoes" class="form-control not-resize" rows="3"
                                                  placeholder="Ex. nome de quem está a conta"></textarea>
                                    </div>
                                    <div class="col-xl-12">
                                        <p class="mb-1">*As informações bancárias são somente armazenar os dados para futuros pagamentos ao fornecedor.</p>
                                    </div>
                                </div>
                                <div class="hide">
                                    <input type="hidden" name="fornecedor_id" class="callback_fornecedor_id">
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="text-center">
                                    <button class="btn btn-gradient-01" type="submit">Próximo passo <i class="la la-angle-right right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="regras">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form name="regras-cadastro" method="POST" action="{{ route('app.fornecedores.regras.store') }}" class="form-horizontal">
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>05. Regras e termos</h3>
                                        <p class="mt-1">Regras e termos genéricas do parceiro</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-12">
                                        <label for="termos" class="form-control-label">Condições dos serviços <small>&nbsp;(Não é obrigatório)</small></label>
                                        <div class="position-relative">
                                            <textarea id="termos" name="termos" class="form-control not-resize simple-editor" placeholder="Ex. é necessário levar documento com foto em todos os serviços"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <p class="mb-1">*As regras e termos irão aparecer na página de serviço e no voucher do cliente!</p>
                                    </div>
                                </div>
                                <div class="hide">
                                    <input type="hidden" name="fornecedor_id" class="callback_fornecedor_id">
                                </div>
                                <div class="em-separator separator-dashed"></div>
                                <div class="text-center">
                                    <button class="btn btn-gradient-01" type="submit">Finalizar cadastro <i class="la la-angle-right right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
