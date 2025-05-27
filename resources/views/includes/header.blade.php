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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    * {
    scrollbar-width: thin;
    scrollbar-color: #4a6bff #f1f1f1;
}
    .page-content,.modal-body{
        background-color: #EEEEEE;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    /* Password field styles */
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
                    <form id="changePasswordForm">
                        @csrf
    
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password</label>
                            <div class="password-field">
                                <input type="password" class="form-control" id="currentPassword" name="current_password" placeholder="Enter current password" required>
                                <button type="button" class="password-toggle" data-target="currentPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
    
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <div class="password-field">
                                <input type="password" class="form-control" id="newPassword" name="new_password" placeholder="Enter new password" required>
                                <button type="button" class="password-toggle" data-target="newPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary generate-password-btn" id="generatePassword">
                                <i class="fas fa-magic"></i> Generate Strong Password
                            </button>
                        </div>
    
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <div class="password-field">
                                <input type="password" class="form-control" id="confirmPassword" name="new_password_confirmation" placeholder="Confirm new password" required>
                                <button type="button" class="password-toggle" data-target="confirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
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
                        <h5 class="mb-0 fw-semibold text-truncate" id="timeBasedGreeting">
                            @if(Auth::check())
                                Good Morning, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}!
                            @else
                                Welcome!
                            @endif
                        </h5>
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
                            @if(Auth::check())
                                <img src="{{ Auth::user()->profile_image_url }}" alt="{{ Auth::user()->name }}" 
                                    class="thumb-md rounded-circle" 
                                    onerror="this.onerror=null; this.src='{{ asset('assets/images/no-profile-image.png') }}';">
                            @else
                                <img src="{{ asset('assets/images/no-profile-image.png') }}" alt="User" class="thumb-md rounded-circle">
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end py-0">
                            @if(Auth::check())
                                <div class="d-flex align-items-center dropdown-item py-2 bg-secondary-subtle">
                                    <div class="flex-shrink-0">
                                        <img src="{{ Auth::user()->profile_image_url }}" alt="{{ Auth::user()->name }}" 
                                            class="thumb-md rounded-circle"
                                            onerror="this.onerror=null; this.src='{{ asset('assets/images/no-profile-image.png') }}';">
                                    </div>
                                    <div class="flex-grow-1 ms-2 text-truncate align-self-center">
                                        <h6 class="my-0 fw-medium text-dark fs-13">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h6>
                                        <small class="text-muted mb-0">
                                            <span class="badge bg-{{ Auth::user()->privilege === 'admin' ? 'danger' : (Auth::user()->privilege === 'manager' ? 'warning' : 'info') }}">
                                                {{ ucfirst(Auth::user()->privilege) }}
                                            </span>
                                            {{ Auth::user()->position }}
                                        </small>
                                    </div><!--end media-body-->
                                </div>
                                <div class="dropdown-divider mt-0"></div>
                                <small class="text-muted px-2 pb-1 d-block">Account</small>
                                <a class="dropdown-item" id="profileLink" href="{{ route('user.profile') }}"><i class="las la-user fs-18 me-1 align-text-bottom"></i> Profile</a>
                                {{-- <a class="dropdown-item" href="pages-faq.html"><i class="las la-wallet fs-18 me-1 align-text-bottom"></i> Earning</a> --}}
                                <small class="text-muted px-2 py-1 d-block">Settings</small>                        
                                {{-- <a class="dropdown-item" href="pages-profile.html"><i class="las la-cog fs-18 me-1 align-text-bottom"></i>Account Settings</a> --}}
                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#accountSettingsModal"><i class="las la-lock fs-18 me-1 align-text-bottom"></i> Security</a>
                                <a class="dropdown-item" href="pages-faq.html"><i class="las la-question-circle fs-18 me-1 align-text-bottom"></i> Help Center</a>                       
                                <div class="dropdown-divider mb-0"></div>
                                <a class="dropdown-item text-danger" href="#" id="logoutBtn"><i class="las la-power-off fs-18 me-1 align-text-bottom"></i> Logout</a>
                            @else
                                <a class="dropdown-item" href="{{ route('login') }}"><i class="las la-sign-in-alt fs-18 me-1 align-text-bottom"></i> Login</a>
                            @endif
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
                    <img src="{{ asset('assets/images/logo-sm.png') }}" alt="logo-small" class="logo-sm">
                </span>
                <span class="">
                    <img src="{{ asset('assets/images/logo-light.png') }}" alt="logo-large" class="logo-lg logo-light">
                    <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo-large" class="logo-lg logo-dark">
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

                        @if(!(Auth::user()->privilege === 'agent' && App\Models\SalesRep::where('user_id', Auth::user()->id)->whereIn('position', ['Driver', 'Sales Representative'])->exists()))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ Auth::user()->privilege === 'agent' ? url('/loadboard-agent') : url('/loadboard') }}">
                                <i class="fas fa-car menu-icon"></i>
                                <span>Load Board</span>
                            </a>
                        </li><!--end nav-item-->
                        @endif

                        @if(Auth::user()->privilege !== 'agent')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/agents') }}">
                                <i class="fab fa-github-alt menu-icon"></i>
                                <span>Agent List</span>
                            </a>
                        </li><!--end nav-item-->
                        @endif

                        @if(!(Auth::user()->privilege === 'agent' && App\Models\SalesRep::where('user_id', Auth::user()->id)->whereIn('position', ['Driver', 'Sales Representative'])->exists()))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/salesreps') }}">
                                <i class="fas fa-users menu-icon"></i>
                                <span>Sales Representatives</span>
                            </a>
                        </li><!--end nav-item-->
                        @endif

                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/callcenter') }}">
                            <i class="fas fa-headphones menu-icon"></i>                                    
                            <span>Call Center</span>
                            </a>
                        </li><!--end nav-item-->


                        @if(Auth::user()->privilege === 'agent' && !App\Models\SalesRep::where('user_id', Auth::user()->id)->whereIn('position', ['Driver', 'Sales Representative'])->exists())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/salesreports') }}">
                                <i class="fas fa-dollar-sign menu-icon"></i>                                    
                                <span>Sales Report</span>
                            </a>
                        </li><!--end nav-item-->
                        @endif

                        @if(Auth::user()->privilege !== 'agent')
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
                                    <li class="nav-item">
                                        <a href="{{ url('/email-templates') }}" class="nav-link ">Email Templates</a>
                                    </li><!--end nav-item-->
                                </ul><!--end nav-->
                            </div>
                        </li><!--end nav-item-->  
                        @endif
                              
                        
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

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Time-based greeting
    function updateGreeting() {
        const hour = new Date().getHours();
        const greetingElement = document.getElementById('timeBasedGreeting');
        
        let greeting;
        if (hour >= 5 && hour < 12) {
            greeting = "Good Morning";
        } else if (hour >= 12 && hour < 17) {
            greeting = "Good Afternoon";
        } else if (hour >= 17 && hour < 22) {
            greeting = "Good Evening";
        } else {
            greeting = "Good Night";
        }
        
        @if(Auth::check())
            const userName = "{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}";
            greetingElement.textContent = `${greeting}, ${userName}!`;
        @else
            greetingElement.textContent = `${greeting}!`;
        @endif
    }
    
    // Update greeting immediately and then every minute
    updateGreeting();
    setInterval(updateGreeting, 60000);
    
    // Logout functionality
    document.getElementById('logoutBtn')?.addEventListener('click', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out of your account",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, logout!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the logout form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("logout") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
    
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
        document.getElementById('newPassword').value = password;
        document.getElementById('confirmPassword').value = password;
        
        // Show success message
        Swal.fire({
            title: 'Password Generated',
            text: 'A strong password has been generated and filled in both fields',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    });
    
    // Change password functionality
    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const currentPassword = document.getElementById('currentPassword').value;
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        if (newPassword !== confirmPassword) {
            Swal.fire({
                title: 'Error!',
                text: 'New passwords do not match',
                icon: 'error',
                confirmButtonColor: '#3085d6'
            });
            return;
        }
        
        // Show loading state
        Swal.fire({
            title: 'Updating Password',
            text: 'Please wait...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Create FormData object
        const formData = new FormData();
        formData.append('current_password', currentPassword);
        formData.append('new_password', newPassword);
        formData.append('new_password_confirmation', confirmPassword);
        formData.append('_token', '{{ csrf_token() }}');
        
        // Send AJAX request to update password
        fetch('{{ route("password.update") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Server error');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Your password has been updated successfully',
                    icon: 'success',
                    confirmButtonColor: '#3085d6'
                }).then(() => {
                    // Close the modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('accountSettingsModal'));
                    modal.hide();
                    
                    // Reset the form
                    document.getElementById('changePasswordForm').reset();
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message || 'Failed to update password',
                    icon: 'error',
                    confirmButtonColor: '#3085d6'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: error.message || 'An error occurred while updating your password',
                icon: 'error',
                confirmButtonColor: '#3085d6'
            });
        });
    });

    const copyBtn = document.getElementById('copyAgentUrlBtn');
    if (copyBtn) {
        copyBtn.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            navigator.clipboard.writeText(url).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'Agent URL copied to clipboard',
                    timer: 1200,
                    showConfirmButton: false
                });
            });
        });
    }

    const profileLink = document.getElementById('profileLink');
    if (profileLink) {
        profileLink.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = this.getAttribute('href');
        });
    }
});
</script>