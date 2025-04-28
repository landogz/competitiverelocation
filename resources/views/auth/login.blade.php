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
    .page-content{
        background-color: #EEEEEE;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    .hidden{
        display: none !important;
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
                                    <a href="#" class="logo logo-admin">
                                        <img src="{{ asset('assets/images/logo-sm.png') }}" height="50" alt="logo" class="auth-logo">
                                    </a>
                                    <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Let's Get Started</h4>   
                                    <p class="text-muted fw-medium mb-0">Sign in to continue to Competitive Relocation.</p>  
                                </div>
                            </div>
                            <div class="card-body pt-0">                                    
                                <form class="my-4" method="POST" action="{{ route('login.submit') }}" id="loginForm">
                                    @csrf
                                    <div class="form-group mb-2">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Enter email" required autofocus>                               
                                    </div><!--end form-group--> 
        
                                    <div class="form-group">
                                        <label class="form-label" for="password">Password</label>                                            
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Enter password" required>                            
                                    </div><!--end form-group--> 
        
                                    <div class="form-group row mt-3">
                                        <div class="col-sm-6">
                                            <div class="form-check form-switch form-switch-success">
                                                <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="remember">Remember me</label>
                                            </div>
                                        </div><!--end col--> 
                                        <div class="col-sm-6 text-end">
                                            <a href="{{ route('password.request') }}" class="text-muted font-13"><i class="dripicons-lock"></i> Forgot password?</a>                                    
                                        </div><!--end col--> 
                                    </div><!--end form-group--> 
        
                                    <div class="form-group mb-0 row">
                                        <div class="col-12">
                                            <div class="d-grid mt-3">
                                                <button class="btn btn-primary" type="submit">Log In <i class="fas fa-sign-in-alt ms-1"></i></button>
                                            </div>
                                        </div><!--end col--> 
                                    </div> <!--end form-group-->                           
                                </form><!--end form-->
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end card-body-->
        </div><!--end col-->
    </div><!--end row-->                                        
</div><!-- container -->

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
