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
<style>
    .page-content{
        background-color: #EEEEEE;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
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
                                    <a href="index-2.html" class="logo logo-admin">
                                        <img src="assets/images/logo-sm.png" height="50" alt="logo" class="auth-logo">
                                    </a>
                                    <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Let's Get Started Mifty</h4>   
                                    <p class="text-muted fw-medium mb-0">Sign in to continue to Mifty.</p>  
                                </div>
                            </div>
                            <div class="card-body pt-0">                                    
                                <form class="my-4" action="https://mannatthemes.com/mifty/default/index.html">            
                                    <div class="form-group mb-2">
                                        <label class="form-label" for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">                               
                                    </div><!--end form-group--> 
        
                                    <div class="form-group">
                                        <label class="form-label" for="userpassword">Password</label>                                            
                                        <input type="password" class="form-control" name="password" id="userpassword" placeholder="Enter password">                            
                                    </div><!--end form-group--> 
        
                                    <div class="form-group row mt-3">
                                        <div class="col-sm-6">
                                            <div class="form-check form-switch form-switch-success">
                                                <input class="form-check-input" type="checkbox" id="customSwitchSuccess">
                                                <label class="form-check-label" for="customSwitchSuccess">Remember me</label>
                                            </div>
                                        </div><!--end col--> 
                                        <div class="col-sm-6 text-end">
                                            <a href="{{ url('/forgot') }}" class="text-muted font-13"><i class="dripicons-lock"></i> Forgot password?</a>                                    
                                        </div><!--end col--> 
                                    </div><!--end form-group--> 
        
                                    <div class="form-group mb-0 row">
                                        <div class="col-12">
                                            <div class="d-grid mt-3">
                                                <button class="btn btn-primary" type="button">Log In <i class="fas fa-sign-in-alt ms-1"></i></button>
                                            </div>
                                        </div><!--end col--> 
                                    </div> <!--end form-group-->                           
                                </form><!--end form-->
                                <div class="text-center  mb-2">
                                    <p class="text-muted">Don't have an account ?  <a href="{{ url('/register') }}" class="text-primary ms-2">Free Register</a></p>
                                    <h6 class="px-3 d-inline-block">Or Login With</h6>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <a href="#" class="d-flex justify-content-center align-items-center thumb-md bg-blue-subtle text-blue rounded-circle me-2">
                                        <i class="fab fa-facebook align-self-center"></i>
                                    </a>
                                    <a href="#" class="d-flex justify-content-center align-items-center thumb-md bg-info-subtle text-info rounded-circle me-2">
                                        <i class="fab fa-twitter align-self-center"></i>
                                    </a>
                                    <a href="#" class="d-flex justify-content-center align-items-center thumb-md bg-danger-subtle text-danger rounded-circle">
                                        <i class="fab fa-google align-self-center"></i>
                                    </a>
                                </div>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end card-body-->
        </div><!--end col-->
    </div><!--end row-->                                        
</div><!-- container -->


</body>
</html>