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
                                <label for="date_range_fornecedor" class="form-control-label">Período para busca</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-calendar-check-o"></i></span>
                                    <input id="date_range_fornecedor" type="tel" class="form-control datepicker date_range" placeholder="DD/MM/AAAA" required
                                           data-required title="Data inicial">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-2">
                                <label class="form-control-label">Serviços</label>
                                <div class="list-servicos-modal">
                                    <div class="list-check mt-2">
                                        <div class="mb-3">
                                            <div class="styled-checkbox">
                                                <input type="checkbox" id="select-all-servicos">
                                                <label for="select-all-servicos" title="Todos os serviços">Todos os serviços</label>
                                            </div>
                                        </div>
                                        @foreach($servicos as $servico_modal)
                                            <div class="mb-3">
                                                <div class="styled-checkbox text-truncate">
                                                    <input type="checkbox" name="servicos[]" value="{{ $servico_modal->id }}" id="servico-{{ $servico_modal->id }}">
                                                    <label for="servico-{{ $servico_modal->id }}" title="{{ $servico_modal->nome }}">{{ $servico_modal->nome }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
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
