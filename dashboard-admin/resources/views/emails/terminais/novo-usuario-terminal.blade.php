@extends('emails.terminais.base-terminais')

@section('title', 'Seja bem vindo')

@section('content')

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
        <tr>
            <td bgcolor="#f2f3f8" align="center" style="padding: 0 15px 0 15px;" class="section-padding">
                <table border="0" cellpadding="0" cellspacing="0" width="600" class="responsive-table">
                    <tr>
                        <td>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>
                                        <!-- Begin Content -->
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                                            <tr>
                                                <td align="left" style="font-size: 35px; font-family: Montserrat, Arial, sans-serif; color: #2c304d; padding: 30px 15px 0 15px;" class="padding-copy">Olá {{ $user->nome }},</td>
                                            </tr>
                                            <tr>
                                                <td align="left" style="padding: 25px 15px 0 15px; font-size: 15px; line-height: 25px; font-family: Noto Sans, Arial, sans-serif; color: #3e3e3e;" class="padding-copy">
                                                    Seja bem vindo a Terminais CDI.<br>
                                                    Criamos uma nova conta para você acompanhar suas comissões do terminal. <br>
                                                    Segue os dados de login abaixo:
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- End Content -->
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <!-- Begin Content -->
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                                            <tr>
                                                <td align="left" style="padding: 25px 15px 0 15px; font-size: 15px; line-height: 25px; font-family: Montserrat, Arial, sans-serif; color: #2c304d;" class="padding-copy">
                                                    <p style="font-size: 15px; line-height: 25px; font-family: Montserrat, Arial, sans-serif; color: #2c304d; margin-top: 0; margin-bottom: 10px;">
                                                        Terminal: &nbsp; <strong> {{ $user->terminal->nome }}</strong>
                                                    </p>
                                                    <p style="font-size: 15px; line-height: 25px; font-family: Montserrat, Arial, sans-serif; color: #2c304d; margin-top: 0; margin-bottom: 10px;">
                                                        &nbsp; Usuário: &nbsp; <strong> {{ $user->email }}</strong>
                                                    </p>
                                                    <p style="font-size: 15px; line-height: 25px; font-family: Montserrat, Arial, sans-serif; color: #2c304d; margin-top: 0;">
                                                        &nbsp;&nbsp;&nbsp; Senha: &nbsp; <strong>{{ $password }}</strong>
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- End Content -->
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <!-- Begin Content -->
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                                            <tr>
                                                <td align="center" style="padding: 20px 15px 0 15px; font-size: 15px; line-height: 25px; font-family: Noto Sans, Arial, sans-serif; color: #3e3e3e; text-align: center" class="padding-copy">
                                                    Clique no botão abaixo para fazer login
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- End Content -->
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <!-- Begin Button -->
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mobile-button-container" bgcolor="#ffffff">
                                            <tr>
                                                <td align="center" style="padding: 25px 0 0 0;" class="padding-copy">
                                                    <table border="0" cellspacing="0" cellpadding="0" class="responsive-table">
                                                        <tr>
                                                            <td align="center">
                                                                <a href="//{{ \TourFacil\Core\Enum\CanaisVendaEnum::URL_TERMINAIS_LOGIN }}" target="_blank" style="font-size: 14px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #503b2c; font-weight: 600; text-decoration: none; background-color: #edd406; border-top: 15px solid #edd406; border-bottom: 15px solid #edd406; border-left: 35px solid #edd406; border-right: 35px solid #edd406; border-radius: 35px; -webkit-border-radius: 35px; -moz-border-radius: 35px; display: inline-block; text-transform: uppercase;" class="mobile-button">Fazer login</a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- End Button -->
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

@endsection
