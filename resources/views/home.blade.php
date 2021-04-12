<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bopal Daily</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <!-- Styles -->
    </head>
    <body>
        <table width="100%" style="width: 100%;text-align: center;">
            <tr>
                <td style="text-align: center;">
                    <table style="width: 100%;text-align: center;margin-left: auto;margin-right: auto;">
                        <tr>
                            <td>
                                <?php
                                    $img = asset('images/coming_soon.png');
                                    $img = asset('uploads/homepage/BopalDailyHome.png');
                                ?>
                                <a href="https://play.google.com/store/apps/details?id=com.phpdots.bopaldaily">
                                    <img src="{{$img}}" border="2" width="100%" height="100%" class="img-rounded" align="center" />
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
