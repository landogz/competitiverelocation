<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM - Competitive Relocation System</title>
    <!-- App CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            padding: 2.5rem 2rem 2rem 2rem;
            max-width: 400px;
            width: 100%;
            margin: 2rem 0;
            position: relative;
        }
        .login-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .login-logo img {
            height: 60px;
        }
        .login-title {
            font-size: 1.7rem;
            font-weight: 700;
            color: #22223b;
            text-align: center;
            margin-bottom: 0.5rem;
        }
        .login-subtitle {
            color: #6c757d;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1rem;
        }
        .form-label {
            font-weight: 600;
            color: #22223b;
        }
        .form-control {
            border-radius: 0.75rem;
            border: 1px solid #e0e0e0;
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15);
        }
        .btn-primary {
            background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
            border: none;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.75rem;
            transition: background 0.2s;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
        }
        .forgot-link {
            color: #3b82f6;
            text-decoration: none;
            font-size: 0.97rem;
        }
        .forgot-link:hover {
            text-decoration: underline;
        }
        .form-check-label {
            font-size: 0.97rem;
        }
        @media (max-width: 500px) {
            .login-card {
                padding: 1.5rem 0.5rem;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-card">
        <div class="login-logo">
            <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo">
        </div>
        <div class="login-title">Welcome Back</div>
        <div class="login-subtitle">Sign in to Competitive Relocation</div>
        <form class="my-4" method="POST" action="{{ route('login.submit') }}" id="loginForm">
            @csrf
            <div class="form-group mb-3">
                <label class="form-label" for="email">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Enter email" required autofocus>
            </div>
            <div class="form-group mb-3">
                <label class="form-label" for="password">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Enter password" required>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <a href="{{ route('password.request') }}" class="forgot-link"><i class="dripicons-lock"></i> Forgot password?</a>
            </div>
            <button class="btn btn-primary w-100" type="submit">Log In <i class="fas fa-sign-in-alt ms-1"></i></button>
        </form>
    </div>
</div>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: `@foreach($errors->all() as $error){{ $error }}<br>@endforeach`,
            confirmButtonColor: '#3085d6'
        });
    @endif

    @if(session('status'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('status') }}',
            confirmButtonColor: '#3085d6'
        });
    @endif

    const form = document.getElementById('loginForm');
    form.addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        if (!email || !password) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Required Fields',
                text: 'Please fill in all required fields',
                confirmButtonColor: '#3085d6'
            });
        }
    });
});
</script>
</body>
</html>
