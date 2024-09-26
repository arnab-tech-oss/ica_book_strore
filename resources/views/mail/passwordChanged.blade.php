<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Your ICC trade desk password is changed successfully</title>
</head>
<body>

<pre>
Hello {{ $user }},

Your trade desk login password is changed successfully. you can login using the new credential <a href="https://tradedesk.indianchamber.org/login">Click Here</a>.
If you experience any issues logging into your account, reach out to us on below mention emails
{{ $supportEmail }}.

Your credential with new password is mention below:
Username => {{ $user_email }}
Password => {{ $user_password }}

please, do not share your credential with any other person.

Thanks,
ICC Trade Desk Team

</pre>

</body>
</html>
