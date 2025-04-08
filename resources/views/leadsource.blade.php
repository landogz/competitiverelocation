@extends('includes.app')

@section('title', 'Dashboard')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Lead Source</h4>                              
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div><!--end row-->
    <div class="row">                        
        <div class="col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">Lead Source Title</h4>     
                        </div><!--end col-->
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <form id="leadSourceForm" onsubmit="return false;">
                        @csrf
                        <div class="mb-3">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Lead Source here...">
                        </div>
                        <button type="submit" class="btn btn-primary">Create Lead Source</button>
                    </form>
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col--> 
        <div class="col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">Manage Lead Source
                            </h4>                      
                        </div><!--end col-->                                        
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 table-centered">
                            <thead class="table-light">
                            <tr>
                                <th>Lead Source Title</th>
                                <th class="text-center" style="width:150px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($leadSources) > 0)
                                @foreach($leadSources as $leadSource)
                                <tr>
                                    <td>{{ $leadSource->title }}</td>
                                    <td class="text-center">                                                
                                        <button type="button" class="btn btn-info edit-btn" data-id="{{ $leadSource->id }}" data-title="{{ $leadSource->title }}"><i class="fas fa-edit"></i></button>
                                        <button type="button" class="btn btn-danger delete-btn" data-id="{{ $leadSource->id }}"><i class="fas fa-trash-alt"></i></button>       
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" class="text-center">No records available</td>
                                </tr>
                            @endif
                            </tbody>
                        </table><!--end /table-->
                    </div>
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col-->           
    </div><!--end row-->                                         
</div>

@endsection

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