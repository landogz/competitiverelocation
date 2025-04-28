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
        <div class="col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">SMS API Keys</h4>                      
                        </div><!--end col-->
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <form id="smsSettingsForm">
                        @csrf
                        <div class="mb-3">
                            <label for="publickey" class="form-label">Account SID (Public Key)</label>
                            <input type="text" class="form-control" id="publickey" name="public_key" value="{{ $settings->public_key ?? '' }}" placeholder="Enter Twilio Account SID">
                            <small class="text-muted">Your Twilio Account SID</small>
                        </div>
                        <div class="mb-3">
                            <label for="secretkey" class="form-label">Auth Token (Secret Key)</label>
                            <input type="password" class="form-control" id="secretkey" name="secret_key" value="{{ $settings->secret_key ?? '' }}" placeholder="Enter Twilio Auth Token">
                            <small class="text-muted">Your Twilio Auth Token</small>
                        </div>
                        <div id="accountInfo" class="mb-3 d-none">
                            <div class="alert alert-info">
                                <h5>Account Information</h5>
                                <p class="mb-1"><strong>Name:</strong> <span id="accountName"></span></p>
                                <p class="mb-1"><strong>Status:</strong> <span id="accountStatus"></span></p>
                                <p class="mb-0"><strong>Type:</strong> <span id="accountType"></span></p>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Save
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
                            <input type="text" class="form-control" id="recipient" name="to" placeholder="Enter phone number (e.g., +1234567890)">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="3" placeholder="Enter your message"></textarea>
                            <small class="text-muted">Character count: <span id="charCount">0</span></small>
                        </div>
                        <button type="submit" class="btn btn-success">Send SMS</button>
                    </form>
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col-->   
        <div class="col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">SMS Logs</h4>                      
                        </div><!--end col-->                                        
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 table-centered">
                            <thead class="table-light">
                            <tr>
                                <th>Trans #</th>
                                <th>Message</th>
                                <th>Recipient</th>
                                <th class="text-center">Date</th>
                                <th class="text-center" style="width:50px;">Status</th>
                            </tr>
                            </thead>
                            <tbody id="smsLogsTableBody">
                                @forelse($logs as $log)
                            <tr>
                                    <td>{{ $log->transaction_id }}</td>   
                                    <td>{{ $log->message }}</td>
                                    <td>{{ $log->recipient }}</td>                                
                                    <td class="text-center">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $log->status === 'sent' ? 'success' : ($log->status === 'failed' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($log->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No logs available</td>
                            </tr>
                                @endforelse
                            </tbody>
                        </table><!--end /table-->
                    </div>
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col-->         
    </div><!--end row-->                                         
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle SMS Settings Form Submit
    $('#smsSettingsForm').on('submit', function(e) {
        e.preventDefault();
        
        // Show loading spinner
        const $button = $(this).find('button[type="submit"]');
        const $spinner = $button.find('.spinner-border');
        $button.prop('disabled', true);
        $spinner.removeClass('d-none');
        
        // Hide previous account info
        $('#accountInfo').addClass('d-none');
        
        $.ajax({
            url: '{{ route("sms.settings.update") }}',
            method: 'POST',
            data: $(this).serialize(),
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

    // Handle Send SMS Form Submit
    $('#sendSmsForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("sms.send") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
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
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON.message || 'Something went wrong!'
                });
            }
        });
    });

    // Character counter for SMS message
    $('#message').on('input', function() {
        $('#charCount').text($(this).val().length);
    });

    // Function to load SMS logs
    function loadSmsLogs() {
        $.ajax({
            url: '{{ route("sms.logs") }}',
            method: 'GET',
            success: function(logs) {
                let html = '';
                if (logs.length > 0) {
                    logs.forEach(function(log) {
                        html += `
                            <tr>
                                <td>${log.transaction_id}</td>
                                <td>${log.message}</td>
                                <td>${log.recipient}</td>
                                <td class="text-center">${new Date(log.created_at).toLocaleString()}</td>
                                <td class="text-center">
                                    <span class="badge bg-${log.status === 'sent' ? 'success' : (log.status === 'failed' ? 'danger' : 'warning')}">
                                        ${log.status.charAt(0).toUpperCase() + log.status.slice(1)}
                                    </span>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    html = '<tr><td colspan="5" class="text-center">No logs available</td></tr>';
                }
                $('#smsLogsTableBody').html(html);
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
});
</script>
@endsection