@extends('emails.base')

@section('title', "Teste")

@section('content')

    @include('emails.partials.head-email', ['title' => 'E-mail teste'])

    <!-- START OF CALL TO ACTION-->
    <table data-templateapp="Call To Action" width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#f8f8f8" class="ds-bg-module" style="padding: 0; margin: 0;">
        <tr>
            <td valign="top" align="center">
                <table class="ds-bg-element" bgcolor="#ffffff" width="600" border="0" align="center" cellpadding="0" cellspacing="0">
                    <!-- START OF VERTICAL SPACER-->
                    <tr>
                        <td class="resize-spacer" height="40" style="padding:0; line-height: 0; font-size:0px;">
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
                                                <td class="center" align="center" style="padding-bottom: 0; text-transform: uppercase; font-family: Lucida Sans Unicode; color:#666666; font-size:24px; line-height:34px; mso-line-height-rule: exactly;">
                                                    <span>
												LOOKING FOR MORE AWESOME DEALS?
												</span>
                                                </td>
                                            </tr>
                                            <!-- END OF HEADING-->

                                            <!-- START OF VERTICAL SPACER-->
                                            <tr>
                                                <td class="resize-spacer" height="10" style="padding:0; line-height: 0; font-size:0px;">
                                                    &nbsp;
                                                </td>
                                            </tr>
                                            <!-- END OF VERTICAL SPACER-->

                                            <!-- START OF TEXT-->
                                            <tr class="ds-remove">
                                                <td class="center" align="center" style="margin: 0; font-size:14px ; color:#aaaaaa; font-family: Helvetica, Arial, sans-serif; line-height: 24px;mso-line-height-rule: exactly;">
                                                    <span>
												Lorem ipsum dolor sit amet, adipicing elit. Nulla fringilla auctor sem, sit amet ornare ac blandit consequat. Nec in nemore apeirian repudiandae maecenas mollis.
												</span>
                                                </td>
                                            </tr>
                                            <!-- END OF TEXT-->

                                            <!-- START OF VERTICAL SPACER-->
                                            <tr>
                                                <td class="resize-spacer" height="15" style="padding:0; line-height: 0; font-size:0px;">
                                                    &nbsp;
                                                </td>
                                            </tr>
                                            <!-- END OF VERTICAL SPACER-->

                                            <!-- START BUTTON-->
                                            <tr class="ds-remove">
                                                <td align="center" valign="top" style="padding-top: 0;">
                                                    <table border="0" align="center" cellpadding="0" cellspacing="0" style="margin: 0;">
                                                        <tr>
                                                            <td align="center" valign="middle" bgcolor="#ffc526" class="ds-bg-element" style="padding: 5px 20px; text-transform: uppercase; font-size:14px; line-height: 24px; font-family:Arial, sans-serif; mso-line-height-rule: exactly;">
                                                                <a href="#" style="font-weight: normal; color:#ffffff; ">
                                                                        check it out
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
