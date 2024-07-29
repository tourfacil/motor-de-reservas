@extends('template.header')

@section('title', 'Novo terminal')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Novo terminal</h2>
                <div>{{ Breadcrumbs::render('app.terminais.create') }}</div>
            </div>
        </div>
    </div>

    <div class="row" data-controller="TerminaisCtrl">
        <div class="col-12">
            <div class="tab-content">
                <div class="tab-pane active" id="terminal">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form data-validate-ajax method="POST" action="{{ route('app.terminais.store') }}" class="form-horizontal">
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>01. Identificação do terminal</h3>
                                        <p class="mt-1">Nome e identificação do terminal</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-4 mb-3">
                                        <label for="nome" class="form-control-label">Nome terminal</label>
                                        <input id="nome" type="text" class="form-control" placeholder="Ex. Laghetto Stilo" required
                                               data-required data-min="5" title="Nome terminal" data-auto-capitalize name="nome" autocomplete="nope">
                                        <div class="invalid-feedback"></div>
                                        <small>Pode ser o nome do local onde ficará o terminal.</small>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="identificacao" class="form-control-label">Identificação &nbsp; <small>(Patrimônio do fabricante)</small></label>
                                        <input id="identificacao" type="text" class="form-control" placeholder="000000" required
                                               data-required data-min="6" title="Identificação" name="identificacao" autocomplete="nope">
                                        <div class="invalid-feedback"></div>
                                        <small>O código é localizado na etiqueta atrás do terminal.</small>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="fornecedor_id" class="form-control-label">Parceiro do Terminal &nbsp; <small>(Fornecedor)</small></label>
                                        <select id="fornecedor_id" name="fornecedor_id" class="form-control boostrap-select-custom" required
                                                data-required title="Selecione um parceiro">
                                            @foreach($fornecedores as $fornecedor)
                                                <option value="{{ $fornecedor->id }}">{{ $fornecedor->nome_fantasia }}</option>
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
                                                <option value="{{ $destino->id }}">{{ $destino->nome }}</option>
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
                                                <option value="{{ $id }}">{{ $fabricante }}</option>
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
                                        <input id="nome_responsavel" type="text" class="form-control" placeholder="Nome e sobrenome" required
                                               data-required data-min="5" title="Nome completo" data-auto-capitalize name="nome_responsavel" autocomplete="nope">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="email_responsavel" class="form-control-label">E-mail contato</label>
                                        <input id="email_responsavel" type="email" class="form-control" placeholder="nome.sobrenome@email.com" required
                                               title="E-mail contato" data-required data-min="3" name="email_responsavel" autocomplete="nope">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-xl-4 mb-3">
                                        <label for="telefone_responsavel" class="form-control-label">Telefone</label>
                                        <input id="telefone_responsavel" type="tel" class="form-control vanillaMask" placeholder="(99) 9 9999-9999"
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
                                                <label for="search_google" class="form-control-label">Local onde ficará o terminal &nbsp; <small>(Nome do hotel ou endereço)</small></label>
                                                <input id="search_google" type="text" class="form-control" placeholder="Ex. Hotel Laghetto Toscana" required
                                                       data-required data-min="5" title="Local do terminal" data-auto-capitalize name="endereco_mapa" autocomplete="nope">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-xl-12 mb-4">
                                                <label for="endereco_terminal" class="form-control-label">Endereço</label>
                                                <input id="endereco_terminal" type="text" class="form-control" placeholder="Rua, avenida, travessa e etc." required
                                                       data-required data-min="5" title="Endereço" data-auto-capitalize name="endereco" autocomplete="nope">
                                                <div class="invalid-feedback"></div>
                                                <input type="hidden" name="nome_local">
                                            </div>
                                            <div class="col-xl-6 mb-4">
                                                <label for="cidade" class="form-control-label">Cidade</label>
                                                <input id="cidade" type="text" required class="form-control" placeholder="Ex. Gramado, Canela e etc."
                                                       name="cidade" data-required title="Cidade" data-min="3" data-auto-capitalize autocomplete="nope">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-xl-6 mb-4">
                                                <label for="cep" class="form-control-label">CEP</label>
                                                <input id="cep" type="tel" required class="form-control vanillaMask" placeholder="00000-000"
                                                       name="cep" data-required title="CEP" data-min="9" data-mask="cep" autocomplete="nope">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-xl-12">
                                                <label for="estado" class="form-control-label">Estado</label>
                                                <select id="estado" name="estado" class="form-control boostrap-select-custom" required
                                                        data-required title="Selecione um estado">
                                                    @foreach($estados as $uf => $estado)
                                                        <option value="{{ $uf }}" data-uf="{{ $uf }}">{{ $estado }} - {{ $uf }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div id="google-map"></div>
                                        <div id="infowindow-content">
                                            <strong id="place-name" class="title"></strong><br>
                                            <span id="place-address"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="hide">
                                    <input type="hidden" name="geolocation">
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

@section("scripts")
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env("GOOGLE_API_KEY") }}&libraries=places&callback=TerminaisCtrl.initMap" async defer></script>
@endsection
