<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        .body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f4f7; padding: 20px; }
        .wrapper { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background-color: #4F46E5; padding: 20px; text-align: center; color: #ffffff; }
        .content { padding: 40px; text-align: center; }
        .otp-code { font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #4F46E5; background: #f0f0ff; padding: 15px; border-radius: 4px; display: inline-block; margin: 20px 0; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body class="body">
    <div class="wrapper">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
        </div>
        <div class="content">
            <h2>Verify Your Account</h2>
            <p>Hello,</p>
            <p>You requested a password reset. Use the code below to complete the process. This code will expire in 15 minutes.</p>
            
            <div class="otp-code">
                {{ $otp }}
            </div>

            <p>If you did not request this, please ignore this email or contact support.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>