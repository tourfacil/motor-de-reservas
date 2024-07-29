<div id="filtro-periodo-modal" class="modal modal-top fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title event-title">Pesquisa por data</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">close</span>
                </button>
            </div>
            <div class="modal-body">
                <form data-validate-post method="GET" class="form-horizontal" autocomplete="off">
                    <div class="form-group mb-0 pr-2 pl-2">
                        <div class="row">
                            <div class="col-xl-12 mb-2">
                                <label for="date_range" class="form-control-label">Período para busca</label>
                                <div class="input-group">
                                    <span class="input-group-addon addon-secondary left"><i class="la la-calendar-check-o"></i></span>
                                    <input id="date_range" type="tel" class="form-control datepicker date_range" placeholder="DD/MM/AAAA" required
                                           data-required title="Data inicial">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="alert alert-secondary mt-2 mb-2" role="alert">
                                    <i class="la la-info-circle mr-2"></i>
                                    Informe a data inicial e final do período!
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
