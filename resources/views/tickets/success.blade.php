<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <link href='https://fonts.googleapis.com/css?family=Lato:300,400|Montserrat:700' rel='stylesheet' type='text/css'>
    <style>
        @import url(//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.min.css);
        @import url(//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css);

        .header {
            margin: 0;
            font-family: Montserrat, sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.1;
            text-transform: uppercase;
            hyphens: auto;
        }

        .sub-header {
            color: green;
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
        <h1 class="header">THANK YOU!</h1>
        <h2 class="sub-header">Your Payment is Successfully Received.</h2>
    </header>

    <div class="main-content">
        <i class="fa fa-check main-content__checkmark" id="checkmark"></i>
        <p style="margin-bottom: 30px;" class="main-content__body" data-lead-id="main-content-body">Thanks for your
            payment. It means a lot to us, just like you do! We really appreciate you giving us a moment of your time
            today. Thanks for being you.</p>
        <div class="row">
            @php
                $payment = DB::table('payments')
                    ->where('signature', $data->razorpay_signature)
                    ->orderByDesc('id')
                    ->first();

            @endphp
            <table class="table table-striped table-bordered">
                <tbody>

                    <tr>
                        <th scope="row">Payment status</th>
                        <td>{{ $payment->payment_link_status }}</td>

                    </tr>

                    <tr>
                        <th scope="row">Payment Link Reference Id</th>
                        <td>{{ $payment->payment_link_reference_id }}</td>

                    </tr>

                    <tr>
                        <th scope="row">Payment Mode</th>
                        <td>{{ $payment->method }}</td>

                    </tr>
                    <tr>
                        <th scope="row">Payment Amount </th>
                        <td>{{ $payment->amount }}</td>

                    </tr>
                    <tr>
                        <th scope="row">Remaining Amount </th>
                        <td>{{ $payment->remaining_amount }}</td>

                    </tr>
                    <tr>
                        <th scope="row">Transaction No </th>
                        <td>{{ $payment->transaction_no }}</td>

                    </tr>

                  

                </tbody>
            </table>
        </div>

        <a href="{{ route('admin.tickets.index') }}">Go Back</a>
    </div>

    <footer class="" style="padding: 20px 0;" id="footer">
        <p class="site-footer__fineprint" id="fineprint">Copyright ©2022 | All Rights Reserved</p>
    </footer>
</body>

</html>
