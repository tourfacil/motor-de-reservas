
@extends('template.header')

@section('title', 'Homelist')

@section('content')

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
                        <div class="col">
                            <div class="form-group pl-2 m-0">
                                <div class="col-4 pr-0">
                                    <label for="periodo_vendas_filtro" class="m-auto text-primary">Filtrar por período de utilização</label>
                                    <form action="#" method="get" style="display: flex;">
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left"><i class="la la-calendar-check-o"></i></span>
                                            <input id="date_range" type="tel" class="form-control datepicker date_range" placeholder="DD/MM/AAAA" required
                                                   data-required title="Data inicial" autocomplete="off">
                                            <input type="hidden" name="inicio" id="input-data">
                                            <input type="hidden" name="final">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <input value="Filtrar" class="btn" type="submit" style="margin-left: 10px; overflow: unset;">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="widget-body pr-2 pl-2">
                    <div class="table-responsive">
                        <table class="table table-hover table-border-bottom mb-0 data-table" data-page-length="1000">
                            <thead>
                            <tr>
                                <th class="text-center">Cod.Reserva</th>
                                <th class="text-center">D.Utilização</th>
                                <th class="text-center">Serviço</th>
                                <th class="text-center">Titular</th>
                                <th class="text-center">Telefone</th>
                            </tr>
                            </thead>
                            <tbody sty>
                            @foreach($reservas as $key => $reserva)
                            <tr style="color: #5d5386;">
                                <td class="text-center">
                                    <a href="{{ route('app.reservas.view', $reserva->voucher) }}" target="_blank">#{{ $reserva->voucher }}</a>
                                </td>
                                <td class="text-center">{{ $reserva->agendaDataServico->data->format('d/m/Y') }}</td>
                                <td class="text-center">{{ $reserva->servico->nome }}</td>
                                <td class="text-center">{{$reserva->pedido->cliente->nome}}</td>
                                <td class="text-center">{{$reserva->pedido->cliente->telefone}}</td>
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
        }

        let onChangeConferencia = (reserva_id) => {

            let observacao = $(`#conferencia_obs_${reserva_id}`).val();
            let status = $(`#conferencia_status_${reserva_id}`).val();

            let req = {
                reserva_pedido_id: reserva_id,
                observacao: observacao,
                status_conferencia_reserva: status,
            };

            {{--let response = axios.post("{{ Route('app.relatorios.conferencia-reserva.atualizar') }}", req).then((response) => {--}}
            {{--    window.location.reload();--}}
            {{--});--}}
        }

    </script>
@endsection
