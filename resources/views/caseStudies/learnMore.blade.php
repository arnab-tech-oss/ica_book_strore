@extends('welcome')
@section('content')
    <section class="p-0 border-top border-bottom page-title" @if(!empty($caseStudy->banner_image)) style="background-image: url({{ $caseStudy->banner_image }})" @endif>
        <div class="container">
            <div class=" row no-gutters">
                <div class="col-12">
                    <div class="">
                        <div class="row justify-content-center">
                            <div class="col py-4 py-sm-5">
                                <div class="my-4 my-md-5 my-lg-0 my-xl-5 text-center position-relative">
                                    <h1 class="display-4 mt-4 mt-lg-5 text-light">
                                        “We are working at almost twice the capacity”
                                    </h1>
                                    <p class="lead text-light">
                                        Volantis vitae unuch sed velit sodales. Sandor imperdiet proin fermentum leo vel Hodor.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="sec-pad">
        <div class="container">
            <div class="row align-items-start justify-content-around">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <article class="article">
                        
                        @php
                            $allowedImgMimeTypes = ['image/jpeg','image/gif','image/png'];
                        @endphp

                        <div class="">
                            @if(!empty($caseStudy->banner_image))
                                <img src="{{ asset('images/'.$caseStudy->banner_image) }}" class="img-fluid">
                            @endif
                            <h3 class="h2 mt-5 mb-4">Case Study Title : {{ $caseStudy->title }}</h3>
                        </div>
                        <hr/>
                        <p><b>Description :</b> {!! $caseStudy->description !!}</p>
                    </article>
                </div>
            </div>
        </div>
    </section>
    </div>
@endsection
