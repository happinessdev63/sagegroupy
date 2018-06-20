<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width">
        <title>Welcome to Sagegroupy</title>
        <link href="/css/emails.css" rel="stylesheet" type="text/css">
        @section('styles')
        @show
    </head>

    <body>
        <!-- <style> -->
        <table class="body" data-made-with-foundation="">
            <tr>
                <td class="float-center" align="center" valign="top">
                    <center data-parsed="">
                        <table class="spacer float-center">
                            <tbody>
                                <tr>
                                    <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                                </tr>
                            </tbody>
                        </table>
<!--
                        <table align="center" class="container header float-center">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row collapse">
                                            <tbody>
                                                <tr>
                                                    <th class="small-12 large-12 columns first last">
                                                        <table>
                                                            <tr>
                                                                <th><img src="http://placehold.it/150x30/663399" alt=""></th>
                                                                <th class="expander"></th>
                                                            </tr>
                                                        </table>
                                                    </th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
 -->
                        <table align="center" class="container body-drip float-center">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="spacer">
                                            <tbody>
                                                <tr>
                                                    <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table class="row">
                                            <tbody>
                                                <tr>
                                                    <th class="small-12 large-12 columns first last">
                                                        <table>
                                                            <tr>
                                                                <th>
                                                                    @section('header')

                                                                     @show
                                                                </th>
                                                                <th class="expander"></th>
                                                            </tr>
                                                        </table>
                                                    </th>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <hr>
                                        <table class="row">
                                            <tbody>
                                                <tr>
                                                    <th class="small-12 large-12 columns first last">
                                                        <table>
                                                            <tr>
                                                                <th>
                                                                    @section('content')

                                                                    @show

                                                                    @section('button')

                                                                    @show
                                                                </th>
                                                                <th class="expander"></th>
                                                            </tr>
                                                        </table>
                                                    </th>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table class="row collapsed footer">
                                            <tbody>
                                                <tr>
                                                    <th class="small-12 large-12 columns first last">
                                                        <table>
                                                            <tr>
                                                                <th>
                                                                    <table class="spacer">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    @section('footer')
                                                                    <p class="text-center">Sagegroupy<br>
                                                                        <a href="#">support@sagegroupy.com</a>
                                                                     @show
                                                                </th>
                                                                <th class="expander"></th>
                                                            </tr>
                                                        </table>
                                                    </th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </center>
                </td>
            </tr>
        </table>
    </body>

</html>
