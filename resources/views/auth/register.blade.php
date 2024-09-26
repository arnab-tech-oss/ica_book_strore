<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Register Here</title>

    <!-- Favicon -->
    <link href="{{ asset('assets/img/fav_logo.png') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('assets/css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('assets/css/front-style.css') }}" rel="stylesheet">

    <link href="{{ asset('img/favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>
    <?php

    $site_title = 'Indian Chamber Of Commerce - Trade Desk Portal';
    $site_blogo = 'https://www.indianchamber.org/wp-content/themes/icc/images/logo.png';
    $site_slogo = 'https://www.indianchamber.org/wp-content/uploads/2022/07/fav_logo.png';
    ?>

    <style>
        .front-header,
        .footer-sec {
            display: none !important;
        }
    </style>
    <div class="position-relative min-vh-100 bg-light d-flex flex-column justify-content-md-center">
        <section class="py-3">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="card card-body">
                            <div class="text-center m-0 ">
                                <img src="{{ asset('img/logo.svg') }}" class="logo mb-5" width="160">
                            </div>
                            <h1 class="text-center mb-5">{{ __('Register') }}</h1>
                            @if (Session::has('message'))
                                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
                                    {{ Session::get('message') }}</p>
                            @endif
                            <form method="POST" action="{{ route('createUser') }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 col-xl-4">
                                        <div class="form-group">
                                            <label for="title" class="col-form-label">Title <span style="color: red;">*</span></label>

                                            <select name="title" id="title"
                                                class="form-control  @error('name') is-invalid @enderror select2"
                                                autocomplete="title" autofocus required>
                                                <option value="" selected>Select Title</option>
                                                <option value="mr">Mr.</option>
                                                <option value="mrs">Mrs.</option>
                                            </select>
                                            @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-xl-4">
                                        <div class="form-group">
                                            <label for="name" class="col-form-label">{{ __('Name') }} <span style="color: red;">*</span></label></label>


                                            <input id="name" type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                value="{{ old('name') }}" required autocomplete="name" autofocus>

                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-xl-4">
                                        <div class="form-group">
                                            <label for="number" class="col-form-label">Phone
                                                Number<span style="color: red;">*</span></label></label>


                                            <input id="number" type="number"
                                                class="form-control @error('number') is-invalid @enderror"
                                                name="number" required autocomplete="Phone Number" value="{{old ('number')}}">

                                            @error('number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="col-md-6 col-xl-4">
                                        <div class="form-group">
                                            <label for="email"
                                                class="col-form-label">{{ __('E-Mail Address') }}<span style="color: red;">*</span></label></label>

                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autocomplete="email">

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-md-6 col-xl-4">
                                        <div class="form-group">
                                            <label for="country" class="col-form-label">Country<span style="color: red;">*</span></label></label>

                                            <select name="country" id="country"
                                                class="form-control select2 country_input">
                                                <option value="" selected>Select Country</option>
                                                @foreach ($countries as $id => $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('country')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-xl-4">
                                        <div class="form-group">
                                            <label for="city" class="col-form-label">City<span style="color: red;">*</span></label></label>

                                            <input id="city" type="text"
                                                class="form-control @error('city') is-invalid @enderror"
                                                name="city" required autocomplete="city" value="{{old ('city')}}">

                                            @error('city')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-xl-4">
                                        <div class="form-group">
                                            <label for="password" class="col-form-label">{{ __('Password') }}<span style="color: red;">*</span></label></label>

                                            <input id="password" type="password"
                                                placeholder="Password must be at least min. 8 character"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" required autocomplete="new-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-xl-4">
                                        <div class="form-group">
                                            <label for="password-confirm"
                                                class="col-form-label">{{ __('Confirm Password') }}<span style="color: red;">*</span></label></label>


                                            <input id="password-confirm" type="password"
                                                placeholder="Password must be at least min. 8 character"
                                                class="form-control" name="password_confirmation" required
                                                autocomplete="new-password">
                                        </div>
                                    </div>



                                    <div class="col-md-6 col-xl-4">
                                        <div class="form-group">
                                            <label for="company" class="col-form-label">Company</label>

                                            <input id="" type="text"
                                                class="form-control @error('company') is-invalid @enderror"
                                                name="company" autocomplete="company" value="{{old ('company')}}">

                                            @error('company')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-xl-4">
                                        <div class="form-group">
                                            <label for="designation" class="col-form-label">Designation</label>

                                            <input id="" type="text"
                                                class="form-control @error('designation') is-invalid @enderror"
                                                name="designation" autocomplete="designation" value= {{old ('designation')}}>

                                            @error('designation')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>



                                    <div class="col-md-6 col-xl-8">
                                        <div class="form-group">
                                            <label for="billing_address" class="form-label">Billing
                                                Address</label>

                                            <textarea id="billing_address" rows="10" type="text" class="form-control ml-2" name="billing_address"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4 is-invalid">
                                        <div class="form-group gst_no_input">
                                            <label for="gst_no" class="col-md-4 col form-label text-md-right">GST
                                                No</label>
                                            <input id="gst_no" type="text" class="form-control ml-2 "
                                                name="gst_no">
                                        </div>
                                    </div>
                                    <div class="col-md-4 pt-3 align-self-center">
                                        <div class="form-group">
                                            <input id="isGST" type="checkbox"
                                                class="form-check-input ml-2 is_gst_change" name="isGST"
                                                value="1">
                                            <label for="isGST" class=" col form-label text-md-right">Is GST
                                                Not
                                                Available ?</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mt-4 p-3">

                                            <input id="isMember" type="checkbox"
                                                class="form-check-input ml-2  icc_member_change" name="isMember">
                                            <label for="isMember" class="col form-check-label text-md-right">ICC
                                                Member ?</label>
                                        </div>
                                    </div>
                                </div>



                                <div class="col-md-6 is-invalid">
                                    <div class="form-group icc_member_id_input d-none">
                                        <label for="icc_member_id" class="form-label">Member
                                            Id</label>

                                        <input id="icc_member_id" type="text" class="form-control ml-2 "
                                            name="icc_member_id">

                                    </div>
                                </div>


                                <div class="text-center mt-4">
                                    <button class="btn btn-lg btn-primary rounded-pill py-3 w-25"
                                        type="submit">{{ __('Register') }}</button>
                                </div>
                            </form>
                        </div>
                        <div class="text-center text-small mt-3">
                            Do you have an account? <a href="{{ route('login') }}">Login here</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="{{ asset('assets/js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/js/easing.min.js') }}"></script>
    <script src="{{ asset('assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/counterup.min.js') }}"></script>
    <script src="{{ asset('assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('assets/js/front-main.js') }}"></script>
    <script>
        $(".icc_member_change").on('change', function() {

            if ($(this).prop('checked') == true) {
                $(".icc_member_id_input").removeClass("d-none");
                $("#icc_member_id").prop("required", true);
            } else {
                $(".icc_member_id_input").addClass('d-none');
                $("#icc_member_id").prop("required", false);
            }
        });

        $(".is_gst_change").on('change', function() {
            if ($(this).prop('checked') == true) {
                $(".gst_no_input").addClass('d-none');
                $("#gst_no").prop("required", false);

            } else {
                $(".gst_no_input").removeClass('d-none');
                $("#gst_no").prop("required", true);
            }
        });



    </script>
</body>
<!-- JavaScript Libraries -->

</html>
