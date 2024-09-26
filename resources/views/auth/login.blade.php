<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login Here</title>

    <!-- Favicon -->
    <link href="{{ asset('assets/img/fav_logo.png') }}" rel="icon">

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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

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
    $site_blogo = 'https://www.indianchamber.org/wp-content/themes/icc/img/logo.svg';
    $site_slogo = 'https://www.indianchamber.org/wp-content/uploads/2022/07/fav_logo.png';
    ?>
    <style>
        .front-header,
        .footer-sec {
            display: none !important;
        }
    </style>
    @include('alertMessage')
    <div class="position-relative min-vh-100 bg-light d-flex flex-column justify-content-md-center">
        <section class="py-3">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-4 col-lg-5 col-md-6">
                        <div class="card card-body shadow">
                            <img src="{{ asset('img/logo.svg') }}" class="logo mb-5" style="height: 80px;">
                            <h1 class="h5 text-center mb-3">{{ trans('global.login') }}</h1>
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="email"
                                        class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        name="email" required autocomplete="email" autofocus
                                        placeholder="{{ trans('global.login_email') }}"
                                        value="{{ old('email', null) }}">
                                    @if ($errors->has('email'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                                <button class="btn btn-primary rounded-pill py-3 px-5 w-100"
                                    type="submit">{{ trans('global.login') }}</button>
                            </form>
                        </div>
                        <div class="text-center text-small mt-3">
                            Don't have an account? <a href="{{ route('createUser') }}">Register here</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
<!-- JavaScript Libraries -->
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
    $(document).ready(function() {

        let interval = setInterval(function() {
            removeSnackbar(interval);
        }, 2000);
    });

    function removeSnackbar(interval) {
        if ($("#snackbar").is(':visible')) {
            setTimeout(function() {
                $("#snackbar").fadeOut("slow", function() {
                    $(this).removeClass("slow");
                });
                clearInterval(interval);
            }, 800);
        }
    }
</script>

</html>
