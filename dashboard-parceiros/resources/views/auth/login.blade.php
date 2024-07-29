@extends('template.master')

@section('class_body', 'login')

@section('title', "Login")

@section('body')

    <div class="container-fluid no-padding h-100">
        <div class="row flex-row h-100 bg-white">
            <div class="col-xl-8 col-lg-6 col-md-5 no-padding">
                <div class="elisyam-bg background-login">
                    <div class="elisyam-overlay overlay-06"></div>
                    <div class="authentication-col-content mx-auto">
                        <h1 class="gradient-text-01">Olá, {{ saudacao() }}!</h1>
                        <span class="description">Login para parceiros da Tourfacil.</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-7 my-auto no-padding">
                <div class="authentication-form mx-auto">
                    <img src="{{ asset('images/logo.svg') }}" alt="Logo Grupo Tourfacil" class="logo">
                    <h3 class="text-grey-dark">Bem vindo(a) faça login!</h3>
                    <hr>
                    @if(session()->has('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session()->get('status') }}
                        </div>
                    @endif
                    <form data-validate-post method="POST" action="{{ route('login') }}">
                        <div class="row margin-bottom-20">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="email" class="form-control-label">E-mail de login</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left">
                                            <i class="la la-user"></i>
                                        </span>
                                        <input id="email" type="email" name="email" value="{{ old('email') }}" data-required placeholder="nome.sobrenome@email.com.br"
                                               required class="form-control {{ ($errors->has('email')) ? "invalid" : "" }}" title="E-mail de login" data-min="5">
                                        <div class="invalid-feedback">
                                            {{ ($errors->has('email')) ? $errors->first('email') : "" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="form-control-label">Senha para acesso</label>
                                    <div class="input-group">
                                        <span class="input-group-addon addon-secondary left">
                                            <i class="la la-unlock"></i>
                                        </span>
                                        <input id="password" type="password" name="password" required title="Senha para acesso"
                                               class="form-control" placeholder="Informe sua senha" data-required data-min="3">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hidden"> @csrf </div>
                        <div class="row">
                            <div class="col text-left">
                                <div class="styled-checkbox">
                                    <input type="checkbox" value="true" name="remember" id="remember">
                                    <label for="remember">Manter conectado</label>
                                </div>
                            </div>
                            <div class="col text-right">
                                <a href="{{ route('password.request') }}">Esqueceu a senha ?</a>
                            </div>
                        </div>
                        <div class="sign-btn text-center">
                            <button class="btn btn-lg btn-gradient-01">Entrar <i class="la la-angle-right right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
