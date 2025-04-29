@extends('includes.app')

@section('title', 'Load Board')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Load Board</h4>
                <div class="page-title-right">
                    <button class="btn btn-primary" id="syncTransactions">
                        <i class="fas fa-sync-alt me-1"></i> Sync Transactions
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">                        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 rounded-3 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2 fs-14">Total Transactions</p>
                            <h3 class="mb-0 fw-bold">{{ $transactions->count() }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-md rounded-circle bg-primary bg-opacity-10 p-3">
                                <i class="fas fa-truck text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 rounded-3 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2 fs-14">Pending</p>
                            <h3 class="mb-0 fw-bold">{{ $transactions->where('status', 'pending')->count() }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-md rounded-circle bg-warning bg-opacity-10 p-3">
                                <i class="fas fa-clock text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 rounded-3 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2 fs-14">In Progress</p>
                            <h3 class="mb-0 fw-bold">{{ $transactions->where('status', 'in_progress')->count() }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-md rounded-circle bg-info bg-opacity-10 p-3">
                                <i class="fas fa-spinner text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 rounded-3 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2 fs-14">Completed</p>
                            <h3 class="mb-0 fw-bold">{{ $transactions->where('status', 'completed')->count() }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-md rounded-circle bg-success bg-opacity-10 p-3">
                                <i class="fas fa-check text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 rounded-3 shadow-sm">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="transactionsTable" class="table table-centered align-middle table-hover mb-0">
                            <thead class="text-muted bg-light">
                                <tr>
                                    <th class="border-0">ID</th>
                                    <th class="border-0">Customer</th>
                                    <th class="border-0 date-column">Move Date</th>
                                    <th class="border-0">Service</th>
                                    <th class="border-0">Pickup</th>
                                    <th class="border-0">Delivery</th>
                                    <th class="border-0">Miles</th>
                                    <th class="border-0">Total</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->transaction_id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <!-- <div class="avatar-sm rounded-circle bg-primary bg-opacity-10 p-2">
                                                <span class="text-primary fw-semibold">{{ substr($transaction->firstname, 0, 1) }}{{ substr($transaction->lastname, 0, 1) }}</span>
                                            </div> -->
                                            <div>
                                                <h6 class="mb-0">{{ $transaction->firstname }} {{ $transaction->lastname }}</h6>
                                                <small class="text-muted">{{ $transaction->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="date-column">{{ $transaction->date ? $transaction->date->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        @php
                                            $services = is_string($transaction->services) ? json_decode($transaction->services, true) : $transaction->services;
                                            $serviceNames = [];
                                            
                                            if (is_array($services) && count($services) > 0) {
                                                foreach ($services as $service) {
                                                    $serviceNames[] = trim($service['name']);
                                                }
                                            } else {
                                                $serviceNames[] = trim($transaction->service);
                                            }
                                            // Join with explicit newline character
                                            $formattedServices = implode("\n", $serviceNames);
                                        @endphp
                                        <span class="badge bg-primary bg-opacity-10 text-primary service-badge">{{ $formattedServices }}</span>
                                    </td>
                                    <td>{{ $transaction->pickup_location }}</td>
                                    <td>{{ $transaction->delivery_location }}</td>
                                    <td>{{ number_format($transaction->miles) }}</td>
                                    <td><span class="fw-semibold">${{ number_format($transaction->grand_total, 2) }}</span></td>
                                    <td>
                                        @php
                                            $statusClass = [
                                                'completed' => 'bg-success-subtle text-success',
                                                'in_progress' => 'bg-info-subtle text-info',
                                                'cancelled' => 'bg-danger-subtle text-danger',
                                                'pending' => 'bg-warning-subtle text-warning'
                                            ][$transaction->status] ?? 'bg-warning-subtle text-warning';
                                        @endphp
                                        <span class="badge {{ $statusClass }} rounded-pill px-3">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm rounded-pill px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu shadow-sm border-0">
                                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#transactionModal{{ $transaction->id }}">
                                                    <i class="fas fa-eye me-2 text-muted"></i> View Details
                                                </a></li>
                                                <li><a class="dropdown-item" href="{{ route('leads.edit', $transaction->id) }}">
                                                    <i class="fas fa-edit me-2 text-primary"></i> Edit
                                                </a></li>
                                                <li><a class="dropdown-item" href="#" onclick="updateStatus('{{ $transaction->id }}', 'pending')">
                                                    <i class="fas fa-clock me-2 text-warning"></i> Mark as Pending
                                                </a></li>
                                                <li><a class="dropdown-item" href="#" onclick="updateStatus('{{ $transaction->id }}', 'in_progress')">
                                                    <i class="fas fa-spinner me-2 text-info"></i> Mark In Progress
                                                </a></li>
                                                <li><a class="dropdown-item" href="#" onclick="updateStatus('{{ $transaction->id }}', 'completed')">
                                                    <i class="fas fa-check me-2 text-success"></i> Mark Completed
                                                </a></li>
                                                <li><a class="dropdown-item" href="#" onclick="updateStatus('{{ $transaction->id }}', 'cancelled')">
                                                    <i class="fas fa-times me-2 text-danger"></i> Cancel
                                                </a></li>
                                            </ul>
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

<!-- Transaction Modals -->
@foreach($transactions as $transaction)
<div class="modal fade" id="transactionModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="transactionModalLabel{{ $transaction->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionModalLabel{{ $transaction->id }}">Transaction Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                
            <hr>
                <div class="row">

                <div style="font-size: 1rem; display: flex; align-items: center; flex-wrap: wrap;justify-content: center;">
                            <strong>Lead Source:</strong>&nbsp;
                            @php
                                // Prefer joined field if available
                                $leadSourceName = $transaction->lead_source_title ?? null;
                                if (!$leadSourceName) {
                                    $leadSourceName = (!empty($transaction->lead_source) && isset($leadSources[$transaction->lead_source])) ? $leadSources[$transaction->lead_source] : 'N/A';
                                }
                            @endphp
                            {{ $leadSourceName }}
                            <span class="mx-3">|</span>
                            <strong>Lead Type:</strong>&nbsp;
                            @php
                                $leadTypeLabel = $transaction->lead_type === 'local' ? 'Local' : ($transaction->lead_type === 'long_distance' ? 'Long Distance' : 'N/A');
                            @endphp
                            {{ $leadTypeLabel }}
                            <span class="mx-3">|</span>
                            <strong>Assigned Agent:</strong>&nbsp;
                            @php
                                // Prefer joined field if available
                                $agentName = $transaction->assigned_agent_company_name ?? null;
                                if (!$agentName) {
                                    $agentName = (!empty($transaction->assigned_agent) && isset($agents[$transaction->assigned_agent])) ? $agents[$transaction->assigned_agent] : 'N/A';
                                }
                            @endphp
                            {{ $agentName }}
                        </div>
                </div>
                
                
            <hr>
    <div class="row">                        
                    <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <p><strong>Name:</strong> {{ $transaction->firstname }} {{ $transaction->lastname }}</p>
                        <p>
                            <strong>Email:</strong> 
                            <a href="mailto:{{ $transaction->email }}" class="contact-link">
                                <i class="fas fa-envelope"></i> {{ $transaction->email }}
                            </a>
                        </p>
                        <p>
                            <strong>Phone:</strong> 
                            <a href="tel:{{ $transaction->phone }}" class="contact-link">
                                <i class="fas fa-phone"></i> {{ $transaction->phone }}
                            </a>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Sales Information</h6>
                        <p><strong>Name:</strong> {{ $transaction->sales_name }}</p>
                        <p>
                            <strong>Email:</strong> 
                            <a href="mailto:{{ $transaction->sales_email }}" class="contact-link">
                                <i class="fas fa-envelope"></i> {{ $transaction->sales_email }}
                            </a>
                        </p>
                        <p><strong>Location:</strong> {{ $transaction->sales_location }}</p>
                    </div>
                </div>
                
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <h6>Service Details</h6>
                        @php
                            $services = is_string($transaction->services) ? json_decode($transaction->services, true) : $transaction->services;
                            $totalItems = 0;
                            $totalCrew = 0;
                            $totalSubtotal = 0;
                        @endphp
                        
                        @if(is_array($services) && count($services) > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                        <thead>
                            <tr>
                                            <th>Service</th>
                                            <th>Rate</th>
                                            <th>Crew</th>
                                            <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                                        @foreach($services as $service)
                                            @php
                                                $totalItems++;
                                                $totalCrew += intval($service['no_of_crew']);
                                                $totalSubtotal += floatval(str_replace(['$', ','], '', $service['subtotal']));
                                            @endphp
                                            <tr>
                                                <td>{{ $service['name'] }}</td>
                                                <td>{{ $service['rate'] }}</td>
                                                <td>{{ $service['no_of_crew'] }}</td>
                                                <td>{{ $service['subtotal'] }}</td>
                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2">Total</th>
                                            <th>{{ $totalCrew }}</th>
                                            <th>${{ number_format($totalSubtotal, 2) }}</th>
                            </tr>
                                    </tfoot>
                    </table>
                            </div>
                        @else
                            <p><strong>Service:</strong> {{ $transaction->service }}</p>
                            <p><strong>Items:</strong> {{ $transaction->no_of_items }}</p>
                            <p><strong>Crew:</strong> {{ $transaction->no_of_crew }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6>Financial Details</h6>
                        @php
                            // Get services data
                            $services = is_string($transaction->services) ? json_decode($transaction->services, true) : $transaction->services;
                            $totalSubtotal = 0;
                            $isMovingService = false;
                            
                            // Calculate total from services and check if it's moving services
                            if (is_array($services) && count($services) > 0) {
                                foreach ($services as $service) {
                                    $totalSubtotal += floatval(str_replace(['$', ','], '', $service['subtotal']));
                                    if (strtoupper($service['name']) === 'MOVING SERVICES') {
                                        $isMovingService = true;
                                    }
                                }
                            } else {
                                $totalSubtotal = $transaction->subtotal;
                                $isMovingService = strtoupper($transaction->service) === 'MOVING SERVICES';
                            }
                            
                            // Calculate fees based on moving services logic
                            if ($isMovingService) {
                                $addedMileRate = 0; // You might want to get this from somewhere
                                $truckFee = 198.80 + $addedMileRate;
                                $softwareFee = ($totalSubtotal + 198.80) * 0.12; // 12% of (subtotal + base truck fee)
                                $grandTotal = $totalSubtotal + $softwareFee + $truckFee;
                                $downPayment = $grandTotal * 0.315;
                                $remainingBalance = $grandTotal - $downPayment;
                            } else {
                                $truckFee = $transaction->truck_fee;
                                $softwareFee = $transaction->software_fee;
                                $grandTotal = $transaction->grand_total;
                            }
                        @endphp
                        
                        <p><strong>Subtotal:</strong> ${{ number_format($totalSubtotal, 2) }}</p>
                        <p><strong>Software Management Fee:</strong> ${{ number_format($softwareFee, 2) }}</p>
                        <p><strong>Truck Fee:</strong> ${{ number_format($truckFee, 2) }}</p>
                        <p><strong>Grand Total:</strong> ${{ number_format($grandTotal, 2) }}</p>
                        @if($isMovingService)
                            <p><strong>Down Payment:</strong> ${{ number_format($downPayment, 2) }}</p>
                            <p><strong>Remaining Balance:</strong> ${{ number_format($remainingBalance, 2) }}</p>
                            <small class="text-muted">* Fees calculated based on Moving Services rates</small>
                        @endif
                    </div>
                </div>
                
                @if($transaction->uploaded_image)
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h6>Uploaded Images</h6>
                        <div class="d-flex gap-2 flex-wrap mt-3">
                            @php
                                $images = explode(',', $transaction->uploaded_image);
                            @endphp
                            @foreach($images as $image)
                                @if(trim($image))
                                    <a href="{{ trim($image) }}" 
                                       class="lightbox-image"
                                       data-lightbox="gallery-{{ $transaction->id }}" 
                                       data-title="{{ $transaction->firstname }} {{ $transaction->lastname }} - {{ $transaction->service }}">
                                        <img src="{{ trim($image) }}" 
                                             alt="Transaction Image" 
                                             class="img-thumbnail"
                                             style="height: 100px; object-fit: cover; cursor: pointer;">
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
<!-- Lightbox2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<style>
    .fs-14 { font-size: 14px; }
    .avatar-md { width: 48px; height: 48px; }
    .avatar-sm { width: 32px; height: 32px; }
    
    /* DataTables Custom Styling */
    .dataTables_wrapper .dataTables_scroll { overflow: hidden !important; }
    .dataTables_wrapper { overflow-x: hidden !important; }
    .table-responsive { overflow-x: hidden !important; }
    table.dataTable { width: 100% !important; margin: 0 !important; }
    
    /* Custom Styling */
    .table > :not(caption) > * > * {
        padding: 1rem 1rem;
        background-color: transparent;
        border-bottom-width: 1px;
        box-shadow: inset 0 0 0 9999px transparent;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    /* Service Column Wrap Text */
    .service-badge {
        white-space: pre-line !important;
        text-align: left;
        display: block;
        line-height: 1.5;
        margin: 2px 0;
        max-width: 150px;
        word-break: break-word;
        padding: 0.5rem;
    }
    
    /* Date Column Styling */
    .date-column {
        width: 100px !important;
        text-align: left !important;
    }
    
    .dropdown-menu {
        padding: 0.5rem 0;
        border-radius: 0.5rem;
    }
    
    .dropdown-item {
        padding: 0.5rem 1rem;
    }
    
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    
    /* Search and Length Menu Styling */
    .dataTables_length select {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
    }
    
    .dataTables_filter input {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        margin-left: 0.5rem;
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

    /* Contact Link Styling */
    .contact-link {
        color: #556ee6;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .contact-link:hover {
        color: #485ec4;
        text-decoration: none;
    }

    .contact-link i {
        font-size: 0.875rem;
    }

    /* Image Preview Styling */
    .img-thumbnail {
        transition: transform 0.2s ease-in-out;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .img-thumbnail:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    /* Lightbox Styling */
    .lightbox-image {
        display: inline-block;
        text-decoration: none;
    }
</style>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
<!-- Lightbox2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#transactionsTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'desc']], // Sort by date column by default
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search transactions..."
            },
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 text-end'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            columnDefs: [
                {
                    targets: [0, 7, 8], // ID, Status, and Actions columns
                    className: 'd-none d-md-table-cell'
                },
                {
                    targets: [3, 4, 5, 6], // Pickup, Delivery, Miles, Total columns
                    className: 'd-none d-lg-table-cell'
                },
                {
                    targets: [1], // Customer column
                    className: 'all'
                },
                {
                    targets: [2], // Date column
                    className: 'all date-column',
                    width: '100px'
                },
                {
                    targets: [3], // Service column
                    className: 'd-none d-lg-table-cell',
                    width: '150px'
                }
            ],
            scrollX: false,
            autoWidth: false,
            scrollCollapse: false
        });

        // Adjust table layout on window resize
        $(window).on('resize', function() {
            table.columns.adjust();
        });

        // Configure Lightbox
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'albumLabel': 'Image %1 of %2',
            'fadeDuration': 300
        });

        // Sync Transactions
        document.getElementById('syncTransactions').addEventListener('click', function() {
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Syncing...';
            
            fetch('{{ route("transactions.sync") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success'
                    }).then(() => {
                        window.location.reload();
                    });
            } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: error.message,
                    icon: 'error'
                });
            })
            .finally(() => {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Sync Transactions';
            });
        });

        // Update Status
        window.updateStatus = function(transactionId, status) {
            fetch(`/transactions/${transactionId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: error.message,
                    icon: 'error'
                });
            });
        };
    });
    </script>

@endsection