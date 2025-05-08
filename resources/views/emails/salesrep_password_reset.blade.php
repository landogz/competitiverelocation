@php
    $loginUrl = url('/auth/login');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Successful</title>
    <style>
        body {
            background: #f4f6fb;
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #22223b;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 480px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 32px rgba(60,72,88,0.10);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(90deg, #3b82f6 0%, #0ea5e9 100%);
            color: #fff;
            padding: 32px 24px 16px 24px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 8px 0;
            font-size: 2rem;
            font-weight: 700;
        }
        .content {
            padding: 32px 24px;
        }
        .content h2 {
            font-size: 1.3rem;
            margin-bottom: 12px;
            color: #3b82f6;
        }
        .info {
            background: #f1f5f9;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
        }
        .info p {
            margin: 0 0 8px 0;
            font-size: 1rem;
        }
        .company {
            margin-bottom: 24px;
            text-align: center;
            font-size: 1.08rem;
            color: #0ea5e9;
            font-weight: 600;
        }
        .center {
            text-align: center;
        }
        .login-btn {
            display: inline-block;
            background: linear-gradient(90deg, #3b82f6 0%, #0ea5e9 100%);
            color: #fff !important;
            padding: 12px 32px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            margin-top: 12px;
            box-shadow: 0 2px 8px rgba(59,130,246,0.10);
            transition: background 0.2s;
        }
        .login-btn:hover {
            background: linear-gradient(90deg, #2563eb 0%, #0284c7 100%);
        }
        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 0.95rem;
            padding: 16px 24px 24px 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Reset Successful!</h1>
            <p>Your password has been reset successfully.</p>
        </div>
        <div class="content">
            <h2>Login Details</h2>
            <div class="info">
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>New Password:</strong> {{ $password }}</p>
            </div>
            <div class="company">
                <span>Company: {{ $company ?? 'N/A' }}</span>
            </div>
            <div class="center">
                <a href="{{ $loginUrl }}" class="login-btn">Login to Your Account</a>
            </div>
        </div>
        <div class="footer">
            If you have any questions, please contact your administrator.<br>
            &copy; {{ date('Y') }} Competitive Relocation. All rights reserved.
        </div>
    </div>
</body>
</html> 