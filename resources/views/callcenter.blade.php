@extends('includes.app')

@section('title', 'Call Center')

@section('content')
<!-- Add Select2 CSS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.ckeditor.com/4.19.1/standard-all/ckeditor.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Call Center</h4>                             
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary" id="syncLeads">
                        <i class="fas fa-sync-alt me-1"></i> Sync Leads
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
                <div class="card-body position-relative">
                    <div class="loading-overlay" style="display:none; position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index:10; align-items:center; justify-content:center;">
                        <div>
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered table-sm datatable mb-0" id="datatable_1">
                            <thead class="table-light">
                              <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Sales Name</th>
                                <th>Move Date</th>
                                <th>Status</th>
                                <th>Company</th>
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

<!-- Send Email Modal -->
<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="sendEmailModalLabel"><i class="fas fa-envelope me-2"></i>Send Email</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendEmailForm">
                <input type="hidden" id="emailLeadId" name="lead_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="emailRecipient" class="form-label">To</label>
                                <input type="email" class="form-control" id="emailRecipient" name="recipient" readonly required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="emailTemplateSelect" class="form-label">Select Template</label>
                                <select class="form-select" id="emailTemplateSelect">
                                    <option value="">Select a template</option>
                                    @foreach($templates as $template)
                                        <option value="{{ $template->id }}" data-subject="{{ $template->subject }}" data-content="{{ htmlspecialchars($template->content) }}">
                                            {{ $template->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="emailSubject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="emailSubject" name="subject" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="emailMessage" class="form-label">Message</label>
                                <div class="card">
                                    <div class="card-body">
                                        <textarea id="ckeditorEmailEditor" name="message" style="height: 250px;"></textarea>
                                        <input type="hidden" id="emailMessage" name="message">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="sendEmailBtn">Send Email</button>
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
    .loading-overlay {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.7);
        z-index: 10;
        align-items: center;
        justify-content: center;
    }
    .loading-overlay.active {
        display: flex !important;
    }
    /* Hide CKEditor notifications */
    .cke_notifications_area {
        display: none !important;
    }
    /* Add this to hide notifications */
    .cke_notification {
        display: none !important;
    }
    .cke_notification_area {
        display: none !important;
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
    
    /* Fix table responsiveness */
    .table-responsive {
        overflow-x: unset;
        -webkit-overflow-scrolling: touch;
    }
    
    /* DataTable Styles */
    .dataTables_wrapper {
        padding: 0.5rem;
    }
    
    .dataTables_wrapper .row:first-child,
    .dataTables_wrapper .row:last-child {
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .dataTables_filter input {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        margin-left: 0.5rem;
    }
    
    .dataTables_length select {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        margin: 0 0.3rem;
    }
    
    .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin: 0 0.2rem;
        border-radius: 0.25rem;
        border: none;
    }
    
    .dataTables_paginate .paginate_button.current {
        background: #0d6efd !important;
        border-color: #0d6efd !important;
        color: #fff !important;
    }
    
    .dataTables_paginate .paginate_button:hover {
        background: #e9ecef;
        color: #212529 !important;
        border: none;
    }
    
    .loading-overlay {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.7);
        z-index: 1050;
        align-items: center;
        justify-content: center;
    }
    
    .loading-overlay.active {
        display: flex !important;
    }
    
    /* Card Styles */
    .card {
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }
    
    .card-header {
        border-bottom: 1px solid #eee;
    }
    
    /* Table Styles */
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
        var $overlay = $('.loading-overlay');
        
        // First, destroy any existing tables to prevent duplicates
        if ($.fn.DataTable.isDataTable('#datatable_1')) {
            $('#datatable_1').DataTable().destroy();
        }
        
        // Remove any existing DataTables controls before initialization
        $('.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate').remove();
        
        // Initialize the DataTable with deferRender and no initial load
        table = $('#datatable_1').DataTable({
            processing: true,
            serverSide: true,
            deferRender: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            ajax: {
                url: '{{ route("callcenter.datatable") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function(d) {
                    @if(Auth::user()->privilege === 'agent')
                    d.company = '{{ Auth::user()->last_name }}';
                    @endif
                },
                beforeSend: function() {
                    $overlay.addClass('active');
                },
                complete: function() {
                    $overlay.removeClass('active');
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
                        if (type === 'display') {
                            return row.date_display;
                        }
                        return data;
                    }
                },
                { 
                    data: 'status',
                    render: function(data, type, row) {
                        return data; // This ensures HTML is rendered properly
                    }
                },
                { data: 'company_name' },
                { 
                    data: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],
            order: [[0, 'desc']],
            responsive: true,
            dom: '<"row"<"col-sm-12"tr>><"row g-3 mt-3"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                infoFiltered: "(filtered from _MAX_ total entries)",
                zeroRecords: "No matching records found",
                paginate: {
                    first: '<i class="fas fa-angle-double-left"></i>',
                    previous: '<i class="fas fa-angle-left"></i>',
                    next: '<i class="fas fa-angle-right"></i>',
                    last: '<i class="fas fa-angle-double-right"></i>'
                }
            },
            drawCallback: function() {
                // Reinitialize tooltips after table redraw
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });
            },
            // Prevent initial load
            initComplete: function() {
                // Don't load data initially
                table.ajax.reload(null, false);
            }
        });

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
                    if (response.success) {
                        table.ajax.reload();
                        
                        // Build the message text based on what was actually updated
                        let messageText = '';
                        if (response.new_count > 0 && response.updated_count > 0) {
                            messageText = `Successfully synchronized ${response.new_count} new leads and ${response.updated_count} existing records`;
                        } else if (response.new_count > 0) {
                            messageText = `Successfully synchronized ${response.new_count} new leads`;
                        } else if (response.updated_count > 0) {
                            messageText = `Successfully synchronized ${response.updated_count} existing records`;
                        } else {
                            messageText = 'All records are up to date. No changes were required.';
                        }
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Synchronization Complete',
                            text: messageText,
                            confirmButtonText: 'Continue'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to sync leads'
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

        // Add silent sync function
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
                    if (response.success) {
                        table.ajax.reload();
                        // Only show toast if there are new leads
                        if (response.new_count > 0) {
                            showToast(`Added ${response.new_count} new leads`);
                        }
                    }
                },
                error: function(xhr) {
                    console.error('Silent sync failed:', xhr.responseJSON?.message || 'Unknown error');
                }
            });
        }

        // Perform initial sync when page loads
        silentSync();

        // Set up periodic silent sync every 5 minutes
        setInterval(silentSync, 300000); // 300000 ms = 5 minutes

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
            sendEmail(leadId);
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
                    <div class="modal-dialog">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="editLeadModalLabel"><i class="fas fa-edit me-2"></i>Update Lead Status</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form id="editLeadForm" action="/callcenter/${leadId}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group mb-0">
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
                                    </div>
                                </div>
                                <div class="modal-footer bg-light">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i>Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Update Status
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
            
            // Get the status from the row data and set it
            var row = table.row($(`.edit-lead[data-id="${leadId}"]`).closest('tr')).data();
            var status = '';
            
            if (row && row.status) {
                // Extract status value from the HTML badge
                var statusText = $(row.status).text().trim().toLowerCase();
                status = statusText;
            } else {
                status = 'new';
            }
            
            $('#edit_status').val(status);

            // Show the modal
            var editModal = new bootstrap.Modal(document.getElementById('editLeadModal'));
            editModal.show();

            // Handle form submission
            $('#editLeadForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                
                // Show loading state with SweetAlert2
                Swal.fire({
                    title: 'Updating Status',
                    text: 'Please wait...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Create FormData object with only the status field
                var formData = new FormData();
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                formData.append('_method', 'PUT');
                formData.append('status', $('#edit_status').val());
                
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Status updated successfully',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            // Close the modal
                            $('#editLeadModal').modal('hide');
                            
                            // Reload the DataTable
                            table.ajax.reload(null, false);
                        });
                    },
                    error: function(xhr) {
                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Failed to update status',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        }

        // Add global sendEmail function
        var ckeditorEmailEditor;
        var currentLead = {};
        window.sendEmail = function(leadId) {
            $.get(`/callcenter/${leadId}/data`, function(data) {
                currentLead = data; // Store for placeholder replacement
                // Format date fields for display in templates
                if (currentLead.date) {
                    currentLead.date_formatted = moment(currentLead.date).format('MMMM D, YYYY');
                }
                if (currentLead.created_at) {
                    currentLead.created_at_formatted = moment(currentLead.created_at).format('MMMM D, YYYY');
                }
                $('#emailLeadId').val(leadId);
                $('#emailRecipient').val(data.email);
                $('#emailSubject').val('');
                $('#emailMessage').val('');
                $('#emailTemplateSelect').val('').trigger('change');
                
                // Initialize CKEditor if not already initialized
                if (!ckeditorEmailEditor) {
                    ckeditorEmailEditor = CKEDITOR.replace('ckeditorEmailEditor', {
                        height: 250,
                        toolbarGroups: [
                            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                            { name: 'forms', groups: [ 'forms' ] },
                            '/',
                            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                            { name: 'links', groups: [ 'links' ] },
                            { name: 'insert', groups: [ 'insert' ] },
                            '/',
                            { name: 'styles', groups: [ 'styles' ] },
                            { name: 'colors', groups: [ 'colors' ] },
                            { name: 'tools', groups: [ 'tools' ] },
                            { name: 'others', groups: [ 'others' ] }
                        ],
                        extraPlugins: 'font,colorbutton,justify,tableresize,tabletools,lineutils,widget',
                        removeButtons: '',
                        startupMode: 'wysiwyg',
                        notification: {
                            duration: 0
                        }
                    });
                    
                    ckeditorEmailEditor.on('change', function() {
                        $('#emailMessage').val(ckeditorEmailEditor.getData());
                    });
                } else {
                    ckeditorEmailEditor.setData('');
                }
                
                $('#sendEmailModal').modal('show');
            });
        };

        // Helper: Replace placeholders in a string with lead data
        function replacePlaceholders(str, data) {
            if (!str) return '';
            return str.replace(/\{(\w+)\}/g, function(match, key) {
                // Directly map all placeholders to data object properties
                return typeof data[key] !== 'undefined' && data[key] !== null ? data[key] : match;
            });
        }

        // When template is selected, load subject/content and replace placeholders in both
        $('#emailTemplateSelect').on('change', function() {
            const selected = this.selectedOptions[0];
            if (!selected || !selected.value) {
                $('#emailSubject').val('');
                if (ckeditorEmailEditor) ckeditorEmailEditor.setData('');
                return;
            }
            // Replace placeholders in subject and content using current lead data
            const subject = replacePlaceholders(selected.dataset.subject, currentLead);
            const content = replacePlaceholders($('<textarea/>').html(selected.dataset.content).text(), currentLead);
            $('#emailSubject').val(subject);
            if (ckeditorEmailEditor) ckeditorEmailEditor.setData(content);
        });

        // On send, set hidden input to CKEditor HTML and send email via AJAX
        $('#sendEmailBtn').click(function() {
            $('#emailMessage').val(ckeditorEmailEditor ? ckeditorEmailEditor.getData() : '');
            var formData = {
                recipient: $('#emailRecipient').val(),
                subject: $('#emailSubject').val(),
                message: $('#emailMessage').val(),
                lead_id: $('#emailLeadId').val(),
                template_id: $('#emailTemplateSelect').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            };
            
            // Show loading state
            Swal.fire({
                title: 'Sending Email...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            $.ajax({
                url: '/callcenter/send-email',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Email sent successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        $('#sendEmailModal').modal('hide');
                        // Reset the form
                        $('#sendEmailForm')[0].reset();
                        if (ckeditorEmailEditor) ckeditorEmailEditor.setData('');
                    } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                            text: response.message || 'Failed to send email'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Something went wrong. Please try again.'
                    });
                }
            });
        });

        // Initialize Select2 for email template selection
        $('#emailTemplateSelect').select2({
            dropdownParent: $('#sendEmailModal'),
            width: '100%',
            placeholder: 'Select a template',
            allowClear: true,
            theme: 'bootstrap-5'
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Handle Add Log button click
        $(document).on('click', '.add-log-btn', function() {
            var leadId = $(this).data('lead-id');
            $('#addLogForm').attr('action', '/callcenter/' + leadId + '/logs');
            
            // Close any existing modals first
            $('.modal').modal('hide');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            $('body').css('padding-right', '');
            
            // Show the new modal
            var addLogModal = new bootstrap.Modal(document.getElementById('addLogModal'));
            addLogModal.show();
        });

        // Handle Add Log form submission
        $('#addLogForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var formData = form.serialize();
            
            // Extract leadId from the URL
            var leadId = url.split('/').filter(Boolean).pop();

            // Show loading state
            var submitBtn = form.find('button[type="submit"]');
            var originalBtnText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    console.log('Server Response:', response); // Debug log
                    
                    // Check if response is HTML (indicating an error)
                    if (typeof response === 'string' && response.trim().startsWith('<!DOCTYPE html>')) {
                        console.error('Received HTML response instead of JSON');
                        Swal.fire({
                            icon: 'error',
                            title: 'Session Error',
                            text: 'Your session may have expired. Please refresh the page and try again.'
                        });
                        return;
                    }
                    
                    // Check if response is a string and try to parse it
                    if (typeof response === 'string') {
                        try {
                            response = JSON.parse(response);
                        } catch (e) {
                            console.error('Failed to parse response:', e);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Invalid response from server. Please try again.'
                            });
                            return;
                        }
                    }

                    // Handle both success and error cases
                    if (response && (response.success || response.log)) {
                        // Close the modal
                        var addLogModal = bootstrap.Modal.getInstance(document.getElementById('addLogModal'));
                        addLogModal.hide();
                        
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Log entry added successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Add the new log entry to the table without reloading
                        var logData = response.log || response;
                        var newRow = `
                            <tr>
                                <td>${logData.created_at || new Date().toLocaleString()}</td>
                                <td>${logData.type || ''}</td>
                                <td>${logData.content || ''}</td>
                                <td>${logData.created_by || ''}</td>
                            </tr>
                        `;
                        $('#logsTable tbody').prepend(newRow);
                        
                        // Update the log count badge
                        if (response.log_count !== undefined) {
                            // Update badge for view-details button
                            var viewDetailsBadge = $(`.view-details[data-id="${leadId}"] .badge`);
                            if (viewDetailsBadge.length) {
                                viewDetailsBadge.text(response.log_count);
                            }
                            
                            // Update badge for view-logs button
                            var viewLogsBadge = $(`.view-logs[data-id="${leadId}"] .badge`);
                            if (viewLogsBadge.length) {
                                viewLogsBadge.text(response.log_count);
                            }
                            
                            // Update badge for any other elements with the same lead ID
                            var allBadges = $(`[data-id="${leadId}"] .badge`);
                            allBadges.text(response.log_count);
                        }
                        
                        // Reset the form
                        form[0].reset();
                    } else {
                        // Handle error case
                        let errorMessage = 'Failed to add log entry';
                        if (response && response.message) {
                            errorMessage = response.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', {xhr, status, error}); // Debug log
                    
                    let errorMessage = 'Failed to add log entry';
                    
                    // Check if response is HTML (indicating an error)
                    if (xhr.responseText && xhr.responseText.trim().startsWith('<!DOCTYPE html>')) {
                        errorMessage = 'Your session may have expired. Please refresh the page and try again.';
                    } else {
                        // Try to get detailed error message
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON.errors) {
                                errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                            }
                        } else if (xhr.responseText) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                errorMessage = response.message || errorMessage;
                            } catch (e) {
                                errorMessage = xhr.responseText || errorMessage;
                            }
                        }
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage
                    });
                },
                complete: function() {
                    // Reset button state
                    submitBtn.prop('disabled', false).html(originalBtnText);
                }
            });
        });
    });
</script>

<!-- Add Select2 JS before the closing body tag -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@endsection