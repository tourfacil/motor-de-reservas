@extends('emails.base')

@section('title', "Aviso sobre disponibilidade")

@section('content')

    @include('emails.partials.head-email', ['title' => 'Aviso sobre disponibilidade'])

    <!-- START OF CALL TO ACTION-->
    <table  width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#f8f8f8" class="ds-bg-module" style="padding: 0; margin: 0;">
        <tr>
            <td valign="top" align="center">
                <table class="ds-bg-element" bgcolor="#ffffff" width="600" border="0" align="center" cellpadding="0" cellspacing="0">
                    <!-- START OF VERTICAL SPACER-->
                    <tr>
                        <td class="resize-spacer" height="30" style="padding:0; line-height: 0; font-size:0px;">
                            &nbsp;
                        </td>
                    </tr>
                    <!-- END OF VERTICAL SPACER-->

                    <tr>
                        <td>
                            <table class="table-inner" width="540" border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td valign="top" style="padding:0px;">
                                        <table class="full" width="540" border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">

                                            <!-- START OF TEXT-->
                                            <tr class="ds-remove">
                                                <td class="left" align="left" style="margin: 0; font-size:14px ; color:#4b4b4b; font-family: Helvetica, Arial, sans-serif; line-height: 24px;mso-line-height-rule: exactly;">
                                                    <span>
                                                        Os serviços abaixo estão com pouca disponíbilidade para venda:
                                                    </span>
                                                </td>
                                            </tr>
                                            <!-- END OF TEXT-->
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!-- END OF CALL TO ACTION-->

    @foreach($servicos as $servico)
        <!-- START OF CALL TO ACTION-->
        <table  width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#f8f8f8" class="ds-bg-module" style="padding: 0; margin: 0;">
            <tr>
                <td valign="top" align="center">
                    <table class="ds-bg-element" bgcolor="#ffffff" width="600" border="0" align="center" cellpadding="0" cellspacing="0">
                        <!-- START OF VERTICAL SPACER-->
                        <tr>
                            <td class="resize-spacer" height="30" style="padding:0; line-height: 0; font-size:0px;">
                                &nbsp;
                            </td>
                        </tr>
                        <!-- END OF VERTICAL SPACER-->

                        <tr>
                            <td>
                                <table class="table-inner" width="540" border="0" align="center" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="top" style="padding:0px;">
                                            <table class="full" width="540" border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">

                                                <!-- START OF TEXT-->
                                                <tr class="ds-remove">
                                                    <td class="left" align="left" style="margin: 0; font-size:14px ; color:#4b4b4b; font-family: Helvetica, Arial, sans-serif; line-height: 24px;mso-line-height-rule: exactly;">
                                                        <span>
                                                            <a href="{{ route('app.agenda.view', $servico['agenda_id']) }}" title="Ver agenda serviço" style="font-size: 17px; color: #4b4b4b; text-decoration: none;" target="_blank">
                                                                <strong style="font-size: 17px;">{{ $servico['servico'] }}</strong>
                                                            </a>
                                                        </span><br>
                                                        <span>Canal de venda <strong style="color: #0c5460;">{{ $servico['canal_venda'] }}</strong></span><br>
                                                        @if($servico['minimo_disponivel'] == false)
                                                            <span>A agenda vai somente até o dia <strong style="font-size: 15px; color: #0c5460">{{ $servico['ultimo_data'] }}</strong></span><br>
                                                        @endif
                                                        @if(count($servico['disponibilidade_baixa']))
                                                            <span>
                                                                Os dias
                                                                @foreach($servico['disponibilidade_baixa'] as $data)
                                                                    <strong style="font-size: 15px; color: #0c5460">{{ $data['data'] }}@if(!$loop->last), @endif</strong>
                                                                @endforeach
                                                                estão com <strong style="color: #F44336">baixa disponibilidade</strong>
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!-- END OF CALL TO ACTION-->

        @if(!$loop->last)
            @include('emails.partials.divider', ['height' => 30])
        @endif
    @endforeach

@endsection
