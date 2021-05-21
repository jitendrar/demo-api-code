<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>Bopal Daily</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="{{ asset('css/front/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/front/style.css') }}">
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
            fbq('init', '448043992931917');
            fbq('track', 'PageView');
      </script>
      <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=448043992931917&ev=PageView&noscript=1" /></noscript>
    <!-- End Facebook Pixel Code -->

      <!-- Start Google Recaptcha Code -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.sitekey') }}"></script>
    <script>
             // grecaptcha.ready(function() {
             //     grecaptcha.execute('{{ config('services.recaptcha.sitekey') }}', {action: 'contact'}).then(function(token) {
             //        if (token) {
             //          document.getElementById('recaptcha').value = token;
             //        }
             //     });
             // });
    </script>
<!-- End Google Recaptcha Code -->
    
    <style type="text/css">
        .text-danger{
            color: #000000;
        }
    </style>

</head>
<body class="bg-color-dv">
<div class="navigation-wrap start-header start-style">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg navbar-light header-main-blg-dv">
                    <a class="navbar-brand" href="https://front.codes/" target="_blank"><img
                            src="images/logo.png" alt=""></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto py-4 py-md-0 header-ul-dv">
                            <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4 active" >
                                <a class="nav-link" data-hash="{{ URL('/') }}#home" href="#home">Home</a>

                            </li>
                            <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4" >
                                <a class="nav-link" data-hash="#about" href="{{ URL('/') }}#How-it-work" >How it Works</a>
                            </li>
                            <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4" >
                                <a class="nav-link" href="{{ URL('/') }}#about-us" >Abou us</a>
                            </li>
                            <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4" >
                                <a class="nav-link" href="{{ URL('/') }}#seasonal-products">Seasonal Products</a>

                            </li>
                            <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4" >
                                <a class="nav-link" href="{{ URL('/') }}#download">Download</a>
                            </li>
                            <li class=" pl-4 pl-md-0 ml-0 ml-md-4 nav-btn-dv">
                            <a href="#contact-us" class="btn-nav-dv  ">Contact us
                                 <i class="fas fa-arrow-right"></i>
                            </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
