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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLeadModal">
                        <i class="fas fa-plus me-2"></i>Add New Lead
                    </button>
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
                                <th>Date</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                                @forelse($leads as $lead)
                                    @if(Auth::user()->privilege === 'agent')
                                        {{-- Debug information --}}
                                        <tr class="debug-info" style="display: none;">
                                            <td colspan="7">
                                                Debug: Lead Company: {{ $lead->company }} | User Last Name: {{ Auth::user()->last_name }}
                                            </td>
                                        </tr>
                                        @if(strtolower($lead->company) === strtolower(Auth::user()->last_name))
                                        <tr data-source="{{ $lead->source }}" data-notes="{{ $lead->notes }}">
                                            <td>{{ $lead->created_at->format('m/d/Y') }}</td>
                                            <td>{{ $lead->name }}</td>
                                            <td>
                                                @if($lead->phone)
                                                    <a href="tel:{{ $lead->phone }}" class="clickable-link">
                                                        <i class="fas fa-phone-alt"></i>{{ $lead->phone }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>     
                                            <td>
                                                @if($lead->email)
                                                    <a href="mailto:{{ $lead->email }}" class="clickable-link">
                                                        <i class="fas fa-envelope"></i>{{ $lead->email }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>        
                                            <td>{{ $lead->company }}</td>
                                            <td>
                                                <span class="badge bg-{{ $lead->status === 'new' ? 'warning' : ($lead->status === 'contacted' ? 'info' : ($lead->status === 'qualified' ? 'success' : ($lead->status === 'unqualified' ? 'danger' : 'primary'))) }}">
                                                    {{ ucfirst($lead->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">                                                
                                                <button type="button" class="btn btn-sm btn-warning view-logs-btn" data-lead-id="{{ $lead->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="View Logs">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-primary edit-lead-btn" data-lead-id="{{ $lead->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Lead">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger delete-lead-btn" data-lead-id="{{ $lead->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Lead">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endif
                                    @else
                                        <tr data-source="{{ $lead->source }}" data-notes="{{ $lead->notes }}">
                                            <td>{{ $lead->created_at->format('m/d/Y') }}</td>
                                            <td>{{ $lead->name }}</td>
                                            <td>
                                                @if($lead->phone)
                                                    <a href="tel:{{ $lead->phone }}" class="clickable-link">
                                                        <i class="fas fa-phone-alt"></i>{{ $lead->phone }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>     
                                            <td>
                                                @if($lead->email)
                                                    <a href="mailto:{{ $lead->email }}" class="clickable-link">
                                                        <i class="fas fa-envelope"></i>{{ $lead->email }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>        
                                            <td>{{ $lead->company }}</td>
                                            <td>
                                                <span class="badge bg-{{ $lead->status === 'new' ? 'warning' : ($lead->status === 'contacted' ? 'info' : ($lead->status === 'qualified' ? 'success' : ($lead->status === 'unqualified' ? 'danger' : 'primary'))) }}">
                                                    {{ ucfirst($lead->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">                                                
                                                <button type="button" class="btn btn-sm btn-warning view-logs-btn" data-lead-id="{{ $lead->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="View Logs">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-primary edit-lead-btn" data-lead-id="{{ $lead->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Lead">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger delete-lead-btn" data-lead-id="{{ $lead->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Lead">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No leads found</td>
                                </tr>
                                @endforelse
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
            <form action="{{ route('callcenter.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
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
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                    <button type="submit" class="btn btn-primary">
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
    $(document).ready(function() {
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

        // Perform silent sync on page load
        function silentSync() {
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
                        // Reload table data silently if new leads were added
                        $('#datatable_1').DataTable().ajax.reload(null, false);
                        
                        // Update the stats boxes with fresh counts using IDs
                        $('#totalLeads').text(parseInt($('#totalLeads').text()) + response.new_count);
                        $('#newLeads').text(parseInt($('#newLeads').text()) + response.new_count);
                        $('#contactedLeads').text(parseInt($('#contactedLeads').text()) + response.new_count);
                        $('#qualifiedLeads').text(parseInt($('#qualifiedLeads').text()) + response.new_count);

                        // Show toast notification
                        showToast(`${response.new_count} new lead${response.new_count > 1 ? 's' : ''} added`);
                    }
                },
                error: function(xhr) {
                    console.log('Silent sync failed:', xhr.responseJSON?.message || 'Unknown error');
                }
            });
        }

        // Add custom styles for toast
        $('<style>')
            .text(`
                .colored-toast.swal2-icon-success {
                    background-color: #a5dc86 !important;
                    color: white !important;
                }
                .colored-toast .swal2-title {
                    color: white !important;
                }
                .colored-toast .swal2-close {
                    color: white !important;
                }
                .colored-toast .swal2-timer-progress-bar {
                    background: rgba(255, 255, 255, 0.7) !important;
                }
            `)
            .appendTo('head');

        // Initialize DataTable
        var table = $('#datatable_1').DataTable({
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
                { data: 'created_at', name: 'created_at' },
                { data: 'name', name: 'name' },
                { data: 'phone', name: 'phone' },
                { data: 'email', name: 'email' },
                { data: 'company', name: 'company' },
                { data: 'status', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            order: [[0, 'desc']]
        });

        // Perform silent sync after DataTable initialization
        silentSync();

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

        // Handle Create Lead form submission via AJAX
        $('#createLeadModal form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            
            // Show loading state with SweetAlert2
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
            
            $.ajax({
                type: 'POST',
                url: url,
                data: form.serialize(),
                success: function(response) {
                    // Show success message with SweetAlert2
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Lead created successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Close the modal
                        $('#createLeadModal').modal('hide');
                        
                        // Reload the table data without refreshing the page
                        reloadTableData();
                        
                        // Reset the form
                        form[0].reset();
                    });
                },
                error: function(xhr) {
                    // Show error message with SweetAlert2
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Failed to create lead',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Handle view logs button click
        $(document).on('click', '.view-logs-btn', function(e) {
            e.preventDefault();
            var leadId = $(this).data('lead-id');
            loadLogsModal(leadId);
        });

        // Handle Add Log button click
        $(document).on('click', '.add-log-btn', function(e) {
            e.preventDefault();
            var leadId = $(this).data('lead-id');
            
            // Close any open modals first
            $('.modal').modal('hide');
            
            // Set the form action
            $('#addLogForm').attr('action', `/callcenter/${leadId}/logs`);
            
            // Show the modal
            var addLogModal = new bootstrap.Modal(document.getElementById('addLogModal'));
            addLogModal.show();
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
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Create FormData object
            var formData = new FormData();
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('type', $('#type').val());
            formData.append('content', $('#content').val());
            
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Show success message
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
                    
                    // Update the badge count
                    var viewLogsBtn = $('.view-logs-btn[data-lead-id="' + leadId + '"]');
                    var currentBadge = viewLogsBtn.find('.badge');
                    var currentCount = currentBadge.length ? parseInt(currentBadge.text()) : 0;
                    
                    if (currentBadge.length) {
                        currentBadge.text(currentCount + 1);
                    } else {
                        viewLogsBtn.prepend('<span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6em; padding: 0.25em 0.4em;">1</span>');
                    }
                    
                    // Reload logs modal
                    loadLogsModal(leadId);
                },
                error: function(xhr) {
                    // Show error message
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Something went wrong',
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });
                }
            });
        });

        // Handle edit button click
        $(document).on('click', '.edit-lead-btn', function(e) {
            e.preventDefault();
            var leadId = $(this).data('lead-id');
            
            // Get lead data from the table row
            var row = $(this).closest('tr');
            var leadData = {
                name: row.find('td:eq(1)').text().trim(),
                phone: row.find('td:eq(2)').text().trim(),
                email: row.find('td:eq(3)').text().trim(),
                company: row.find('td:eq(4)').text().trim(),
                status: row.find('td:eq(5) .badge').text().trim().toLowerCase(),
                source: row.data('source') || 'website', // Default to 'website' if not set
                notes: row.data('notes') || '' // Get notes from data attribute
            };
            
            loadEditLeadModal(leadId, leadData);
        });

        // Handle delete button click
        $(document).on('click', '.delete-lead-btn', function(e) {
            e.preventDefault();
            var leadId = $(this).data('lead-id');
            
            // Show confirmation dialog with SweetAlert2
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
                    // Show loading state
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
                    
                    // Create a form and submit it via AJAX
                    var formData = new FormData();
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                    formData.append('_method', 'DELETE');
                    
                    $.ajax({
                        url: '/callcenter/' + leadId,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Lead has been deleted successfully',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                // Reload the table data
                                reloadTableData();
                            });
                        },
                        error: function(xhr) {
                            // Show error message
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Failed to delete lead',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
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
    });
</script>

@endsection