<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400|Montserrat:700' rel='stylesheet'
          type='text/css'>
    <style>
        @import url(//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.min.css);
        @import url(//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css);
        .header{
            margin: 0;
            font-family: Montserrat, sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.1;
            text-transform: uppercase;
            hyphens: auto;
        }
        .sub-header{
            color: #ef1515;
            font-family: Montserrat, sans-serif;
            font-weight: 700;
            line-height: 1.1;
            hyphens: auto;
        }
    </style>
    <link rel="stylesheet" href="https://2-22-4-dot-lead-pages.appspot.com/static/lp918/min/default_thank_you.css">
    <script src="https://2-22-4-dot-lead-pages.appspot.com/static/lp918/min/jquery-1.9.1.min.js"></script>
    <script src="https://2-22-4-dot-lead-pages.appspot.com/static/lp918/min/html5shiv.js"></script>
</head>
<body>
<header class="site-header" id="header">
    <h1 class="header">OOPS, SOMETHING IS WRONG!</h1>
    <h2 class="sub-header">Your Payment is Not Received.</h2>
</header>

<div class="main-content">
    <i class="fa fa-close main-content__checkmark" style="color:red;" id="checkmark"></i>
    <p style="margin-bottom: 30px;" class="main-content__body" data-lead-id="main-content-body">Thanks for your payment. It means a lot to us, just like you do! We really appreciate you giving us a moment of your time today. Thanks for being you.</p>
    <a href="{{ route('admin.tickets.index') }}">Go Back</a>
</div>

<footer class="" style="padding: 20px 0;" id="footer">
    <p class="site-footer__fineprint" id="fineprint">Copyright ©2022 | All Rights Reserved</p>
</footer>
</body>
</html>
