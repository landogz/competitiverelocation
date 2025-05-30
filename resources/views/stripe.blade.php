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
        <div class="col-md-12 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">Stripe Connect</h4>                      
                        </div><!--end col-->
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form id="stripeSettingsForm" class="mt-4" onsubmit="return false;">
                        @csrf
                        <div class="mb-3">
                            <label for="secret_key" class="form-label">Secret Key</label>
                            <input type="text" class="form-control" id="secret_key" name="secret_key" 
                                value="{{ $settings->secret_key ?? '' }}" required>
                            <div class="form-text">Your Stripe secret key starting with 'sk_'</div>
                        </div>

                        <div class="mb-3">
                            <label for="public_key" class="form-label">Public Key</label>
                            <input type="text" class="form-control" id="public_key" name="public_key" 
                                value="{{ $settings->public_key ?? '' }}" required>
                            <div class="form-text">Your Stripe public key starting with 'pk_'</div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                    {{ ($settings->is_active ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>

                        @if(isset($settings->last_error))
                        <div class="mb-3">
                            <label class="form-label">Last Error</label>
                            <input type="text" class="form-control" value="{{ $settings->last_error }}" readonly>
                        </div>
                        @endif

                        @if(isset($settings->last_checked_at))
                        <div class="mb-3">
                            <label class="form-label">Last Checked</label>
                            <input type="text" class="form-control" value="{{ $settings->last_checked_at }}" readonly>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" id="saveSettingsBtn" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Settings
                            </button>
                            
                            @if(!($settings->is_active ?? false))
                            <a href="{{ route('stripe.connect') }}" class="btn btn-success">
                                <i class="fab fa-stripe me-2"></i>Connect with Stripe
                            </a>
                            @endif
                        </div>
                    </form>
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col-->   
        <div class="col-md-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">Stripe Logs</h4>                      
                        </div><!--end col-->                                        
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 table-centered">
                            <thead class="table-light">
                            <tr>
                                <th>Transaction ID</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Payment Method</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_intent_id }}</td>
                                <td>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</td>
                                <td>
                                    @php
                                        $statusClass = $payment->status === 'succeeded' ? 'success' : 
                                                     ($payment->status === 'failed' ? 'danger' : 'warning');
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ ucfirst($payment->status) }}</span>
                                </td>
                                <td>{{ $payment->payment_method ? 'Credit Card' : 'N/A' }}</td>
                                <td>{{ $payment->created_at->format('M d, Y H:i A') }}</td>
                            </tr>
                            @endforeach
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

<script>
$(document).ready(function() {
    $('#saveSettingsBtn').on('click', function() {
        var $btn = $(this);
        var originalText = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...').prop('disabled', true);
        
        // Get form data
        var data = {
            secret_key: $('#secret_key').val(),
            public_key: $('#public_key').val(),
            is_active: $('#is_active').is(':checked') ? 1 : 0,
            _token: '{{ csrf_token() }}'
        };
        
        // Send AJAX request
        $.ajax({
            url: '{{ route("stripe.settings.update") }}',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        confirmButtonText: 'OK'
                    });
                    
                    // Update connect button visibility
                    var $connectBtn = $('a[href*="stripe.connect"]');
                    if ($connectBtn.length) {
                        $connectBtn.toggle(!response.is_active);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Failed to save settings',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to save settings. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: message,
                    confirmButtonText: 'OK'
                });
            },
            complete: function() {
                $btn.html(originalText).prop('disabled', false);
            }
        });
    });
});
</script>