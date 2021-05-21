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
                    <li><a href="{{ URL('/') }}">Home</a></li>
                    <li><a href="{{ URL('/') }}#How-it-work">How it Works</a></li>
                    <li><a href="{{ URL('/') }}#about-us">About us</a></li>
                    <li><a href="{{ URL('/') }}#seasonal-products">Seasonal Products</a></li>
                    <li><a href="{{ URL('/') }}#download">Download</a></li>
                </ul>
                </div>
                <div class="col-lg-3 address-footer">
                    <div class="address-inner-dv">
                            <p><span><img src="{{ asset('images/email.png') }}" class="img-fluid" alt=""></span> contact@phpdots.com</p>
                            <p><span><img src="{{ asset('images/phone-call.png') }}" clas="img-fluid" alt=""></span>+91 98250 96687</p>
                    </div>
                </div>
                <div class="col-lg-4 form-blg-dv">
                    {!! Form::open(array('method' => 'post', 'route' => 'front.storecontactus', 'class' => 'footer-form-dv form', 'files'=>true)) !!}
                        <input type="hidden" name="recaptcha" id="recaptcha">
                        @if (session('status'))
                        <div class="alert alert-success statusclass">
                            {{ session('status') }}
                        </div>
                        @endif
                         @if (session('error'))
                        <div class="alert alert-danger statusclass">
                            {{ session('error') }}
                        </div>
                        @endif
                        {{ csrf_field() }}

                        <h5>Contact Us</h5>
                        <div class="group">
                            <input type="text" name="first_name" id="first_name" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label>First name*</label>
                            <span class="text-danger">{{ $errors->first('first_name') }}</span>
                        </div>
                        <div class="group">
                            <input type="text" name="last_name" id="last_name" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label>Last name*</label>
                            <span class="text-danger">{{ $errors->first('last_name') }}</span>

                        </div>
                        <div class="group">
                            <input type="text" name="phone_number" id="phone_number" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label>Phone number*</label>
                            <span class="text-danger">{{ $errors->first('phone_number') }}</span>

                        </div>
                        <div class="group">
                            <input type="email" name="email" id="email" required>
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label>Email*</label>
                            <span class="text-danger">{{ $errors->first('email') }}</span>

                        </div>
                        <div class="btn-contact-form-dv">

                           {!! Form::button('Contact Us   <i class="sake-effact fas fa-arrow-right"></i>', array('class'=>'btn-nav-dv form-btn-dv', 'type'=>'submit')) !!}

                           <!-- <a href="{{ route('front.storecontactus') }}" class="btn-nav-dv form-btn-dv">Contact us -->
                            <!-- <i class="fas fa-arrow-right"></i> -->
                            <!-- </a> -->
                        </div>
                        {!! Form::close() !!}
                    </div>
            </div>
            <div class="col-lg-12 footer-down-heading-dv">
                <span>
                    <p>Copyright @2021, Bopal Daily - All Rights Reserved</p>
                </span>
                <span>
                <p class="right-details-footer-dv">
                    <a href="/privacy-policy">Privacy Policy</a> 
                    |
                    <a href="/terms-of-use">Terms of Use</a></p>
                    </p>
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
    <script>
    $(function(){
        setTimeout(function() {
            $('.statusclass').slideUp();
        }, 5000);
    });
    </script>
</body>

</html>