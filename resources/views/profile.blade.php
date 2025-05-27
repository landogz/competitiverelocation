@extends('includes.app')

@section('title', 'Profile Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <h4 class="page-title">Profile Settings</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Left Side: Profile Picture -->
        <div class="col-md-12 col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <form id="photoForm" enctype="multipart/form-data">
                        @csrf
                        <div class="position-relative d-inline-block mb-4">
                            <div class="profile-image-wrapper">
                                <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('assets/images/no-profile-image.png') }}" 
                                    class="rounded-circle profile-image" alt="Profile Picture" id="profileImage">
                                <div class="profile-image-overlay">
                                    <label for="photoInput" class="btn btn-light btn-sm rounded-circle">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                </div>
                            </div>
                            <input type="file" name="photo" id="photoInput" class="d-none" accept="image/*">
                        </div>
                    </form>
                    <h4 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    <div class="d-flex justify-content-center gap-2">
                        <span class="badge bg-primary">{{ ucfirst($user->privilege) }}</span>
                        <span class="badge bg-info">{{ $user->position }}</span>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Right Side: Profile Details -->
        <div class="col-md-12 col-lg-8">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h4 class="card-title mb-0">Profile Details</h4>
                </div>
                <div class="card-body">
                    <form id="profileForm" method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $user->first_name }}">
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $user->last_name }}" {{ $user->privilege === 'agent' ? 'readonly' : '' }}>
                                @if($user->privilege === 'agent')
                                    <small class="text-muted">Last name cannot be changed for agent accounts as it is linked to your company name.</small>
                                @endif
                            </div>
                        </div>
    
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
                        </div>
    
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ $user->address }}">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ $user->city }}">
                            </div>
                            <div class="col-md-4">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" name="state" value="{{ $user->state }}">
                            </div>
                            <div class="col-md-4">
                                <label for="zip_code" class="form-label">ZIP Code</label>
                                <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ $user->zip_code }}">
                            </div>
                        </div>
    
                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control" id="bio" name="bio" rows="3">{{ $user->bio }}</textarea>
                        </div>
    
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-image-wrapper {
    position: relative;
    width: 150px;
    height: 150px;
    margin: 0 auto;
}

.profile-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.profile-image-overlay {
    position: absolute;
    bottom: 0;
    right: 0;
    background: rgba(255,255,255,0.9);
    border-radius: 50%;
    padding: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.profile-image-overlay:hover {
    transform: scale(1.1);
}

.form-control {
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 0.6rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-control:disabled {
    background-color: #f8f9fa;
    cursor: not-allowed;
}

.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(0,0,0,0.05);
}

.card-header {
    border-bottom: 1px solid #e5e7eb;
    padding: 1.5rem;
}

.btn-primary {
    padding: 0.6rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
}

.badge {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-weight: 500;
}
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Handle profile photo change
    $('#photoInput').change(function() {
        const file = this.files[0];
        if (file) {
            const formData = new FormData();
            formData.append('photo', file);
            formData.append('_token', '{{ csrf_token() }}');
            
            // Show loading state
            Swal.fire({
                title: 'Uploading...',
                text: 'Please wait while we upload your photo.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send the request
            $.ajax({
                url: '{{ route("profile.update.photo") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('Upload response:', response);
                    if (response.success) {
                        // Update all profile images on the page
                        const newImageUrl = response.photo_url + '?t=' + new Date().getTime();
                        $('#profileImage').attr('src', newImageUrl);
                        
                        // Update header profile images
                        $('.topbar .thumb-md.rounded-circle').attr('src', newImageUrl);
                        
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        throw new Error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Upload error:', {xhr, status, error});
                    let errorMessage = 'Something went wrong while uploading your photo.';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: errorMessage,
                        confirmButtonText: 'Try Again'
                    });
                }
            });
        }
    });

    // Handle profile form submission
    $('#profileForm').submit(function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        Swal.fire({
            title: 'Saving...',
            text: 'Please wait while we save your changes.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors;
                let errorMessage = 'Something went wrong while saving your changes.';
                
                if (errors) {
                    errorMessage = Object.values(errors)[0][0];
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    confirmButtonText: 'Try Again'
                });
            }
        });
    });
});
</script>

@endsection