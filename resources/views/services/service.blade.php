@extends('welcome')
@section('content')
    @php
        $imgs = $service->attachments;
        $imgSrc = '../img/sucess-story-01.jpg';
        if(count($service->attachments) > 0 && isset($imgs[0])){
            $imgSrc = $imgs[0]->getUrl();
        }
        $user = Auth::user();
    @endphp

    <section class="sec-pad">
        <div class="container" style="min-height: 700px;">
            <div class="row align-items-start justify-content-around">
                <div class="col-xl-7 col-lg-8 col-md-9">
                    <div class="p-0 my-2 border-top border-bottom page-title">
                        <img src="{{ $imgSrc }}" class="img-fluid">
                     </div>
                    <article class="article">
                        <h3 class="h2 mt-5 mb-4">{{ $service->name }}</h3>
                        @if(isset($user) && $service->cost > '0')
                            <h6 class="mb-4">Cost : {{ ($service->currency == 'INR' ? '₹' : '$').$service->cost }}</h6>
                        @endif
<!--                        <h5>Cost : <strong>{{ $service->cost }}</strong></h5>
                        <h5>Category : <strong>{{ $service->category->name }}</strong></h5>
                        <br>
                        <hr/>-->
                        @php
                            $allowedImgMimeTypes = ['image/jpeg','image/gif','image/png'];
                        @endphp

                       {{-- <div class="row row-cols-2 g-2 d-flex flex-column align-content-start">
                            @if(count($service->attachments))
                                @foreach($service->attachments as $attachment)
                                    @php
                                        $path = public_path(str_replace("http://localhost/public/","/invoices/",$attachment->getUrl()));
                                        $isExists = File::exists($path);
                                    @endphp
                                    @if(in_array($attachment->mime_type,$allowedImgMimeTypes) && $isExists)
                                        <div class="col">
                                            <img class="serviceImg"
                                                 src="{{ asset(str_replace("http://localhost/public/","/invoices/",$attachment->getUrl())) }}">
                                        </div>
                                    @else
                                        <div class="col">
                                            <a href="#" download="{{ $attachment->file_name }}" target="_blank">{{ $attachment->file_name }}</a>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <hr/>--}}
                        <hr/>
{{--                        <h5>Description : </h5>--}}
                        {!! $service->description !!}
                    </article>
                </div>
                <div class="col-md-9 col-lg col-xl-4 sticky-lg-top mb-5 mb-lg-0">
                    <div class="sidebar-service text-center bg-primary">
                        <div class="inner-box">
                            <h2 class="text-white mb-4">Want to Know More? <br>Contact Us</h2>
                            <a href="{{ route('admin.tickets.createWithService',$service->id) }}" class="btn btn-light rounded-pill"><i class="fa fa-shopping-cart me-2"></i>Add
                                To Cart</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
@endsection
