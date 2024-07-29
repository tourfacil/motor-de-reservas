<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="pt-BR" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - TourFácil</title>
    <style type="text/css">
        .Freshmail html
        {
            width: 100%;
        }

        .Freshmail body {
            background-color: #e1e1e1;
            margin: 0px;
            padding: 0px;
        }

        .Freshmail .ReadMsgBody
        {
            width: 100%;
            background-color: #e1e1e1;
        }
        .Freshmail .ExternalClass
        {
            width: 100%;
            background-color: #e1e1e1;
        }

        .Freshmail a {
            color:#ffc526;
            text-decoration:none;
            font-weight:normal;
            font-style: normal;
        }
        .Freshmail a:hover {
            color:#aaaaaa;
            text-decoration:underline;
            font-weight:normal;
            font-style: normal;
        }

        .Freshmail a.heading-link {
            text-decoration:none;
            font-weight:normal;
            font-style: normal;
        }

        .Freshmail a.heading-link:hover {
            text-decoration:none;
            font-weight:normal;
            font-style: normal;
        }



        .Freshmail p,
        .Freshmail span {
            margin: 0px !important;
        }
        .Freshmail table {
            border-collapse: collapse;
        }


        @media only screen and (max-width: 640px)  {
            body body { width: auto !important; }
            body table td {padding: 0px 20px;}
            body table table{width:100% !important; }
            body table[class="table-wrapper"] {width: 100%  !important; margin: 0px auto !important;}
            body table[class="table-inner"] {width: 100%  !important;  margin: 0px auto !important;}
            body table[class="full"] {width: 100%  !important;  margin: 0px auto !important;}
            body td[class="center"] {text-align: center !important;}

            body img[class="img_scale"] {width: 100% !important; height: auto !Important;}
            body img[class="divider"] {width: 100% !important; height: 1px !Important;}

        }


        @media only screen and (max-width: 479px)  {
            body body { width: auto !important;}
            body table td {padding: 0px 20px;}
            body table table{width:100% !important; }
            body table[class="table-wrapper"] {width: 100% !important; margin: 0px auto !important;}
            body table[class="table-inner"] {width: 100%  !important;  margin: 0px auto !important;}
            body table[class="full"] {width: 100%  !important;  margin: 0px auto !important;}
            body td[class="center"] {text-align: center !important;}

            body img[class="img_scale"] {width: 100% !important; height: auto !Important;}
            body img[class="divider"] {width: 100% !important; height: 1px !Important;}

        }
    </style>
</head>
<body class="Freshmail" style="padding: 0px !important; margin: 0px !important;">
<!-- START OF PRE-HEADER BLOCK-->
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#f8f8f8" class="ds-bg-module" style="padding: 0px; margin: 0px;">
    <tr>
        <td valign="top" align="center">
            <table class="ds-bg-element" width="600" border="0" align="center" cellpadding="0" cellspacing="0">
                <!-- START OF VERTICAL SPACER-->
                <tr>
                    <td class="resize-spacer" height="40" style="padding:0px; line-height: 0px; font-size:0px;">
                        &nbsp;
                    </td>
                </tr>
                <!-- END OF VERTICAL SPACER-->
            </table>
        </td>
    </tr>
</table>
<!-- END OF PRE-HEADER BLOCK-->

@yield('content')

<!-- START OF SOCIAL BLOCK-->
<table data-templateapp="Social Block" width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#f8f8f8" class="ds-bg-module" style="padding: 0px; margin: 0px;">
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
            </table>


        </td>
    </tr>
</table>
<!-- END OF SOCIAL BLOCK-->

<!-- START OF FOOTER BLOCK-->
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#f8f8f8" class="ds-bg-module" style="padding: 0px; margin: 0px;">
    <tr>
        <td valign="top" align="center">
            <table class="ds-bg-element" bgcolor="#777777" width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="border: none;">
                <tr>
                    <td>
                        <table class="table-inner" width="540" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr class="ds-remove">
                                <td valign="top" style="padding: 0px;">
                                    <table class="full" width="540" border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                        <!-- START OF VERTICAL SPACER-->
                                        <tr>
                                            <td class="resize-spacer" height="20" style="padding:0px; line-height: 0px; font-size:0px;">
                                                &nbsp;
                                            </td>
                                        </tr>
                                        <!-- END OF VERTICAL SPACER-->

                                        <tr>
                                            <td class="center" align="center" style="margin: 0px;  font-size:12px ; color:#ededed; font-family: Helvetica, Arial, sans-serif; line-height: 18px;">
                                                <span>Grupo TourFácil {{ date('Y') }}<br> &copy; Todos direitos reservados</span>
                                            </td>
                                        </tr>

                                        <!-- START OF VERTICAL SPACER-->
                                        <tr>
                                            <td class="resize-spacer" height="20" style="padding:0px; line-height: 0px; font-size:0px;">
                                                &nbsp;
                                            </td>
                                        </tr>
                                        <!-- END OF VERTICAL SPACER-->
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
    </tr>

    <!-- START OF VERTICAL SPACER-->
    <tr>
        <td class="resize-spacer" height="40" style="padding:0px; line-height: 0px; font-size:0px;">
            &nbsp;
        </td>
    </tr>
    <!-- END OF VERTICAL SPACER-->
</table>
<!-- END OF FOOTER BLOCK-->
</body>
</html>
