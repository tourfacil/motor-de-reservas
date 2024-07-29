@extends('template.master')

@section('class_body', 'bg-fixed-04')

@section('title', "Recuperação de senha")

@section('body')
    <div data-page="recupera-senha" class="container-fluid h-100 overflow-y">
        <div class="row flex-row h-100">
            <div class="col-12 my-auto">
                <div class="password-form mx-auto">
                    <div class="logo-centered text-center">
                        <img src="{{ asset('images/logo.svg') }}" alt="logo">
                    </div>
                    <h3>Redefinição de senha</h3>
                    @if(session()->has('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session()->get('status') }}
                        </div>
                    @endif
                    <form data-validate-post method="POST" action="{{ route('password.update') }}">
                        <div class="form-group">
                            <label for="email" class="form-control-label">Informe seu e-mail</label>
                            <div class="input-group">
                                <span class="input-group-addon addon-secondary left">
                                    <i class="la la-at"></i>
                                </span>
                                <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" data-required data-min="3" title="E-mail de acesso"
                                       required autofocus class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="nome.sobrenome@email.com.br">
                                <div class="invalid-feedback">
                                    {{ ($errors->has('email')) ? $errors->first('email') : "" }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-control-label">Nova senha (min 6 caracteres)</label>
                            <div class="input-group">
                                <span class="input-group-addon addon-secondary left">
                                    <i class="la la-lock"></i>
                                </span>
                                <input id="password" type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                                       required data-required placeholder="Sua nova senha" title="Nova senha" data-min="6">
                                <div class="invalid-feedback">
                                    {{ ($errors->has('password')) ? $errors->first('password') : "" }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password-confirm" class="form-control-label">Confirme sua senha</label>
                            <div class="input-group">
                                <span class="input-group-addon addon-secondary left">
                                    <i class="la la-lock"></i>
                                </span>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                                       title="Confirme sua senha" placeholder="Confirme sua nova senha" data-required data-min="6">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="hidden">
                            <input type="hidden" name="token" value="{{ $token }}">
                            @csrf
                        </div>
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
