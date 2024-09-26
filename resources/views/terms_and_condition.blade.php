@extends('welcome')
@section('content')
    <div class="container-xxl py-6" id="about">
        <div class="container">
            <div class="row g-5 flex-column-reverse flex-lg-row">
                <div class="col-lg-12 wow fadeInUp" data-wow-delay="0.1s">
                    <h1 class="mb-4">{{ $terms->title }}</h1>

                    <p>{!! $terms->content !!}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
