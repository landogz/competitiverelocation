@extends('includes.app')

@section('title', 'Lead Source')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <h4 class="page-title">Lead Source</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Lead Source</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Create Lead Source Section -->
        <div class="col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h4 class="card-title mb-0">Create Lead Source</h4>
                </div>
                <div class="card-body">
                    <form id="leadSourceForm" onsubmit="return false;">
                        @csrf
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter Lead Source Title">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Create
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Manage Lead Source Section -->
        <div class="col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h4 class="card-title mb-0">Manage Lead Sources</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Lead Source Title</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($leadSources) > 0)
                                    @foreach($leadSources as $leadSource)
                                    <tr>
                                        <td>{{ $leadSource->title }}</td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-info edit-btn" data-id="{{ $leadSource->id }}" data-title="{{ $leadSource->title }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $leadSource->id }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2" class="text-center">No records available</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(0,0,0,0.05);
}

.card-header {
    border-bottom: 1px solid #e5e7eb;
    padding: 1.5rem;
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

.btn {
    border-radius: 8px;
    padding: 0.6rem 1.2rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #3b82f6;
    border-color: #3b82f6;
}

.btn-primary:hover {
    background: #2563eb;
    border-color: #2563eb;
}

.btn-info {
    background: #0ea5e9;
    border-color: #0ea5e9;
    color: white;
}

.btn-info:hover {
    background: #0284c7;
    border-color: #0284c7;
    color: white;
}

.btn-danger {
    background: #ef4444;
    border-color: #ef4444;
}

.btn-danger:hover {
    background: #dc2626;
    border-color: #dc2626;
}

.table {
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    color: #1f2937;
    border-bottom-width: 1px;
}

.table td {
    vertical-align: middle;
    color: #4b5563;
}

.input-group .form-control {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.input-group .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.page-title-box {
    margin-bottom: 2rem;
}

.breadcrumb {
    margin-bottom: 0;
}

.breadcrumb-item a {
    color: #3b82f6;
    text-decoration: none;
}

.breadcrumb-item.active {
    color: #6b7280;
}

.btn-group {
    display: inline-flex;
    gap: 0.25rem;
}

.btn-group .btn {
    padding: 0.4rem 0.8rem;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    // Create Lead Source
    $('#leadSourceForm').on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const title = $('#title').val();
        
        if (!title) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please enter a lead source title'
            });
            return false;
        }

        // Show loading state
        Swal.fire({
            title: 'Saving...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '/leadsource_save',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                title: title
            },
            success: function(response) {
                // Check if there's a "No records available" message and remove it
                if ($('tbody tr td[colspan="2"]').length > 0) {
                    $('tbody').empty();
                }
                
                // Add new row to table
                const newRow = `
                    <tr>
                        <td>${response.data.title}</td>
                        <td class="text-center">                                                
                            <button type="button" class="btn btn-info edit-btn" data-id="${response.data.id}" data-title="${response.data.title}"><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-danger delete-btn" data-id="${response.data.id}"><i class="fas fa-trash-alt"></i></button>       
                        </td>
                    </tr>
                `;
                $('tbody').append(newRow);
                
                // Clear form
                $('#title').val('');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function(xhr) {
                let errorMessage = 'Something went wrong!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage
                });
            }
        });
        
        return false;
    });

    // Delete Lead Source
    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Deleting...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '/leadsource/' + id,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        row.remove();
                        
                        // Check if there are any rows left
                        if ($('tbody tr').length === 0) {
                            $('tbody').html('<tr><td colspan="2" class="text-center">No records available</td></tr>');
                        }
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Something went wrong!';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage
                        });
                    }
                });
            }
        });
    });

    // Edit Lead Source
    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        const title = $(this).data('title');
        const row = $(this).closest('tr');
        
        Swal.fire({
            title: 'Edit Lead Source',
            input: 'text',
            inputValue: title,
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'You need to write something!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Updating...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '/leadsource/' + id,
                    method: 'PUT',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        title: result.value
                    },
                    success: function(response) {
                        // Update row data
                        row.find('td:first').text(result.value);
                        row.find('.edit-btn').data('title', result.value);
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Something went wrong!';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage
                        });
                    }
                });
            }
        });
    });
});
</script>

@endsection