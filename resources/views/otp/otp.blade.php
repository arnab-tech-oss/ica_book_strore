<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Otp Here</title>

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

        .otp-field {
            flex-direction: row;
            column-gap: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            }

            .otp-field input {
            height: 45px;
            width: 42px;
            border-radius: 6px;
            outline: none;
            font-size: 1.125rem;
            text-align: center;
            border: 1px solid #ddd;
            }
            .otp-field input:focus {
            box-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
            }
            .otp-field input::-webkit-inner-spin-button,
            .otp-field input::-webkit-outer-spin-button {
            display: none;
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
                            <h1 class="h5 text-center mb-3">Get OTP</h1>
                            <p class="text-center"><small>You have received an OTP in your registred email ID</small></p>
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <form method="post" action="{{ route('otp.check') }}">
                                @csrf
                                <div class="form-group">
                                    <div class="otp-field mb-4">
                                        <input type="number" name="otp[]"/>
                                        <input type="number" disabled maxlength="1" name="otp[]"/>
                                        <input type="number" disabled maxlength="1" name="otp[]"/>
                                        <input type="number" disabled maxlength="1" name="otp[]"/>
                                        <input type="number" disabled maxlength="1" name="otp[]"/>
                                      </div>
                                </div>
                                <button class="btn btn-primary rounded-pill py-3 px-5 w-100"
                                    type="submit">Verify OTP</button>
                            </form>
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

    const inputs = document.querySelectorAll(".otp-field > input");
const button = document.querySelector(".btn");

window.addEventListener("load", () => inputs[0].focus());
button.setAttribute("disabled", "disabled");

inputs[0].addEventListener("paste", function (event) {
  event.preventDefault();

  const pastedValue = (event.clipboardData || window.clipboardData).getData(
    "text"
  );
  const otpLength = inputs.length;

  for (let i = 0; i < otpLength; i++) {
    if (i < pastedValue.length) {
      inputs[i].value = pastedValue[i];
      inputs[i].removeAttribute("disabled");
      inputs[i].focus;
    } else {
      inputs[i].value = ""; // Clear any remaining inputs
      inputs[i].focus;
    }
  }
});

inputs.forEach((input, index1) => {
  input.addEventListener("keyup", (e) => {
    const currentInput = input;
    const nextInput = input.nextElementSibling;
    const prevInput = input.previousElementSibling;

    if (currentInput.value.length > 1) {
      currentInput.value = "";
      return;
    }

    if (
      nextInput &&
      nextInput.hasAttribute("disabled") &&
      currentInput.value !== ""
    ) {
      nextInput.removeAttribute("disabled");
      nextInput.focus();
    }

    if (e.key === "Backspace") {
      inputs.forEach((input, index2) => {
        if (index1 <= index2 && prevInput) {
          input.setAttribute("disabled", true);
          input.value = "";
          prevInput.focus();
        }
      });
    }

    button.classList.remove("active");
    button.setAttribute("disabled", "disabled");

    const inputsNo = inputs.length;
    if (!inputs[inputsNo - 1].disabled && inputs[inputsNo - 1].value !== "") {
      button.classList.add("active");
      button.removeAttribute("disabled");

      return;
    }
  });
});

</script>

</html>
