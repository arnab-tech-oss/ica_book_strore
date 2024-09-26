@extends('welcome')
@section('content')
    @if(\Session::has('message'))
        <div class="d-flex justify-content-center">
            <div id="snackbar"
                 class="show {{ \Session::get('alert-class', 'alert-info') }}">{{ \Session::get('message') }}</div>
        </div>
    @endif
    @if(isset($homePage) && !empty($homePage))
        <section class="main-banner">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="main-banner-content sec-title">
                                    <span class="subtitle subtitle--extended text-white"
                                          data-wow-delay="0.1s">{{ $homePage->title }}</span>
                                    <div class="lead text-white my-4 wow fadeInLeft"
                                         data-wow-delay="0.5s">{!! $homePage->content !!}</div>
                                    <a class="theme-btn btn-dark" href="#contact">Consult now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- About Start -->
    @if(isset($about) && !empty($about))
        <section class="sec-pad" id="about-area" style="background-image: url('img/abt-bg.png')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 align-self-center">
                        <img src="{{ asset('img/abt-feat.png') }}" class="img-fluid feat-img" alt="">
                        <h3 class="mt-5 mb-0 text-center">Years In The Industry</h3>
                    </div>
                    <div class="col-lg-6 offset-lg-1">
                        <div class="sec-title wow fadeInUp" data-wow-delay="0.1s">
                            <span class="subtitle">About Us</span>
                            <h1><span class="highlight">{{ $about->title }}</h1>
                        </div>
                        <div class="about-content custom-scroll">{!! $about->content !!}</div>
                    </div>
                </div>
                </div>
            </div>
        </section>
    @endif
    <!-- About End -->

    <!-- Advanced Feature Start -->
    @if(isset($services) && !empty($services) && count($services) > 0)
        <section class="sec-pad" id="services">
            <div class="container">
                <div class="sec-title wow fadeInUp" data-wow-delay="0.1s">
                    <span class="subtitle">Services</span>
                    <h1>ICC Trade Desk Cater These <span class="highlight">Services</span></h1>

                </div>
                <div class="row">
                    @foreach($services as $key => $service)
                        <div class="col-lg-4 col-md-6 col-xl-3">
                            <div class="feature-block-one">
                                <div class="inner-box">
                                    <figure class="image">
                                        @php
                                            $allowedImgMimeTypes = ['image/jpeg','image/gif','image/png'];
                                        @endphp
                                        {{-- @if(count($service->banner) > 0) --}}
                                            {{-- @foreach($service->banner as $attachment) --}}
                                                {{-- @php
                                                    $path = public_path(str_replace("http://localhost/public/","/invoices/",$attachment->getUrl()));
                                                    $isExists = File::exists($path);
                                                @endphp --}}
                                                {{-- @if(in_array($attachment->mime_type,$allowedImgMimeTypes) && $isExists)
                                                    <div class="col">
                                                        <img class="serviceImg"
                                                             src="{{asset('images/product/' . $service->banner)}}">
                                                    </div>
                                                @else
                                                    <div class="col">
                                                        <img class="serviceImg" src="{{ asset('img/serv-01.jpg') }}"
                                                             alt="">
                                                    </div>
                                                @endif
                                                @break --}}
                                            {{-- @endforeach --}}
                                        {{-- @else --}}
                                            <div class="col">
                                                <img src="{{asset('images/product/' . $service->banner)}}" alt="">
                                            </div>
                                        {{-- @endif --}}
                                    </figure>
                                    <div class="text">
                                        <img src="{{ asset('img/serv-icon.png') }}" class="img-fluid">
                                        <h4 class="mb-3">{{ $service->name }}</h4>
                                        @if(!empty(\Illuminate\Support\Facades\Auth::user()))
                                            <a class="theme-btn btn-sm bg-primary"
                                               href="{{ route('admin.tickets.create') }}"><i
                                                    class="fa fa-shopping-cart me-2"></i> Add To Cart</a>
                                         @else
                                            <a class="theme-btn btn-sm bg-primary"
                                               href="{{ route('verifyUser') }}"><i
                                                    class="fa fa-shopping-cart me-2"></i> Add To Cart</a>
                                        @endif
                                    </div>
                                    <a href="{{ route('services.show',str_replace(" ","-",str_replace("/","--",strtolower($service->name)))) }}">
                                        <div class="overlay-content">
                                            <img src="{{ asset('img/serv-icon.png') }}" class="img-fluid mb-3">
                                            <h4 class="mb-3">{{ $service->name }}</h4>
                                            @if(!empty(\Illuminate\Support\Facades\Auth::user()))
                                                <a class="theme-btn btn-sm btn-dark"
                                                    href="{{ route('admin.tickets.createWithService',$service->id) }}"><i
                                                    class="fa fa-shopping-cart me-2"></i> Add To Cart </a>
                                            @else
                                                <a class="theme-btn btn-sm btn-dark"
                                                    href="{{ route('verifyUser',encrypt($service->id)) }}"><i
                                                    class="fa fa-shopping-cart me-2"></i> Add To Cart </a>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!-- Advanced Feature End -->


    @if(isset($whyChooseUs) && !empty($whyChooseUs) && count($whyChooseUs) > 0)
        <section class="sec-pad" id="wcu-area">
            <div class="container">
                <div class="sec-title wow fadeInUp" data-wow-delay="0.1s">
                    <span class="subtitle">why choose us</span>
                    <h1>What Makes Us <span class="highlight">Different</span></h1>
                </div>
                <div class="row px-3">
                    @foreach($whyChooseUs as $key => $whyChooseUsValue)
                        <div class="col-lg-4 px-0">
                            <div class="ts-feature-box">
                                <div class="ts-feature">
                                    <div class="ts-feature-info">
                                        <div class="feature-icon">
                                            <img style="width: 66px;height: 66px;"
                                                 src="{{ asset('img/'.$whyChooseUsValue->icon) }}"
                                                 alt="Building Staffs">
                                        </div>
                                        <span class="feature-number">{{ $key+1 }}</span>
                                        <h3 class="ts-title">{{ $whyChooseUsValue->title }}</h3>
                                        <p>
                                            {{ $whyChooseUsValue->description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

<!--
    @if(isset($serviceCategories) && !empty($serviceCategories) && count($serviceCategories) > 0)
        <section class="sec-pad" id="service_category">
            <div class="container">
                <div class="sec-title wow fadeInUp" data-wow-delay="0.1s">
                    <span class="subtitle">Services</span>
                    <h1>Service <span class="highlight">Categories</span></h1>
                </div>
                <div class="row">
                    @foreach($serviceCategories as $key => $serviceCategory)
                        <div class="col-lg-4">
                            <div class="service-cat mb-5 position-relative">
                                <div class="ts-service-box position-relative">
                                    <div class="ts-service-box-img float-start">
                                        @if(!empty($serviceCategory->icon))
                                            <img style="width: 62px;height: 62px;"
                                                 src="{{ asset('img/'.$serviceCategory->icon) }}">
                                        @else
                                            <img src="{{ asset('img/service-cat01.png') }}">
                                        @endif
                                    </div>
                                    <div class="ts-service-box-info text-capitalize">
                                        <h3 class="ts-title">{{ $serviceCategory->name }}</h3>
                                        {{--                                <p>Benefit of the socie where we oper ate success for the website</p>--}}
                                    </div>
                                </div> Service 2 end
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
-->


    @if(isset($caseStudies) && !empty($caseStudies) && count($caseStudies) > 0)
        <section class="sec-pad" id="case_study">
            <div class="container">
                <div class="sec-title wow fadeInUp" data-wow-delay="0.1s">
                    <span class="subtitle">case study</span>
                    <h1>Our <span class="highlight">Case Studies</span></h1>
                </div>
                <div class="case-stud-carousel owl-carousel owl-theme">
                    @foreach($caseStudies as $caseStudy)
                        <div class="ce-portfolio-item position-relative">
                            <div class="inner-box position-relative">
                                <div class="image">
                                    <img src="{{ asset('images/'.$caseStudy->banner_image) }}" class="img-fluid" alt="">
                                    <div class="overlay-box">
                                        <div class="overlay-inner">
                                            <div class="portfolio-categories">
                                                <a href="#" rel="tag">case study</a>
                                            </div>
                                            <h4 class="text-white">{{ $caseStudy->title }}</h4>
                                            <a class="link" href="{{ route('caseStudies.learnMore',$caseStudy->id) }}">
                                                Learn More <i class="fas fa-long-arrow-alt-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Testimonial Start -->
    @if(isset($testimonials) && !empty($testimonials) && count($testimonials) > 0)
        <section class="sec-pad position-relative" id="testimonial">
            <div class="texture" style="background-image: url('{{ asset('img/caseStudies-texture.png') }}');"></div>

            <div class="container">

                <div class="row">
                    <div class="col-lg-4 align-self-center">
                        <div class="sec-title wow fadeInUp mb-lg-0" data-wow-delay="0.1s">
                            <img width="86" src="img/quote.png" class="img-fluid mb-4 d-block">
                            <span class="subtitle color-2 text-white">testimonials</span>
                            @if(isset($testimonialPage) && !empty($testimonialPage))
                                <h1 class="text-white"><span class="highlight-2">{{ $testimonialPage->title }}</h1>
                                <div class="text-white">{!! $testimonialPage->content !!}</div>
                            @else
                                <h1 class="text-white"><span class="highlight-2">Client's</span> Talk</h1>
                                <p class=" text-white">We have a wealth of experience working as main building
                                    contractors
                                    on
                                    all kinds of projects, big and small, from home maintenance and improvements.</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-7 offset-lg-1">
                        <div class="testimonial-carousel owl-carousel owl-theme">
                            @foreach($testimonials as $key => $testimonial)
                                <div class="testimonial-item">
                                    <div class="content">
                                        <div class="text">
                                            <p>{{ $testimonial->testimonial }}</p></div>
                                        <div class="data">
                                            <div class="author-image">
                                                <img src="{{ asset('images/'. $testimonial->image) }}"
                                                     class="img-fluid" alt=""></div>
                                            <div class="author-data">
                                                <div class="title">
                                                    <h4>{{ $testimonial->name }}</h4>
                                                    <h5>{{ $testimonial->company_name }}
                                                        | {{ $testimonial->designation }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- Testimonial End -->

    <!-- partners start -->

    @if(isset($partners) && !empty($partners) && count($partners) > 0)
        <section class="sec-pad" id="partners">
            <div class="container">
                <div class="sec-title wow fadeInUp" data-wow-delay="0.1s">
                    <span class="subtitle">partners</span>
                    <h1>Our <span class="highlight">Partners</span></h1>
                </div>

                <section class="customer-logos slider">
                    @foreach($partners as $key => $partner)
                        <div class="slide text-center ">
                            <img src="{{ asset('img/'.$partner->company_logo) }}">
<!--                            <span class="subtitle">{{ $partner->company_name }}</span>-->
                        </div>
                    @endforeach
                </section>
            </div>
        </section>
    @endif

    <section class="sec-pad" id="cta-area">

        <div class="container">

            <div class="cta-cont">

                <div class="row">

                    <div class="col-xl-8">
                        <h2 class="text-white mb-2">Get An Quotation For Your Business</h2>
                        <p class="text-white mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting
                            industry.</p>
                    </div>

                    <div class="col-xl-4 align-self-center">
                        <div class="d-flex">
                            <div class="flex-shrink-0 btn-lg-square rounded-circle bg-white text-dark">
                                <i class="fa fa-phone-alt"></i>
                            </div>
                            <div class="ms-md-3">
                                <h4 class="mb-0"><a class="text-dark" href="tel:+919930054691">(+91) 9930054691</a> <br> <a  class="text-dark" href="tel:03322534250">(033) 22534250</a></h4>
<!--                                <p class="mb-1 text-uppercase"><small>Call Us Now</small></p>-->

                            </div>
                        </div>
                    </div>

                </div>


            </div>


        </div>

    </section>

    @if(isset($faqs) && !empty($faqs) && count($faqs) > 0)
        <section class="sec-pad" id="faq-area">
            <div class="container">
                <div class="sec-title wow fadeInUp" data-wow-delay="0.1s">
                    <span class="subtitle">FAQs</span>
                    <h1>Get Every Single <span class="highlight">Answers</span></h1>
                </div>
                <div class="row">
                    @foreach($faqs as $key => $faq)
                        @if($key % 2 == 0)
                            <div class="col-lg-6">
                                <div class="accordion accordion-flush" id="{{ 'accordionFlushExample_'.$faq->id }}">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingOne">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="{{ '#flush-collapseOne_'.$faq->id }}"
                                                    aria-expanded="false"
                                                    aria-controls="flush-collapseOne">
                                                {{ $faq->question }}
                                            </button>
                                        </h2>
                                        <div id="{{ 'flush-collapseOne_'.$faq->id }}"
                                             class="accordion-collapse collapse"
                                             aria-labelledby="flush-headingOne"
                                             data-bs-parent="{{ '#accordionFlushExample_'.$faq->id }}">
                                            <div class="accordion-body">{{ $faq->answer }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-lg-6">
                                <div class="accordion accordion-flush" id="{{ 'accordionFlushExample_'.$faq->id }}">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingOne">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapseOne" aria-expanded="false"
                                                    aria-controls="flush-collapseOne">
                                                {{ $faq->question }}
                                            </button>
                                        </h2>
                                        <div id="flush-collapseOne" class="accordion-collapse collapse"
                                             aria-labelledby="flush-headingOne"
                                             data-bs-parent="{{ '#accordionFlushExample_'.$faq->id }}">
                                            <div class="accordion-body">{{ $faq->answer }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="sec-pad">
        <div class="container">
            <div class="funfact-content">
                <div class="row clearfix">
                    <div class="col-xl-4 col-md-12 col-sm-12 title-column">
                        <div class="sec-title wow fadeInUp mb-lg-0">
                            <span class="subtitle color-2">Milestones</span>
                            <h1 class=""><span class="highlight-2">Milestones</span></h1>
                        </div>
                    </div>
                    <div class="col-xl-8 col-md-12 col-sm-12 inner-column">
                        <div class="funfact-inner centred">
                            <div class="row clearfix">
                                <div class="col-md-3 col-6 funfact-block">
                                    <div class="funfact-block-one">
                                        <div class="inner-box">
                                            <div class="count-outer count-box counted">
                                                <span class="count-text">150</span><span>+</span>
                                            </div>
                                            <h6>Members</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 funfact-block">
                                    <div class="funfact-block-one">
                                        <div class="inner-box">
                                            <div class="count-outer count-box counted">
                                                <span class="count-text">50</span><span>+</span>
                                            </div>
                                            <h6>Event Organised</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 funfact-block">
                                    <div class="funfact-block-one">
                                        <div class="inner-box">
                                            <div class="count-outer count-box counted">
                                                <span class="count-text">30</span><span>+</span>
                                            </div>
                                            <h6>Investors</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 funfact-block">
                                    <div class="funfact-block-one">
                                        <div class="inner-box">
                                            <div class="count-outer count-box counted">
                                                <span class="count-text">5</span><span>+</span>
                                            </div>
                                            <h6>Worldwide Offices</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Start -->
    <section class="sec-pad position-relative" id="contact">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-7 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="sec-title wow fadeInUp mb-5" data-wow-delay="0.1s">
                        <span class="subtitle color-2 text-white">Contact us</span>
                        <h1 class="text-white">Do You Have any <span class="highlight-2">Questions?</span></h1>
                    </div>
                    <form action="{{ route('contact.store') }}" method="post">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="Your Name" required>
                                    <label for="name">Your Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email"
                                           placeholder="Your Email" required>
                                    <label for="email">Your Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="subject" name="subject"
                                           placeholder="Subject" required>
                                    <label for="subject">Subject</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a message here" id="message"
                                                  name="message"
                                                  style="height: 150px" required></textarea>
                                    <label for="message">Message</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="theme-btn mt-4" type="submit">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
                @if(isset($settings) && !empty($settings))
                    <div class="col-lg-4 offset-lg-1 wow fadeInUp contact-sidebar" data-wow-delay="0.1s">
                        <h2 class="mb-5">Are You Going to Implement Project?</h2>



                        <div class="d-flex mb-5">
                            <div class="">
                                <p class="text-uppercase">Email</p>
                                <h5 class="mb-0"><a href="mailto:{{ $settings['company_email'] }}">{{ $settings['company_email'] }}</a> <br>
                                    <a href="mailto:{{ $settings['company_alternative_email'] }}">{{ $settings['company_alternative_email'] }}</a></h5>
                            </div>
                        </div>

                        <div class="d-flex mb-5">
                            <div class="">
                                <p class="text-uppercase">PHONE</p>
                                <h5 class="mb-0"><a href="tel:{{ $settings['company_contact_no'] }}">{{ $settings['company_contact_no'] }}</a> <br>
                                    <a href="tel:{{ $settings['company_alternative_contact_no'] }}">{{ $settings['company_alternative_contact_no'] }}</a></h5>
                            </div>
                        </div>

                                            <div class="d-flex mb-5">
                          <div class="">
                              <p class="text-uppercase">head office</p>
                               <h5 class="mb-0">ICC Towers, 4, India Exchange Pl Rd, Murgighata, B.B.D. Bagh, <br> Kolkata, West Bengal 700001</h5>
                            </div>
                        </div>

                    </div>
                @endif
            </div>
        </div>
    </section>
    <!-- Contact End -->

@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
    <script>
        $(document).ready(function () {
            $('.customer-logos').slick({
                slidesToShow: 6,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 1500,
                arrows: false,
                dots: false,
                pauseOnHover: false,
                responsive: [{
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 4
                    }
                }, {
                    breakpoint: 520,
                    settings: {
                        slidesToShow: 3
                    }
                }]
            });
        });
    </script>
@endsection
