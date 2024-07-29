@extends('template.header')

@section('title', 'Faturas a pagar')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Faturas para pagamento</h2>
                <div>{{ Breadcrumbs::render('app.faturas.pendente-pagamento') }}</div>
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
                                <strong class="text-secondary">{{ $faturas->count() }} fatura(s) aberta(s)</strong>
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
                                <p class="m-0 text-primary">Valor pendente</p>
                                <strong class="text-warning">R${{ formataValor($faturas->sum('valor')) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="col-xl-3 col-md-6">
            <div class="widget has-shadow">
                <div class="widget-body">
                    <div class="row align-items-center">
                        <div class="col-xl-2 d-flex">
                            <i class="la la-line-chart la-2x"></i>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="content-widget">
                                <p class="m-0 text-primary"></p>
                                <strong class="text-warning"></strong>
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
                                <p class="m-0 text-primary"></p>
                                <strong class="text-info"></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>



    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Listagem das faturas a pagar do Tour Fácil</h4>
                    <div class="botoes">
                        <a href="{{ Route('app.faturas.pendente-pagamento') . '?tipo_relatorio=XLSX' }}" target="_blank"
                            class="btn btn-secondary line-height-inherit">Baixar XLS <i
                                class="la la-download right"></i></a>
                        <a href="{{ Route('app.faturas.pendente-pagamento') . '?tipo_relatorio=PDF' }}" target="_blank"
                            class="btn btn-secondary line-height-inherit">Baixar PDF <i
                                class="la la-download right"></i></a>
                    </div>
                </div>
                <div class="widget-body">
                    <form action="{{ route('app.faturas.pendente-pagamento') }}" method="GET">
                        <div class="form-group">
                            <label for="fornecedor-filter">Filtrar por Fornecedor</label>
                            <select id="fornecedor-filter" name="fornecedor_id" class="form-control">
                                <option value="">Todos os Fornecedores</option>
                                @foreach ($fornecedores as $fornecedor)
                                    <option value="{{ $fornecedor->id }}"
                                        {{ request('fornecedor_id') == $fornecedor->id ? 'selected' : '' }}>
                                        {{ $fornecedor->nome_fantasia }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </form>
                </div>
                <div class="widget-body">
                    <div class="table-responsive no-overflow">
                        <table class="table table-hover table-border-bottom mb-0 data-table table-mobile">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fornecedor</th>
                                    <th>Data Início</th>
                                    <th>Data Final</th>
                                    {{-- <th>Apr. Interna</th> --}}
                                    {{-- <th>Apr. Externa</th> --}}
                                    <th>Vencimento</th>
                                    <th>Tipo</th>
                                    <th>Tipo Período</th>
                                    <th class="text-center">Valor (R$)</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($faturas as $fatura)
                                    <tr style="color: #5d5386;">
                                        <td>
                                            <a href="{{ Route('app.faturas.show') . '?fatura_id=' . $fatura->id }}"
                                                class="text-secondary">#{{ $fatura->id }}</a>
                                        </td>
                                        <td>{{ $fatura->fornecedor->nome_fantasia }}</td>
                                        <td>{{ $fatura->inicio->format('d/m/Y') }}</td>
                                        <td>{{ $fatura->final->format('d/m/Y') }}</td>
                                        {{-- <td>{{ $fatura->aprovacao_interna ? 'SIM' : 'NÃO' }}</td> --}}
                                        {{-- <td>{{ $fatura->aprovacao_externa ? 'SIM' : 'NÃO' }}</td> --}}
                                        <td>{{ $fatura->data_pagamento->format('d/m/Y') }}</td>
                                        <td>{{ $fatura->tipo }}</td>
                                        <td>{{ $fatura->tipo_periodo }}</td>
                                        <td class="text-warning text-center font-weight-bold">
                                            R${{ formataValor($fatura->valor) }}</td>
                                        <td style="display: flex;">
                                            <a href="{{ Route('app.faturas.show') . '?fatura_id=' . $fatura->id }}"
                                                target="_blank" class="btn btn-outline-primary">
                                                Revisar <i class="la la-edit right"></i>
                                            </a>
                                            <button class="btn btn-danger" onclick="pagarFatura('{{ $fatura->id }}')">
                                                PAGO <i class="la la-edit right"></i>
                                            </button>
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

    <label id="url-set-pago" style="display: none;">{{ Route('app.faturas.set-fatura-paga') }}</label>

    <script>
        let pagarFatura = (id) => {

            swal({
                title: 'Tem certeza ?',
                icon: 'warning',
                text: `Tem certeza que deseja marcar a fatura #${id} como paga ? A ação não poderá ser desfeita`,
                buttons: ['Cancelar', 'Sim'],
            }).then((result) => {

                if (result) {

                    let url = `${$("#url-set-pago").text()}?fatura_id=${id}`
                    let payload = {
                        fatura_id: id
                    };

                    axios.post(url, payload).then((result) => {
                        window.location.reload();
                    })
                }
            })

        }
    </script>
@endsection
