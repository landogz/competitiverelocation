<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <title>Reset Password - Competitive Relocation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
        :root {
            --primary-color: #4a6bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            position: relative;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .login-header {
            background: var(--primary-color);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
    }

        .login-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 600;
        }

        .login-header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }

        .login-form {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 40px 12px 15px !important;
            transition: all 0.3s ease;
            background: #fff;
            box-shadow: none;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(74, 107, 255, 0.08);
            background: #fff;
        }

        .position-relative {
        position: relative;
    }

    .password-toggle {
        position: absolute;
            right: 14px;
        top: 50%;
        transform: translateY(-50%);
            background: transparent;
        border: none;
            color: var(--secondary-color);
        cursor: pointer;
            padding: 0;
            z-index: 2;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: none;
        }

        .password-toggle:focus {
            outline: none;
    }

    .password-toggle:hover {
            color: var(--primary-color);
            background: transparent;
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: var(--secondary-color);
        }

        .input-group {
            position: relative;
            background: none;
        }

        .btn-login {
            background: var(--primary-color);
            border: none;
            border-radius: 10px;
            color: white;
            padding: 12px;
            width: 100%;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: #2541b2;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(37, 65, 178, 0.2);
            color: #ffffff;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            background: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }

        .forgot-password a {
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .forgot-password a:hover {
            color: #3a5bef;
            text-decoration: underline;
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }

        @media (max-width: 576px) {
            .login-container {
                margin: 20px;
            }
    }
</style>
</head>
<body>
    @if(session('status'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('status') }}",
                confirmButtonText: 'OK'
            });
        });
    </script>
    @endif
    @if($errors->any())
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'OK'
            });
        });
    </script>
    @endif
    <div class="login-container">
        <div class="login-header">
            <h1>Reset Password</h1>
            <p>Enter your new password</p>
        </div>
        
        <div class="login-form">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                                    </div>
            @endif

            <form method="POST" action="{{ route('password.reset.submit') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ $email ?? old('email') }}" 
                               placeholder="Email Address" required autofocus>
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" placeholder="New Password" required>
                        <button type="button" class="password-toggle" tabindex="-1">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" class="form-control" 
                               name="password_confirmation" placeholder="Confirm New Password" required>
                        <button type="button" class="password-toggle" tabindex="-1">
                            <i class="fas fa-eye"></i>
                        </button>   
                    </div>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="fas fa-key me-2"></i> Reset Password
                </button>
            </form>

            <div class="forgot-password">
                <a href="{{ route('login') }}">
                    <i class="fas fa-arrow-left me-1"></i> Back to Login
                </a>
            </div>
                                                </div>
                                    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.password-toggle');
            
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('input');
                    const icon = this.querySelector('i');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
                });
            });
            
        // Add animation to form elements
        document.querySelectorAll('.form-group').forEach((element, index) => {
            element.style.animation = `fadeIn 0.5s ease-out ${index * 0.1}s forwards`;
            element.style.opacity = '0';
        });

        // Add keyframe animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                    }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html> 