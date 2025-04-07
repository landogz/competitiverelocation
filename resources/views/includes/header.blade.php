<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Laravel App')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/logo.png') }}">
    <link href="{{ asset('assets/libs/simple-datatables/style.css') }}" rel="stylesheet" type="text/css" />
    
       
    <link href="{{ asset('assets/libs/mobius1-selectr/selectr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/huebee/huebee.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/vanillajs-datepicker/css/datepicker.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css">
<style>
    .page-content,.modal-body{
        background-color: #EEEEEE;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }
</style>
</head>
<body>

    <div class="modal fade new-leads" id="new-leads" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title m-0" id="myExtraLargeModalLabel">Create new local leads</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div><!--end modal-header-->
                <div class="modal-body">
                    <h5 class="mt-0">You can create a new local lead by selecting clients on the dropdown lists or you can create a new lead for a new client.</h5>
                        <div class="row">                        
                            <div class="col-md-12 col-lg-6">
                                <div class="card">
                                    <div class="card-body pt-2">
                                        <div class="col-md-12">
                                            <label class="mb-2">Select clients account here</label>
                                            <select id="existing_leads">
                                                <option value="value-1">Value 1</option>
                                                <option value="value-2">Value 2</option>
                                                <option value="value-3">Value 3</option>
                                            </select>                                    
                                        </div><!-- end col -->    
                                    </div><!--end card-body--> 
                                </div><!--end card-->                             
                            </div> <!--end col--> 
                            <div class="col-md-12 col-lg-6">
                                <button type="button" class="btn btn-primary w-100">CREATE FRESH LEADS</button>                           
                            </div> <!--end col-->           
                        </div>
                </div><!--end modal-body-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div><!--end modal-footer-->
            </div><!--end modal-content-->
        </div><!--end modal-dialog-->
    </div><!--end modal-->


    <div class="modal fade" id="accountSettingsModal" tabindex="-1" role="dialog" aria-labelledby="accountSettingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
    
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="accountSettingsModalLabel">
                        <i class="fas fa-user-cog me-2"></i>Account Settings
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
    
                <!-- Modal Body -->
                <div class="modal-body">
                    <form method="POST" action="">
                        @csrf
    
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword" name="current_password" placeholder="Enter current password" required>
                        </div>
    
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword" name="new_password" placeholder="Enter new password" required>
                        </div>
    
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="new_password_confirmation" placeholder="Confirm new password" required>
                        </div>
    
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-key me-1"></i> Update Password
                        </button>
                    </form>
                </div>
    
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <small class="text-muted">Make sure your new password is strong and secure.</small>
                </div>
            </div>
        </div>
    </div>
    

      <!-- Top Bar Start -->
      <div class="topbar d-print-none">
        <div class="container-fluid">
            <nav class="topbar-custom d-flex justify-content-between" id="topbar-custom">    
        

                <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">                        
                    <li>
                        <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu">
                            <i class="iconoir-menu"></i>
                        </button>
                    </li> 
                    <li class="mx-2 welcome-text">
                        <h5 class="mb-0 fw-semibold text-truncate">Good Morning, James!</h5>
                        <!-- <h6 class="mb-0 fw-normal text-muted text-truncate fs-14">Here's your overview this week.</h6> -->
                    </li>                   
                </ul>
                <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">    
                    {{-- <li class="topbar-item">
                        <a class="nav-link nav-icon" href="javascript:void(0);" id="light-dark-mode">
                            <i class="iconoir-half-moon dark-mode"></i>
                            <i class="iconoir-sun-light light-mode"></i>
                        </a>                    
                    </li> --}}

                    <li class="hide-phone app-search">
                        <form role="search" action="#" method="get">
                            <input type="search" name="search" class="form-control top-search mb-0" placeholder="Search here...">
                            <button type="submit"><i class="iconoir-search"></i></button>
                        </form>
                    </li>
    
                    <li class="dropdown topbar-item">
                        <a class="nav-link arrow-none nav-icon" href="{{ url('/calendar') }}" role="button" aria-expanded="false" data-bs-offset="0,19">
                            <i class="iconoir-calendar"></i>
                            <span class="alert-badge"></span>
                        </a>
                        
                    </li>
                    <li class="dropdown topbar-item">
                        <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#" role="button"
                            aria-haspopup="false" aria-expanded="false" data-bs-offset="0,19">
                            <img src="assets/images/users/avatar-1.jpg" alt="" class="thumb-md rounded-circle">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end py-0">
                            <div class="d-flex align-items-center dropdown-item py-2 bg-secondary-subtle">
                                <div class="flex-shrink-0">
                                    <img src="assets/images/users/avatar-1.jpg" alt="" class="thumb-md rounded-circle">
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate align-self-center">
                                    <h6 class="my-0 fw-medium text-dark fs-13">William Martin</h6>
                                    <small class="text-muted mb-0">Front End Developer</small>
                                </div><!--end media-body-->
                            </div>
                            <div class="dropdown-divider mt-0"></div>
                            <small class="text-muted px-2 pb-1 d-block">Account</small>
                            <a class="dropdown-item" href="{{ url('/profile') }}"><i class="las la-user fs-18 me-1 align-text-bottom"></i> Profile</a>
                            {{-- <a class="dropdown-item" href="pages-faq.html"><i class="las la-wallet fs-18 me-1 align-text-bottom"></i> Earning</a> --}}
                            <small class="text-muted px-2 py-1 d-block">Settings</small>                        
                            {{-- <a class="dropdown-item" href="pages-profile.html"><i class="las la-cog fs-18 me-1 align-text-bottom"></i>Account Settings</a> --}}
                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#accountSettingsModal"><i class="las la-lock fs-18 me-1 align-text-bottom"></i> Security</a>
                            <a class="dropdown-item" href="pages-faq.html"><i class="las la-question-circle fs-18 me-1 align-text-bottom"></i> Help Center</a>                       
                            <div class="dropdown-divider mb-0"></div>
                            <a class="dropdown-item text-danger" href="auth-login.html"><i class="las la-power-off fs-18 me-1 align-text-bottom"></i> Logout</a>
                        </div>
                    </li>
                </ul><!--end topbar-nav-->
            </nav>
            <!-- end navbar-->
        </div>
    </div>
    <!-- Top Bar End -->
    <!-- leftbar-tab-menu -->
    <div class="startbar d-print-none">
        <!--start brand-->
        <div class="brand">
            <a href="{{ url('/dashboard') }}" class="logo">
                <span>
                    <img src="assets/images/logo-sm.png" alt="logo-small" class="logo-sm">
                </span>
                <span class="">
                    <img src="assets/images/logo-light.png" alt="logo-large" class="logo-lg logo-light">
                    <img src="assets/images/logo-dark.png" alt="logo-large" class="logo-lg logo-dark">
                </span>
            </a>
        </div>
        <!--end brand-->
        <!--start startbar-menu-->
        <div class="startbar-menu" >
            <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
                <div class="d-flex align-items-start flex-column w-100">
                    <!-- Navigation -->
                    <ul class="navbar-nav mb-auto w-100">
                        <li class="menu-label mt-2">
                            <span>Navigation</span>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/dashboard') }}">
                                <i class="iconoir-report-columns menu-icon"></i>
                                <span>Dashboard</span>
                            </a>
                        </li><!--end nav-item-->

                        {{-- <li class="nav-item">
                            <a class="nav-link" href="{{ url('/registercustomer') }}">
                                <i class="fas fa-user menu-icon"></i>
                                <span>Register Customer</span>
                            </a>
                        </li> --}}

                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/loadboard') }}">
                                <i class="fas fa-car menu-icon"></i>
                                <span>Load Board</span>
                            </a>
                        </li><!--end nav-item-->

                        <li class="nav-item">
                            <a class="nav-link" href="#sidebarAnalytics" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarAnalytics"> 
                                <i class="iconoir-reports menu-icon"></i>                                       
                                <span>Leads</span>
                            </a>
                            <div class="collapse " id="sidebarAnalytics">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        {{-- <a href="{{ url('/leads') }}" class="nav-link" data-bs-toggle="modal" data-bs-target="#bd-example-modal-xl">New</a> --}}
                                        <a class="nav-link" data-bs-toggle="modal" data-bs-target="#new-leads">New</a>
                                    </li><!--end nav-item-->
                                    <li class="nav-item">
                                        <a href="{{ url('/local') }}" class="nav-link ">Local</a>
                                    </li><!--end nav-item-->
                                    <li class="nav-item">
                                        <a href="{{ url('/longdistance') }}" class="nav-link ">Long Distance</a>
                                    </li><!--end nav-item-->
                                </ul><!--end nav-->
                            </div>
                        </li><!--end nav-item-->   

                        <li class="nav-item">
                            <a class="nav-link" href="#services" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarAnalytics"> 
                                <i class="iconoir-cart-alt menu-icon"></i>                                       
                                <span>Services</span>
                            </a>
                            <div class="collapse " id="services">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a href="{{ url('/delivery') }}" class="nav-link ">DELIVERY SERVICE</a>
                                    </li><!--end nav-item-->
                                    <li class="nav-item">
                                        <a href="{{ url('/furniture') }}" class="nav-link ">FURNITURE REMOVAL</a>
                                    </li><!--end nav-item-->
                                    <li class="nav-item">
                                        <a href="{{ url('/moving') }}" class="nav-link ">MOVING SERVICE</a>
                                    </li><!--end nav-item-->
                                    <li class="nav-item">
                                        <a href="{{ url('/mattress') }}" class="nav-link ">MATTRESS REMOVAL</a>
                                    </li><!--end nav-item-->
                                    <li class="nav-item">
                                        <a href="{{ url('/rearranging') }}" class="nav-link ">RE ARRANGING SERVICE</a>
                                    </li><!--end nav-item-->
                                    <li class="nav-item">
                                        <a href="{{ url('/cleaning') }}" class="nav-link ">CLEANING</a>
                                    </li><!--end nav-item-->
                                    <li class="nav-item">
                                        <a href="{{ url('/housting') }}" class="nav-link ">HOUSTING</a>
                                    </li><!--end nav-item-->
                                </ul><!--end nav-->
                            </div>
                        </li><!--end nav-item-->   

                        
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/agents') }}">
                                <i class="fab fa-github-alt menu-icon"></i>
                                <span>Agent List</span>
                            </a>
                        </li><!--end nav-item-->

                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/salesreps') }}">
                                <i class="fas fa-users menu-icon"></i>
                                <span>Sales Representatives</span>
                            </a>
                        </li><!--end nav-item-->

                        <li class="nav-item">
                            <a class="nav-link" href="#callceter" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="callceter"> 
                                <i class="fas fa-headphones menu-icon"></i>                                       
                                <span>Call Center</span>
                            </a>
                            <div class="collapse " id="callceter">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a href="{{ url('/callcenter') }}" class="nav-link ">List</a>
                                    </li><!--end nav-item-->
                                    
                                </ul><!--end nav-->
                            </div>
                        </li><!--end nav-item--> 


                        <li class="nav-item">
                            <a class="nav-link" href="#sales" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sales"> 
                                <i class="fas fa-dollar-sign menu-icon"></i>                                       
                                <span>Sales</span>
                            </a>
                            <div class="collapse " id="sales">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a href="{{ url('/salesreports') }}" class="nav-link ">Sales Report</a>
                                    </li><!--end nav-item-->
                                    <li class="nav-item">
                                        <a href="{{ url('/bestagents') }}" class="nav-link ">Best Agents</a>
                                    </li><!--end nav-item-->
                                    
                                </ul><!--end nav-->
                            </div>
                        </li><!--end nav-item--> 

                        <li class="nav-item">
                            <a class="nav-link" href="servicerates">
                                <i class="iconoir-dollar-circle menu-icon"></i>
                                <span>Service Rates</span>
                            </a>
                        </li><!--end nav-item-->


                        <li class="nav-item">
                            <a class="nav-link" href="#settings" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="settings"> 
                                <i class="fas fa-cog menu-icon"></i>                                       
                                <span>Settings</span>
                            </a>
                            <div class="collapse " id="settings">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a href="{{ url('/settings') }}" class="nav-link ">Local Inventory</a>
                                    </li><!--end nav-item-->
                                    <li class="nav-item">
                                        <a href="{{ url('/leadsource') }}" class="nav-link ">Lead Source</a>
                                    </li><!--end nav-item-->
                                    <li class="nav-item">
                                        <a href="{{ url('/stripe') }}" class="nav-link ">Stripe Account</a>
                                    </li><!--end nav-item-->
                                    <li class="nav-item">
                                        <a href="{{ url('/sms') }}" class="nav-link ">SMS API</a>
                                    </li><!--end nav-item-->
                                </ul><!--end nav-->
                            </div>
                        </li><!--end nav-item-->  
                              
                        
                    </ul><!--end navbar-nav--->
                </div>
            </div><!--end startbar-collapse-->
        </div><!--end startbar-menu-->    
    </div><!--end startbar-->
    <div class="startbar-overlay d-print-none"></div>
    <!-- end leftbar-tab-menu-->

    <div class="page-wrapper">

        <!-- Page Content-->
        <div class="page-content">