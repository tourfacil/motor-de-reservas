@extends('emails.base')

@section('title', 'Seja Bem vindo')

@section('content')

    @include('emails._partials.header-email', ['banner' => asset('images/email/bem_vindo_email.png'), 'alt' => 'Bem vindo a TourFácil'])

    <!-- Section --><!--[if mso | IE]>
    <table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
        <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
    <div style="Margin:0px auto;max-width:600px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
            <tbody>
            <tr>
                <td style="direction:ltr;font-size:0px;padding:0;text-align:center;vertical-align:top;">
                    <!--[if mso | IE]>
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="" style="vertical-align:top;width:600px;"><![endif]-->
                    <div class="mj-column-per-100 outlook-group-fix"
                         style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                               style="background-color:white;vertical-align:top;" width="100%">
                            <tr>
                                <td style="font-size:0px;word-break:break-word;"><!--[if mso | IE]>
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td height="15" style="vertical-align:top;height:15px;"><![endif]-->
                                    <div style="height:15px;">&nbsp;</div>
                                    <!--[if mso | IE]></td></tr></table><![endif]-->
                                </td>
                            </tr>
                            <tr>
                                <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                    <div style="font-family:Roboto, Arial;font-size:16px;line-height:160%;text-align:left;color:#666666;">
                                        <span>Seja bem vindo(a), <strong>{{ somenteNome($cliente->nome) }}!</strong></span><br>
                                        <p style="line-height: inherit; margin: 0; margin-top: 15px; color: #666666; text-align: center; border:none; font-family:Roboto, Arial;font-size:16px;">
                                            Estamos muito felizes com seu cadastro e por você fazer <br class="d-sm-none">
                                            parte do mundo <strong>Tour Fácil</strong>
                                        </p>
                                        <p style="line-height: inherit; margin: 0; margin-top: 15px; color: #666666; text-align: center; border:none; font-family:Roboto, Arial;font-size:16px;">Buscando agilizar a sua compra, geramos uma senha para você: </p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                    <div style="font-family:Roboto, Arial;font-size:16px;line-height:160%;text-align:center;color:#666666;">
                                        <p style="line-height: inherit; color: #666666; margin: 0; margin-bottom: 5px; text-align: center; border:none; font-family:Roboto, Arial;font-size:16px;">
                                            Aqui está a senha de acesso:
                                        </p>
                                        <p style="line-height: inherit; color: #666666; margin: 0; text-align: center; border:none; font-family:Roboto, Arial;font-size:20px;">
                                            <strong>{{$password}}</strong>
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" vertical-align="middle" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;line-height:100%;">
                                        <tr>
                                            <td align="center" bgcolor="#5dc3e3"
                                                role="presentation" style="border:none;border-radius:25px;cursor:auto;padding:16px 32px;background:#5dc3e3;" valign="middle">
                                                <a href="{{ route('ecommerce.cliente.login') }}" style="background:#5dc3e3;color:#ffffff;font-family:Roboto, Arial;text-transform: uppercase; font-size:13px;font-weight:700;line-height:130%;margin:0;text-decoration:none;"
                                                   target="_blank">Quero acessar minha conta</a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:0px;word-break:break-word;"><!--[if mso | IE]>
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td height="20" style="vertical-align:top;height:20px;"><![endif]-->
                                    <div style="height:20px;">&nbsp;</div>
                                    <!--[if mso | IE]></td></tr></table><![endif]-->
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!--[if mso | IE]></td></tr></table><![endif]--></td>
            </tr>
            </tbody>
        </table>
    </div>
    <!--[if mso | IE]></td></tr></table><![endif]-->

@endsection
