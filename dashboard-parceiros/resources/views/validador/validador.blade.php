@extends('validador.template.header')

@section('title', "Painel do anunciante")

@section('content')

    <div class="validador" data-controller="ValidadorCtrl">
        <div id="container">
            <br>
            <div class="logo">
                <img alt="Compra de Ingressos" src="{{ asset("images/terminais_cdi.svg") }}">
                <img alt="Compra de Ingressos" src="{{ asset("images/logo-ingressos-vetor-preto.svg") }}">
            </div>

            @if(session()->has('aviso'))
                <div class="aviso-erro flash red">
                    {{ session()->get('aviso') }}
                </div>
            @endif

            <form class="simple_form ticket" action="{{ route('app.validador.view') }}">
                <div class="form-row clearfix">
                    <div class="form-group string required ticket_code">
                        @if($tipo_validacao == $validacao_codigo)
                            <label class="string required control-label" for="ticket_code">
                                <abbr title="Campo obrigatório">*</abbr> Código do ingresso:
                            </label>
                            <input class="string required form-control grey" required autofocus="autofocus" placeholder="Código do ingresso"
                                   type="text" name="ticket" id="ticket_code" data-required="true" data-min="5" autocomplete="off">
                        @else
                            <label class="string required control-label" for="cpf_buyer">
                                <abbr title="Campo obrigatório">*</abbr> CPF do comprador:
                            </label>
                            <input class="required form-control grey vanillaMask" required autofocus="autofocus" placeholder="CPF somente números"
                                   type="tel" name="cpf" id="cpf_buyer" data-mask="cpf" data-required="true" data-min="13" autocomplete="off" value="{{ old('cpf') }}">
                        @endif
                    </div>
                </div>
                <div>
                    <input type="hidden" name="validacao" value="{{ strtolower($tipo_validacao) }}">
                    <br>
                    <button class="m-button">Buscar</button>
                </div>
            </form>
            <p class="center">
                <br>
                @if($tipo_validacao == $validacao_codigo)
                    <a href="{{ route('app.validador.index', ['tipo' => strtolower($validacao_cpf)]) }}">Buscar por CPF</a>
                @else
                    <a href="{{ route('app.validador.index', ['tipo' => strtolower($validacao_codigo)]) }}">Buscar por CÓDIGO</a>
                @endif
            </p>
        </div>
    </div>

@endsection
