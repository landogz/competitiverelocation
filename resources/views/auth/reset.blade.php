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
    .generate-password-btn {
        margin-top: 10px;
        font-size: 0.85rem;
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
                                        <p class="text-muted fw-medium mb-0">Enter your new password below!</p>  
                                    </div>
                                </div>
                                <div class="card-body pt-0">                                    
                                    <form class="my-4" id="resetPasswordForm" method="POST" action="{{ route('password.reset.submit') }}">            
                                        @csrf
                                        <input type="hidden" name="token" value="{{ $token }}">
                                        <input type="hidden" name="email" value="{{ $email }}">
                                        
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="password">New Password</label>
                                            <div class="password-field">
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" required>
                                                <button type="button" class="password-toggle" data-target="password">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-secondary generate-password-btn" id="generatePassword">
                                                <i class="fas fa-magic"></i> Generate Strong Password
                                            </button>
                                        </div><!--end form-group-->             
                                        
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="password_confirmation">Confirm New Password</label>
                                            <div class="password-field">
                                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" required>
                                                <button type="button" class="password-toggle" data-target="password_confirmation">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div><!--end form-group-->             
                                        
                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <div class="d-grid mt-3">
                                                    <button class="btn btn-primary" type="submit" id="resetBtn">Reset Password <i class="fas fa-key ms-1"></i></button>
                                                </div>
                                            </div><!--end col--> 
                                        </div> <!--end form-group-->                           
                                    </form><!--end form-->
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
            const resetPasswordForm = document.getElementById('resetPasswordForm');
            const resetBtn = document.getElementById('resetBtn');
            
            // Password toggle functionality
            document.querySelectorAll('.password-toggle').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const passwordField = document.getElementById(targetId);
                    const icon = this.querySelector('i');
                    
                    if (passwordField.type === 'password') {
                        passwordField.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        passwordField.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
            
            // Generate strong password
            document.getElementById('generatePassword').addEventListener('click', function() {
                const length = 16;
                const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
                let password = "";
                
                // Ensure at least one of each required character type
                password += charset.match(/[A-Z]/)[0]; // Uppercase
                password += charset.match(/[a-z]/)[0]; // Lowercase
                password += charset.match(/[0-9]/)[0]; // Number
                password += charset.match(/[!@#$%^&*()_+]/)[0]; // Special character
                
                // Fill the rest randomly
                for (let i = password.length; i < length; i++) {
                    const randomIndex = Math.floor(Math.random() * charset.length);
                    password += charset[randomIndex];
                }
                
                // Shuffle the password
                password = password.split('').sort(() => Math.random() - 0.5).join('');
                
                // Set the generated password
                document.getElementById('password').value = password;
                document.getElementById('password_confirmation').value = password;
                
                // Show success message
                Swal.fire({
                    title: 'Password Generated',
                    text: 'A strong password has been generated and filled in both fields',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
            
            // Form submission
            resetPasswordForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;
                
                if (password !== confirmPassword) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Passwords do not match',
                        icon: 'error',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }
                
                // Disable the button and show loading state
                resetBtn.disabled = true;
                resetBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
                
                // Submit the form using fetch
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        token: document.querySelector('input[name="token"]').value,
                        email: document.querySelector('input[name="email"]').value,
                        password: password,
                        password_confirmation: confirmPassword
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonColor: '#3085d6'
                        }).then(() => {
                            window.location.href = data.redirect;
                        });
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        text: error.message || 'An error occurred while resetting your password.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6'
                    });
                    resetBtn.disabled = false;
                    resetBtn.innerHTML = 'Reset Password <i class="fas fa-key ms-1"></i>';
                });
            });
        });
    </script>
</body>
</html> 