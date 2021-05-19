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

</head>
<body>
<div class="navigation-wrap start-header start-style">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg navbar-light header-main-blg-dv">
                    <a class="navbar-brand" href="https://front.codes/" target="_blank"><img
                            src="{{ asset('images/logo.png') }}" alt=""></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto py-4 py-md-0 header-ul-dv">
                            <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4 active" >
                                   <a class="nav-link" data-hash="#home" href="#home">Home</a>
                            </li>
                            <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4" >
                                <a class="nav-link" data-hash="#about" href="#How-it-work" >How it Works</a>
                            </li>
                            <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4" >
                                <a class="nav-link" href="#about-us" >About Us</a>
                            </li>
                            <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4" >
                                <a class="nav-link" href="#seasonal-products">Seasonal Products</a>
                               
                            </li>
                            <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4" >
                                <a class="nav-link" href="#download">Download</a>
                            </li>
                            <li class=" pl-4 pl-md-0 ml-0 ml-md-4 nav-btn-dv">
                            <a href="#contact-us" class="btn-nav-dv  ">Contact Us
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
<section class="frist-blg-dv " id="home">
    <div class="container">
        <div class="row mt20">
            <div class="col-lg-5 col-md-9 col-sm-10 left-banner-details">
                <div class="banner-inner-detail">
                    <h1>BEST WAY TO </h1>
                    <h2>Make your shopping easy with this App</h2>
                    <p>Bopal Daily is a grocery delivery app. Get great deals on fresh vegetables, fruits, dairy products and seasonal products with economical Price.</p>
                    <div class="btn-banner-dv">
                      <a href="https://play.google.com/store/apps/details?id=com.phpdots.bopaldaily">
                        <img src="{{ asset('images/banner-link.jpg') }}" class="img-fluid" alt="">
                      </a>
                    </div>
                    <div class="icon-banner-media-dv">
                    <p>Follow us on</p>
                    <ul class="icon-media-banner">
                        <li>   
                            <a href=" https://www.facebook.com/Bopal-Daily-106560664871241/">
                                <img src="{{ asset('images/facebook-banner-icon.png') }}" class="img-fluid" alt="icon">
                            </a>
                        </li>
                    </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-md-7 col-sm-10 right-banner-img">
                <img src="{{ asset('images/banner-img.png') }}" class="img-fluid" alt="">
            </div>
        </div>
    </div>
</section>
<section class="secand-section-dv" id="How-it-work">
    <div class="container ">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-6 col-md-7 col-sm-8 title-secand-heading-dv">
                <h2>How it Works</h2>
                <p>Bopal Daily App is user friendly and easy to use app, that will make your shopping easier</p>
            </div>
        </div>
        <div class="row services-row-dv">
            <div class="col-lg-4 col-md-12 service-box-dv">
                <div class="box-dv ">
                    <img src="{{ asset('images/services-01.png') }}" class="img-fluid" alt="">
                    <h6>Choose products</h6>
                    <p>Add items to your shopping basket</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 service-box-dv">
                <div class="secand-no">
                    <img src="{{ asset('images/service-02.png') }}" class="img-fluid" alt="">
                    <h6>Choose time slot</h6>
                    <p>Choose a convenient date and delivery time </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 service-box-dv ">
                <div class=" thard-no">
                    <img src="{{ asset('images/services-03.png') }}" class="img-fluid" alt="">
                    <h6>Get your order</h6>
                    <p>Your products will be home-delivered as per your order.</p>
                </div>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-lg-4 btn-services-dv">
                <a href="https://play.google.com/store/apps/details?id=com.phpdots.bopaldaily">
                  <img src="{{ asset('images/banner-link.jpg') }}" class="img-fluid" alt="">
                </a>
            </div>
        </div>
    </div>
</section>
<section class="thard-section-dv" id="about-us">
    <div class="container">
        <div class="row ">
            <div class="col-lg-7 col-md-12 img-blg-dv ">
                <img src="{{ asset('images/about-us-img.png') }}" class="img-fluid" alt="">

            </div>
            <div class="col-lg-5 col-md-9 col-sm-9 right-details-dv d-flex align-items-center">
            <div class="about-inner-details-dv">
                <h2>About Us</h2>
                <p>
                  Bopal Daily is the best app to buy your daily necessities products online near you. We are delivering product in Bopal, Ghuma and Shela area of Ahmedabad.
                </p>
                <p>
                  Wide range of products, including fresh fruits and vegetables. Enjoy assured low prices with great offers including discounts, bundle pack offerings and promotions. We take pride in assured quality and timely delivery.
                </p>
                <a href="#" class="btn-nav-dv  ">Contact us
                   <i class="fas fa-arrow-right"></i><span class="circle"></span></a>
            </div>
            </div>
            <img src="{{ asset('images/all veg.png') }}" class="img-fluid veg-img-dv" alt="">
        </div>
    </div>
</section>
<section class="seasonal-products-dv"  id="seasonal-products">
    <div class="container">
       <div class="main-dv"> 
        <div class="row">
            <div class="col-lg-5 col-md-12 col-sm-12 col-12 seasonal-products-img-dv">
                <img src="{{ asset('images/Ellipse 135.png') }}" class="shap-top-dv" alt="">
                <img src="{{ asset('images/Seasonal Product iPhone X.png') }}" class="mobile-device" alt="">
                <img src="{{ asset('images/round-pattrn.png') }}" class="shap-bottom-dv" alt="">
            </div>
            <div class="col-lg-7 col-md-12 col-sm-12 col-12 seasonal-products-details-dv">
                <div class="product-inner-details-dv">
                    <img src="{{ asset('images/green-whaite.png') }}" class="vector-box-dv" alt="">
                    <div class="heading-product-title">
                        <span class="orange-bg-dv">New</span>
                        <p>Seasonal products are now available on our app</p>
                        <span class="smile-blg-dv"><img src="{{ asset('images/smile.png') }}" alt=""></span>
                    </div>
                    <div class="inner-product-details-dv">
                        <h2>Seasonal Products</h2>
                        <p>
                          We are selling all seasonal products like whet (Lokwan, Tukdi, Bhalia etc.), mangoes (Gir Kesar, Organic Kesar, Hafus etc.)
                        </p>
                    </div>
                    <div class="btn-product-blg">
                        <div class="inner-btn d-flex">
                           <ul class="product-btn-ul">
                               <li>
                                   <a href="#" class="btn-nav-dv product-btn  ">Contact us
                                    <i class="fas fa-arrow-right"></i>
                                  </a>
                              </li>
                               <li><p>OR</p></li>
                               <li>
                                   <a href="https://play.google.com/store/apps/details?id=com.phpdots.bopaldaily">
                                    <img src="{{ asset('images/Google-pay.png') }}" class="img-fluid google-product" alt="">
                                  </a>
                                </li>
                           </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       </div>
    </div>
</section>
<section class="download-section-dv" id="download"> 
    <div class="container">
        <div class="row main-download-inner-blg">
            <div class="col-xl-6 col-lg-6  col-sm-9 down-left-details">
                <div class="inner-details-download">
                    <img src="{{ asset('images/download-shap-top.png') }}" class="top-left-shap" alt="">
                    <h2>Download the App</h2>
                    <h5><i>Get groceries at one store</i></h5>
                    <p>
                      Discover new products and shop for all your food and grocery needs from the comfort of your home or office. No more getting stuck in traffic jams, paying for parking, standing in long queues and carrying heavy bags – get everything you need, when you need, right at your doorstep.
                    </p>
                    <div class="services-main-dv">
                        <div class="col-lg-6 col-md-6 same-service-download">
                            <img src="{{ asset('images/credit-card.png') }}" class="img-fluid" alt="">
                            <h6>Easy Payment</h6>
                        </div>
                        <div class="col-lg-6 same-service-download">
                            <img src="{{ asset('images/XMLID_561_.png') }}" class="img-fluid" alt="">
                            <h6>Rewards In Wallet</h6>
                        </div>
                        <div class="col-lg-6 same-service-download">
                            <img src="{{ asset('images/juice-box.png') }}" class="img-fluid" alt="">
                            <h6>100% Fresh Food</h6>
                        </div>
                        <div class="col-lg-6 same-service-download">
                            <img src="{{ asset('images/best-price.png') }}" class="img-fluid" alt="">
                            <h6>Best Price From Market</h6>
                        </div>
                    </div>
                    <div class="download-section-btn">
                        <a href="https://play.google.com/store/apps/details?id=com.phpdots.bopaldaily">
                          <img src="{{ asset('images/Google-pay.png') }}" class="img-fluid" alt="">
                        </a>
                    </div>
                </div>
            </div>
            <div class=" col-xl-6 col-lg-6 col-md-7 col-sm-8 col-9 down-right-img-dv">
                <img src="{{ asset('images/Ellipse 160.png') }}" class="top-vector-one" alt="">
                <img src="{{ asset('images/download-product.png') }}" class="download-img-one" alt="">
                <img src="{{ asset('images/Ellipse 193.png') }}" class="bottom-left-vector" alt="">
                <img src="{{ asset('images/Union 7.png') }}" class="bottom-right-vector" alt="">
            </div>
        </div>
    </div>
</section>
<!-- footer-section-start -->
<section class="footer-section-dv"  id="contact-us">
    <div class="main-bg-blg-dv">
        <div class="container">
            <div class="footer-main">
              
                <div class="col-lg-3  logo-footer">
                    <img src="{{ asset('images/logo.png') }}" class="img-fluid" alt="">
                    <p>We Deliver Fresh and
                    Healthy Products</p>
                </div>
                <div class="col-lg-2  link-footer">
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">How it Works</a></li>
                    <li><a href="#">About us</a></li>
                    <li><a href="#">Seasonal Products</a></li>
                    <li><a href="#">Download</a></li>
                </ul>
                </div>
                <div class="col-lg-3 address-footer">
                    <div class="address-inner-dv">
                            <p><span><img src="{{ asset('images/email.png') }}" class="img-fluid" alt=""></span> contact@phpdots.com</p>
                            <p><span><img src="{{ asset('images/phone-call.png') }}" clas="img-fluid" alt=""></span>+91 98250 96687</p>
                    </div>
                </div>
                <div class="col-lg-4 form-blg-dv">
                <form action="" class="footer-form-dv">
                    <h5>Contact Us</h5>
                    <div class="group">
                        <input type="text" required>
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label>First name*</label>
                    </div>
                    <div class="group">
                        <input type="text" required>
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label>Last name*</label>
                    </div>
                    <div class="group">
                        <input type="text" required>
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label>Phone number*</label>
                    </div>
                    <div class="group">
                        <input type="text" required>
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label>Email*</label>
                    </div>
                    <div class="btn-contact-form-dv">
                        <a href="#" class="btn-nav-dv form-btn-dv">Contact us
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </form>
                </div>
               
            </div>
            <div class="col-lg-12 footer-down-heading-dv">
                <span>
                    <p>Copyright @2021, Bopal Daily - All Rights Reserved</p>
                </span>
                <span>
                    <p>Privacy Policy | Terms of Use</p>
                </span>
            </div>
        </div>
    </div>
</section>
    <script src="{{ asset('js/front/jquery.min.js') }}"></script>
    <script src="{{ asset('js/front/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/front/popper.min.js') }}"></script>
    <script>
         /* Please ❤ this if you like it! */
         

            (function ($) {
                  $('.navbar-toggler').click(function(){
                
                   $('body').toggleClass('menu-open');
                });
                // var $root = jQuery('html, body');

                // jQuery('a[href^="#"]').click(function () {
                //     var href = jQuery.attr(this, 'href');

                //     $root.animate({
                //         scrollTop: jQuery(href).offset().top
                //     }, 1500, function () {
                //         window.location.hash = href;
                //     });

                //     return false;
                // });
                
                "use strict";
                 $(function () {
                    var header = $(".start-style");
                    $(window).scroll(function () {
                        var scroll = $(window).scrollTop();

                        if (scroll >= 10) {
                            header.removeClass('start-style').addClass("scroll-on");
                        } else {
                            header.removeClass("scroll-on").addClass('start-style');
                        }
                    });
                });
               

                //Animation

                $(document).ready(function () {
                    $('body.hero-anime').removeClass('hero-anime');
                });

                //Menu On Hover

                $('body').on('mouseenter mouseleave', '.nav-item', function (e) {
                    if ($(window).width() > 750) {
                        var _d = $(e.target).closest('.nav-item'); _d.addClass('show');
                        setTimeout(function () {
                            _d[_d.is(':hover') ? 'addClass' : 'removeClass']('show');
                        }, 1);
                    }
                });

               

            })(jQuery);
    </script>
</body>

</html>