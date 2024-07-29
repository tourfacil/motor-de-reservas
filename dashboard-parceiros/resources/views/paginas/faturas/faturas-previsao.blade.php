@extends('template.header')

@section('title', 'Faturas')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Previsão de faturas</h2>
                <div>{{ Breadcrumbs::render('app.faturas.fatura-previstao') }}</div>
            </div>
        </div>
    </div>

    <div class="row flex-row">
        <div class="col-xl-6 col-md-6">
            <div class="widget has-shadow">
                <div class="widget-body">
                    <div class="row align-items-center">
                        <div class="col-xl-2 d-flex">
                            <i class="la la-calendar la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center" style="justify-content: space-between;">
                            <div class="content-widget">
                                <p class="m-0 text-primary">Quantidade</p>
                                <strong class="text-secondary">{{ $faturas->count() }} fatura(s) previstas(s)</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6">
            <div class="widget has-shadow">
                <div class="widget-body">
                    <div class="row align-items-center">
                        <div class="col-xl-2 d-flex">
                            <i class="la la-cart-plus la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary">Valor previsto</p>
                                <strong class="text-warning">R${{ formataValor($faturas->sum('valor')) }}</strong>
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
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <div class="col-3">
                        <div class="ml-2">
                            <div class="row">
                                <label for="data_venda" class="form-control-label">Período</label>
                                <div class="input-group">
                                    <input id="data_venda" type="tel" class="form-control datepicker" placeholder="DD/MM/AAAA"
                                           data-required title="Data inicial"  value="{{ $inicio->format('d/m/Y') . ' - ' . $final->format('d/m/Y') }}">
                                </div>
                                <input type="hidden" name="inicio" id="data_inicial_venda">
                                <input type="hidden" name="final" id="data_final_venda">
                            </div>
                        </div>
                    </div>
                </div>
                @php($contador=0)
                <div class="widget-body">
                    <div class="table-responsive no-overflow">
                        <table class="table table-hover table-border-bottom mb-0 data-table table-mobile">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Fornecedor</th>
                                <th>Data Início</th>
                                <th>Data Final</th>
                                <th>Status</th>
                                <th>Tipo</th>
                                <th>Tipo Período</th>
                                <th class="text-center">Valor (R$)</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($faturas as $fatura)
                                <tr style="color: #5d5386;">
                                    <td>{{ $contador + 1 }}</td>@php($contador++)
                                    <td>{{ $fatura->fornecedor->nome_fantasia }}</td>
                                    <td>{{ $fatura->inicio->format('d/m/Y') }}</td>
                                    <td>{{ $fatura->final->format('d/m/Y') }}</td>
                                    <td>
                                        {{-- <span class="badge-text badge-text-small {{ \TourFacil\Core\Enum\Faturas\StatusFaturaEnum::CORES[$fatura->status] }}">
                                            {{ \TourFacil\Core\Enum\Faturas\StatusFaturaEnum::STATUS[$fatura->status]}}</td>
                                        </span>  --}}
                                        <span class="badge-text badge-text-small {{ \TourFacil\Core\Enum\Faturas\StatusFaturaEnum::CORES[$fatura->status] }}">
                                            Previsão de fatura
                                        </span>
                                    <td>{{ $fatura->tipo }}</td>
                                    <td>{{ $fatura->tipo_periodo }}</td>
                                    <td class="text-warning text-center font-weight-bold">R${{ formataValor($fatura->valor) }}</td>
                                    <td>
                                        <a href="{{ Route('app.faturas.fatura-prevista-individual') . '?fornecedor=' . $fatura->fornecedor_id . '&inicio=' . $fatura->inicio->format('Y-m-d') . '&final=' . $fatura->final->format('Y-m-d') }}" target="_blank" class="btn btn-outline-primary">
                                            Revisar <i class="la la-edit right"></i>
                                        </a>
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



        // Configura os inputs na modal para filtro de datas
        let calendario = ($modal) => {
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
                let $calendario = $modal.find("input.datepicker");
                let $data_inicio = $modal.find("input[name='inicio']");
                let $data_final = $modal.find("input[name='final']");
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

        window.onload = () => {
            let body = $("body")
            calendario(body);
            onCloseDataVenda()
        }

        let onCloseDataVenda =  () => {
            $("input#data_venda").on('apply.daterangepicker', (event1, event2) => {

                let date_inicio = event2.startDate._d;
                let date_final = event2.endDate._d;

                let url = window.location.origin + window.location.pathname;

                data_inicial_venda = `${date_inicio.getFullYear()}-${date_inicio.getMonth() + 1}-${date_inicio.getDate()}`;
                data_final_venda = `${date_final.getFullYear()}-${date_final.getMonth() + 1}-${date_final.getDate()}`;

                window.location.replace(`${url}?inicio=${data_inicial_venda}&final=${data_final_venda}`);
            })
        }
    </script>
@endsection


