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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .page-content{
        background-color: #EEEEEE;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    .hidden{
        display: none !important;
    }
    .alert {
        display: none;
        margin-top: 10px;
    }
    .password-field {
        position: relative;
    }
    .password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        z-index: 10;
    }
    .password-toggle:hover {
        color: #3b82f6;
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
</style>
</head>
<body>


    <div class="container-xxl">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mx-auto">
                            <div class="card">
                                <div class="card-body p-0 bg-black auth-header-box rounded-top">
                                    <div class="text-center p-3">
                                        <a href="{{ url('/') }}" class="logo logo-admin">
                                            <img src="{{ asset('assets/images/logo-sm.png') }}" height="50" alt="logo" class="auth-logo">
                                        </a>
                                        <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Reset Password</h4>   
                                        <p class="text-muted fw-medium mb-0">Enter your Email and instructions will be sent to you!</p>  
                                    </div>
                                </div>
                                <div class="card-body pt-0">                                    
                                    <form class="my-4" id="forgotPasswordForm">            
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="userEmail">Email</label>
                                            <input type="email" class="form-control" id="userEmail" name="email" placeholder="Enter Email Address" required>                               
                                        </div><!--end form-group-->             
                                        
                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <div class="d-grid mt-3">
                                                    <button class="btn btn-primary" type="submit" id="resetBtn">Reset <i class="fas fa-sign-in-alt ms-1"></i></button>
                                                </div>
                                            </div><!--end col--> 
                                        </div> <!--end form-group-->                           
                                    </form><!--end form-->
                                    
                                    <!-- Development environment token display (hidden by default) -->
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
                                    
                                    <div class="text-center mb-2">
                                        <p class="text-muted">Remember It ?  <a href="{{ url('/') }}" class="text-primary ms-2">Sign in here</a></p>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end card-body-->
            </div><!--end col-->
        </div><!--end row-->                                        
    </div><!-- container -->

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