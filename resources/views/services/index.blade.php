@extends('welcome')
@section('content')
    <div class="container px-3 py-5">
        <h2>Services</h2>
        <hr/>
        <div class="row row-cols-2 row-cols-lg-4 g-2 g-lg-3">
            @foreach($services as $key => $service)
                <a href="{{ route('services.show',$service->id) }}">
                    <div class="col overflow-hidden">
                        <div class="card">
                            <h5 class="card-header text-primary">{{ $service->name }}</h5>
                            <div class="card-body text-dark px-2">
                                <p class="card-text">Category : {{ $service->category->name }}</p>
                                <p class="card-text">Service Cost : <b>{!! $service->cost !!}</b></p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">Last updated at {{ $service->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
