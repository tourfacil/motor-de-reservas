@extends('template.header')

@section('title', 'Afiliados')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-gradient-01">Afiliados</h2>
                <div>{{ Breadcrumbs::render('app.afiliados.index') }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Listagem dos afiliados do site</h4>
                </div>
                <div class="widget-body">
                    <div class="table-responsive no-overflow">
                        <table class="table table-hover table-border-bottom mb-0 data-table table-mobile">
                            <thead>
                            <tr>
                                <th># ID</th>
                                <th>Nome <small>(Clique para copiar o link de venda)</small></th>
                                <th>Contato</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Administração</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($afiliados as $afiliado)
                                <tr>
                                    <td data-label="#">{{ $afiliado->id }}</td>
                                    <td data-label="Nome" onclick="copyLink({{ $afiliado->id }})"><a href="#">{{ $afiliado->nome_fantasia }}</a></td>
                                    <td data-label="Telefone">{{ $afiliado->telefone }}</td>
                                    <td data-label="Cadastrado" class="text-center">
{{--                                        @if($cupom->status == \TourFacil\Core\Enum\Descontos\StatusDesconto::ATIVO)--}}
                                            <span class="badge-text badge-text-small info text-center">Ativo</span>
{{--                                        @elseif($cupom->status == \TourFacil\Core\Enum\Descontos\StatusDesconto::INATIVO)--}}
{{--                                            <span class="badge-text badge-text-small danger text-center">Inativo</span>--}}
{{--                                        @endif--}}
                                    </td>
                                    <td data-label="" class="text-center">
                                        <a href="{{ Route('app.afiliados.edit', $afiliado->id) }}" class="btn btn-outline-primary">Editar<i class="la la-edit right"></i></a>
                                        <a href="{{ Route('app.relatorios.afiliados.index') }}?afiliado_id={{ $afiliado->id }}&tipo_operacao=VENDA&comissao_passeios=5&comissao_ingressos=5&comissao_gastronomia=5&comissao_transfer=5&periodo=custom&inicio={{ \Carbon\Carbon::now()->firstOfMonth() }}&final={{ \Carbon\Carbon::now()->lastOfMonth() }}" class="btn btn-outline-primary">Vendas<i class="la la-edit right"></i></a>
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

    <div class="float-button">
        <a href="{{ Route('app.afiliados.create') }}" class="btn btn-gradient-01 btn-shadow"><i class="ion ion-plus-round"></i></a>
        <p class="float-tooltip">Cadastrar novo afiliado</p>
    </div>

    <script>

        let copyLink = (afiliado_id) => {

            let url = `{{ env('ECOMMERCE_URL') }}?aid=${afiliado_id}`;
            window.click_copy(url);
            swal('Link copiado', `O link do afiliado foi copiado para a área de transferência.`, 'success')

        }

    </script>

@endsection


