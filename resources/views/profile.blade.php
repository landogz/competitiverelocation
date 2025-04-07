@extends('includes.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Settings</h4>                               
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div><!--end row-->
    <div class="row">
        <!-- Left Side: Profile Picture -->
        <div class="col-md-12 col-lg-4">
            <div class="card text-center">
                <div class="card-body">
                    <img src="/assets/images/users/avatar-1.jpg" class="rounded-circle mb-3" alt="Profile Picture" width="150" height="150">
                    <h5 class="card-title">John Doe</h5>
                    <p class="text-muted">johndoe@example.com</p>
                    <button class="btn btn-outline-primary btn-sm">Change Photo</button>
                </div>
            </div>
        </div>
    
        <!-- Right Side: Profile Details -->
        <div class="col-md-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Profile Details</h4>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" value="John">
                            </div>
                            <div class="col-md-6">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" value="Doe">
                            </div>
                        </div>
    
                        <div class="mb-3">
                            <label for="emailAddress" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="emailAddress" value="johndoe@example.com">
                        </div>
    
                        <div class="mb-3">
                            <label for="phoneNumber" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phoneNumber" value="+1 123 456 7890">
                        </div>
    
                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control" id="bio" rows="3">Lorem ipsum dolor sit amet.</textarea>
                        </div>
    
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
                                           
</div>

@endsection