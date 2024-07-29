@extends('template.header')

@section('title', 'NOVO - Ingressos vendidos')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Relatório de <span class="text-gradient-01">ingressos vendidos</span></h2>
                <div>{{ Breadcrumbs::render('app.relatorios.vendidos.index') }}</div>
            </div>
        </div>
    </div>

    <div class="row flex-row">
        <div class="col-xl-3 col-md-6">
            <div class="widget has-shadow">
                <div class="widget-body">
                    <div class="row align-items-center">
                        <div class="col-xl-2 d-flex">
                            <i class="la la-calendar la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center" style="justify-content: space-between;">
                            @if($data_inicial_venda && $data_final_venda)
                                <div class="content-widget">
                                    <p class="m-0 text-primary">Período de venda</p>
                                    <strong class="text-secondary">{{ $data_inicial_venda->format('d/m') }} até {{ $data_final_venda->format('d/m') }}</strong>
                                </div>
                            @endif
                            @if($data_inicial_utilizacao && $data_final_utilizacao)
                                <div class="content-widget">
                                    <p class="m-0 text-primary">Período de utilização</p>
                                    <strong class="text-secondary">{{ $data_inicial_utilizacao->format('d/m') }} até {{ $data_final_utilizacao->format('d/m') }}</strong>
                                </div>
                            @endif
                            @if(!$data_inicial_venda && !$data_final_venda && !$data_inicial_utilizacao && !$data_final_utilizacao)
                                <div class="content-widget">
                                    <p class="m-0 text-primary">Período</p>
                                    <strong class="text-danger">Sem limite</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="widget has-shadow">
                <div class="widget-body">
                    <div class="row align-items-center">
                        <div class="col-xl-2 d-flex">
                            <i class="la la-cart-plus la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary">R$ Total reservas</p>
                                <strong class="text-success">R$ {{ formataValor($valor_total_venda) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="widget has-shadow">
                <div class="widget-body">
                    <div class="row align-items-center">
                        <div class="col-xl-2 d-flex">
                            <i class="la la-line-chart la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary">R$ Tarifa NET</p>
                                <strong class="text-warning">R$ {{ formataValor($valor_total_net) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="widget has-shadow">
                <div class="widget-body">
                    <div class="row align-items-center">
                        <div class="col-xl-2 d-flex">
                            <i class="la la-tags la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary">Ingressos vendidos</p>
                                <strong class="text-info">{{ $quantidade_total }} ingresso(s) em {{ $quantidade_reservas }} reserva(s)</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header p-3 bordered">
                    <div class="row d-flex align-items-center">
                        <div class="col-8">
                            <div class="ml-2">
                                <div class="row">
                                    <div class="col-4">
                                        <label for="fornecedor_filtro_relatorio_a" class="form-control-label">Fornecedor</label>
                                        <div class="input-group">
                                            <select id="fornecedor_filtro_relatorio_a" name="fornecedor" class="form-control boostrap-select-custom">
                                                <option value="ALL">Todos</option>
                                                @foreach($fornecedores as $fornecedor)
                                                    <option {{ $fornecedor_id == $fornecedor->id ? 'selected' : '' }} value="{{ $fornecedor->id }}">{{ $fornecedor->nome_fantasia }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="servico_filtro_relatorio_a" class="form-control-label">Serviço</label>
                                        <div class="input-group">
                                            <select id="servico_filtro_relatorio_a" name="servico" class="form-control boostrap-select-custom" {{ $servicos->count() == 0 ? 'disabled' : '' }}>
                                                <option value="ALL">Todos</option>
                                                @foreach($servicos as $servico)
                                                    <option {{ $servico_id == $servico->id ? 'selected' : '' }} value="{{ $servico->id }}">{{ $servico->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="status" class="form-control-label">Status</label>
                                        <div class="input-group">
                                            <select id="status" name="status" class="form-control boostrap-select-custom" multiple title="Todos">
                                                @foreach ($status_reservas as $key => $status_reserva)
                                                    <option {{ str_contains($status, $key ) ? 'selected' : '' }} value="{{ $key }}">{{ $status_reserva }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-4">
                                        <label for="data_venda" class="form-control-label">Período de venda</label>
                                        <div class="input-group">
                                            <input id="data_venda" type="tel" class="form-control datepicker" placeholder="DD/MM/AAAA"
                                                   data-required title="Data inicial"  value="{{ $data_inicial_venda ? $data_inicial_venda->format('d/m/Y') . ' - ' . $data_final_venda->format('d/m/Y') : '' }}">
                                        </div>
                                        <input type="hidden" name="data_inicial_venda" id="data_inicial_venda">
                                        <input type="hidden" name="data_final_venda" id="data_final_venda">
                                    </div>
                                    <div class="col-4">
                                        <label for="data_utilizacao" class="form-control-label">Período de utilização</label>
                                        <div class="input-group">
                                            <input id="data_utilizacao" type="tel" class="form-control datepicker" placeholder="DD/MM/AAAA"
                                                   data-required title="Data inicial" value="{{ $data_inicial_utilizacao ? $data_inicial_utilizacao->format('d/m/Y') . ' - ' . $data_final_utilizacao->format('d/m/Y') : '' }}">
                                            <input type="hidden" name="data_inicial_utilizacao" id="data_inicial_utilizacao">
                                            <input type="hidden" name="data_final_utilizacao" id="data_final_utilizacao">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="data_utilizacao" class="form-control-label">Limprar filtros</label>
                                        <div class="input-group">
                                            <button class="form-control" style="width:50%;" onclick="limparURL()">Limpar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 text-right">
                            <div class="form-group pl-2 m-0">
                                <a href="{{ (request()->all() == []) ? url()->full() . '?tipo_relatorio=PDF' : url()->full() . '&tipo_relatorio=PDF' }}"
                                   target="_blank" class="btn btn-secondary mr-2 line-height-inherit">Baixar PDF <i class="la la-download right"></i></a>
                                <a href="{{ (request()->all() == []) ? url()->full() . '?tipo_relatorio=XLS' : url()->full() . '&tipo_relatorio=XLS' }}"
                                   target="_blank" class="btn btn-secondary line-height-inherit">Baixar XLS <i class="la la-download right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="widget-body pr-2 pl-2">
                    <div class="table-responsive">
                        <table class="table table-hover table-border-bottom mb-0 data-table" data-page-length="1000">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Reserva</th>
                                    <th>Serviço utilizado</th>
                                    <th>Status</th>
                                    <th class="text-center">Agendado</th>
                                    <th class="text-center">R$ Valor</th>
                                    <th class="text-center">R$ NET</th>
                                    <th class="text-center">Data da venda</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservas as $reserva)
                                    <tr>
                                        <td width="10" class="text-center pt-3 pb-3">{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('app.reservas.view', $reserva->voucher) }}" target="_blank" class="text-secondary">#{{ $reserva->voucher }}</a>
                                        </td>
                                        <td class="text-primary">
                                            <div class="d-inline text-truncate m-0 is-95">
                                                <strong>({{ $reserva->quantidade }}x)</strong>
                                                <span class="text-truncate">{{ $reserva->servico->nome }}</span>
                                            </div>
                                        </td>
                                        <td class="text-{{ $reserva->cor_status }} font-weight-bold">{{ $reserva->status_reserva }}</td>
                                        <td class="text-primary text-center">{{ $reserva->agendaDataServico->data->format('d/m/Y') }}</td>
                                        <td class="text-primary text-center font-weight-bold">R$ {{ formataValor($reserva->valor_total) }}</td>
                                        <td class="text-warning text-center font-weight-bold">R$ {{ formataValor($reserva->valor_net) }}</td>
                                        <td class="text-primary text-center">{{ $reserva->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center pt-3 pb-3">Sem dados para o período</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        window.onload = () => {
            onChangeFornecedor();
            onChangeServico();
            setCalendarioVenda();
            setCalendarioUtilizacao();
            onCloseDataVenda();
            onCloseDataUtilizacao();
            onChangeStatus();
            onChangeCampoDataVenda();
            onChangeCampoDataUtilizacao();
        }

        let fornecedor_id = "{!! $fornecedor_id !!}";
        let servico_id = "{!! $servico_id !!}";
        let status = "{!! $status !!}";

        @if($sem_data_venda)
            let sem_data_venda = false;
        @else
            let sem_data_venda = true;
        @endif
        

        @if($data_inicial_venda && $data_final_venda)
            let data_inicial_venda = "{!! $data_inicial_venda->format('Y-m-d') !!}"
            let data_final_venda = "{!! $data_final_venda->format('Y-m-d') !!}"
        @else
            let data_inicial_venda = ''
            let data_final_venda = ''
        @endif


        @if($data_inicial_utilizacao && $data_final_utilizacao)
            let data_inicial_utilizacao = "{!! $data_inicial_utilizacao->format('Y-m-d') !!}"
            let data_final_utilizacao = "{!! $data_final_utilizacao->format('Y-m-d') !!}"
        @else
            let data_inicial_utilizacao = ''
            let data_final_utilizacao = ''
        @endif

        let url = "{!! Route('app.relatorios.vendidos.index2') !!}"

        let onChangeFornecedor = () => {

            let fornecedor = $("#fornecedor_filtro_relatorio_a");

            fornecedor.on('change', (event) => {

                fornecedor_id = fornecedor.val();
                window.location.replace(window.location.origin + window.location.pathname + '?fornecedor_id=' + fornecedor_id);
                
            }); 
        }

        let onChangeServico = () => {

            let servico = $("#servico_filtro_relatorio_a");

            servico.on('change', (event) => {

                servico_id = servico.val();
                window.location.replace(getURL());

            })
        }

        let setCalendarioVenda = () => {

            Plugins.loadLibDateRanger(() => {
                // Variavel para nao dar duplo bind nos inputs
                window.App._loadedDateRangePicker = true;
                let minDate = new Date(), maxDate = new Date(), startDate = new Date();
                minDate.setFullYear(minDate.getFullYear() - 1);
                maxDate.setFullYear(maxDate.getFullYear() + 1);
                startDate.setMonth(startDate.getMonth() - 3);
                // Configurações padrões
                let config = {
                    autoApply: true,
                    minDate: minDate,
                    maxDate: maxDate,
                    autoUpdateInput: false,
                    locale: {
                        format: 'DD/MM/YYYY',
                        daysOfWeek: ["Do", "2º", "3º", "4º", "5º", "6º", "Sá"],
                        monthNames: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                    }, 
                    
                };
                // Calendario de pesquisa
                let $calendario = $(document).find("input#data_venda");
                let $data_inicio = $(document).find("input[name='data_inicial_venda']");
                let $data_final = $(document).find("input[name='data_final_venda']");
                // Configura o datepicker
                $calendario.daterangepicker(config);
                $calendario.on('apply.daterangepicker', (ev, picker) => {
                    let inicio = picker.startDate.format(config.locale.format);
                    let final = picker.endDate.format(config.locale.format);
                    // Coloca a data no input
                    $(ev.currentTarget).val(inicio + " - " + final);
                    $data_inicio.val(picker.startDate.format("DD-MM-YYYY"));
                    $data_final.val(picker.endDate.format("DD-MM-YYYY"));
                });
            });
        }

        let setCalendarioUtilizacao = () => {

            Plugins.loadLibDateRanger(() => {
                // Variavel para nao dar duplo bind nos inputs
                window.App._loadedDateRangePicker = true;
                let minDate = new Date(), maxDate = new Date(), startDate = new Date();
                minDate.setFullYear(minDate.getFullYear() - 1);
                maxDate.setFullYear(maxDate.getFullYear() + 1);
                startDate.setMonth(startDate.getMonth() - 3);
                // Configurações padrões
                let config = {
                    autoApply: true,
                    minDate: minDate,
                    maxDate: maxDate,
                    autoUpdateInput: false,
                    locale: {
                        format: 'DD/MM/YYYY',
                        daysOfWeek: ["Do", "2º", "3º", "4º", "5º", "6º", "Sá"],
                        monthNames: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                    }
                };
                // Calendario de pesquisa
                let $calendario = $(document).find("input#data_utilizacao");
                let $data_inicio = $(document).find("input[name='data_inicial_utilizacao']");
                let $data_final = $(document).find("input[name='data_final_utilizacao']");
                // Configura o datepicker
                $calendario.daterangepicker(config);
                $calendario.on('apply.daterangepicker', (ev, picker) => {
                    let inicio = picker.startDate.format(config.locale.format);
                    let final = picker.endDate.format(config.locale.format);
                    // Coloca a data no input
                    $(ev.currentTarget).val(inicio + " - " + final);
                    $data_inicio.val(picker.startDate.format("DD-MM-YYYY"));
                    $data_final.val(picker.endDate.format("DD-MM-YYYY"));
                });
            });
        }

        let onCloseDataVenda =  () => {
            $("input#data_venda").on('apply.daterangepicker', (event1, event2) => {

                let date_inicio = event2.startDate._d;
                let date_final = event2.endDate._d;

                data_inicial_venda = `${date_inicio.getFullYear()}-${date_inicio.getMonth() + 1}-${date_inicio.getDate()}`;
                data_final_venda = `${date_final.getFullYear()}-${date_final.getMonth() + 1}-${date_final.getDate()}`;

                window.location.replace(getURL());
            })
        }

        let onCloseDataUtilizacao =  () => {
            $("input#data_utilizacao").on('apply.daterangepicker', (event1, event2) => {

                let date_inicio = event2.startDate._d;
                let date_final = event2.endDate._d;

                data_inicial_utilizacao = `${date_inicio.getFullYear()}-${date_inicio.getMonth() + 1}-${date_inicio.getDate()}`;
                data_final_utilizacao = `${date_final.getFullYear()}-${date_final.getMonth() + 1}-${date_final.getDate()}`;

                window.location.replace(getURL());
            })
        }

        let onChangeStatus = () => {
            
            let status_campo = $("#status");

            status_campo.on('change', () => {
                status = status_campo.val();
                window.location.replace(getURL());
            })

        }

        let onChangeCampoDataVenda = () => {

            let campo = $("#data_venda");

            campo.on('change', (event) => {
                
                if(campo.val() == '') {
                    sem_data_venda = true;
                    data_inicial_venda = ''
                    data_final_venda = ''
                    window.location.replace(getURL());
                }
            });
        }

        let onChangeCampoDataUtilizacao = () => {

            let campo = $("#data_utilizacao");

            campo.on('change', (event) => {
                
                if(campo.val() == '') {
                    data_inicial_utilizacao = ''
                    data_final_utilizacao = ''
                    window.location.replace(getURL());
                }
            });
        }

        let getURL = () => {

            let url = window.location.origin + window.location.pathname;
            let prefix = '?';
            
            if(fornecedor_id != '') {
                url += prefix + 'fornecedor_id=' + fornecedor_id;
                prefix = '&';
            }

            if(servico_id != '') {
                url += prefix + 'servico_id=' + servico_id;
                prefix = '&';
            }

            if(status != '') {
                url += prefix + 'status=' + status;
                prefix = '&';
            }

            if(data_inicial_venda != '') {
                url += prefix + 'data_inicial_venda=' + data_inicial_venda;
                prefix = '&';
            }

            if(data_final_venda != '') {
                url += prefix + 'data_final_venda=' + data_final_venda;
                prefix = '&';
            }

            if(data_inicial_utilizacao != '') {
                url += prefix + 'data_inicial_utilizacao=' + data_inicial_utilizacao;
                prefix = '&';
            }

            if(data_final_utilizacao != '') {
                url += prefix + 'data_final_utilizacao=' + data_final_utilizacao;
                prefix = '&';
            }

            if(sem_data_venda) {
                url += prefix + 'sem_data_venda=' + 'true';
                prefix = '&';
            }

            return url;
        }

        let limparURL = () => {
            let url = window.location.origin + window.location.pathname;
            window.location.replace(url);
        }

    </script>

@endsection