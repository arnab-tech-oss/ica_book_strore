<!-- Footer Start -->

@php
    $settings = config('app.setting');
@endphp
<!-- Footer Start -->
<footer class="footer primary-bg">
    <div class="container">
        <div class="footer_main">
            <div class="row">
                <div class="col-xl-5 col-xxl-5">
                    <div class="footer_main-block pe-lg-5">
                        @if(isset($settings['company_logo']) && !empty($settings['company_logo']))
                            <a href="/"><img class="brand_logo"
                                             src="{{ url($settings['company_logo']) }}" alt="ICC"></a>
                        @else
                            <a href="#"><img class="brand_logo" src="{{ asset('/img/logo.svg') }}" alt="ICC"></a>
                        @endif
                        @if(isset($settings['company_intro']) && !empty($settings['company_intro']))
                            <p class="footer_main-block_subtitle footer_main-block_subtitle--brand" style="text-align: justify;"><small>{{ $settings['company_intro'] }}</small></p>
                        @endif
                    </div>
                </div>
                <div class="col-xl-4 col-xxl-4">
                    <div class="footer_main-block">
                        <h4 class="footer_main-block_title">Company</h4>
                        <ul class="footer_main-block_nav">
                            <li class="list-item"><a class="nav-link link nav-move" href="#" id="head_about"><i
                                        class="fas fa-chevron-right icon"></i> About</a></li>
                            <li class="list-item"><a class="nav-link link nav-move" href="#" id="head_services"><i
                                        class="fas fa-chevron-right icon"></i> Services</a>
                            </li>
                            <li class="list-item"><a class="nav-link link nav-move" href="#" id="head_case_study"><i
                                        class="fas fa-chevron-right icon"></i> Case Study</a>
                            </li>
                            <li class="list-item"><a class="nav-link link nav-move" href="#" id="head_testimonial"><i
                                        class="fas fa-chevron-right icon"></i> Testimonial</a></li>
                            <li class="list-item"><a class="nav-link link nav-move" href="#" id="head_case_study"><i
                                        class="fas fa-chevron-right icon"></i> Case Study</a>
                            </li>
                            <li class="list-item"><a class="nav-link link nav-move" href="#" id="head_testimonial"><i
                                        class="fas fa-chevron-right icon"></i> Testimonial</a></li>

                        </ul>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-3">
                    <div class="footer_main-block">
                        <!--                        <h4 class="footer_main-block_title">Subscribe to Our News</h4>-->
                        <div class="footer-widget register-widget">
                            <div class="inner-box">
                                <div class="upper">
                                    <div class="icon-box"><i class="fas fa-headset"></i></div>
                                    <h4>Connect <br> With us</h4>
                                </div>
                                <p>Connect with us if you have any queries.</p>
                                @if(isset($settings['company_email']) && !empty($settings['company_email']))
                                    <a href="mailto:{{ $settings['company_email'] }}"><i class="far fa-paper-plane d-none d-xxl-inline-block"></i>
                                    Email us</a>
                                @endif
                                <a href="javascript:void(Tawk_API.toggle())                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             "><i class="far fa-comment d-none d-xxl-inline-block"></i> Chat With us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer_secondary">
            <p class="footer_secondary-copyright">
                @if(isset($settings['billing_footer']) && !empty(isset($settings['billing_footer'])))
                    <span>{{ $settings['billing_footer'] }}</span>
                @else
                    <span>Copyright © 2022 <a class="border-bottom" href="/">Indian Chamber of Commerce.</a> AllRight Reserved.</span>
                @endif
            </p>
            <ul class="socials">
                <li class="socials_item"><a class="socials_item-link" href="https://www.facebook.com/IndianChamber" target="_blank"
                                            aria-label="Facebook"><i class="fab fa-facebook-square"></i></a></li>
                <li class="socials_item"><a class="socials_item-link" href="https://twitter.com/ICC_Chamber" target="_blank"
                                            aria-label="Instagram"><i class="fab fa-twitter"></i></a></li>
                <li class="socials_item"><a class="socials_item-link" href="https://www.linkedin.com/company/indian-chamber-of-commerce-kolkata" target="_blank"
                                            aria-label="Twitter"><i class="fab fa-linkedin"></i></a></li>
                <li class="socials_item"><a class="socials_item-link" href="https://www.instagram.com/indianchamber/" target="_blank"
                                            aria-label="Twitter"><i class="fab fa-instagram"></i></a></li>
                <li class="socials_item"><a class="socials_item-link" href="https://www.youtube.com/c/IndianChamberofCommerce" target="_blank"
                                            aria-label="WhatsApp"><i class="fab fa-youtube"></i></a></li>
            </ul>
        </div>
    </div>
</footer>
<!-- Footer End -->
</div>
