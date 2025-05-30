@extends('includes.app')

@section('title', 'Sales Representatives')

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">

<!-- jQuery and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
        margin-bottom: 24px;
    }

    .card-header {
        border-bottom: 1px solid #e5e7eb;
        padding: 1.5rem;
        background: transparent;
    }

    .card-header h4 {
        margin-bottom: 0;
        color: #1f2937;
    }

    .card-body {
        padding: 1.5rem;
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

    .btn-sm {
        padding: 0.4rem 0.8rem;
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        font-weight: 600;
        color: #1f2937;
        border-bottom-width: 1px;
        padding: 1rem;
    }

    .table td {
        vertical-align: middle;
        color: #4b5563;
        padding: 1rem;
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

    .form-label {
        color: #374151;
        font-weight: 500;
        margin-bottom: 0.5rem;
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

    .modal-content {
        border: none;
        border-radius: 15px;
    }

    .modal-header {
        border-bottom: 1px solid #e5e7eb;
        padding: 1.5rem;
        background: transparent;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #e5e7eb;
        padding: 1.5rem;
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper {
        display: flex;
        flex-direction: column;
    }
    
    .dataTables_scroll {
        flex: 1;
        overflow: auto;
    }
    
    .dataTables_wrapper .dataTables_scroll { overflow: hidden !important; }
    .dataTables_wrapper { overflow-x: hidden !important; }
    .table-responsive { 
        overflow-x: hidden !important;
        height: 100%;
    }
    table.dataTable { 
        width: 100% !important; 
        margin: 0 !important;
        height: 100%;
    }
    
    /* DataTables Header and Search Spacing */
    .dataTables_wrapper .row:first-child {
        margin-bottom: 1.5rem;
    }
    
    .dataTables_filter input {
        margin-bottom: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        margin-left: 0.5rem;
    }
    
    .dataTables_length select {
        margin-bottom: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
    }
    
    table.dataTable thead th {
        padding-top: 1.25rem;
        padding-bottom: 1.25rem;
        background-color: #f8f9fa;
        position: sticky;
        top: 0;
        z-index: 1;
        font-size: 0.875rem;
        font-weight: 600;
        color: #1f2937;
    }
    
    table.dataTable tbody td {
        font-size: 0.875rem;
        color: #4b5563;
        padding: 1rem;
    }

    /* Pagination Styling */
    .page-link {
        border-radius: 0.375rem;
        margin: 0 0.2rem;
    }
    
    .page-item.active .page-link {
        background-color: #556ee6;
        border-color: #556ee6;
    }

    /* DataTables Processing Indicator */
    #salesRepsTable_processing {
        z-index: 99 !important;
        background: rgba(255, 255, 255, 0.9) !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        padding: 1rem !important;
        margin-top: 1rem !important;
    }

    /* Loading Overlay */
    .table-responsive {
        position: relative;
    }

    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .loading-overlay.active {
        display: flex;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <h4 class="page-title">Sales Representatives</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Sales Representatives</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Manage Sales Representatives</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSalesRepModal">
                        <i class="fas fa-plus me-2"></i>Add Representative
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="salesRepsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Position</th>
                                    <th>Office</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Company</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesReps as $salesRep)
                                    <tr id="{{ $salesRep->id }}" data-id="{{ $salesRep->id }}">
                                        <td data-first-name="{{ $salesRep->first_name }}">{{ $salesRep->first_name }}</td>
                                        <td data-last-name="{{ $salesRep->last_name }}">{{ $salesRep->last_name }}</td>
                                        <td data-position="{{ $salesRep->position }}">{{ $salesRep->position }}</td>
                                        <td data-office="{{ $salesRep->office }}">{{ $salesRep->office }}</td>
                                        <td data-email="{{ $salesRep->email }}">{{ $salesRep->email }}</td>
                                        <td data-phone="{{ $salesRep->phone }}">{{ $salesRep->phone }}</td>
                                        <td data-agent-id="{{ $salesRep->agent_id }}">{{ $salesRep->agent->company_name }}</td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-info edit-sales-rep" 
                                                        data-id="{{ $salesRep->id }}"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editSalesRepModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger delete-sales-rep" 
                                                        data-id="{{ $salesRep->id }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-warning reset-password" data-id="{{ $salesRep->id }}" title="Reset Password">
                                                    <i class="fas fa-key"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Sales Rep Modal -->
<div class="modal fade" id="createSalesRepModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>Add Sales Representative
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createSalesRepForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="last_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Position <span class="text-danger">*</span></label>
                                <select class="form-control" name="position" required>
                                    <option value="">Select Position</option>
                                    <option value="Driver">Driver</option>
                                    <option value="Sales Representative">Sales Representative</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Office <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="office" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Company <span class="text-danger">*</span></label>
                                <select class="form-select" name="agent_id" id="agent_id_select_create" required @if(Auth::user()->privilege === 'agent') disabled @endif>
                                    <option value="">Select Company</option>
                                    @if(Auth::user()->privilege === 'agent')
                                        @php
                                            $agentId = Auth::user()->agent_id;
                                            $agent = $agentId ? \App\Models\Agent::find($agentId) : null;
                                        @endphp
                                        <option value="{{ $agent?->id ?? '' }}" selected>{{ $agent?->company_name ?? 'Unknown Company' }}</option>
                                    @else
                                        @foreach($agents as $agent)
                                            <option value="{{ $agent->id }}">{{ $agent->company_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if(Auth::user()->privilege === 'agent')
                                    <input type="hidden" name="agent_id" id="agent_id_hidden_create" value="{{ $agent?->id ?? '' }}">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Add Representative
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Sales Rep Modal -->
<div class="modal fade" id="editSalesRepModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit me-2"></i>Edit Sales Representative
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSalesRepForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="last_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Position <span class="text-danger">*</span></label>
                                <select class="form-control" name="position" required>
                                    <option value="">Select Position</option>
                                    <option value="Driver">Driver</option>
                                    <option value="Sales Representative">Sales Representative</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Office <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="office" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Company <span class="text-danger">*</span></label>
                                <select class="form-select" name="agent_id" id="agent_id_select_edit" required @if(Auth::user()->privilege === 'agent') disabled @endif>
                                    <option value="">Select Company</option>
                                    @if(Auth::user()->privilege === 'agent')
                                        @php
                                            $agentId = Auth::user()->agent_id;
                                            $agent = $agentId ? \App\Models\Agent::find($agentId) : null;
                                        @endphp
                                        <option value="{{ $agent?->id ?? '' }}" selected>{{ $agent?->company_name ?? 'Unknown Company' }}</option>
                                    @else
                                        @foreach($agents as $agent)
                                            <option value="{{ $agent->id }}">{{ $agent->company_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if(Auth::user()->privilege === 'agent')
                                    <input type="hidden" name="agent_id" id="agent_id_hidden_edit" value="{{ $agent?->id ?? '' }}">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Update Representative
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    var table = $('#salesRepsTable').DataTable({
        processing: true,
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        order: [[0, 'asc']],
        search: {
            smart: true,
            regex: true,
            caseInsensitive: true
        },
        language: {
            search: "",
            searchPlaceholder: "Search representatives...",
            emptyTable: "No sales representatives found."
        },
        columnDefs: [
            { targets: -1, className: 'text-end' }
        ]
    });


    // Function to attach event listeners to buttons
    function attachEventListeners() {
        // Remove existing event listeners first
        $('.edit-sales-rep').off('click');
        $('.delete-sales-rep').off('click');
        $('.reset-password').off('click');
        
        // Attach new event listeners
        $('.edit-sales-rep').on('click', handleEdit);
        $('.delete-sales-rep').on('click', handleDelete);
        $('.reset-password').on('click', handleResetPassword);
    }

    // Handle edit button click
    function handleEdit() {
        const salesRepId = this.dataset.id;
        const form = document.getElementById('editSalesRepForm');
        form.action = `/salesreps/${salesRepId}`;
        
        // Show loading state
        Swal.fire({
            title: 'Loading...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Fetch sales rep data and populate form
        fetch(`/salesreps/${salesRepId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const salesRep = data.salesRep;
                    form.querySelector('[name="first_name"]').value = salesRep.first_name;
                    form.querySelector('[name="last_name"]').value = salesRep.last_name;
                    form.querySelector('[name="position"]').value = salesRep.position;
                    form.querySelector('[name="office"]').value = salesRep.office;
                    form.querySelector('[name="email"]').value = salesRep.email;
                    form.querySelector('[name="phone"]').value = salesRep.phone;
                    form.querySelector('[name="agent_id"]').value = salesRep.agent_id;
                    Swal.close();
                } else {
                    throw new Error('Failed to load sales representative data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to load sales representative data',
                    icon: 'error'
                });
            });
    }

    // Handle delete button click
    function handleDelete() {
        const salesRepId = this.dataset.id;
        
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

                fetch(`/salesreps/${salesRepId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove row from table
                        table.row(`#${salesRepId}`).remove().draw();
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Sales representative has been deleted.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        throw new Error(data.message || 'Failed to delete sales representative');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: error.message || 'Failed to delete sales representative'
                    });
                });
            }
        });
    }

    // Handle reset password button click
    function handleResetPassword() {
        const salesRepId = $(this).data('id');
        Swal.fire({
            title: 'Reset Password?',
            text: 'This will reset the password to 12345678.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, reset it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show waiting/loading Swal
                Swal.fire({
                    title: 'Resetting Password...',
                    text: 'Please wait...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.ajax({
                    url: `/salesreps/${salesRepId}/reset-password`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire('Success!', response.message, 'success');
                    },
                    error: function() {
                        Swal.fire('Error!', 'Failed to reset password.', 'error');
                    }
                });
            }
        });
    }

    // Handle edit form submission
    document.getElementById('editSalesRepForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('_method', 'PUT');

        // Show loading state
        Swal.fire({
            title: 'Updating...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update table row
                const row = table.row(`#${data.salesRep.id}`);
                row.data([
                    data.salesRep.first_name,
                    data.salesRep.last_name,
                    data.salesRep.position,
                    data.salesRep.office,
                    data.salesRep.email,
                    data.salesRep.phone,
                    data.salesRep.agent.company_name,
                    `<div class="btn-group">
                        <button type="button" class="btn btn-sm btn-info edit-sales-rep" 
                                data-id="${data.salesRep.id}"
                                data-bs-toggle="modal" 
                                data-bs-target="#editSalesRepModal">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger delete-sales-rep" 
                                data-id="${data.salesRep.id}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-warning reset-password" data-id="${data.salesRep.id}" title="Reset Password">
                            <i class="fas fa-key"></i>
                        </button>
                    </div>`
                ]).draw();
                
                // Reattach event listeners
                attachEventListeners();
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editSalesRepModal'));
                modal.hide();

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Sales representative updated successfully',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                throw new Error(data.message || 'Failed to update sales representative');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.message || 'Something went wrong'
            });
        });
    });

    // Handle create form submission
    document.getElementById('createSalesRepForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        // Show loading state
        Swal.fire({
            title: 'Saving...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('/salesreps', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add new row to table
                const newRow = table.row.add([
                    data.salesRep.first_name,
                    data.salesRep.last_name,
                    data.salesRep.position,
                    data.salesRep.office,
                    data.salesRep.email,
                    data.salesRep.phone,
                    data.salesRep.agent.company_name,
                    `<div class="btn-group">
                        <button type="button" class="btn btn-sm btn-info edit-sales-rep" 
                                data-id="${data.salesRep.id}"
                                data-bs-toggle="modal" 
                                data-bs-target="#editSalesRepModal">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger delete-sales-rep" 
                                data-id="${data.salesRep.id}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-warning reset-password" data-id="${data.salesRep.id}" title="Reset Password">
                            <i class="fas fa-key"></i>
                        </button>
                    </div>`
                ]).draw().node();
                $(newRow).attr('id', data.salesRep.id).attr('data-id', data.salesRep.id);
                
                // Reattach event listeners
                attachEventListeners();
                
                // Close modal and reset form
                const modal = bootstrap.Modal.getInstance(document.getElementById('createSalesRepModal'));
                modal.hide();
                this.reset();

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Sales representative added successfully',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                throw new Error(data.message || 'Failed to add sales representative');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.message || 'Something went wrong'
            });
        });
    });

    // Attach event listeners to existing rows
    attachEventListeners();

    // Handle modal hidden events
    document.getElementById('createSalesRepModal').addEventListener('hidden.bs.modal', function () {
        this.querySelector('form').reset();
    });

    document.getElementById('editSalesRepModal').addEventListener('hidden.bs.modal', function () {
        this.querySelector('form').reset();
    });
});
</script>