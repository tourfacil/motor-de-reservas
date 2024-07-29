@extends('template.header')

@section('title', "Detalhes agenda")

@section('content')

    @php($steps = 1)
    @php($count_subs = 1)
    @php($share_disabled = ($agenda->servicos->count() > 1) ? "disabled" : "")

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">
                    Detalhes agenda <span class="text-gradient-01">| {{ $agenda->servicos->count() }} serviço(s) cadastrado(s)</span>
                </h2>
                <div>{{ Breadcrumbs::render('app.agenda.view') }}</div>
            </div>
        </div>
    </div>

    <div class="row" data-controller="AgendaCtrl" data-calendar="{{ route('app.agenda.datas.calendario', $agenda->id) }}">
        <div class="col-12">
            <ul id="tab_agenda" class="nav nav-tabs nav-tab-header nav-tab-no-border tabs-mobile">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#calendario">
                        <i class="la la-calendar-check-o la-2x align-middle mr-2"></i> Datas calendário
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#configuracoes">
                        <i class="la la-gear la-2x align-middle mr-2"></i> Configurações
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#alteracao-valores">
                        <i class="la la-random la-2x align-middle mr-2"></i> Substituição de valores
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#servicos">
                        <i class="la la-cube la-2x align-middle mr-2"></i> Serviços da agenda - {{ $agenda->servicos->count() }}
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="calendario">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <div class="ml-auto mt-4">
                                <div class="row mb-0">
                                    <div class="col-12 col-md-6">
                                        <div class="section-title mr-auto">
                                            <h3>0{{ $steps++ }}. Datas calendário</h3>
                                            <p class="mt-1">Datas para utilização do serviço.</p>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="button-action text-right tabs-mobile">
                                            <button class="btn btn-secondary mr-3" data-target="#new-dates" data-toggle="modal">Cadastrar <i class="la la-plus right"></i></button>
                                            <button class="btn btn-secondary mr-3" data-target="#edit-dates" data-toggle="modal">Alterar <i class="la la-edit right"></i></button>
                                            <button class="btn btn-secondary" data-target="#remove-dates" data-toggle="modal">Remover <i class="la la-trash right"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="em-separator separator-dashed mb-4 mt-2"></div>
                            <div class="loader-calendar text-center">
                                <div class="vertical-loader">
                                    <div class="spinner"></div>
                                    <br>
                                    <h4 class="mt-4">Carregando datas</h4>
                                </div>
                            </div>
                            <div id="service-calendar"></div>
                            <h4 class="mt-4">Legenda das cores (disponibilidade)</h4>
                            <p class="mt-3 mb-3">
                                <span class="legend-calendar green"></span> Normal - Maior que {{ $dispo_normal }}
                                <span class="legend-calendar orange"></span> Média - Até {{ $dispo_media }}
                                <span class="legend-calendar red"></span> Baixa - Abaixo de {{ $dispo_baixa }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="configuracoes">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <form data-validate-ajax method="POST" action="{{ route('app.agenda.update') }}" class="form-horizontal">
                                <div class="ml-auto mt-4 mb-4">
                                    <div class="section-title mr-auto">
                                        <h3>0{{ $steps++ }}. Configurações da agenda</h3>
                                        <p class="mt-1">Quantidade mínima e compartilhamento.</p>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <div class="col-xl-4 mb-3">
                                        <label for="disponibilidade_minima" class="form-control-label">Aviso de disponibilidade</label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left">
                                                Mínimo de
                                            </span>
                                            <input id="disponibilidade_minima" type="tel" class="form-control text-center" placeholder="3 até 10" required
                                                   data-required data-min="1" title="Aviso de disponibilidade" name="disponibilidade_minima" value="{{ $agenda->disponibilidade_minima }}">
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
                                                    <input type="radio" required name="compartilhada" value="{{ $nao_compartilha }}"  title="Compartilhar agenda" {{ $share_disabled }}
                                                           {{ ($agenda->compartilhada == $nao_compartilha) ? "checked" : "" }} id="nao_compartilha_{{ $nao_compartilha }}">
                                                    <label for="nao_compartilha_{{ $nao_compartilha }}">Não</label>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 mt-1">
                                                <div class="styled-radio">
                                                    <input type="radio" required data-required name="compartilhada" value="{{ $compartilha_agenda }}" title="Compartilhar agenda"
                                                           {{ ($agenda->compartilhada == $compartilha_agenda) ? "checked" : "" }} id="compartilhar_{{ $compartilha_agenda }}" {{ $share_disabled }}>
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
                                                <input type="checkbox" name="dias_semana[]" value="{{ $value }}" title="{{ $dia_semana }}"
                                                       id="semana_{{ $dia_semana }}"  {{ (in_array($value, $agenda->dias_semana)) ? "checked" : "" }}>
                                                <label for="semana_{{ $dia_semana }}">{{ $dia_semana }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="hide">
                                    {{ method_field("PUT") }}
                                    <input type="hidden" name="agenda_id" value="{{ $agenda->id }}">
                                </div>
                                <div class="em-separator separator-dashed mt-1"></div>
                                <div class="text-center">
                                    <button class="btn btn-gradient-01" type="submit">Salvar alterações <i class="la la-refresh right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="alteracao-valores">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <div class="ml-auto mt-4 mb-4">
                                <div class="section-title mr-auto">
                                    <h3>0{{ $steps++ }}. Substituição de valores</h3>
                                    <p class="mt-1">Valores a serem substituidos na agenda.</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-divider mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tipo de substituição</th>
                                        <th>Original</th>
                                        <th>Modificado</th>
                                        <th class="text-center">Administração</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(sizeof($substituicoes_agenda) > 0)
                                        @foreach($substituicoes_agenda as $tipo => $list)
                                            @foreach($list as $from => $to)
                                                <tr>
                                                    <td>{{ $count_subs++ }}</td>
                                                    <td class="text-primary">{{ $e_substituicao_agenda[$tipo] }}</td>
                                                    <td class="text-warning font-weight-bold">R$ {{ formataValor($from) }}</td>
                                                    <td class="text-success font-weight-bold">R$ {{ formataValor($to) }}</td>
                                                    <td class="text-center">
                                                        <a href="{{ route('app.agenda.substituicao.view', [$agenda->id, 'tipo' => $tipo, 'index' => $from]) }}" data-action="edit-substituicao"
                                                           class="btn btn-outline-primary">Alterar <i class="la la-edit right"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center pt-4 pb-4">Nenhuma substituição cadastrada</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-4">
                                <button class="btn btn-gradient-01" data-target="#new-alteracao-agenda"
                                        data-toggle="modal">Nova substituição <i class="la la-plus right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="servicos">
                    <div class="widget has-shadow">
                        <div class="widget-body pl-lg-5 pr-lg-5">
                            <div class="ml-auto mt-4 mb-4">
                                <div class="section-title mr-auto">
                                    <h3>0{{ $steps++ }}. Serviços da agenda</h3>
                                    <p class="mt-1">Listagem dos serviços que utilizam essa agenda.</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-divider mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Serviço</th>
                                        <th>Destino</th>
                                        <th>Canal venda</th>
                                        <th>Compartilhado</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($agenda->servicos as $servico)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><a href="{{ route('app.servicos.view', $servico->id) }}" target="_blank">{{ $servico->nome }}</a></td>
                                            <td>{{ $servico->destino->nome }}</td>
                                            <td>{{ $servico->canalVenda->site }}</td>
                                            <td>{{ $servico->pivot->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 alert alert-secondary">
                                ** <strong>Sobre os serviços da agenda</strong> <br>
                                Ao alterar as datas na agenda irá alterar as agendas de todos os serviços acima. <br>
                                Não é possível remover um serviço da agenda após a confirmação de uma reserva!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para ver os detalhes das datas --}}
    <div id="modal-view-event" class="modal modal-top fade calendar-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title event-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax action="{{ route('app.agenda.datas.update-one') }}" class="form-horizontal" autocomplete="off">
                        <div class="form-group mb-0 pr-2 pl-2">
                            <div class="row">
                                <div class="col-xl-6 mb-3">
                                    <label for="view_date_net" class="form-control-label">Tarifa net</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left">R$</span>
                                        <input id="view_date_net" type="tel" class="form-control vanillaMaskMoney" placeholder="R$ 0,00" required
                                               data-required data-min="3" title="Tarifa net" name="valor_net">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-xl-6 mb-4">
                                    <label for="view_date_qtd" class="form-control-label">Quantidade disponível</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left">Total</span>
                                        <input id="view_date_qtd" type="tel" class="form-control" placeholder="Quantidade de lugares" required
                                               data-required data-min="1" title="Quantidade disponível" name="quantidade">

                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-outline-secondary mb-0">
                            Para remover a data coloque a disponibilidade para 0 (zero)
                        </div>
                        <div class="list-valores-servicos"></div>
                        <div class="hide">
                            {{ method_field("PUT") }}
                            <input type="hidden" name="data_id">
                        </div>
                        <div class="mt-4 mb-2 text-center">
                            <button class="btn btn-success">Atualizar data <i class="la la-angle-right right ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para cadastrar novas datas --}}
    <div id="new-dates" class="modal modal-top fade calendar-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title event-title">Cadastrar novas datas</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax method="POST" action="{{ route('app.agenda.datas.store') }}" class="form-horizontal" autocomplete="off">
                        <div class="form-group mb-0 pr-2 pl-2">
                            <div class="row">
                                <div class="col-xl-6 mb-3">
                                    <label for="new_date_start" class="form-control-label">Data inicial</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-calendar-check-o"></i></span>
                                        <input id="new_date_start" type="tel" class="form-control datepicker" data-range="#new_date_end" placeholder="DD/MM/AAAA" required
                                               data-required data-min="10" title="Data inicial" name="date_start">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label for="new_date_end" class="form-control-label">Data final</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-calendar-o"></i></span>
                                        <input id="new_date_end" type="tel" class="form-control" placeholder="DD/MM/AAAA" required
                                               data-required data-min="10" title="Data final" name="date_end">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 mb-3">
                                    <label for="new_date_net" class="form-control-label">Tarifa net</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left">R$</span>
                                        <input id="new_date_net" type="tel" class="form-control vanillaMaskMoney" placeholder="R$ 0,00" required
                                               data-required data-min="3" title="Tarifa net" name="valor_net">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-xl-6 mb-4">
                                    <label for="new_date_qtd" class="form-control-label">Quantidade disponível</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left">Máx.</span>
                                        <input id="new_date_qtd" type="tel" class="form-control" placeholder="Limite de vendas" required
                                               data-required data-min="1" title="Quantidade disponível" name="quantidade">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-xl-12 mb-3">
                                    <label for="new_date_semanas" class="form-control-label">Dias da semana &nbsp;<small>(Em que poderá ocorrer vendas)</small></label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-unlock-alt"></i></span>
                                        <select id="new_date_semanas" name="dias_semana[]" class="form-control boostrap-select-custom" required title="Selecione os dias da semana" multiple>
                                            @foreach($dias_semanas as $value => $dia_semana)
                                                <option value="{{ $value }}" {{ (in_array($value, $agenda->dias_semana)) ? "selected" : "" }}>{{ $dia_semana }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hide">
                            <input type="hidden" name="agenda_id" value="{{ $agenda->id }}">
                        </div>
                        <div class="em-separator separator-dashed mt-2"></div>
                        <div class="mt-2 mb-2 text-center">
                            <button class="btn btn-success">Cadastrar datas <i class="la la-angle-right right ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para editar datas --}}
    <div id="edit-dates" class="modal modal-top fade calendar-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title event-title">Editar datas agenda</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax method="POST" action="{{ route('app.agenda.datas.update') }}" class="form-horizontal" autocomplete="off">
                        <div class="form-group mb-0 pr-2 pl-2">
                            <div class="row">
                                <div class="col-xl-6 mb-3">
                                    <label for="edit_date_start" class="form-control-label">Data inicial</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-calendar-check-o"></i></span>
                                        <input id="edit_date_start" type="tel" class="form-control datepicker" data-range="#edit_date_end" placeholder="DD/MM/AAAA" required
                                               data-required data-min="10" title="Data inicial" name="date_start">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label for="edit_date_end" class="form-control-label">Data final</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-calendar-o"></i></span>
                                        <input id="edit_date_end" type="tel" class="form-control" placeholder="DD/MM/AAAA" required
                                               data-required data-min="10" title="Data final" name="date_end">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12 mb-3">
                                    <label for="select_edit_option" class="form-control-label">O que você deseja alterar?</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-edit"></i></span>
                                        <select id="select_edit_option" name="edit_option" class="form-control boostrap-select-custom" required title="Selecione uma opção">
                                            <option value="EDIT_DISPONIBILIDADE">Alterar disponibilidade</option>
                                            <option value="EDIT_VALOR_NET">Alterar tarifa NET</option>
                                        </select>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row fields-edit">
                                <div class="col-xl-12 field mb-4 hide" data-option="EDIT_VALOR_NET">
                                    <label for="edit_date_net" class="form-control-label">Nova tarifa net de custo</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left">R$</span>
                                        <input id="edit_date_net" type="tel" class="form-control vanillaMaskMoney" placeholder="R$ 0,00"
                                               data-min="3" title="Nova tarifa net de custo" name="valor_net" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-xl-12 field mb-4 hide" data-option="EDIT_DISPONIBILIDADE">
                                    <label for="edit_date_qtd" class="form-control-label">Quantidade disponível &nbsp;<small>(Limite de vendas)</small></label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left">Total de </span>
                                        <input id="edit_date_qtd" type="tel" class="form-control text-center" placeholder="Quantidade de lugares" required
                                               data-required data-min="1" title="Quantidade disponível" name="quantidade">
                                        <span class="input-group-addon addon-secondary right">lugares por dia</span>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-xl-12 mb-3">
                                    <label for="edit_date_semanas" class="form-control-label">Dias da semana &nbsp;<small>(Que deseja alterar)</small></label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-unlock-alt"></i></span>
                                        <select id="edit_date_semanas" name="dias_semana[]" class="form-control boostrap-select-custom" required title="Selecione os dias da semana" multiple>
                                            @foreach($dias_semanas as $value => $dia_semana)
                                                <option value="{{ $value }}" {{ (in_array($value, $agenda->dias_semana)) ? "selected" : "" }}>{{ $dia_semana }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hide">
                            <input type="hidden" name="agenda_id" value="{{ $agenda->id }}">
                            {{ method_field("PUT") }}
                        </div>
                        <div class="em-separator separator-dashed mt-2"></div>
                        <div class="mt-2 mb-2 text-center">
                            <button class="btn btn-success">Atualizar datas <i class="la la-angle-right right ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para remover datas --}}
    <div id="remove-dates" class="modal modal-top fade calendar-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title event-title">Remover datas agenda</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax method="POST" action="{{ route('app.agenda.datas.remove') }}" class="form-horizontal" autocomplete="off">
                        <div class="form-group mb-0 pr-2 pl-2">
                            <div class="row">
                                <div class="col-xl-6 mb-3">
                                    <label for="remove_date_start" class="form-control-label">Data inicial</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-calendar-check-o"></i></span>
                                        <input id="remove_date_start" type="tel" class="form-control datepicker" data-range="#remove_date_end" placeholder="DD/MM/AAAA" required
                                               data-required data-min="10" title="Data inicial" name="date_start">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label for="remove_date_end" class="form-control-label">Data final</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-calendar-o"></i></span>
                                        <input id="remove_date_end" type="tel" class="form-control" placeholder="DD/MM/AAAA" required
                                               data-required data-min="10" title="Data final" name="date_end">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-xl-12 mb-4">
                                    <label for="remove_date_semanas" class="form-control-label">Dias da semana &nbsp;<small>(Que deseja remover)</small></label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-unlock-alt"></i></span>
                                        <select id="remove_date_semanas" name="dias_semana[]" class="form-control boostrap-select-custom" required title="Selecione os dias da semana" multiple>
                                            @foreach($dias_semanas as $value => $dia_semana)
                                                <option value="{{ $value }}" {{ (in_array($value, $agenda->dias_semana)) ? "selected" : "" }}>{{ $dia_semana }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hide">
                            <input type="hidden" name="agenda_id" value="{{ $agenda->id }}">
                            {{ method_field("DELETE") }}
                        </div>
                        <div class="alert alert-outline-secondary">
                            <i class="la la-ban mr-2"></i>
                            A disponibilidade será alterada para 0 (zero).
                        </div>
                        <div class="em-separator separator-dashed mt-2"></div>
                        <div class="mt-2 mb-2 text-center">
                            <button class="btn btn-danger">Remover datas <i class="la la-trash right ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal criar uma substituicao de valor --}}
    <div id="new-alteracao-agenda" class="modal modal-top fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nova substituição agenda</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax method="POST" action="{{ route('app.agenda.substituicao.store') }}" class="form-horizontal">
                        <div class="form-group row mb-0">
                            <div class="col-xl-12 mb-3">
                                <label for="new_alteracao_agenda" class="form-control-label">Tipo de substituição &nbsp;<small>(Onde o valor será alterado)</small></label>
                                <select id="new_alteracao_agenda" name="tipo_alteracao" class="form-control boostrap-select-custom" required
                                        data-required title="Selecione uma opção">
                                    @foreach($e_substituicao_agenda as $key => $substituicao)
                                        <option value="{{ $key }}">{{ $substituicao }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="new_alteracao_from" class="form-control-label">Valor a ser substituído &nbsp; <small>(Original)</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left">R$</span>
                                    <input id="new_alteracao_from" type="tel" class="form-control vanillaMaskMoney" placeholder="0,00" required
                                           data-required data-min="4" title="Valor a ser substituído" name="from">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="new_alteracao_to" class="form-control-label">Novo valor substituto &nbsp; <small>(Modificado)</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left">R$</span>
                                    <input id="new_alteracao_to" type="tel" class="form-control vanillaMaskMoney" placeholder="0,00" required
                                            data-required data-min="4" title="Novo valor substituto" name="to">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="hide">
                            <input type="hidden" name="agenda_id" value="{{ $agenda->id }}">
                        </div>
                        <div class="em-separator separator-dashed mt-2"></div>
                        <div class="mt-2 mb-2 text-center">
                            <button class="btn btn-success">Salvar substituição <i class="la la-save right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal editar uma substituicao de valor --}}
    <div id="edit-alteracao-agenda" class="modal modal-top fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar substituição agenda</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax method="POST" action="{{ route('app.agenda.substituicao.update') }}" class="form-horizontal">
                        <div class="form-group row mb-0">
                            <div class="col-xl-12 mb-3">
                                <label for="edit_alteracao_agenda" class="form-control-label">Tipo de substituição &nbsp;<small>(Onde o valor será alterado)</small></label>
                                <input id="edit_alteracao_agenda" type="text" class="form-control" readonly disabled>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="edit_alteracao_from" class="form-control-label">Valor a ser substituído &nbsp; <small>(Original)</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left">R$</span>
                                    <input id="edit_alteracao_from" type="tel" class="form-control vanillaMaskMoney" placeholder="0,00" required
                                           data-required data-min="4" title="Valor a ser substituído" name="from">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="edit_alteracao_to" class="form-control-label">Novo valor substituto &nbsp; <small>(Modificado)</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left">R$</span>
                                    <input id="edit_alteracao_to" type="tel" class="form-control vanillaMaskMoney" placeholder="0,00" required
                                           data-required data-min="4" title="Novo valor substituto" name="to">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="hide">
                            {{ method_field("PUT") }}
                            <input type="hidden" name="delete_substituicao" value="off">
                            <input type="hidden" name="agenda_id" value="{{ $agenda->id }}">
                            <input type="hidden" name="tipo_alteracao">
                            <input type="hidden" name="index">
                        </div>
                        <div class="em-separator separator-dashed mt-2"></div>
                        <div class="mt-2 mb-2">
                            <button type="button" data-action="delete_substituicao" class="btn btn-danger left">Excluir <i class="la la-trash right"></i></button>
                            <button class="btn btn-success pull-right">Atualizar <i class="la la-refresh right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
