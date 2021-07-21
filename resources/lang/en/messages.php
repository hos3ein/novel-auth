<?php

return [
    'token' => [
        'invalid' => 'Invalid Token',
        'expired' => 'Expired Token',
        'consumed' => 'Consumed Token',
    ],
    'auth' => 'Enter Email/Phone to login or register',

    "home" => "Home",
    "restart" => "Restart",
    "logout" => "Logout",
    "email/phone" => "Email/Phone",
    "submit" => "Submit",
    "code" => "Code",
    "password" => "Password",
    "password_confirmation" => "Password confirmation",
    "login_with_password" => "Login with password",
    "login_with_otp" => "Login with OTP",
    "resend_otp" => "Resend OTP",
    "select_another_otp" => "Select another OTP",

    'users' => [
        'inactive' => 'Your account is deactivated',
    ],
    'login' => [
        'disabled' => 'Login is disabled',
        'pass' => 'Input password',
        'no_otp_use_pass' => 'No otp service for you. input pass',
        'pass_error' => 'Invalid password',
        'user_no_otp' => 'No anyway to send otp for you',
        'one_otp_error_use_pass' => 'Please enter password',
    ],
    'register' => [
        'disabled' => 'Register is disabled',
        'with_email' => 'Please register with email address',
        'with_phone' => 'Please register with phone number',
        'passes' => 'Input password and confirmation',
        'pass_conf' => 'Invalid password and password confirmation',
        'no_otp_email_use_phone' => 'Email otp service is disabled. Please register with phone number',
        'no_otp_email_phone' => 'Otp service for email is disabled. for phone is disabled too',
        'no_otp_phone_use_email' => 'Phone otp services is disabled. Please register with email address',
        'no_otp_phone_email' => 'Otp services for phone is disabled. for email is disabled too',
    ],
    'email_phone' => [
        'invalid' => [
            'email' => 'Invalid email address',
            'phone' => 'Invalid phone number',
        ],
    ],
    'otp' => [
        'options' => 'OK! select one of them',
        'error' => [
            'invalid_type' => 'Invalid otp type',
            'invalid_code' => 'Invalid code',
            'email' => 'Oops! unable to send code to your email :identifier',
            'call' => 'Unable to call to :identifier',
            'sms' => 'Unable to send sms to :identifier',
            'ussd' => 'Error in ussd',
            'otp_generator' => '2fa error',
            'telegram' => 'Unable to send code to telegram :identifier',
            'whatsapp' => 'Unable to send code to whatsapp :identifier',
        ],
        'send' => [
            'email' => 'The code was sent to :identifier',
            'call' => 'We will call you soon (:identifier)',
            'sms' => 'The code was sent to (:identifier) via SMS',
            'ussd' => 'Call to (:ussd) from (:identifier) and give code',
            'otp_generator' => 'OK use your 2fa app',
            'telegram' => 'The code was sent to telegram :identifier',
            'whatsapp' => 'The code was sent to whatsapp :identifier',
        ],
        'sent' => [
            'email' => 'The code has been sent to :identifier',
            'call' => 'We will call you soon (:identifier)',
            'sms' => 'The code has been sent to (:identifier) via SMS',
            'ussd' => 'Call to (:ussd) from (:identifier)',
            'otp_generator' => 'OK use your 2fa app',
            'telegram' => 'The code has been sent to telegram :identifier',
            'whatsapp' => 'The code has been sent to whatsapp :identifier',
        ],
    ],
];
