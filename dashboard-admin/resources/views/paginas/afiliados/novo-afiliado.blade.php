@extends('template.header')

@section('title', 'Novo Afiliado')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Novo afiliado</h2>
                <div>{{ Breadcrumbs::render('app.afiliados.create') }}</div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="tab-content">
            <div class="tab-pane active" id="cadastro">
                <div class="widget has-shadow">
                    <div class="widget-body pl-lg-5 pr-lg-5">
                        <form name="ficha-cadastro" method="POST" action="{{ route('app.afiliados.store') }}" class="form-horizontal">
                            @CSRF
                            <div class="ml-auto mt-4 mb-4">
                                <div class="section-title mr-auto">
                                    <h3>01. Dados do afiliado</h3>
                                    <p class="mt-1">Informações para a criação do novo afiliado</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xl-6 mb-3">
                                    <label for="nome_fantasia" class="form-control-label">Nome fantasia *</label>
                                    <input id="nome_fantasia" type="text" class="form-control" placeholder="Nome fantasia do afiliado" required
                                           data-required data-min="3" title="nome_fantasia" name="nome_fantasia">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label for="razao_social" class="form-control-label">Razão social</label>
                                    <input id="razao_social" type="text" class="form-control" placeholder="Razão social do afiliado"
                                           data-required data-min="3" title="Razão social" name="razao_social">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xl-4 mb-3">
                                    <label for="cpf" class="form-control-label">CPF</label>
                                    <input id="cpf" type="text" class="form-control vanillaMask" placeholder="000.000.000-00"
                                           data-required data-min="14" title="cpf" data-mask="cpf" name="cpf">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label for="cnpj" class="form-control-label">CNPJ</label>
                                    <input id="cnpj" type="text" class="form-control vanillaMask" data-mask="cnpj" placeholder="00.000.000/0001-00"
                                           data-required data-min="18" title="cnpj" name="cnpj">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label for="cep" class="form-control-label">CEP *</label>
                                    <input id="cep" type="text" class="form-control vanillaMask" data-mask="cep" placeholder="00000-000"
                                           data-required data-min="9" title="cep" name="cep" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xl-4 mb-3">
                                    <label for="email" class="form-control-label">E-mail *</label>
                                    <input id="email" type="email" required class="form-control" placeholder="afiliado@tourfacil.com.br" title="email" name="email">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label for="telefone" class="form-control-label">Telefone *</label>
                                    <input id="telefone" type="text" class="form-control vanillaMask" placeholder="(00) 0 0000-0000"
                                           data-required data-mask="phone" data-min="16" required title="telefone" name="telefone">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label for="site" class="form-control-label">Site</label>
                                    <input id="site" type="text" class="form-control" placeholder="www.tourfacil.com.br"
                                           data-required data-min="3" title="site" name="site">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xl-4 mb-3">
                                    <label for="endereco" class="form-control-label">Endereço *</label>
                                    <input id="endereco" type="text" class="form-control" required data-required data-min="3" placeholder="R. João Alfredo Schneider, 635" title="endereco" name="endereco">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label for="cidade" class="form-control-label">Cidade *</label>
                                    <input id="cidade" type="text" class="form-control" placeholder="Gramado"
                                           data-required data-min="3" title="cidade" name="cidade" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label for="bairro" class="form-control-label">Bairro *</label>
                                    <input id="bairro" type="text" class="form-control" placeholder="Centro"
                                           data-required data-min="3" required title="bairro" name="bairro">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-2 mb-3">
                                    <label for="estado" class="form-control-label">Estado *</label>
                                    <select id="select-estado" name="estado" class="form-control boostrap-select-custom" required>
                                        @FOREACH($estados as $sigla => $estado)
                                            <option {{ ($sigla == 'RS') ? 'selected' : '' }} value="{{ $sigla }}">{{ $estado }}</option>
                                        @ENDFOREACH
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xl-3 mb-3">
                                    <label for="comissao_passeios" class="form-control-label">Comissão passeios *</label>
                                    <input id="comissao_passeios" type="text" class="form-control" required data-required data-min="3" placeholder="Comissão de passeios" title="Comissão de passeios" name="comissao_passeios">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label for="comissao_ingressos" class="form-control-label">Comissão de ingressos *</label>
                                    <input id="comissao_ingressos" type="text" class="form-control" required data-required data-min="3" placeholder="Comissão de ingressos" title="Comissão de ingressos" name="comissao_ingressos">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label for="comissao_gastronomia" class="form-control-label">Comissão de gastronomia *</label>
                                    <input id="comissao_gastronomia" type="text" class="form-control" required data-required data-min="3" placeholder="Comissão de gastronomia" title="Comissão de gastronomia" name="comissao_gastronomia">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label for="comissao_transfer" class="form-control-label">Comissão de transfers *</label>
                                    <input id="comissao_transfer" type="text" class="form-control" required data-required data-min="3" placeholder="Comissão de transfers" title="Comissão de transfers" name="comissao_transfer">
                                    <div class="invalid-feedback"></div>
                                </div>
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

    <script>

        {{--    Carrega as mascaras    --}}
        window.onload = () => {
            App.vanillaMask();
        }

    </script>
@endsection
