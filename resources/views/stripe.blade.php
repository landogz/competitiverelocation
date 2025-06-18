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
                            <h4 class="card-title mb-0">Stripe Logs</h4>                      
                        </div><!--end col-->
                        <div class="col-auto d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" onclick="exportStripeTableToCSV()">Export as CSV</a></li>
                                <li><a class="dropdown-item" href="#" onclick="exportStripeTableToExcel()">Export as Excel</a></li>
                            </ul>
                        </div>
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="stripeLogsTable" class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Payment Method</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($payments) > 0)
                                    @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ substr($payment->payment_intent_id, 0, 8) }}...</td>
                                        <td>{{ strtoupper($payment->currency) }} {{ number_format($payment->amount, 2) }}</td>
                                        <td>
                                            @php
                                                $statusClass = $payment->status === 'succeeded' ? 'success' : 
                                                             ($payment->status === 'failed' ? 'danger' : 'warning');
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">{{ ucfirst($payment->status) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-dark">{{ $payment->payment_method ? 'Credit Card' : 'N/A' }}</span>
                                        </td>
                                        <td>{{ $payment->created_at->format('M d, Y H:i A') }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col-->         
    </div><!--end row-->                                         
</div>

@endsection

@section('scripts')
<!-- jQuery (only once, before DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<!-- DataTables JS (after jQuery) -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<!-- XLSX for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
// Define showStripeDetails in global scope
function showStripeDetails(paymentIntentId) {
    // You can implement an AJAX call here to fetch and show more details if needed
    Swal.fire({
        title: 'Stripe Payment Details',
        html: `<div class='text-center'><code>${paymentIntentId}</code></div>`,
        width: '600px',
        showCloseButton: true,
        showConfirmButton: false
    });
}

$(document).ready(function() {
    // Initialize DataTable
    const stripeLogsTable = $('#stripeLogsTable').DataTable({
        processing: true,
        serverSide: false,
        order: [[4, 'desc']], // Sort by date column
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        language: {
            search: "",
            searchPlaceholder: "Search Stripe logs...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ payments",
            infoEmpty: "No payments available",
            infoFiltered: "(filtered from _MAX_ total payments)",
            emptyTable: '<div class="text-center py-4"><i class="fas fa-inbox fa-2x text-muted mb-2"></i><br><span class="text-muted">No Stripe logs found</span></div>',
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last: '<i class="fas fa-angle-double-right"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>'
            }
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        columnDefs: [
            { targets: [0, 1, 2, 3, 4], orderable: true }
        ]
    });

    // Handle save settings button click
    $('#saveSettingsBtn').on('click', function() {
        const formData = {
            secret_key: $('#secret_key').val(),
            public_key: $('#public_key').val(),
            is_active: $('#is_active').is(':checked') ? 1 : 0,
            _token: $('input[name="_token"]').val()
        };

        $.ajax({
            url: '{{ route("stripe.settings.update") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Settings saved successfully',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message || 'Failed to save settings',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'An error occurred while saving settings',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Export to CSV
    window.exportStripeTableToCSV = function() {
        const table = stripeLogsTable.table().node();
        const rows = table.querySelectorAll('tr');
        let csv = [];
        for (let i = 0; i < rows.length; i++) {
            const row = [], cols = rows[i].querySelectorAll('td, th');
            for (let j = 0; j < cols.length; j++) {
                let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/,/g, ';');
                row.push('"' + data + '"');
            }
            csv.push(row.join(','));
        }
        const blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'stripe_logs.csv';
        link.click();
    }

    // Export to Excel
    window.exportStripeTableToExcel = function() {
        const table = stripeLogsTable.table().node();
        const wb = XLSX.utils.table_to_book(table, {sheet: "Stripe Logs"});
        XLSX.writeFile(wb, 'stripe_logs.xlsx');
    }
});
</script>