@extends('template.header')

@section('title', 'Colaboradores')

@section('content')

    <div class="row">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <h2 class="page-header-title text-dark">Colaboradores <span class="text-gradient-01">administrativo</span></h2>
                <div>{{ Breadcrumbs::render('app.colaboradores.index') }}</div>
            </div>
        </div>
    </div>

    <div class="row" data-controller="ColaboradoresCtrl">
        <div class="col-12">
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4 class="mr-auto">Listagem dos colaboradores cadastrados</h4>
                </div>
                <div class="widget-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-border-bottom mb-0 data-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Email de acesso</th>
                                <th>Nível de acesso</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Administração</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($colaboradores as $colaborador)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if($colaborador->status)
                                            <a href="{{ route('app.colaboradores.view', $colaborador->id) }}" data-action="edit-colaborador">
                                                {{ $colaborador->name }}
                                            </a>
                                        @else
                                            {{ $colaborador->name }}
                                        @endif
                                    </td>
                                    <td class="{{ ($colaborador->status) ? "text-primary" : "" }}">{{ $colaborador->email }}</td>
                                    <td class="{{ ($colaborador->status) ? "text-primary" : "" }}"><strong>{{ $colaborador->nivel_acesso }}</strong></td>
                                    <td class="text-center">
                                        @if($colaborador->status)
                                            <span class="badge-text badge-text-small success">Ativo</span>
                                        @else
                                            <span class="badge-text badge-text-small danger">Inativo</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($colaborador->status)
                                            <a href="{{ route('app.colaboradores.view', $colaborador->id) }}" data-action="edit-colaborador" class="btn btn-outline-primary">
                                                Editar <i class="la la-edit right"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('app.colaboradores.restore') }}" data-action="activate-colaborador" data-id="{{ $colaborador->id }}" class="btn btn-outline-light">
                                                Ativar <i class="la la-level-up right"></i>
                                            </a>
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

    {{-- Modal para cadastrar novo colaborador --}}
    <div id="novo-colaborador" class="modal modal-top fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Novo colaborador</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax method="POST" action="{{ route('app.colaboradores.store') }}" class="form-horizontal">
                        <div class="form-group row mb-0">
                            <div class="col-xl-12 mb-3">
                                <label for="name" class="form-control-label">Nome completo</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-user"></i></span>
                                    <input id="name" type="text" class="form-control" placeholder="Nome e sobrenome"
                                           title="Nome do colaborador" name="name" required data-required data-min="4" data-auto-capitalize>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="email" class="form-control-label">Email de acesso <small>&nbsp; (O e-mail deve ser único)</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-at"></i></span>
                                    <input id="email" type="email" class="form-control" placeholder="nome.sobrenome@email.com.br"
                                           title="Email de acesso" name="email" required data-required data-min="4">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="afiliado_id" class="form-control-label">ID Afiliado <small>&nbsp; (Se for afiliado é nescessário informar ID)</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-slack"></i></span>
                                    <input id="afiliado_id" type="afiliado_id" class="form-control" placeholder="1"
                                           title="ID do afiliado" name="afiliado_id">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="level" class="form-control-label">Nível de acesso</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-balance-scale"></i></span>
                                    <select id="level" name="level" class="form-control boostrap-select-custom" required
                                            data-required title="Selecione um nível">
                                        @foreach($nivels_acesso as $key => $nivel)
                                            <option value="{{ $key  }}">{{ $nivel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="alert alert-secondary mb-3 mt-2" role="alert">
                            <i class="la la-unlock mr-2"></i>
                            A senha de acesso será gerada automáticamente!
                        </div>
                        <div class="em-separator separator-dashed mt-1"></div>
                        <div class="mt-3 text-center">
                            <button class="btn btn-success">Cadastrar colaborador <i class="la la-angle-right right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para editar o colaborador --}}
    <div id="edit-colaborador" class="modal modal-top fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar colaborador</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form data-validate-ajax method="POST" action="{{ route('app.colaboradores.update') }}" class="form-horizontal">
                        <div class="form-group row mb-0">
                            <div class="col-xl-12 mb-3">
                                <label for="edit_name" class="form-control-label">Nome completo</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-user"></i></span>
                                    <input id="edit_name" type="text" class="form-control" placeholder="Nome e sobrenome"
                                           title="Nome do colaborador" name="name" required data-required data-min="4" data-auto-capitalize>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="edit_email" class="form-control-label">Email de acesso <small>&nbsp; (O e-mail deve ser único)</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-at"></i></span>
                                    <input id="edit_email" type="email" class="form-control" placeholder="nome.sobrenome@email.com.br"
                                           title="Email de acesso" name="email" required data-required data-min="4">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="afiliado_id" class="form-control-label">ID Afiliado <small>&nbsp; (Se for afiliado é nescessário informar ID)</small></label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-slack"></i></span>
                                    <input id="afiliado_id" type="afiliado_id" class="form-control" placeholder="1"
                                           title="ID do afiliado" name="afiliado_id">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="edit_level" class="form-control-label">Nível de acesso</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-balance-scale"></i></span>
                                    <select id="edit_level" name="level" class="form-control boostrap-select-custom" required
                                            data-required title="Selecione um nível">
                                        @foreach($nivels_acesso as $key => $nivel)
                                            <option value="{{ $key }}" data-nivel="{{ $key }}">{{ $nivel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-xl-12 mb-1 mt-2 pl-4">
                                <div class="styled-checkbox">
                                    <input type="checkbox" name="new_password" id="new-password">
                                    <label for="new-password">Gerar nova senha de acesso</label>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-secondary mb-3 mt-2" role="alert">
                            <i class="la la-exclamation-circle mr-2"></i>
                            Marque a opção acima para gerar uma nova senha!
                        </div>
                        <div class="hide">
                            {{ method_field("PUT") }}
                            <input type="hidden" name="colaborador_id">
                            <input type="hidden" name="desativar_colaborador" value="off">
                        </div>
                        <div class="em-separator separator-dashed mt-1"></div>
                        <div class="mt-3">
                            <button type="button" data-action="desativar" class="btn btn-danger pull-left">Desativar <i class="la la-trash right"></i></button>
                            <button class="btn btn-success pull-right">Atualizar <i class="la la-refresh right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="float-button">
        <button data-target="#novo-colaborador" data-toggle="modal" class="btn btn-gradient-01 btn-shadow"><i class="ion ion-plus-round"></i></button>
        <p class="float-tooltip">Novo colaborador</p>
    </div>

@endsection
