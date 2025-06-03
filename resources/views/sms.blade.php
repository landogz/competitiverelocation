@extends('includes.app')

@section('title', 'SMS Settings')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">SMS Settings</h4>                               
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div><!--end row-->
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-times-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">                        
        <div class="col-md-12 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">SMS API Keys</h4>                      
                        </div><!--end col-->
                        <div class="col-auto">
                            <div class="d-flex align-items-center">
                                <div id="connectionStatus" class="me-2">
                                    @if($settings && $settings->is_active)
                                        <span class="badge bg-success">Connected</span>
                                    @else
                                        <span class="badge bg-danger">Not Connected</span>
                                    @endif
                                </div>
                                <button type="button" id="connectTwilioBtn" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plug me-1"></i>
                                    Connect to Twilio
                                </button>
                            </div>
                        </div>
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <form id="smsSettingsForm" onsubmit="return false;">
                        @csrf
                        <div class="mb-3">
                            <label for="publickey" class="form-label">Account SID (Public Key)</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="publickey" name="public_key" value="{{ $settings->public_key ?? '' }}" placeholder="Enter Twilio Account SID" required>
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title="<strong>Find your Account SID</strong><br>1. Log in to your Twilio Console<br>2. Go to Account > Account Info<br>3. Copy the Account SID (starts with 'AC')">
                                    <i class="fas fa-question-circle"></i>
                                </button>
                            </div>
                            <small class="text-muted">Your Twilio Account SID (34 characters)</small>
                            <div class="invalid-feedback">Please enter a valid Account SID</div>
                        </div>
                        <div class="mb-3">
                            <label for="secret_key" class="form-label">Auth Token</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="secret_key" name="secret_key" value="{{ $settings->secret_key ?? '' }}" required minlength="32">
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title="<strong>Find your Auth Token</strong><br>1. Log in to your Twilio Console<br>2. Go to Account > Account Info<br>3. Copy the Auth Token (32+ characters)<br><br><strong>Note:</strong> Keep this token secure and never share it">
                                    <i class="fas fa-question-circle"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">
                                Auth Token should be at least 32 characters long
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Twilio Phone Number</label>
                            <div class="input-group">
                                <input type="tel" class="form-control" id="phone_number" name="phone_number" value="{{ $settings->phone_number ?? '' }}" required pattern="\+1\d{10}" placeholder="+1XXXXXXXXXX">
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title="<strong>Find your Twilio Phone Number</strong><br>1. Log in to your Twilio Console<br>2. Go to Phone Numbers > Manage<br>3. Copy your active phone number<br><br><strong>Format:</strong> Must be in E.164 format (+1XXXXXXXXXX)">
                                    <i class="fas fa-question-circle"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">
                                Phone number should be in the format: +1XXXXXXXXXX
                            </div>
                        </div>
                        <div id="accountInfo" class="mb-3">
                            <div class="alert {{ $settings && $settings->is_active ? 'alert-info' : 'alert-secondary' }}">
                                <h5>Account Information</h5>
                                <p class="mb-1">
                                    <strong>Name:</strong> 
                                    <span id="accountName">
                                        @if($settings && $settings->is_active)
                                            {{ $settings->account_name ?? 'N/A' }}
                                        @else
                                            <span class="text-muted">Not Connected</span>
                                        @endif
                                    </span>
                                </p>
                                <p class="mb-1">
                                    <strong>Status:</strong> 
                                    <span id="accountStatus">
                                        @if($settings && $settings->is_active)
                                            {{ $settings->account_status ?? 'N/A' }}
                                        @else
                                            <span class="text-muted">Not Connected</span>
                                        @endif
                                    </span>
                                </p>
                                <p class="mb-0">
                                    <strong>Type:</strong> 
                                    <span id="accountType">
                                        @if($settings && $settings->is_active)
                                            {{ $settings->account_type ?? 'N/A' }}
                                        @else
                                            <span class="text-muted">Not Connected</span>
                                        @endif
                                    </span>
                                </p>
                            </div>
                        </div>
                        <button type="button" id="saveSettingsBtn" class="btn btn-primary">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Save Settings
                        </button>
                    </form>
                </div><!--end card-body--> 
            </div><!--end card-->
            
            <!-- Send SMS Form -->
            <div class="card mt-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">Send SMS</h4>                      
                        </div><!--end col-->
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <form id="sendSmsForm">
                        @csrf
                        <div class="mb-3">
                            <label for="recipient" class="form-label">Recipient Phone Number</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="recipient" name="to" placeholder="Enter phone number (e.g., 609-222-9282)" required>
                                <button class="btn btn-outline-secondary" type="button" id="formatPhoneBtn">
                                    <i class="fas fa-magic"></i> Format
                                </button>
                            </div>
                            <small class="text-muted">Enter any format (e.g., 609-222-9282, (609) 222-9282, 6092229282)</small>
                            <div class="invalid-feedback">Please enter a valid phone number</div>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="3" placeholder="Enter your message" required></textarea>
                            <small class="text-muted">Character count: <span id="charCount">0</span></small>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Send SMS
                        </button>
                    </form>
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col-->   
        <div class="col-md-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">SMS Logs</h4>                      
                        </div><!--end col-->
                        <div class="col-auto">
                            <div class="d-flex gap-2">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="refreshLogs">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" data-status="all">All Messages</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" data-status="sent">Sent</a></li>
                                        <li><a class="dropdown-item" href="#" data-status="failed">Failed</a></li>
                                        <li><a class="dropdown-item" href="#" data-status="delivered">Delivered</a></li>
                                        <li><a class="dropdown-item" href="#" data-status="undelivered">Undelivered</a></li>
                                    </ul>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="fas fa-download"></i> Export
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" onclick="exportTableToCSV()">Export as CSV</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="exportTableToExcel()">Export as Excel</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="smsLogsTable" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>Message ID</th>
                                <th>Content</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Price</th>
                                <th>Direction</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="text-muted" style="font-size: 0.8em;">{{ substr($log->transaction_id, 0, 8) }}...</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="d-block text-truncate" style="max-width: 200px;">{{ $log->message }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $log->from ?? 'N/A' }}</td>
                                    <td>{{ $log->recipient }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $log->created_at->format('d/m/Y') }}</span>
                                            <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $log->status === 'sent' ? 'success' : ($log->status === 'failed' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($log->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(isset($log->price))
                                            <span class="text-success">{{ $log->price }} {{ $log->price_unit }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $log->direction === 'outbound-api' ? 'primary' : 'info' }}">
                                            {{ $log->direction === 'outbound-api' ? 'Outbound' : 'Inbound' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-soft-primary" onclick="showMessageDetails('{{ $log->transaction_id }}')" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                            <span class="text-muted">No messages found</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col-->         
    </div><!--end row-->                                         
</div>

@endsection

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- DataTables -->
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
// Define showMessageDetails in global scope
function showMessageDetails(transactionId) {
    $.ajax({
        url: '{{ route("sms.status", "") }}/' + transactionId,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                let detailsHtml = `
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tr>
                                <th class="text-muted" style="width: 150px;">Message SID</th>
                                <td><code>${data.sid}</code></td>
                            </tr>
                            <tr>
                                <th class="text-muted">Status</th>
                                <td>
                                    <span class="badge bg-${data.status === 'sent' ? 'success' : (data.status === 'failed' ? 'danger' : 'warning')}">
                                        ${data.status.charAt(0).toUpperCase() + data.status.slice(1)}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">From</th>
                                <td>${data.from}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">To</th>
                                <td>${data.to}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Message</th>
                                <td>${data.body}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Direction</th>
                                <td>
                                    <span class="badge bg-${data.direction === 'outbound-api' ? 'primary' : 'info'}">
                                        ${data.direction === 'outbound-api' ? 'Outbound' : 'Inbound'}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Price</th>
                                <td>
                                    ${data.price ? 
                                        `<span class="text-success">${data.price} ${data.priceUnit}</span>` : 
                                        '<span class="text-muted">N/A</span>'
                                    }
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Date Created</th>
                                <td>${new Date(data.dateCreated).toLocaleString()}</td>
                            </tr>
                            ${data.errorCode ? `
                            <tr>
                                <th class="text-muted">Error Code</th>
                                <td><span class="text-danger">${data.errorCode}</span></td>
                            </tr>
                            <tr>
                                <th class="text-muted">Error Message</th>
                                <td><span class="text-danger">${data.errorMessage}</span></td>
                            </tr>
                            ` : ''}
                        </table>
                    </div>
                `;

                Swal.fire({
                    title: 'Message Details',
                    html: detailsHtml,
                    width: '700px',
                    showCloseButton: true,
                    showConfirmButton: false,
                    customClass: {
                        container: 'message-details-modal'
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to fetch message details'
            });
        }
    });
}

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize DataTable with modern styling
    const smsLogsTable = $('#smsLogsTable').DataTable({
        order: [[4, 'desc']], // Sort by date column
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        language: {
            search: "",
            searchPlaceholder: "Search messages...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ messages",
            infoEmpty: "No messages available",
            infoFiltered: "(filtered from _MAX_ total messages)",
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last: '<i class="fas fa-angle-double-right"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>'
            }
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        columnDefs: [
            { targets: [0, 1, 2, 3], orderable: true },
            { targets: 4, orderable: true }, // Date column
            { targets: [5, 6, 7], orderable: true },
            { targets: 8, orderable: false } // Actions column
        ]
    });

    // Initialize tooltips with custom options
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            html: true,
            trigger: 'hover',
            delay: { show: 100, hide: 100 }
        });
    });

    // Handle SMS Settings Form Submit
    $('#saveSettingsBtn').on('click', function() {
        // Basic validation
        const publicKey = $('#publickey').val().trim();
        const secretKey = $('#secret_key').val().trim();
        
        if (publicKey.length !== 34) {
            $('#publickey').addClass('is-invalid');
            return;
        } else {
            $('#publickey').removeClass('is-invalid');
        }
        
        if (secretKey.length < 32) {
            $('#secret_key').addClass('is-invalid');
            return;
        } else {
            $('#secret_key').removeClass('is-invalid');
        }
        
        // Show loading spinner
        const $button = $(this);
        const $spinner = $button.find('.spinner-border');
        $button.prop('disabled', true);
        $spinner.removeClass('d-none');
        
        // Hide previous account info
        $('#accountInfo').addClass('d-none');
        
        // Get form data
        const formData = new FormData($('#smsSettingsForm')[0]);
        
        $.ajax({
            url: '{{ route("sms.settings.update") }}',
            method: 'POST',
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
                        showConfirmButton: false,
                        timer: 1500
                    });

                    // Update account info
                    if (response.account_info) {
                        $('#accountName').text(response.account_info.friendly_name);
                        $('#accountStatus').text(response.account_info.status);
                        $('#accountType').text(response.account_info.type);
                        $('#accountInfo').removeClass('d-none');
                    }
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'Something went wrong!'
                });
            },
            complete: function() {
                // Hide loading spinner
                $button.prop('disabled', false);
                $spinner.addClass('d-none');
            }
        });
    });

    // Real-time validation for public key
    $('#publickey').on('input', function() {
        const value = $(this).val().trim();
        if (value.length === 34) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            $(this).removeClass('is-valid').addClass('is-invalid');
        }
    });

    // Real-time validation for secret key
    $('#secret_key').on('input', function() {
        const value = $(this).val().trim();
        if (value.length >= 32) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            $(this).removeClass('is-valid').addClass('is-invalid');
        }
    });

    // Function to format phone number
    function formatPhoneNumber(phoneNumber) {
        // Remove all non-numeric characters
        let cleaned = phoneNumber.replace(/\D/g, '');
        
        // If the number starts with 1, remove it
        if (cleaned.startsWith('1')) {
            cleaned = cleaned.substring(1);
        }
        
        // Check if the number is 10 digits
        if (cleaned.length === 10) {
            return '+1' + cleaned;
        }
        
        return null;
    }

    // Format phone number on input
    $('#recipient').on('input', function() {
        let value = $(this).val();
        let formatted = formatPhoneNumber(value);
        
        if (formatted) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            $(this).removeClass('is-valid').addClass('is-invalid');
        }
    });

    // Format button click handler
    $('#formatPhoneBtn').on('click', function() {
        let value = $('#recipient').val();
        let formatted = formatPhoneNumber(value);
        
        if (formatted) {
            $('#recipient').val(formatted).removeClass('is-invalid').addClass('is-valid');
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Number',
                text: 'Please enter a valid 10-digit phone number'
            });
        }
    });

    // Handle Send SMS Form Submit
    $('#sendSmsForm').on('submit', function(e) {
        e.preventDefault();
        
        // Basic validation
        const phoneNumber = $('#recipient').val().trim();
        const message = $('#message').val().trim();
        
        if (!phoneNumber || !message) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please fill in all required fields'
            });
            return;
        }
        
        // Format phone number before sending
        const formattedNumber = formatPhoneNumber(phoneNumber);
        if (!formattedNumber) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please enter a valid phone number'
            });
            return;
        }
        
        // Show loading spinner
        const $button = $(this).find('button[type="submit"]');
        const $spinner = $button.find('.spinner-border');
        $button.prop('disabled', true);
        $spinner.removeClass('d-none');
        
        $.ajax({
            url: '{{ route("sms.send") }}',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                to: formattedNumber,
                message: message
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    
                    // Clear the form
                    $('#sendSmsForm')[0].reset();
                    $('#charCount').text('0');
                    
                    // Reload logs
                    loadSmsLogs();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'Failed to send SMS. Please try again.'
                });
            },
            complete: function() {
                // Hide loading spinner
                $button.prop('disabled', false);
                $spinner.addClass('d-none');
            }
        });
    });

    // Character counter for SMS message
    $('#message').on('input', function() {
        const length = $(this).val().length;
        $('#charCount').text(length);
        
        // Add warning if message is too long
        if (length > 160) {
            $(this).addClass('is-invalid');
            $('#charCount').addClass('text-danger');
        } else {
            $(this).removeClass('is-invalid');
            $('#charCount').removeClass('text-danger');
        }
    });

    // Handle status filter
    $('.dropdown-item[data-status]').on('click', function(e) {
        e.preventDefault();
        const status = $(this).data('status');
        if (status === 'all') {
            smsLogsTable.column(5).search('').draw();
        } else {
            smsLogsTable.column(5).search(status).draw();
        }
    });

    // Refresh logs button
    $('#refreshLogs').on('click', function() {
        const $button = $(this);
        $button.prop('disabled', true).find('i').addClass('fa-spin');
        loadSmsLogs();
        setTimeout(() => {
            $button.prop('disabled', false).find('i').removeClass('fa-spin');
        }, 1000);
    });

    // Function to export table to CSV
    function exportTableToCSV() {
        const table = smsLogsTable.table().node();
        const rows = table.querySelectorAll('tr');
        let csv = [];
        
        for (let i = 0; i < rows.length; i++) {
            const row = [], cols = rows[i].querySelectorAll('td, th');
            
            for (let j = 0; j < cols.length - 1; j++) { // Exclude actions column
                let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/,/g, ';');
                row.push('"' + data + '"');
            }
            
            csv.push(row.join(','));
        }
        
        downloadCSV(csv.join('\n'), 'sms_logs.csv');
    }

    // Function to export table to Excel
    function exportTableToExcel() {
        const table = smsLogsTable.table().node();
        const wb = XLSX.utils.table_to_book(table, {sheet: "SMS Logs"});
        XLSX.writeFile(wb, 'sms_logs.xlsx');
    }

    // Function to download CSV
    function downloadCSV(csv, filename) {
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    }

    // Update the loadSmsLogs function to remove resend button
    function loadSmsLogs() {
        $.ajax({
            url: '{{ route("sms.logs") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    smsLogsTable.clear();
                    
                    if (response.data.length > 0) {
                        response.data.forEach(function(log) {
                            const statusClass = log.status === 'sent' ? 'success' : 
                                              (log.status === 'failed' ? 'danger' : 'warning');
                            const directionClass = log.direction === 'outbound-api' ? 'primary' : 'info';
                            const directionText = log.direction === 'outbound-api' ? 'Outbound' : 'Inbound';
                            
                            smsLogsTable.row.add([
                                `<div class="d-flex align-items-center">
                                    <span class="text-muted" style="font-size: 0.8em;">${log.transaction_id.substring(0, 8)}...</span>
                                </div>`,
                                `<div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <span class="d-block text-truncate" style="max-width: 200px;">${log.message}</span>
                                    </div>
                                </div>`,
                                log.from || 'N/A',
                                log.recipient,
                                `<div class="d-flex flex-column">
                                    <span>${new Date(log.created_at).toLocaleDateString()}</span>
                                    <small class="text-muted">${new Date(log.created_at).toLocaleTimeString()}</small>
                                </div>`,
                                `<span class="badge bg-${statusClass}">${log.status.charAt(0).toUpperCase() + log.status.slice(1)}</span>`,
                                log.price ? `<span class="text-success">${log.price} ${log.price_unit}</span>` : '<span class="text-muted">N/A</span>',
                                `<span class="badge bg-${directionClass}">${directionText}</span>`,
                                `<div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-soft-primary" onclick="showMessageDetails('${log.transaction_id}')" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>`
                            ]);
                        });
                    }
                    
                    smsLogsTable.draw();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Failed to load SMS logs'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load SMS logs'
                });
            }
        });
    }

    // Refresh logs every 30 seconds
    setInterval(loadSmsLogs, 30000);

    // Handle Connect to Twilio button click
    $('#connectTwilioBtn').on('click', function() {
        const $button = $(this);
        const $spinner = $('<span class="spinner-border spinner-border-sm ms-1" role="status" aria-hidden="true"></span>');
        
        $button.prop('disabled', true).append($spinner);
        
        // Check if we have credentials
        const publicKey = $('#publickey').val().trim();
        const secretKey = $('#secret_key').val().trim();
        const phoneNumber = $('#phone_number').val().trim();
        
        if (!publicKey || !secretKey || !phoneNumber) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Information',
                text: 'Please fill in all required fields before connecting to Twilio.'
            });
            $button.prop('disabled', false).find('.spinner-border').remove();
            return;
        }
        
        // Validate credentials
        $.ajax({
            url: '{{ route("sms.settings.update") }}',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                public_key: publicKey,
                secret_key: secretKey,
                phone_number: phoneNumber
            },
            success: function(response) {
                if (response.success) {
                    // Update connection status
                    $('#connectionStatus').html('<span class="badge bg-success">Connected</span>');
                    
                    // Update account info
                    if (response.account_info) {
                        $('#accountName').text(response.account_info.friendly_name);
                        $('#accountStatus').text(response.account_info.status);
                        $('#accountType').text(response.account_info.type);
                        $('#accountInfo .alert').removeClass('alert-secondary').addClass('alert-info');
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Connected!',
                        text: 'Successfully connected to Twilio account.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    $('#connectionStatus').html('<span class="badge bg-danger">Not Connected</span>');
                    // Reset account info to not connected state
                    $('#accountName').html('<span class="text-muted">Not Connected</span>');
                    $('#accountStatus').html('<span class="text-muted">Not Connected</span>');
                    $('#accountType').html('<span class="text-muted">Not Connected</span>');
                    $('#accountInfo .alert').removeClass('alert-info').addClass('alert-secondary');
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Connection Failed',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                $('#connectionStatus').html('<span class="badge bg-danger">Not Connected</span>');
                // Reset account info to not connected state
                $('#accountName').html('<span class="text-muted">Not Connected</span>');
                $('#accountStatus').html('<span class="text-muted">Not Connected</span>');
                $('#accountType').html('<span class="text-muted">Not Connected</span>');
                $('#accountInfo .alert').removeClass('alert-info').addClass('alert-secondary');
                
                Swal.fire({
                    icon: 'error',
                    title: 'Connection Failed',
                    text: xhr.responseJSON?.message || 'Failed to connect to Twilio. Please check your credentials.'
                });
            },
            complete: function() {
                $button.prop('disabled', false).find('.spinner-border').remove();
            }
        });
    });
});
</script>