@extends('emails.base')

@section('title', 'Redefinição de senha')

@section('content')

    @include('emails.partials.logo-email')

    @include('emails.partials.divider', ['height' => 30])

    <!-- START OF CALL TO ACTION-->
    <table data-templateapp="Call To Action" width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#f8f8f8" class="ds-bg-module" style="padding: 0px; margin: 0px;">
        <tr>
            <td valign="top" align="center">
                <table class="ds-bg-element" bgcolor="#ffffff" width="600" border="0" align="center" cellpadding="0" cellspacing="0">
                    <!-- START OF VERTICAL SPACER-->
                    <tr>
                        <td class="resize-spacer" height="40" style="padding:0px; line-height: 0px; font-size:0px;">
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

                                            <!-- START OF HEADING-->
                                            <tr class="ds-remove">
                                                <td class="center" align="center" style="padding-bottom: 0px; font-family: Helvetica, Arial, sans-serif; color:#666666; font-size:24px; line-height:34px; mso-line-height-rule: exactly;">
                                                    <span>Olá {{ $user->name }},</span>
                                                </td>
                                            </tr>
                                            <!-- END OF HEADING-->

                                            <!-- START OF VERTICAL SPACER-->
                                            <tr>
                                                <td class="resize-spacer" height="15" style="padding:0px; line-height: 0px; font-size:0px;">
                                                    &nbsp;
                                                </td>
                                            </tr>
                                            <!-- END OF VERTICAL SPACER-->

                                            <!-- START OF TEXT-->
                                            <tr class="ds-remove">
                                                <td class="center" align="center" style="margin: 0px; font-size:14px ; color:#4b4b4b; font-family: Helvetica, Arial, sans-serif; line-height: 24px;mso-line-height-rule: exactly;">
                                                    <span>
                                                        Recebemos uma solicitação para redefinir sua senha.<br>
                                                        Você pode redefinir sua senha clicando no botão abaixo:
												    </span>
                                                </td>
                                            </tr>
                                            <!-- END OF TEXT-->

                                            <!-- START OF VERTICAL SPACER-->
                                            <tr>
                                                <td class="resize-spacer" height="20" style="padding:0px; line-height: 0px; font-size:0px;">
                                                    &nbsp;
                                                </td>
                                            </tr>
                                            <!-- END OF VERTICAL SPACER-->

                                            <!-- START BUTTON-->
                                            <tr class="ds-remove">
                                                <td align="center" valign="top" style="padding-top: 0px;">
                                                    <table border="0" align="center" cellpadding="0" cellspacing="0" style="margin: 0px;">
                                                        <tr>
                                                            <td align="center" valign="middle" bgcolor="#5d5386" class="ds-bg-element" style="padding: 8px 20px; border-radius: 50px; -webkit-border-radius: 50px; -moz-border-radius: 50px; text-transform: uppercase; font-size:14px; line-height: 24px; font-family:Arial, sans-serif; mso-line-height-rule: exactly;">
                                                                <a href="{{ route('password.reset', $token) }}" target="_blank" style="font-weight: normal; color:#ffffff; text-decoration: none">
                                                                    Redefinir senha
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <!-- END BUTTON-->
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

@endsection
