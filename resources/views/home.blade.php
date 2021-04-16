<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bopal Daily</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <!-- Styles -->
        <!-- Facebook Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,'script',
                    'https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', '503099317539790');
                fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=503099317539790&ev=PageView&noscript=1" />
        </noscript>
            <!-- End Facebook Pixel Code -->
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
