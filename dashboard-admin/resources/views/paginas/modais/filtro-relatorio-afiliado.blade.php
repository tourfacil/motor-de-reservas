<div id="filtro-relatorio-fornecedor" class="modal modal-top fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title event-title">Filtrar relatório</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">close</span>
                </button>
            </div>
            <div class="modal-body">
                <form data-validate-post method="GET" class="form-horizontal" autocomplete="off">
                    <div class="form-group mb-0 pr-2 pl-2">
                        <div class="row">
                            <div class="col-xl-12 mb-3">
                                <label for="date_range" class="form-control-label">Período para busca</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-calendar-check-o"></i></span>
                                    <input id="date_range" type="tel" class="form-control datepicker" placeholder="DD/MM/AAAA" required
                                           data-required title="Data inicial">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            @if(isset($afiliados_lista))
                                @if(userIsAfiliado() == false)
                                    <div class="col-xl-12 mb-3">
                                        <label for="fornecedor_filtro_relatorio" class="form-control-label">Afiliado</label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left"><i class="la la-user"></i></span>
                                            <select id="afiliado_filtro_relatorio" name="afiliado_id" class="form-control boostrap-select-custom" required
                                                    data-required title="Selecione um afiliado">
                                                    @foreach($afiliados_lista as $afiliado)
                                                        <option value="{{ $afiliado->id }}">{{ $afiliado->nome_fantasia }}</option>
                                                    @endforeach
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                @else
                                    <input id="afiliado_filtro_relatorio" name="afiliado_id" type="hidden" value="{{ auth()->user()->afiliado_id }}">
                                @endif
                            @endif
                            @if(isset($vendedores_lista))
                                @if(userIsVendedor() == false)
                                    <div class="col-xl-12 mb-3">
                                        <label for="fornecedor_filtro_relatorio" class="form-control-label">Vendedor</label>
                                        <div class="input-group">
                                            <span class="input-group-addon addon-secondary left"><i class="la la-user"></i></span>
                                            <select id="vendedor_filtro_relatorio" name="vendedor_id" class="form-control boostrap-select-custom" required
                                                    data-required title="Selecione um vendedor">
                                                    @foreach($vendedores_lista as $vendedor)
                                                        <option value="{{ $vendedor->id }}">{{ $vendedor->nome_fantasia }}</option>
                                                    @endforeach
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                @else
                                    <input id="vendedor_filtro_relatorio" name="vendedor_id" type="hidden" value="{{ auth()->user()->vendedor_id }}">
                                @endif
                            @endif
                            <div class="col-xl-12 mb-3">
                                <label for="fornecedor_filtro_relatorio" class="form-control-label">Tipo de comissão</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-suitcase"></i></span>
                                    <select id="vendedor_filtro_relatorio" name="tipo_operacao" class="form-control boostrap-select-custom" required
                                            data-required title="Selecione um tipo de comissão">
                                            <option value="VENDA">Data da Venda</option>
                                            <option value="UTILIZACAO">Data da Utilização</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hide">
                        <input type="hidden" name="periodo" value="custom">
                        <input type="hidden" name="inicio">
                        <input type="hidden" name="final">
                    </div>
                    <div class="em-separator separator-dashed mt-2"></div>
                    <div class="mt-2 mb-2 text-center">
                        <button class="btn btn-success">Filtrar dados <i class="la la-filter right ml-2"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
