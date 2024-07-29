@extends('template.header')

@section('title', 'Nova agenda - ' . $canal_venda->nome)

@section('content')

    @php($steps = 1)

    <div class="row">
        <div class="page-header pb-4">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Nova agenda serviço <span class="text-gradient-01">| {{ $canal_venda->nome }}</span></h2>
                <div>{{ Breadcrumbs::render('app.agenda.create') }}</div>
            </div>
        </div>
    </div>

    {{-- Informativo sobre a pagina --}}
    <div class="row">
        <div class="col-12">
            <div class="alert alert-secondary mb-4" role="alert">
                <i class="la la-info-circle mr-2"></i>
                Você pode criar uma <strong>nova agenda</strong> ou utilizar uma <strong>agenda compartilhada</strong> de outro serviço.
            </div>
        </div>
    </div>

    <div class="row" data-controller="AgendaCtrl">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-body pl-lg-5 pr-lg-5">
                    <form data-validate-ajax method="POST" action="{{ route('app.agenda.store') }}" class="form-horizontal">
                        <div class="ml-auto mt-4 mb-4">
                            <div class="section-title mr-auto">
                                <h3>0{{ $steps++ }}. Selecione o serviço</h3>
                                <p class="mt-1">Informe o serviço para criar a agenda</p>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-xl-8 mb-1">
                                <label for="servico_id" class="form-control-label">Serviço</label>
                                <select id="servico_id" name="servico_id" class="form-control boostrap-select-custom" required
                                        data-required title="Selecione um serviço" data-placeholder="Procurar serviço">
                                    @foreach($servicos_sem_agenda as $servico)
                                        <option value="{{ $servico->id }}">{{ $servico->nome }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-4 mb-1">
                                <label for="canal_venda_id" class="form-control-label">Canal de venda</label>
                                <input id="canal_venda_id" type="text" class="form-control" placeholder="Canal de venda"
                                       readonly title="Canal de venda" value="{{ $canal_venda->site }}">
                            </div>
                        </div>
                        <div class="em-separator separator-dashed mb-4"></div>
                        <div class="ml-auto  mb-4">
                            <div class="section-title mr-auto">
                                <h3>0{{ $steps++ }}. Configuração da agenda</h3>
                                <p class="mt-1">Crie uma nova agenda ou utilize uma existente</p>
                            </div>
                        </div>
                        <div class="sliding-tabs">
                            <ul id="tabs_agenda" class="nav nav-tabs">
                                <li class="nav-item">
                                    <a href="#new_agenda" class="nav-link active text-primary not-save-state" data-toggle="tab">
                                        <i class="la la-pencil-square la-2x mr-2 align-middle text-primary"></i>
                                        Criar nova agenda
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#agenda_compartilhada" class="nav-link text-primary not-save-state" data-toggle="tab">
                                        <i class="la la-share-alt la-2x mr-2 align-middle text-primary"></i>
                                        Usar agenda compartilhada
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content pt-4">
                                <div class="tab-pane active" id="new_agenda">
                                    <p>Configure as opções da nova agenda</p>
                                    <div class="form-group row mb-2">
                                        <div class="col-xl-4 mb-3">
                                            <label for="disponibilidade_minima" class="form-control-label">Aviso de disponibilidade</label>
                                            <div class="input-group">
                                                <span class="input-group-addon addon-secondary left">
                                                    Mínimo de
                                                </span>
                                                <input id="disponibilidade_minima" type="tel" class="form-control text-center" placeholder="3 até 10" required
                                                       data-required data-min="1" title="Aviso de disponibilidade" name="disponibilidade_minima">
                                                <span class="input-group-addon addon-secondary right">lugares</span>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 mb-3">
                                            <div class="row pl-lg-4">
                                                <div class="col-xl-12">
                                                    <p class="text-dark">Compartilhar agenda?</p>
                                                </div>
                                                <div class="col-xl-4 mt-1">
                                                    <div class="styled-radio">
                                                        <input type="radio" required name="compartilhada" value="{{ $nao_compartilha }}"
                                                               id="nao_compartilha_{{ $nao_compartilha }}" title="Compartilhar agenda">
                                                        <label for="nao_compartilha_{{ $nao_compartilha }}">Não</label>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 mt-1">
                                                    <div class="styled-radio">
                                                        <input type="radio" required data-required name="compartilhada" value="{{ $compartilha_agenda }}"
                                                               id="compartilhar_{{ $compartilha_agenda }}" title="Compartilhar agenda">
                                                        <label for="compartilhar_{{ $compartilha_agenda }}">Sim</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Informativo sobre o aviso de disponibilidade --}}
                                    <div class="row mb-2">
                                        <div class="col-12">
                                            <div class="alert alert-outline-secondary">
                                                ** <strong>Sobre o aviso de disponibilidade</strong> <br>
                                                É o mínimo que lugares disponíveis por dia, caso possua mais de 5 datas
                                                com quantidade abaixo do mínimo será enviado um alerta!
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-0">
                                        <div class="col-12">
                                            <p class="text-dark">Dias da semana &nbsp;<small>(em que ocorre o serviço)</small></p>
                                        </div>
                                        @foreach($dias_semanas as $value => $dia_semana)
                                            <div class="col-md-3">
                                                <div class="styled-checkbox d-inline-block mb-3">
                                                    <input type="checkbox" name="dias_semana[]" value="{{ $value }}"
                                                           id="semana_{{ $dia_semana }}" title="{{ $dia_semana }}">
                                                    <label for="semana_{{ $dia_semana }}">{{ $dia_semana }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="tab-pane" id="agenda_compartilhada">
                                    <p>Você irá utilizar a mesma agenda para dois serviços</p>
                                    <div class="form-group row mb-2">
                                        <div class="col-xl-12 mb-3">
                                            <label for="agenda_id" class="form-control-label">Selecione uma agenda &nbsp;<small>(Pelo nome do serviço)</small></label>
                                            <select id="agenda_id" name="agenda_id" class="form-control boostrap-select-custom" disabled
                                                    title="Selecione uma agenda" data-placeholder="Procurar serviço">
                                                @foreach($agendas_compartilhadas as $agenda_compartilhada)
                                                    <option value="{{ $agenda_compartilhada->id }}">{{ $agenda_compartilhada->servicos->first()->nome }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    {{-- Informativo sobre agenda compartilhada --}}
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="alert alert-outline-secondary">
                                                ** <strong>Sobre agenda compartilhada</strong> <br>
                                                As agendas são compartilhadas entre os serviços,
                                                ao ligar dois serviços à mesma agenda os dois terão a mesma disponiblidade.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="em-separator separator-dashed mt-1"></div>
                        <div class="text-center">
                            <button class="btn btn-gradient-01" type="submit">Próximo passo <i class="la la-angle-right right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
