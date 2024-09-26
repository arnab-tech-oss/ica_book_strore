<?php

$site_title = 'Indian Chamber Of Commerce - Trade Desk Portal';
$site_blogo = 'https://www.indianchamber.org/wp-content/themes/icc/images/logo.png';
$site_slogo = 'https://www.indianchamber.org/wp-content/uploads/2022/07/fav_logo.png';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?php echo $site_title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}">

</head>

<body class="bg-white">
    <div class="bg-white p-0">
        <!-- Spinner Start -->


        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <!-- Navbar & Hero Start -->
        <div class="position-relative p-0" id="home">

            <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
                <div class="container">
                    <a href="/" class="navbar-brand p-0">
                        <img src="{{ asset('img/logo.svg') }}" class="img-fluid" title="<?php echo $site_title; ?>"
                            alt="<?php echo $site_title; ?>">
                        <!-- <img src="img/logo.png" alt="Logo"> -->
                    </a>
                    <button class="navbar-toggler rounded-pill" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse position-relative navbar-collapse  justify-content-end" id="navbarCollapse">
                        <div class="navbar-nav align-items-center">

                            <a href="#" id="head_about" class="nav-item nav-move nav-link">About</a>
                            <a href="#" id="head_services" class="nav-item nav-link nav-move">Services</a>
                            <a href="#" id="head_case_study" class="nav-item nav-link nav-move">Case Study</a>
                            <a href="#" id="head_testimonial" class="nav-item nav-link nav-move">Testimonial</a>
                            <a href="#" id="head_partners" class="nav-item nav-link nav-move">Partners</a>
                            <a href="#" id="head_contact" class="nav-item nav-link nav-move">Contact Us</a>
                            <a href="{{ route('admin.tickets.index') }}"
                                class="btn btn-primary rounded-pill py-1 px-3 me-3"> Raise Services Request</a>
                            @if (Route::has('login'))
                                @auth
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown"
                                            role="button" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="fas fa-user-cog"></i>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <a href="{{ url('/home') }}" class="nav-item nav-link">Dashboard</a>
                                            <a href="{{ route('getProfile') }}" class="nav-item nav-link">My Profile</a>
                                            <a href="#" class="nav-link"
                                                onclick="event.preventDefault(); document.getElementById('logoutform2').submit();">
                                                <i class="nav-icon fas fa-fw fa-sign-out-alt">
                                                </i>
                                                {{ trans('global.logout') }}
                                            </a>
                                        </div>
                                    </li>
                                @else
                                    <a href="{{ route('login') }}" class="nav-item nav-link d-inline-block me-2">Login |
                                    </a>
                                    <a href="{{ route('createUser') }}" class="me-0 nav-item nav-link d-inline-block">
                                        Register</a>
                                @endauth
                            @endif
                            <li class="nav-item dropdown currency">
                                @php
                                    $currancy = session('set_currency');
                                @endphp
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    @if (isset($currancy))
                                        Currency ({{ $currancy }})
                                    @else
                                        Currency
                                    @endif
                                </a>
                                <ul class="dropdown-menu" id="currencyType" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="#">INR</a></li>
                                    <li><a class="dropdown-item" href="#">USD</a></li>
                                </ul>
                            </li>
                        </div>

                    </div>
                </div>
            </nav>

        </div>
        <!-- Navbar & Hero End -->
        {{-- @section('scripts')
        <script>
            $(".nav-move").click(function () {
                let id = "#" + $(this).attr('id').replaceAll('head_', '');
                if ($(id).length == 1) {
                    $('html, body').animate({
                        scrollTop: $(id).offset().top
                    });
                } else {
                    let url = window.location.href;
                    let domain = (new URL(url));
                    domain = domain.hostname;
                    window.location.href = "https://" + domain + "/" + id;
                }
            });
        </script>
@endsection --}}
        <style>
            @media screen and (max-width: 580px) {
                .navbar li.currency {
                    position: relative;
                    top: 0px;
                    right: 0px;
                }
            }
        </style>
