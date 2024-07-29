@extends('emails.base')

@section('title', 'Obrigado por sua compra')

@section('content')

    <!-- Section --><!--[if mso | IE]>
    <table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
        <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
    <div style="background:#fafafa;background-color:#fafafa;Margin:0px auto;max-width:600px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
               style="background:#ffffff;background-color:#ffffff;width:100%;">
            <tbody>
            <tr>
                <td style="direction:ltr;font-size:0px;padding: 0 0 10px 0;text-align:center;vertical-align:top;">
                    <!--[if mso | IE]>
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="" style="vertical-align:top;width:600px;"><![endif]-->
                    <div class="mj-column-per-100 outlook-group-fix"
                         style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                               style="vertical-align:top;" width="100%">
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
                                        Olá <strong>{{ formatarNome($cliente->nome) }}</strong>!<br>
                                    </div>
                                    <div style="font-family:Roboto, Arial;font-size:16px;margin-top: 15px; line-height:160%;text-align:center;color:#666666;">
                                        Aqui na TourFácil levamos muito a sério a felicidade e satisfação dos nossos clientes, se você puder dedicar alguns instantes para nos dizer como foi a sua experiência com as atividades reservadas conosco. Desta forma, vamos conseguir melhorar cada vez mais as experiências que oferecemos para nossos clientes.
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!--[if mso | IE]></td></tr></table><![endif]-->
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!--[if mso | IE]></td></tr></table><![endif]-->

    @include('emails._partials.divider')

{{--    @foreach($pedido->reservas as $reserva)--}}
{{--        <!-- Service Section --><!--[if mso | IE]>--}}
{{--        <table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">--}}
{{--            <tr>--}}
{{--                <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->--}}

{{--        <div style="background:#ffffff;background-color:#ffffff;Margin:0px auto;max-width:600px;">--}}
{{--            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"--}}
{{--                   style="background:#ffffff;background-color:#ffffff;width:100%;">--}}
{{--                <tbody>--}}
{{--                <tr>--}}
{{--                    <td style="direction:ltr;font-size:0px;padding:0 20px;text-align:center;vertical-align:top;">--}}
{{--                        <!--[if mso | IE]>--}}
{{--                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">--}}
{{--                            <tr>--}}
{{--                                <td class="" width="600px">--}}
{{--                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class=""--}}
{{--                                           style="width:600px;" width="600">--}}
{{--                                        <tr>--}}
{{--                                            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">--}}
{{--                        <![endif]-->--}}
{{--                        <div style="background:#ffffff;background-color:#ffffff;Margin:0px auto;max-width:600px;">--}}
{{--                            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"--}}
{{--                                   style="background:#ffffff;background-color:#ffffff;width:100%;">--}}
{{--                                <tbody>--}}
{{--                                <tr>--}}
{{--                                    <td style="border:1px solid #ffffff;border-bottom:none;direction:ltr;font-size:0px;padding:0;text-align:center;vertical-align:top;">--}}
{{--                                        <!--[if mso | IE]>--}}
{{--                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">--}}
{{--                                            <tr>--}}
{{--                                                <td class="" style="vertical-align:top;width:600px;"><![endif]-->--}}
{{--                                        <div class="mj-column-per-100 outlook-group-fix"--}}
{{--                                             style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">--}}
{{--                                            <table border="0" cellpadding="0" cellspacing="0" role="presentation"--}}
{{--                                                   style="vertical-align:top;" width="100%">--}}
{{--                                                <tr>--}}
{{--                                                    <td align="left"--}}
{{--                                                        style="font-size:0px;padding:10px 0;word-break:break-word;">--}}
{{--                                                        <div style="font-family:Roboto, Arial;font-size:13px;line-height: normal;text-align:left;color:#473575;">--}}
{{--                                                            <h2 style="margin: 8px 0px 10px 0px">{{ $reserva->servico->nome }}</h2>--}}
{{--                                                        </div>--}}
{{--                                                    </td>--}}
{{--                                                </tr>--}}
{{--                                            </table>--}}
{{--                                        </div>--}}
{{--                                        <!--[if mso | IE]></td></tr></table><![endif]-->--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                        <!--[if mso | IE]></td></tr></table></td></tr>--}}
{{--                        <tr>--}}
{{--                            <td class="" width="600px">--}}
{{--                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;"--}}
{{--                                       width="600">--}}
{{--                                    <tr>--}}
{{--                                        <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->--}}
{{--                        <div style="background:#ffffff;background-color:#ffffff;Margin:0px auto;max-width:600px;">--}}
{{--                            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"--}}
{{--                                   style="background:#ffffff;background-color:#ffffff;width:100%;">--}}
{{--                                <tbody>--}}
{{--                                <tr>--}}
{{--                                    <td style="border:1px solid #ffffff;border-top:none;direction:ltr;font-size:0px;padding:0;text-align:center;vertical-align:top;">--}}
{{--                                        <!--[if mso | IE]>--}}
{{--                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">--}}
{{--                                            <tr>--}}
{{--                                                <td class="" style="vertical-align:top;width:334.8px;"><![endif]-->--}}
{{--                                        <div class="mj-column-per-60 outlook-group-fix"--}}
{{--                                             style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">--}}
{{--                                            <table border="0" cellpadding="0" cellspacing="0" role="presentation"--}}
{{--                                                   width="100%">--}}
{{--                                                <tbody>--}}
{{--                                                <tr>--}}
{{--                                                    <td style="vertical-align:top;padding-top:0;">--}}
{{--                                                        <table border="0" cellpadding="0" cellspacing="0"--}}
{{--                                                               role="presentation" width="100%">--}}
{{--                                                            <tr>--}}
{{--                                                                <td align="left"--}}
{{--                                                                    style="font-size:0px;padding:10px 0;padding-top:0;word-break:break-word;">--}}
{{--                                                                    <div style="font-family:Roboto, Arial;font-size:16px;line-height:160%;text-align:left;color:#6d6d6d;">--}}
{{--                                                                        <p style="margin: 0px; padding: 0px 0px 2px 0px;">--}}
{{--                                                                            Código da reserva: <strong style="color:#505050;">#{{ $reserva->voucher }}</strong>--}}
{{--                                                                        </p>--}}
{{--                                                                        <p style="margin: 0px; padding: 0px 0px 2px 0px;">--}}
{{--                                                                            Data de utilização: <strong style="color:#505050;">{{ $reserva->agendaDataServico->data->format('d/m/Y') }}</strong>--}}
{{--                                                                        </p>--}}
{{--                                                                        <p style="margin: 0px; padding: 0px 0px 2px 0px;">Quantidade adquirida:</p>--}}
{{--                                                                        <ul style="margin: 5px 0px; list-style: none; padding: 0px;">--}}
{{--                                                                            @foreach($reserva->quantidadeReserva as $quantidade)--}}
{{--                                                                                <li><strong style="color:#505050;">({{ $quantidade->quantidade }}x) {{ $quantidade->variacaoServico->nome }}</strong></li>--}}
{{--                                                                            @endforeach--}}
{{--                                                                        </ul>--}}
{{--                                                                    </div>--}}
{{--                                                                </td>--}}
{{--                                                            </tr>--}}
{{--                                                        </table>--}}
{{--                                                    </td>--}}
{{--                                                </tr>--}}
{{--                                                </tbody>--}}
{{--                                            </table>--}}
{{--                                        </div>--}}
{{--                                        <!--[if mso | IE]></td>--}}
{{--                                        <td class="" style="vertical-align:top;width:223.2px;"><![endif]-->--}}
{{--                                        <div class="mj-column-per-40 outlook-group-fix"--}}
{{--                                             style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">--}}
{{--                                            <table border="0" cellpadding="0" cellspacing="0" role="presentation"--}}
{{--                                                   width="100%">--}}
{{--                                                <tbody>--}}
{{--                                                <tr>--}}
{{--                                                    <td style="vertical-align:top;padding-top:0;">--}}
{{--                                                        <table border="0" cellpadding="0" cellspacing="0"--}}
{{--                                                               role="presentation" width="100%">--}}
{{--                                                            <tr>--}}
{{--                                                                <td align="center"--}}
{{--                                                                    style="font-size:0px;padding:10px 25px;word-break:break-word;">--}}
{{--                                                                    <table border="0" cellpadding="0" cellspacing="0"--}}
{{--                                                                           role="presentation"--}}
{{--                                                                           style="border-collapse:collapse;border-spacing:0px;">--}}
{{--                                                                        <tbody>--}}
{{--                                                                        <tr>--}}
{{--                                                                            <td>--}}
{{--                                                                                <img src="{{ $reserva->servico->fotoPrincipal->foto_large }}" alt="Imagem servico" width="186" border="0" style="display: block; -webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px;" />--}}
{{--                                                                            </td>--}}
{{--                                                                        </tr>--}}
{{--                                                                        </tbody>--}}
{{--                                                                    </table>--}}
{{--                                                                </td>--}}
{{--                                                            </tr>--}}
{{--                                                        </table>--}}
{{--                                                    </td>--}}
{{--                                                </tr>--}}
{{--                                                </tbody>--}}
{{--                                            </table>--}}
{{--                                        </div>--}}
{{--                                        <!--[if mso | IE]></td></tr></table><![endif]-->--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                        <!--[if mso | IE]></td></tr></table></td></tr></table><![endif]-->--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--                </tbody>--}}
{{--            </table>--}}
{{--        </div>--}}
{{--        <!--[if mso | IE]></td></tr></table><![endif]-->--}}

{{--        @include('emails._partials.divider-secondary')--}}

{{--    @endforeach--}}

    <!-- Section --><!--[if mso | IE]>
    <table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
        <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
    <div style="background:#ffffff;background-color:#ffffff;Margin:0px auto;max-width:600px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
               style="background:#ffffff;background-color:#ffffff;width:100%;">
            <tbody>
            <tr>
                <td style="direction:ltr;font-size:0px;padding:0px 0px 20px 0px;padding-bottom:0;text-align:center;vertical-align:top;">
                    <!--[if mso | IE]>
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="" style="vertical-align:top;width:600px;"><![endif]-->
                    <div class="mj-column-per-100 outlook-group-fix"
                         style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                               style="vertical-align:top;" width="100%">
                            <tr>
                                <td align="left"
                                    style="font-size:0px;padding:10px 25px;padding-bottom:0;word-break:break-word;">
                                    <div style="font-family:Roboto, Arial;font-size:13px;line-height:130%;text-align:center;color:#666666;">
                                        Clique no link abaixo para fazer a sua avaliação 👇
                                        <br>
                                        (Leva menos de 3 minutos para avaliar)
                                        <br>
                                        <div style="margin-top: 20px;">
                                            <a href="{{ route('ecommerce.cliente.pedidos.avaliacoes.avaliar-login', ['uuid' => $pedido->PedidoAvaliacaoMailHashLogin->uuid, 'hash' => $pedido->PedidoAvaliacaoMailHashLogin->hash]) }}" style="text-decoration: none; font-family: Roboto, Arial; margin-top: 10px;background-color: #473575;padding: 10px;color: white;font-weight: bold;width: 50%;font-size: 20px;border: none;">AVALIAR AGORA</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!--[if mso | IE]></td></tr></table><![endif]-->
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!--[if mso | IE]></td></tr></table>
    <table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
        <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
    <div style="background:#ffffff;background-color:#ffffff;Margin:0px auto;max-width:600px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
               style="background:#ffffff;background-color:#ffffff;width:100%;">
            <tbody>
            <tr>
                <td style="direction:ltr;font-size:0px;padding:20px 0;padding-top:0;text-align:center;vertical-align:top;">
                    <!--[if mso | IE]>
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="" style="vertical-align:top;width:330px;"><![endif]-->
                    <div class="mj-column-per-55 outlook-group-fix"
                         style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                               style="vertical-align:top;" width="100%">
                            <tr>
                                <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">

                                </td>
                            </tr>
                            <tr>
                                <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">

                                </td>
                            </tr>
                        </table>
                    </div>
                    <!--[if mso | IE]></td>
                    <td class="" style="vertical-align:top;width:270px;"><![endif]-->
                    <div class="mj-column-per-45 outlook-group-fix"
                         style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                               style="vertical-align:top;" width="100%">
                            <tr>
                                <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">

                                </td>
                            </tr>
                            <tr>
                                <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                    Clique no link abaixo para fazer a sua avaliação 👇
                                    (Leva menos de 3 minutos para avaliar)
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!--[if mso | IE]></td></tr></table><![endif]-->
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!--[if mso | IE]></td></tr></table><![endif]-->

@endsection
