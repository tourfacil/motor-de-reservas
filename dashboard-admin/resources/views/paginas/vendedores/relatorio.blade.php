@extends('template.header')

@section('title', 'vendedores')

@section('content')

@php
    use Carbon\Carbon;
@endphp

<style>
    .text-color {
        color: #5d5386;
    }
</style>

<div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title text-dark">Relatório de <span class="text-gradient-01">ingressos vendidos </span> </h2>
            {{-- <div>{{ Breadcrumbs::render('app.vendedores.relatorios.index') }}</div> --}}
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
                    <div class="col d-flex align-items-center">
                        <div class="content-widget">
                            @IF($tipo_operacao == "UTILIZACAO")
                                <p class="m-0 text-primary">Período de utilização</p>
                            @ELSE
                                <p class="m-0 text-primary">Período de vendas</p>
                            @ENDIF
                                <strong class="text-secondary">{{Carbon::parse($data_inicio)->format('d/m')}} até {{Carbon::parse($data_final)->format('d/m')}}</strong>
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
                        <i class="la la-cart-plus la-2x"></i>
                    </div>
                    <div class="col d-flex align-items-center">
                        <div class="content-widget">
                            <p class="m-0 text-primary">R$ Total vendido</p>
                            <strong class="text-success">R$ {{ formataValor($total_vendido) }}</strong>
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
                            <p class="m-0 text-primary">R$ Comissão</p>
                            <strong class="text-warning">R$ {{ formataValor($total_comissionado) }}</strong>
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
                            <p class="m-0 text-primary">Quantidade</p>
                            <strong class="text-info">{{ $quantidade_reservas }} reservas(s)</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <div></div>
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
                                <small>Vendedor</small>
                                @IF($vendedor != null)
                                    <h3>{{$vendedor->nome_fantasia}}</h3>
                                @ENDIF
                            </div>
                        </div>
                        <div class="col-4 text-right">
                            <div class="form-group pl-2 m-0">
                                @IF($vendedor != null)
                                    {{-- <a href="{{ route('app.relatorios.vendedores.download.pdf') }}?afiliado_id={{$vendedor->id}}&inicio={{$data_inicio->format('d-m-Y')}}&final={{$data_final->format('d-m-Y')}}&tipo_operacao={{$tipo_operacao}}&comissao_passeios={{$vendedor->comissao_passeios}}&comissao_ingressos={{$vendedor->comissao_ingressos}}&comissao_gastronomia={{$vendedor->comissao_gastronomia}}&comissao_transfer={{$vendedor->comissao_transfer}}" target="_blank"
                                        class="btn btn-secondary mr-2 line-height-inherit">Baixar PDF <i class="la la-download right"></i></a> --}}
                                @ENDIF
                                @IF($vendedor != null)
                                    {{-- <a href="{{ route('app.relatorios.vendedores.download.xls') }}?afiliado_id={{$vendedor->id}}&inicio={{$data_inicio->format('d-m-Y')}}&final={{$data_final->format('d-m-Y')}}&tipo_operacao={{$tipo_operacao}}&comissao_passeios={{$vendedor->comissao_passeios}}&comissao_ingressos={{$vendedor->comissao_ingressos}}&comissao_gastronomia={{$vendedor->comissao_gastronomia}}&comissao_transfer={{$vendedor->comissao_transfer}}" target="_blank"
                                       class="btn btn-secondary mr-2 line-height-inherit">Baixar XLS <i class="la la-download right"></i></a> --}}
                                @ENDIF
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
                                <th>Serviço</th>
                                <th>Categoria</th>
                                @IF($tipo_operacao == "UTILIZACAO")
                                    <th>Data Utilização</th>
                                @ELSE
                                    <th>Data Venda</th>
                                @ENDIF
                                <th class="text-center">Valor Total R$</th>
                                <th class="text-center">Comissão %</th>
                                <th class="text-center">Comissão R$</th>
                            </tr>
                            </thead>
                            <tbody>
                                @FOREACH($reservas as $reserva)
                                    <tr>
                                        <td>
                                            <a style="color: #e76c90" target="_blank" href="{{ Route('app.reservas.view', $reserva->voucher) }}">#{{$reserva->voucher}}</a>
                                        </td>
                                        <td>
                                            <a class="text-truncate text-color" style="color: #5d5386;">
                                                {{$reserva->servico->nome}}
                                            </a>
                                        </td>
                                        <td  style="color: #5d5386;">
                                            <a class="text-truncate text-color">
                                                {{$reserva->servico->categorias->first()->nome}}
                                            </a>
                                        </td>
                                        <td  style="color: #5d5386;">
                                            <a class="text-truncate text-color">
                                                @IF($tipo_operacao == "UTILIZACAO")
                                                    {{$reserva->agendaDataServico->data->format('d/m/Y')}}
                                                @ELSE
                                                {{$reserva->created_at->format('d/m/Y')}}
                                                @ENDIF
                                            </a>
                                        </td>
                                        <td  style="color: #5d5386;">
                                            <a class="text-truncate">
                                                R$ {{formataValor($reserva->valor_total)}}
                                            </a>
                                        </td>
                                        <td  style="color: #5d5386;">
                                            <a class="text-truncate text-center">
                                                {{ \TourFacil\Core\Services\VendedorService::getComissaoPercentual($reserva) }}
                                            </a>
                                        </td>
                                        <td  style="color: #5d5386;">
                                            <a class="text-truncate">
                                                R$ {{ formataValor(\TourFacil\Core\Services\VendedorService::getComissaoVendedor($reserva)) }}
                                            </a>
                                        </td>
                                    </tr>
                                @ENDFOREACH
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="float-button">
        <a href="#" data-toggle="modal" data-target="#filtro-relatorio-fornecedor" class="btn btn-gradient-02 btn-shadow"><i class="ion ion-funnel"></i></a>
        <p class="float-tooltip">Filtrar Reservas</p>
    </div>

    @include('paginas.modais.filtro-relatorio-afiliado')

@endsection


