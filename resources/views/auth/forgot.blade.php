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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            font-size: 1.5rem;
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
        .forgot-link, .login-link {
            color: #3b82f6;
            text-decoration: none;
            font-size: 0.97rem;
        }
        .forgot-link:hover, .login-link:hover {
            text-decoration: underline;
        }
        .form-check-label {
            font-size: 0.97rem;
        }
        .token-display {
            margin-top: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            word-break: break-all;
        }
        .token-display code {
            font-size: 0.9rem;
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
        <div class="login-title">Reset Password</div>
        <div class="login-subtitle">Enter your email and instructions will be sent to you!</div>
        <form class="my-4" id="forgotPasswordForm">
            <div class="form-group mb-3">
                <label class="form-label" for="userEmail">Email</label>
                <input type="email" class="form-control" id="userEmail" name="email" placeholder="Enter Email Address" required>
            </div>
            <button class="btn btn-primary w-100" type="submit" id="resetBtn">Reset <i class="fas fa-sign-in-alt ms-1"></i></button>
        </form>
        <div id="tokenDisplay" class="hidden">
            <div class="alert alert-info">
                <strong>Development Environment:</strong> Use this token to reset your password.
            </div>
            <div class="token-display">
                <code id="resetToken"></code>
            </div>
            <div class="text-center mt-2">
                <a href="#" id="resetLink" class="btn btn-sm btn-outline-primary">Go to Reset Page</a>
            </div>
        </div>
        <div class="text-center mb-2 mt-3">
            <p class="text-muted">Remember it? <a href="{{ url('/') }}" class="login-link ms-2">Sign in here</a></p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const forgotPasswordForm = document.getElementById('forgotPasswordForm');
        const resetBtn = document.getElementById('resetBtn');
        const tokenDisplay = document.getElementById('tokenDisplay');
        const resetToken = document.getElementById('resetToken');
        const resetLink = document.getElementById('resetLink');
        
        forgotPasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('userEmail').value;
            
            // Disable the button and show loading state
            resetBtn.disabled = true;
            resetBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
            
            // Send the password reset request
            fetch('{{ route("password.email") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                // Reset button state
                resetBtn.disabled = false;
                resetBtn.innerHTML = 'Reset <i class="fas fa-sign-in-alt ms-1"></i>';
                
                if (data.status === 'passwords.sent') {
                    // Check if we're in development environment and have a debug token
                    if (data.debug_token) {
                        // Show the token for development
                        resetToken.textContent = data.debug_token;
                        tokenDisplay.classList.remove('hidden');
                        
                        // Set up the reset link
                        resetLink.href = `{{ url('/password/reset') }}/${data.debug_token}?email=${encodeURIComponent(email)}`;
                        
                        // Show success message
                        Swal.fire({
                            title: 'Development Mode',
                            text: 'Password reset token has been generated. Use the link below to reset your password.',
                            icon: 'info',
                            confirmButtonColor: '#3085d6'
                        });
                    } else {
                        // Production environment - show standard message
                        Swal.fire({
                            title: 'Password Reset Link Sent!',
                            text: 'If an account exists with this email, you will receive password reset instructions.',
                            icon: 'success',
                            confirmButtonColor: '#3085d6'
                        }).then(() => {
                            // Redirect to login page after a short delay
                            setTimeout(() => {
                                window.location.href = '{{ url("/") }}';
                            }, 1500);
                        });
                    }
                } else {
                    // Show error message
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Failed to send password reset email. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Reset button state
                resetBtn.disabled = false;
                resetBtn.innerHTML = 'Reset <i class="fas fa-sign-in-alt ms-1"></i>';
                
                // Show error message
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while processing your request. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#3085d6'
                });
            });
        });
    });
</script>
</body>
</html>