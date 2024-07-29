@extends('template.master')

@section('class_body', 'bg-fixed-02')

@section('title', "Recuperação de senha")

@section('body')
    <div data-page="recupera-senha" class="container-fluid h-100 overflow-y">
        <div class="row flex-row h-100">
            <div class="col-12 my-auto">
                <div class="password-form mx-auto">
                    <div class="logo-centered text-center">
                        <img src="{{ asset('images/logo.svg') }}" alt="Logo Grupo Portal Gramado">
                    </div>
                    <h3>Recuperação de senha</h3>
                    @if(session()->has('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session()->get('status') }}
                        </div>
                    @endif
                    <form data-validate-post method="POST" action="{{ route('password.email') }}">
                        <div class="form-group">
                            <label for="email" class="form-control-label">Informe seu e-mail</label>
                            <div class="input-group">
                                <span class="input-group-addon addon-secondary left">
                                    <i class="la la-at"></i>
                                </span>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" data-required data-min="3" title="E-mail de acesso"
                                       required autofocus class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="nome.sobrenome@email.com.br">
                                <div class="invalid-feedback">
                                    {{ ($errors->has('email')) ? $errors->first('email') : "" }}
                                </div>
                            </div>
                        </div>
                        <div class="hidden"> @csrf </div>
                        <div class="button text-center">
                            <button class="btn btn-lg btn-gradient-01">
                                Redefinir senha
                            </button>
                        </div>
                        <div class="back">
                            <a href="{{ route('login') }}" class="text-uppercase">Fazer login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
