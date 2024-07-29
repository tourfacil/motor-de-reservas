<html>
    <head>
        <title>Resumo do mês :: Tour Fácil</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>@yield('title') :: TourFácil</title>
        {{-- Import application CSS --}}
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        {{-- Google fonts --}}
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700|Noto+Sans:400,700&display=swap" rel="stylesheet">
        {{-- Favicon --}}
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <meta name="theme-color" content="#2f2f2f">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <style>
            .text-center {
                text-align: center;
            }

            .color-red {
                color: red;
            }

            .color-green {
                color: #159f15;
            }

            .nav.navbar {
                display: none !important;
            }

            body {
                padding: 30px;
            }
        </style>
    </head>
    <body>
        <div class="text-center">
            <h1>RESUMO</h1>
            <div class="text-center">
                @IF($meta > $valor_total)
                    <h1 class="text-info">FALTAM <span class="color-red">R$ {{ formataValor( $meta - $valor_total ) }}</span> PARA A META</h1>
                    <h4>META DIÁRIA <span class="color-red">R$ {{ formataValor($meta_diaria) }}</span></h4>
                @ELSE
                    <h1 class="text-info">ESTAMOS <span class="color-green">R$ {{ formataValor( $valor_total - $meta  ) }}</span> ACIMA DA META!!! PARABÉNS TIME!!!</h1>
                @ENDIF
                <h2 class="text-info"><b><small style="color: #2c304d;" >Vendas site: R$ {{ formataValor($valor_total - $total_vendedoras) }} ({{ number_format((($valor_total - $total_vendedoras) * 100) / $valor_total, 2, ',', '') }}%) - Vendas Vendedoras: R$ {{ formataValor($total_vendedoras) }} ({{ number_format((($total_vendedoras) * 100) / $valor_total, 2, ',', '') }}%)</small></b></h2>
            </div>
            <div class="flex-row">
                <div class="col-xl-3 col-md-6">
                    <div class="widget has-shadow">
                        <div class="widget-body">
                            <div class="row align-items-center">
                                <div class="col-2 d-flex">
                                    <i class="la la-cart-plus la-2x"></i>
                                </div>
                                <div class="col d-flex align-items-center">
                                    <div class="content-widget">
                                        <p class="m-0 text-primary">Vendas de hoje</p>
                                        <strong class="text-success">+R$ {{ formataValor($vendido_hoje) }}</strong>
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
                                <div class="col-2 d-flex">
                                    <i class="la la-line-chart la-2x"></i>
                                </div>
                                <div class="col d-flex align-items-center">
                                    <div class="content-widget">
                                        <p class="m-0 text-primary">Vendas do mês</p>
                                        <strong class="text-warning">+R$ {{ formataValor($valor_total) }} </strong>
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
                                <div class="col-2 d-flex">
                                    <i class="la la-tags la-2x"></i>
                                </div>
                                <div class="col d-flex align-items-center">
                                    <div class="content-widget">
                                        <p class="m-0 text-primary">Meta do mês</p>
                                        <strong class="text-info">R$ {{ formataValor($meta) }}</strong>

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
                                <div class="col-2 d-flex">
                                    <i class="la la-tags la-2x"></i>
                                </div>
                                <div class="col d-flex align-items-center">
                                    <div class="content-widget">
                                        <p class="m-0 text-primary">Quantidade vendida</p>
                                        <strong class="text-danger">{{ $quantidade }} Ingresso(s)</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4 col-md-12">
                <div class="widget has-shadow">
                    <div class="widget-header bordered no-actions d-flex align-items-center">
                        <h4>Serviços mais vendidos</h4>
                        <i class="la la-certificate la-lg ml-auto"></i>
                    </div>
                    <div class="widget-body">
                        <div class="table-responsive only-mobile">
                            <table class="table table-hover table-servicos-mais-vendidos mb-0">
                                <tbody>
                                @php
                                    $count = 0;
                                    $contador = 0;
                                @endphp
                                @forelse($servicos as $index => $servico)
                                    <tr>
                                        <td>{!! iconeTopServico(($count + 1)) !!}</td>
                                        <td><p class="m-0 is-95 text-primary">{{ $servico['servico'] }} - <span>R$ {{ formataValor($servico['valor']) }} ({{ $servico['percentual'] }})</span></p></td>
                                    </tr>
                                    @php($count++)
                                @empty
                                    {{-- Ghost table --}}
                                    @for($i = 1; $i <= 8; $i++)
                                        <tr>
                                            <td width="10">{!! iconeTopServico($i) !!}</td>
                                            <td><div class="ghost"></div></td>
                                        </tr>
                                    @endfor
                                @endforelse
                                {{-- Ghost table --}}
                                @if($qtd_servicos_vendidos >= 1 && $qtd_servicos_vendidos < 8)
                                    @for($j = ($qtd_servicos_vendidos + 1); $j <= 8; $j++)
                                        <tr>
                                            <td width="10">{!! iconeTopServico($j) !!}</td>
                                            <td><div class="ghost"></div></td>
                                        </tr>
                                    @endfor
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @php($contador = 0)
            <div class="col-xl-4 col-md-12">
                <div class="widget has-shadow">
                    <div class="widget-header bordered no-actions d-flex align-items-center">
                        <h4>Vendedores que mais venderam</h4>
                        <i class="la la-certificate la-lg ml-auto"></i>
                    </div>
                    <div class="widget-body">
                        <div class="table-responsive only-mobile">
                            <table class="table table-hover table-servicos-mais-vendidos mb-0">
                                <tbody>
                                @forelse($vendedores as $index2 => $vendedor)
                                    <tr>
                                        <td>{!! iconeTopServico(($contador + 1)) !!}</td>
                                        <td><p class="m-0 is-95 text-primary">{{ $vendedor['vendedor'] }} - R$ {{ formataValor($vendedor['valor']) }} ({{ $vendedor['percentual'] }})</p></td>
                                    </tr>
                                    @php($contador++)
                                @empty
                                    {{-- Ghost table --}}
                                    @for($i = 1; $i <= 8; $i++)
                                        <tr>
                                            <td width="10">{!! iconeTopServico($i) !!}</td>
                                            <td><div class="ghost"></div></td>
                                        </tr>
                                    @endfor
                                @endforelse
                                {{-- Ghost table --}}
                                @if($qtd_vendas_vendedores >= 1 && $qtd_vendas_vendedores < 8)
                                    @for($j = ($qtd_vendas_vendedores + 1); $j <= 8; $j++)
                                        <tr>
                                            <td width="10">{!! iconeTopServico($j) !!}</td>
                                            <td><div class="ghost"></div></td>
                                        </tr>
                                    @endfor
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @php($contador = 0)
            <div class="col-xl-4 col-md-12">
                <div class="widget has-shadow">
                    <div class="widget-header bordered no-actions d-flex align-items-center">
                        <h4>Afiliados que mais venderam</h4>
                        <i class="la la-certificate la-lg ml-auto"></i>
                    </div>
                    <div class="widget-body">
                        <div class="table-responsive only-mobile">
                            <table class="table table-hover table-servicos-mais-vendidos mb-0">
                                <tbody>
                                @forelse($afiliados as $index2 => $afiliado)
                                    <tr>
                                        <td>{!! iconeTopServico(($contador + 1)) !!}</td>
                                        <td><p class="m-0 is-95 text-primary">{{ $afiliado['afiliado'] }} - R$ {{ formataValor($afiliado['valor']) }} ({{ $afiliado['percentual'] }})</p></td>
                                    </tr>
                                    @php($contador++)
                                @empty
                                    {{-- Ghost table --}}
                                    @for($i = 1; $i <= 8; $i++)
                                        <tr>
                                            <td width="10">{!! iconeTopServico($i) !!}</td>
                                            <td><div class="ghost"></div></td>
                                        </tr>
                                    @endfor
                                @endforelse
                                {{-- Ghost table --}}
                                @if($qtd_vendas_afiliados >= 1 && $qtd_vendas_afiliados < 8)
                                    @for($j = ($qtd_vendas_afiliados + 1); $j <= 8; $j++)
                                        <tr>
                                            <td width="10">{!! iconeTopServico($j) !!}</td>
                                            <td><div class="ghost"></div></td>
                                        </tr>
                                    @endfor
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>

        <script>
            window.onload = () => {
                setInterval(() => {
                    window.location.reload();
                }, 15000);
            }

        </script>
    </body>
</html>
