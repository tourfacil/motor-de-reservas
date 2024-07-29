@extends('template.header')

@section('title', 'Homelist')

@section('content')
    <style>

        tr {
            color: #5d5386;
        }

        .cancelado {
            background-color: #fc1919;
            color: black;
        }

        .cancelado:hover {
            background-color: #fc1919 !important;
            color: black !important;
        }

        .pendente {
            background-color: #ffd500;
            color: black;
        }

        .pendente:hover {
            background-color: #ffd500 !important;
            color: black !important;
        }

        .confirmado {
            background-color: #2bb34f;
            color: black;
        }

        .confirmado:hover {
            background-color: #2bb34f !important;
            color: black !important;
        }

        .integracao {
            background-color: rgb(169, 0, 236);
        }

        .integracao:hover {
            background-color: rgb(169, 0, 236) !important;
        }

        td {
            color: black;
        }

        a {
            color: black;
        }

    </style>

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark"><span class="text-gradient-01">Homelist</span> </h2>
                <div>{{ Breadcrumbs::render('app.relatorios.homelist.index') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header p-3 bordered" id="setup-calendar">
                    <div class="row d-flex align-items-center">
                        <div class="col-4">
                            <div class="form-group pl-2 m-0">
                                <label for="periodo_vendas_filtro" class="m-auto text-primary">Filtrar por período</label>
                                <form action="#" method="get" style="display:flex;">
                                    <div class="input-group" style="width: 70%; margin-right: 10px;">
                                        <span class="input-group-addon addon-secondary left"><i class="la la-calendar-check-o"></i></span>
                                        <input id="date_range" type="tel" class="form-control datepicker" placeholder="DD/MM/AAAA" required
                                            data-required title="Data inicial" autocomplete="off">
                                        <input type="hidden" name="inicio" id="input-data">
                                        <input type="hidden" name="final">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <input type="submit" class="btn">
                                </form>
                            </div>
                        </div>
                        <div class="col-3 pr-0">
                            
                        </div>

                    </div>
                </div>
                <div class="widget-body pr-2 pl-2">
                    <div class="table-responsive">
                        <table class="table table-hover table-border-bottom mb-0 data-table" data-page-length="1000">
                            <thead>
                                <tr>
                                    <th class="text-center">D.Venda</th>
{{--                                    <th class="text-center">Cod.Pedido</th>--}}
                                    <th class="text-center">D.Utilização</th>
                                    <th class="text-center">Cod.Reserva</th>
                                    <th class="text-center">Titular</th>
                                    <th class="text-center">Fornecedor</th>
                                    <th class="text-center">Serviço</th>
                                    <th class="text-center">Vendedor</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Obs</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reservas as $key => $reserva)
                                    @if($reserva->status == 'CANCELADO')
                                    <tr  class="text-center cancelado">
                                    @elseif($reserva->servico->integracao != 'NAO' && isReservaIntegrada($reserva) == false)
                                    <tr  class="text-center integracao">
                                    @elseif($reserva->conferenciaReserva == null)
                                    <tr  class="text-center">
                                    @elseif($reserva->conferenciaReserva->status_conferencia_reserva == \TourFacil\Core\Enum\StatusConferenciaReservaEnum::NAO_INFORMADO)
                                    <tr  class="text-center">
                                    @elseif($reserva->conferenciaReserva->status_conferencia_reserva == \TourFacil\Core\Enum\StatusConferenciaReservaEnum::PENDENTE)
                                    <tr  class="text-center pendente">
                                    @elseif($reserva->conferenciaReserva->status_conferencia_reserva == \TourFacil\Core\Enum\StatusConferenciaReservaEnum::CONFIRMADO)
                                    <tr  class="text-center confirmado">
                                    @endif

                                        <td class="text-center">{{ $reserva->created_at->format('d/m/Y') }}</td>
{{--                                        <td class="text-center">{{ $reserva->pedido->codigo }}</td>--}}
                                        <td class="text-center">{{ $reserva->agendaDataServico->data->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('app.reservas.view', $reserva->voucher) }}" target="_blank">#{{ $reserva->voucher }}</a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ getUrlWhatsapp($reserva->pedido->cliente->telefone) }}" target="_blank">{{ $reserva->pedido->cliente->nome }}</a>
                                        </td>
                                        <td class="text-center">{{ $reserva->servico->fornecedor->nome_fantasia }}</td>
                                        <td class="text-center">{{ $reserva->servico->nome }}</td>
                                        @if($reserva->afiliado == null)
                                            <td class="text-center">Tour Fácil</td>
                                        @else
                                            <td class="text-center">{{ $reserva->afiliado->nome_fantasia }}</td>
                                        @endif
                                        <td class="text-center">
                                            @if($reserva->conferenciaReserva == null)
                                                <select id="conferencia_status_{{ $reserva->id }}"  class="form-control" onchange="onChangeConferencia({{ $reserva->id }}, true)">
                                                    <option value="{{ \TourFacil\Core\Enum\StatusConferenciaReservaEnum::NAO_INFORMADO }}"></option>
                                                    <option value="{{ \TourFacil\Core\Enum\StatusConferenciaReservaEnum::PENDENTE }}">Pendente</option>
                                                    <option value="{{ \TourFacil\Core\Enum\StatusConferenciaReservaEnum::CONFIRMADO }}">Confirmado</option>
                                                </select>
                                            @else
                                                <select id="conferencia_status_{{ $reserva->id }}"  class="form-control" onchange="onChangeConferencia({{ $reserva->id }}, true)">
                                                    <option {{ ($reserva->conferenciaReserva->status_conferencia_reserva == \TourFacil\Core\Enum\StatusConferenciaReservaEnum::NAO_INFORMADO) ? "selected" : "" }} value="{{ \TourFacil\Core\Enum\StatusConferenciaReservaEnum::NAO_INFORMADO }}"></option>
                                                    <option {{ ($reserva->conferenciaReserva->status_conferencia_reserva == \TourFacil\Core\Enum\StatusConferenciaReservaEnum::PENDENTE) ? "selected" : "" }}  value="{{ \TourFacil\Core\Enum\StatusConferenciaReservaEnum::PENDENTE }}">Pendente</option>
                                                    <option {{ ($reserva->conferenciaReserva->status_conferencia_reserva == \TourFacil\Core\Enum\StatusConferenciaReservaEnum::CONFIRMADO) ? "selected" : "" }}  value="{{ \TourFacil\Core\Enum\StatusConferenciaReservaEnum::CONFIRMADO }}">Confirmado</option>
                                                </select>
                                            @endif
                                        </td>
                                        <td class="text-center" style="width: 50%;">
                                            @if($reserva->conferenciaReserva == null)
                                                <input id="conferencia_obs_{{ $reserva->id }}" class="form-control" onchange="onChangeConferencia({{ $reserva->id }})">
                                            @else
                                                <input id="conferencia_obs_{{ $reserva->id }}" class="form-control" onchange="onChangeConferencia({{ $reserva->id }})" value="{{ $reserva->conferenciaReserva->observacao }}">
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        window.onload = () => {
            let container_calendar = $('#setup-calendar');
            window.App.setupCalendarioFiltroPeriodo(container_calendar);

            filtrarData();
        }

        let onChangeConferencia = (reserva_id) => {

            let observacao = $(`#conferencia_obs_${reserva_id}`).val();
            let status = $(`#conferencia_status_${reserva_id}`).val();

            let req = {
                reserva_pedido_id: reserva_id,
                observacao: observacao,
                status_conferencia_reserva: status,
            };

            let response = axios.post("{{ Route('app.relatorios.conferencia-reserva.atualizar') }}", req).then((response) => {
                window.location.reload();
            });
        }

    </script>
@endsection
