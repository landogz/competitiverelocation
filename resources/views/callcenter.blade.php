@extends('includes.app')

@section('title', 'Call Center')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Call Center</h4>                             
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary" id="syncLeads">
                        <i class="fas fa-sync-alt me-1"></i> Sync Leads
                    </button>
                    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLeadModal">
                        <i class="fas fa-plus me-2"></i>Add New Lead
                    </button> --}}
                </div>
            </div>
        </div>
    </div>


    <div class="row">               
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="card-title mb-0">Possible Leads</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover datatable mb-0" id="datatable_1">
                            <thead class="table-light">
                              <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Sales Name</th>
                                <th>Move Date</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                                {{-- Initial data loading removed - will be populated by sync --}}
                            </tbody>
                          </table>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Lead Modal -->
<div class="modal fade" id="createLeadModal" tabindex="-1" aria-labelledby="createLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createLeadModalLabel"><i class="fas fa-plus-circle me-2"></i>Add New Lead</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createLeadForm" action="{{ route('callcenter.store') }}" method="POST" onsubmit="return false;">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required minlength="2" maxlength="255">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phone" name="phone" required minlength="5" maxlength="20">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" maxlength="255">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company" class="form-label">Company</label>
                                <select class="form-select" id="company" name="company" @if(Auth::user()->privilege === 'agent') disabled @endif>
                                    <option value="">Select Company</option>
                                    @if(Auth::user()->privilege === 'agent')
                                        <option value="{{ Auth::user()->last_name }}" selected>{{ Auth::user()->last_name }}</option>
                                    @else
                                        @foreach(\App\Models\Agent::all() as $agent)
                                            <option value="{{ $agent->company_name }}">{{ $agent->company_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if(Auth::user()->privilege === 'agent')
                                    <input type="hidden" name="company" value="{{ Auth::user()->last_name }}">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="new">New</option>
                                    <option value="contacted">Contacted</option>
                                    <option value="qualified">Qualified</option>
                                    <option value="unqualified">Unqualified</option>
                                    <option value="converted">Converted</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="source" class="form-label">Source</label>
                                <select class="form-select" id="source" name="source">
                                    <option value="">Select Source</option>
                                    <option value="website">Website</option>
                                    <option value="referral">Referral</option>
                                    <option value="social">Social Media</option>
                                    <option value="email">Email Campaign</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" maxlength="1000"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                    <button type="button" class="btn btn-primary" id="submitLeadForm">
                        <i class="fas fa-save me-1"></i>Save Lead
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Log Modal -->
<div class="modal fade" id="addLogModal" tabindex="-1" aria-labelledby="addLogModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="addLogModalLabel"><i class="fas fa-plus-circle me-2"></i>Add Log Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addLogForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="type" class="form-label">Log Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="call">Call</option>
                            <option value="email">Email</option>
                            <option value="note">Note</option>
                            <option value="meeting">Meeting</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i>Save Log
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Email Modal -->
<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="sendEmailModalLabel"><i class="fas fa-envelope me-2"></i>Send Email</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendEmailForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Email Template</label>
                                <select class="form-select" id="emailTemplate" name="template_id" required>
                                    <option value="">Select Template</option>
                                    @foreach($templates as $template)
                                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Subject</label>
                                <input type="text" class="form-control" name="subject" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Email Content</label>
                                <div class="card">
                                    <div class="card-body">
                                        <div id="emailEditor" style="height: 400px;"></div>
                                        <input type="hidden" name="content" id="emailContent">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i>Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">

<!-- jQuery and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Add Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-editor p {
        margin: 0 0 10px 0 !important;
        line-height: 1.4 !important;
    }
    .ql-editor {
        line-height: 1.4 !important;
    }
</style>
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<style>
    /* Add these styles for clickable links */
    .clickable-link {
        color: #0d6efd;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .clickable-link:hover {
        color: #0a58ca;
        text-decoration: underline;
    }
    
    .clickable-link i {
        margin-right: 0.25rem;
        font-size: 0.875rem;
    }
    
    /* Existing styles remain unchanged */
    .card {
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }
    
    .card-header {
        border-bottom: 1px solid #eee;
    }
    
    .table {
        margin-bottom: 0;
    }
    
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    /* Badge Styles */
    .badge {
        font-size: 0.85rem;
        padding: 0.5em 0.8em;
        font-weight: 500;
    }
    
    /* Button Styles */
    .btn-sm {
        padding: 0.25rem 0.5rem;
        margin: 0 0.1rem;
    }
    
    /* Form Styles */
    .form-control, .form-select {
        border-radius: 0.375rem;
        border: 1px solid #dee2e6;
        padding: 0.5rem 0.75rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    /* Modal Styles */
    .modal-content {
        border-radius: 0.5rem;
    }
    
    .modal-header {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    
    .modal-footer {
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }
    
    /* DataTable Styles */
    .dataTables_wrapper {
        padding: 1rem;
    }
    
    .dataTables_filter input,
    .dataTables_length select {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
    }
    
    .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin: 0 0.2rem;
    }
    
    .dataTables_paginate .paginate_button.current {
        background: #0d6efd !important;
        border-color: #0d6efd;
        color: #fff !important;
    }
    
    /* Responsive Styles */
    @media (max-width: 768px) {
        .page-title-box {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
    }
</style>

<script>
    // Declare table variable in a higher scope
    var table;

    $(document).ready(function() {
        // Prevent form submission on enter key
        $('#createLeadForm').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                return false;
            }
        });

        // Handle form submission only through the submit button
        $('#createLeadForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission
            return false;
        });

        // Toast function for silent sync
        function showToast(message) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: 'success',
                title: message,
                customClass: {
                    popup: 'colored-toast'
                }
            });
        }

        // Initialize DataTable
        table = $('#datatable_1').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            ajax: {
                url: '{{ route("leads.datatable") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function(d) {
                    @if(Auth::user()->privilege === 'agent')
                    d.company = '{{ Auth::user()->last_name }}';
                    @endif
                }
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'phone' },
                { data: 'email' },
                { data: 'sales_name' },
                {
                    data: 'date',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            return row.date_display;
                        }
                        return data;
                    },
                    name: 'Move Date'
                },
                { data: 'status' },
                { data: 'actions', orderable: false, searchable: false }
            ],
            order: [[0, 'desc']]
        });

        // Remove silent sync on page load
        // silentSync();

        // Add sync button functionality
        $('#syncLeads').on('click', function() {
            var button = $(this);
            button.prop('disabled', true);
            button.html('<i class="fas fa-spinner fa-spin"></i> Syncing...');

            $.ajax({
                url: "{{ route('leads.sync') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    action: 'add_new',
                    @if(Auth::user()->privilege === 'agent')
                    company: '{{ Auth::user()->last_name }}'
                    @endif
                },
                success: function(response) {
                    if (response.new_count > 0) {
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: `Added ${response.new_count} new leads`
                        });
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'No New Leads',
                            text: 'All leads are up to date'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Failed to sync leads data'
                    });
                },
                complete: function() {
                    button.prop('disabled', false);
                    button.html('<i class="fas fa-sync-alt me-1"></i> Sync Leads');
                }
            });
        });

        // Remove any existing DataTables controls before initialization
        $('.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate').remove();

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Handle Create Lead form submission
        $('#submitLeadForm').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var form = $('#createLeadForm');
            
            // Validate required fields
            var name = $('#name').val().trim();
            var phone = $('#phone').val().trim();
            var status = $('#status').val();
            
            // Additional validation to prevent empty submissions
            if (!name || name.length < 2) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Please enter a valid name (at least 2 characters)',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            
            if (!phone || phone.length < 5) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Please enter a valid phone number',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            
            if (!status) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Please select a status',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            
            // Show loading state
            Swal.fire({
                title: 'Creating Lead',
                text: 'Please wait while we save the data...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Create FormData object
            var formData = new FormData();
            
            // Add form fields with additional validation
            formData.append('name', name);
            formData.append('phone', phone);
            formData.append('email', $('#email').val().trim());
            formData.append('company', $('#company').val().trim());
            formData.append('status', status);
            formData.append('source', $('#source').val().trim());
            formData.append('notes', $('#notes').val().trim());
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            
            // Disable the submit button to prevent double submissions
            $('#submitLeadForm').prop('disabled', true);
            
            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            // Close the modal
                            $('#createLeadModal').modal('hide');
                            
                            // Reload the table data
                            table.ajax.reload();
                            
                            // Reset the form
                            form[0].reset();
                            
                            // Re-enable the submit button
                            $('#submitLeadForm').prop('disabled', false);
                        });
                    } else {
                        throw new Error(response.message);
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to create lead';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    }
                    
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                    
                    // Re-enable the submit button
                    $('#submitLeadForm').prop('disabled', false);
                }
            });
            
            return false;
        });

        // Reset form when modal is closed
        $('#createLeadModal').on('hidden.bs.modal', function () {
            $('#createLeadForm')[0].reset();
            $('#submitLeadForm').prop('disabled', false);
        });

        // Handle view details/logs button click
        $(document).on('click', '.view-details', function(e) {
            e.preventDefault();
            var leadId = $(this).data('id');
            loadLogsModal(leadId);
        });

        // Handle edit button click
        $(document).on('click', '.edit-lead', function(e) {
            e.preventDefault();
            var leadId = $(this).data('id');
            // You may want to fetch lead data via AJAX here if needed
            // For now, just call the edit modal loader
            loadEditLeadModal(leadId, {});
        });

        // Handle send quote/email button click
        $(document).on('click', '.send-quote', function(e) {
            e.preventDefault();
            var leadId = $(this).data('id');
            // You may want to open a modal for sending a quote/email
            // For now, just show a placeholder alert
            Swal.fire('Send Quote/Email', 'Lead ID: ' + leadId, 'info');
        });

        // Handle delete button click
        $(document).on('click', '.delete-lead', function(e) {
            e.preventDefault();
            var leadId = $(this).data('id');
            if (!leadId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Invalid lead ID',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            var deleteUrl = '/callcenter/' + leadId;
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting Lead',
                        text: 'Please wait...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    table.ajax.reload();
                                });
                            } else {
                                throw new Error(response.message);
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Failed to delete lead';
                            if (xhr.status === 404) {
                                errorMessage = 'Lead not found. It may have been already deleted.';
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: errorMessage,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                table.ajax.reload();
                            });
                        }
                    });
                }
            });
            return false;
        });

        // Function to load logs modal
        function loadLogsModal(leadId) {
            // Show loading state
            Swal.fire({
                title: 'Loading...',
                text: 'Please wait while we load the logs',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: `/callcenter/${leadId}/logs`,
                type: 'GET',
                success: function(response) {
                    // Close loading state
                    Swal.close();
                    
                    // Parse the HTML response to extract the logs table content
                    var logsContent = '';
                    if (typeof response === 'string') {
                        // If response is HTML
                        var tempDiv = document.createElement('div');
                        tempDiv.innerHTML = response;
                        var rows = tempDiv.querySelectorAll('tbody tr');
                        if (rows.length > 0) {
                            logsContent = Array.from(rows).map(row => row.outerHTML).join('');
                        } else {
                            logsContent = '<tr><td colspan="4" class="text-center">No logs found</td></tr>';
                        }
                    } else if (response.logs && Array.isArray(response.logs)) {
                        // If response is JSON
                        logsContent = response.logs.length > 0 ? 
                            response.logs.map(log => `
                                <tr>
                                    <td>${new Date(log.created_at).toLocaleDateString()}</td>
                                    <td><span class="badge bg-info">${log.type}</span></td>
                                    <td>${log.content}</td>
                                    <td>${log.user ? log.user.name : 'System'}</td>
                                </tr>
                            `).join('') :
                            '<tr><td colspan="4" class="text-center">No logs found</td></tr>';
                    } else {
                        logsContent = '<tr><td colspan="4" class="text-center">No logs found</td></tr>';
                    }

                    // Create modal HTML
                    var modalHtml = `
                        <div class="modal fade" id="viewLogsModal" tabindex="-1" aria-labelledby="viewLogsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="viewLogsModalLabel">
                                            <i class="fas fa-history me-2"></i>Lead Logs
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Type</th>
                                                        <th>Content</th>
                                                        <th>User</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${logsContent}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i>Close
                                        </button>
                                        <button type="button" class="btn btn-primary add-log-btn" data-lead-id="${leadId}">
                                            <i class="fas fa-plus me-1"></i>Add Log
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Remove any existing modal
                    $('#viewLogsModal').remove();
                    
                    // Add the new modal to the body
                    $('body').append(modalHtml);
                    
                    // Show the modal
                    var viewLogsModal = new bootstrap.Modal(document.getElementById('viewLogsModal'));
                    viewLogsModal.show();
                },
                error: function(xhr) {
                    // Show error message
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to load lead logs',
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });
                }
            });
        }

        // Function to reload table data without refreshing the page
        function reloadTableData() {
            // Show loading state with SweetAlert2
            Swal.fire({
                title: 'Refreshing Data',
                text: 'Please wait...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Reload the DataTable with the current filter
            table.ajax.reload(null, false);
            
            // Close loading state
            Swal.close();
        }

        // Function to load edit lead modal
        function loadEditLeadModal(leadId, leadData) {
            // Create edit modal HTML
            var modalHtml = `
                <div class="modal fade" id="editLeadModal" tabindex="-1" aria-labelledby="editLeadModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="editLeadModalLabel"><i class="fas fa-edit me-2"></i>Edit Lead</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form id="editLeadForm" action="/callcenter/${leadId}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_name" class="form-label">Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="edit_name" name="name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="edit_phone" name="phone" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="edit_email" name="email">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_company" class="form-label">Company</label>
                                                <input type="text" class="form-control" id="edit_company" name="company">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_status" class="form-label">Status <span class="text-danger">*</span></label>
                                                <select class="form-select" id="edit_status" name="status" required>
                                                    <option value="">Select Status</option>
                                                    <option value="new">New</option>
                                                    <option value="contacted">Contacted</option>
                                                    <option value="qualified">Qualified</option>
                                                    <option value="unqualified">Unqualified</option>
                                                    <option value="converted">Converted</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_source" class="form-label">Source</label>
                                                <select class="form-select" id="edit_source" name="source">
                                                    <option value="">Select Source</option>
                                                    <option value="website">Website</option>
                                                    <option value="referral">Referral</option>
                                                    <option value="social">Social Media</option>
                                                    <option value="email">Email Campaign</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="edit_notes" class="form-label">Notes</label>
                                                <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i>Close
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Update Lead
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;

            // Remove any existing edit modal
            $('#editLeadModal').remove();
            
            // Add the new modal to the body
            $('body').append(modalHtml);
            
            // Set form values
            $('#edit_name').val(leadData.name);
            $('#edit_phone').val(leadData.phone);
            $('#edit_email').val(leadData.email);
            $('#edit_company').val(leadData.company);
            
            // Get the status from the badge text and convert to lowercase
            var status = leadData.status || 'new';
            $('#edit_status').val(status);

            // Get the source from the row data
            var source = leadData.source || 'website';
            $('#edit_source').val(source);

            // Set notes if available
            $('#edit_notes').val(leadData.notes || '');

            // Show the modal
            var editModal = new bootstrap.Modal(document.getElementById('editLeadModal'));
            editModal.show();

            // Handle form submission
            $('#editLeadForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                
                // Show loading state with SweetAlert2
                Swal.fire({
                    title: 'Updating Lead',
                    text: 'Please wait while we update the data...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Create FormData object
                var formData = new FormData();
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                formData.append('_method', 'PUT');
                formData.append('name', $('#edit_name').val());
                formData.append('phone', $('#edit_phone').val());
                formData.append('email', $('#edit_email').val());
                formData.append('company', $('#edit_company').val());
                formData.append('status', $('#edit_status').val());
                formData.append('source', $('#edit_source').val());
                formData.append('notes', $('#edit_notes').val());
                
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Show success message with SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Lead updated successfully',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            // Close the modal
                            $('#editLeadModal').modal('hide');
                            
                            // Reload the table data
                            reloadTableData();
                        });
                    },
                    error: function(xhr) {
                        // Show error message with SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Failed to update lead',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        }

        // Initialize Quill editor for email content
        var emailQuill = new Quill('#emailEditor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'font': [] }, { 'size': [] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'header': 1 }, { 'header': 2 }, 'blockquote', 'code-block'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'align': [] }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });

        // Handle send email button click
        $(document).on('click', '.send-email-btn', function(e) {
            e.preventDefault();
            var leadId = $(this).data('lead-id');
            var row = $(this).closest('tr');
            var leadEmail = row.find('td:eq(3)').text().trim();
            
            // Set the form action
            $('#sendEmailForm').attr('action', `/callcenter/${leadId}/send-email`);
            
            // Reset form and show modal
            $('#sendEmailForm')[0].reset();
            emailQuill.root.innerHTML = '';
            
            // Show the modal
            var sendEmailModal = new bootstrap.Modal(document.getElementById('sendEmailModal'));
            sendEmailModal.show();
        });

        // Handle email template selection change
        $('#emailTemplate').on('change', function() {
            var templateId = $(this).val();
            if (templateId) {
                // Show loading state
                Swal.fire({
                    title: 'Loading...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Fetch template content
                fetch(`/email-templates/${templateId}`)
                    .then(response => response.json())
                    .then(data => {
                        emailQuill.root.innerHTML = data.content || '';
                        $('#sendEmailForm [name="subject"]').val(data.subject || '');
                        Swal.close();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to load template'
                        });
                    });
            }
        });

        // Handle send email form submission
        $('#sendEmailForm').on('submit', function(e) {
            e.preventDefault();
            document.getElementById('emailContent').value = emailQuill.root.innerHTML;
            
            // Show loading state
            Swal.fire({
                title: 'Sending Email...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Close modal
                        $('#sendEmailModal').modal('hide');
                        
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Email sent successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        throw new Error(response.message || 'Failed to send email');
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Something went wrong. Please try again later.'
                    });
                }
            });
        });

        // Handle Add Log button click
        $(document).on('click', '.add-log-btn', function(e) {
            e.preventDefault();
            var leadId = $(this).data('lead-id') || $(this).data('id');
            // Close any open modals first
            $('.modal').modal('hide');
            // Set the form action
            $('#addLogForm').attr('action', `/callcenter/${leadId}/logs`);
            // Show the modal after a short delay to allow backdrop to clear
            setTimeout(function() {
                var addLogModal = new bootstrap.Modal(document.getElementById('addLogModal'));
                addLogModal.show();
            }, 400);
        });

        // Handle Add Log form submission via AJAX
        $('#addLogForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var leadId = url.split('/')[2]; // Extract lead ID from URL
            // Show loading state
            Swal.fire({
                title: 'Saving...',
                text: 'Please wait while we save the log',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => { Swal.showLoading(); }
            });
            // Create FormData object
            var formData = new FormData(form[0]);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Log added successfully',
                        icon: 'success',
                        confirmButtonText: 'Close'
                    });
                    // Close the modal
                    $('#addLogModal').modal('hide');
                    // Reset the form
                    form[0].reset();
                    // Reload logs modal
                    loadLogsModal(leadId);
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Something went wrong',
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });
                }
            });
        });
    });
</script>

@endsection