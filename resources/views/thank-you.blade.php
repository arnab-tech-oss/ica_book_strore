@extends('welcome')
@section('content')

    <section class="sec-pad thank-you-area">

        <div class="container">

            <div class="row justify-content-center">

                <div class="col-lg-8 text-center">
                    <div class="mb-4 mb-md-5">
                        <h2 class="display-1 mb-3 mb-md-0">Thank You</h2>
                        <p class="lead">For Contacting Us, You will get an confirmation, <br> We will Contact You
                            shortly.</p>
                    </div>
                    @if(isset($transactionId) || isset($referenceId) || isset($transactionDate))
                        <div class="tran-detail-area mb-4">
                            <div class="row justify-content-center">
                                <div class="col-lg-8 text-center">
                                    <div class="">
                                        <div class="card card-body bg-light">
                                            <ul class="list-group">
                                                @if(!empty($transactionId))
                                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                                        <div class="ms-2 me-auto text-start">
                                                            <div class="fw-bold">Tracsaction ID :</div>
                                                            #5286456468
                                                        </div>
                                                        <span class="badge bg-primary rounded-pill">14</span>
                                                    </li>
                                                @endif
                                                @if(!empty($referenceId))
                                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                                        <div class="ms-2 me-auto text-start">
                                                            <div class="fw-bold">Ref ID :</div>
                                                            #523-22-56
                                                        </div>
                                                        <span class="badge bg-primary rounded-pill">14</span>
                                                    </li>
                                                @endif
                                                @if(!empty($transactionDate))
                                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                                        <div class="ms-2 me-auto text-start">
                                                            <div class="fw-bold">Tracsaction date :</div>
                                                            13-10-2022
                                                        </div>
                                                        <span class="badge bg-primary rounded-pill">14</span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <a href="/" class="theme-btn"><i class="fab fa-home"></i> Back To Home</a>
                </div>

            </div>

        </div>

    </section>

@endsection
