@php($third_step_error = isset($has_erro_pagamento))
<div class="payment-steps pt-2 pt-md-4 pb-2 text-md-center {{ ($current_step == 3) ? "d-none d-md-block" : "" }}">
    <div class="row d-flex align-items-center justify-content-center">
        <div class="col-12 col-md-4 col-lg-auto step-item {{ ($current_step <= 3) ? "active" : ""  }}">
            @if($current_step != 3)
                <a href="{{ route('ecommerce.carrinho.index') }}" title="Carrinho de compras">
                    <span class="number">1</span>
                    <span class="text">Carrinho de compras</span>
                </a>
            @else
                <span class="number">1</span>
                <span class="text">Carrinho de compras</span>
            @endif
            <i class="iconify" data-icon="jam:chevron-right"></i>
        </div>
        <div class="col-12 col-md-4 col-lg-auto step-item {{ ($current_step >= 2) ? "active" : ""  }}">
            <span class="number">2</span>
            <span class="text d-md-none d-lg-inline">Informações de pagamento</span>
            <span class="text d-none d-md-inline d-lg-none">Info. pagamento</span>
            <i class="iconify" data-icon="jam:chevron-right"></i>
        </div>
        <div class="col-12 col-md-4 col-lg-auto step-item {{ ($current_step == 3) ? "active" : ""  }}">
            <span class="number">3</span>
            @if($third_step_error)
                @if(!isset($pix_expirado))
                    <span class="text d-md-none d-lg-inline">Pagamento não autorizado</span>
                    <span class="text d-none d-md-inline d-lg-none">Não autorizado</span>
                @else
                    <span class="text d-md-none d-lg-inline">Pagamento expirado</span>
                    <span class="text d-none d-md-inline d-lg-none">Expirado</span>
                @endif
                <i class="iconify icon" data-icon="jam:close"></i>
            @else
                <span class="text d-md-none d-lg-inline">Confirmação do pedido</span>
                <span class="text d-none d-md-inline d-lg-none">Confirmação</span>
                <i class="iconify" data-icon="jam:check"></i>
            @endif
        </div>
    </div>
</div>
