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
            <div class="stats-card">
                <div class="stats-icon total">
                    <i class="fas fa-truck"></i>
                        </div>
                <div class="stats-content">
                    <span class="stats-label">Total Transactions</span>
                    <h3 id="totalTransactions" class="stats-value">{{ $transactions->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon pending">
                    <i class="fas fa-clock"></i>
                        </div>
                <div class="stats-content">
                    <span class="stats-label">Pending</span>
                    <h3 id="pendingTransactions" class="stats-value">{{ $transactions->where('status', 'pending')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon progress">
                    <i class="fas fa-spinner"></i>
                        </div>
                <div class="stats-content">
                    <span class="stats-label">In Progress</span>
                    <h3 id="inProgressTransactions" class="stats-value">{{ $transactions->where('status', 'in_progress')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon completed">
                    <i class="fas fa-check"></i>
                        </div>
                <div class="stats-content">
                    <span class="stats-label">Completed</span>
                    <h3 id="completedTransactions" class="stats-value">{{ $transactions->where('status', 'completed')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 rounded-3 shadow-sm">
                <div class="card-body p-4">
                    <div class="table-responsive" style="overflow-y: auto;">
                        <div class="loading-overlay">
                            <div class="loading-spinner"></div>
                        </div>
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
                                    <th class="border-0">Assigned Agent</th>
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
                                                // Prefer joined field if available
                                                $agentName = $transaction->assigned_agent_company_name ?? null;
                                                if (!$agentName) {
                                                    $agentName = (!empty($transaction->assigned_agent) && isset($agents[$transaction->assigned_agent])) ? $agents[$transaction->assigned_agent] : 'N/A';
                                                }
                                            @endphp
                                            {{ $agentName }}
                                        </td>
                                    <td>
                                        @php
                                            $statusClass = [
                                                'completed' => 'bg-success-subtle text-success',
                                                'in_progress' => 'bg-info-subtle text-info',
                                                'cancelled' => 'bg-danger-subtle text-danger',
                                                'pending' => 'bg-warning-subtle text-warning'
                                            ][$transaction->status] ?? 'bg-warning-subtle text-warning';
                                        @endphp
                                            <span class="badge {{ $statusClass }} rounded-pill px-3 status-badge" data-old-status="{{ $transaction->status }}">
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
                                                <li><a class="dropdown-item" href="#" onclick="sendQuote('{{ $transaction->id }}')">
                                                    <i class="fas fa-paper-plane me-2 text-info"></i> Send Quote
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
@php
    // Prefer joined field if available
    $leadSourceName = $transaction->lead_source_title ?? null;
    if (!$leadSourceName) {
        $leadSourceName = (!empty($transaction->lead_source) && isset($leadSources[$transaction->lead_source])) ? $leadSources[$transaction->lead_source] : 'N/A';
    }

    $leadTypeLabel = $transaction->lead_type === 'local' ? 'Local' : ($transaction->lead_type === 'long_distance' ? 'Long Distance' : 'N/A');

    // Prefer joined field if available
    $agentName = $transaction->assigned_agent_company_name ?? null;
    if (!$agentName) {
        $agentName = (!empty($transaction->assigned_agent) && isset($agents[$transaction->assigned_agent])) ? $agents[$transaction->assigned_agent] : 'N/A';
    }

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
    
    // Calculate added mile rate
    $distanceInMiles = floatval($transaction->miles);
    $addedMiles = 0;
    $addedMileRate = 0;
    
    if ($distanceInMiles > 12.5) {
        $addedMiles = $distanceInMiles;
        $addedMileRate = $addedMiles * 0.89; // $0.89 per mile charge
    }
    
    // Calculate fees based on moving services logic
    if ($isMovingService) {
        $baseTruckFee = 198.80;
        $softwareFee = ($totalSubtotal + $baseTruckFee) * 0.12;
        $truckFee = $baseTruckFee;
        $grandTotal = $totalSubtotal + $softwareFee + $truckFee + $addedMileRate;
        $downPayment = $grandTotal * 0.315;
        $remainingBalance = $grandTotal - $downPayment;
    } else {
        $truckFee = $transaction->truck_fee;
        $softwareFee = $transaction->software_fee;
        $grandTotal = $transaction->grand_total;
    }
@endphp
<div class="modal fade" id="transactionModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="transactionModalLabel{{ $transaction->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="transactionModalLabel{{ $transaction->id }}">
                    <i class="fas fa-file-invoice me-2"></i>Transaction Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Lead Info Section -->
                <div class="lead-info-section mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="info-icon">
                                    <i class="fas fa-funnel-dollar"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Lead Source</span>
                                    <span class="info-value">{{ $leadSourceName }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="info-icon">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Lead Type</span>
                                    <span class="info-value">{{ $leadTypeLabel }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="info-icon">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Assigned Agent</span>
                                    <span class="info-value">{{ $agentName }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Customer Information -->
                    <div class="col-md-6">
                        <div class="info-section-card">
                            <div class="section-header">
                                <i class="fas fa-user-circle"></i>
                                <h6>Customer Information</h6>
                            </div>
                            <div class="info-list">
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">Name</span>
                                        <span class="info-value">{{ $transaction->firstname }} {{ $transaction->lastname }}</span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">Email</span>
                                        <a href="mailto:{{ $transaction->email }}" class="info-value contact-link">
                                            {{ $transaction->email }}
                                        </a>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">Phone</span>
                                        <a href="tel:{{ $transaction->phone }}" class="info-value contact-link">
                                            {{ $transaction->phone }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Information -->
                    <div class="col-md-6">
                        <div class="info-section-card">
                            <div class="section-header">
                                <i class="fas fa-user-tie"></i>
                                <h6>Sales Information</h6>
                            </div>
                            <div class="info-list">
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">Name</span>
                                        <span class="info-value">{{ $transaction->sales_name }}</span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">Email</span>
                                        <a href="mailto:{{ $transaction->sales_email }}" class="info-value contact-link">
                                            {{ $transaction->sales_email }}
                                        </a>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">Location</span>
                                        <span class="info-value">{{ $transaction->sales_location }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service Details -->
                    <div class="col-md-6">
                        <div class="info-section-card">
                            <div class="section-header">
                                <i class="fas fa-truck-loading"></i>
                                <h6>Service Details</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless mb-0">
                                    <thead>
                                        <tr>
                                            <th>Service</th>
                                            <th class="text-end">Rate</th>
                                            <th class="text-end">Crew</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $services = is_string($transaction->services) ? json_decode($transaction->services, true) : $transaction->services;
                                            $totalItems = 0;
                                            $totalCrew = 0;
                                            $totalSubtotal = 0;
                                        @endphp
                                        @foreach($services as $service)
                                            @php
                                                $totalItems++;
                                                $totalCrew += intval($service['no_of_crew']);
                                                $totalSubtotal += floatval(str_replace(['$', ','], '', $service['subtotal']));
                                            @endphp
                                            <tr>
                                                <td>{{ $service['name'] }}</td>
                                                <td class="text-end">{{ $service['rate'] }}</td>
                                                <td class="text-end">{{ $service['no_of_crew'] }}</td>
                                                <td class="text-end">{{ $service['subtotal'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="total-row">
                                            <td colspan="2">Total</td>
                                            <td class="text-end">{{ $totalCrew }}</td>
                                            <td class="text-end">${{ number_format($totalSubtotal, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Details -->
                    <div class="col-md-6">
                        <div class="info-section-card">
                            <div class="section-header">
                                <i class="fas fa-calculator"></i>
                                <h6>Financial Details</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td>Subtotal</td>
                                            <td class="text-end">${{ number_format($totalSubtotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Added Mile Rate</td>
                                            <td class="text-end">${{ number_format($addedMileRate, 2) }}</td>
                                        </tr>
                                        @if($isMovingService)
                                            <tr>
                                                <td>Software Management Fee</td>
                                                <td class="text-end">${{ number_format($softwareFee, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Truck Fee</td>
                                                <td class="text-end">${{ number_format($truckFee, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Estimated Total</td>
                                                <td class="text-end">${{ number_format($grandTotal, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Down Payment (31.5%)</td>
                                                <td class="text-end">${{ number_format($downPayment, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Remaining Balance</td>
                                                <td class="text-end">${{ number_format($remainingBalance, 2) }}</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>Grand Total</td>
                                                <td class="text-end">${{ number_format($grandTotal, 2) }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            @if($isMovingService)
                                <div class="info-note">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span>Fees calculated based on Moving Services rates</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($transaction->uploaded_image)
                    <!-- Uploaded Images -->
                    <div class="col-12">
                        <div class="info-section-card">
                            <div class="section-header">
                                <i class="fas fa-images"></i>
                                <h6>Uploaded Images</h6>
                            </div>
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
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Send Quote Modal -->
<div class="modal fade" id="sendQuoteModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Quote</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="sendQuoteForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">To</label>
                                        <input type="email" class="form-control" name="to" id="quoteTo" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Subject</label>
                                        <input type="text" class="form-control" name="subject" id="quoteSubject" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <textarea id="quoteEditor" name="content"></textarea>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Available Placeholders</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group placeholder-list">
                                        @foreach($placeholders['Transaction'] as $field => $placeholder)
                                        <button type="button" class="list-group-item list-group-item-action placeholder-item" data-placeholder="{{ $placeholder }}">
                                            {{ $field }}
                                        </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send Quote</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Template Selection Modal -->
<div class="modal fade" id="templateSelectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Email Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Select Template</label>
                    <select class="form-select" id="templateSelect">
                        <option value="">Select a template</option>
                        @foreach($templates as $template)
                        <option value="{{ $template->id }}" 
                                data-subject="{{ $template->subject }}"
                                data-content="{{ $template->content }}">
                            {{ $template->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="useTemplateBtn">Use Template</button>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
<!-- Lightbox2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<style>
    .fs-14 { font-size: 14px; }
    .avatar-md { width: 48px; height: 48px; }
    .avatar-sm { width: 32px; height: 32px; }
    
    /* DataTables Custom Styling */
    .dataTables_wrapper {
        /* height: calc(100vh - 300px); */
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
    }
    
    .dataTables_length select {
        margin-bottom: 1rem;
    }
    
    table.dataTable thead th {
        padding-top: 1.25rem;
        padding-bottom: 1.25rem;
        background-color: #f8f9fa;
        position: sticky;
        top: 0;
        z-index: 1;
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
        color: #0d6efd;
        text-decoration: none;
    }

    .contact-link:hover {
        text-decoration: underline;
    }

    .contact-link i {
        font-size: 0.875rem;
    }

    /* Image Preview Styling */
    .img-thumbnail {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
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

    /* Lead Info Section Styling */
    .lead-info-section {
        background: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .info-card {
        background: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        height: 100%;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .info-icon {
        width: 32px;
        height: 32px;
        background: #e7f5ff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .info-icon i {
        color: #0d6efd;
        font-size: 0.875rem;
    }

    .info-content {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
    }

    .info-value {
        font-size: 0.875rem;
        color: #212529;
        font-weight: 500;
    }

    .section-title {
        color: #495057;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
    }

    .table-borderless {
        margin-bottom: 0;
    }

    .table-borderless th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        font-weight: 600;
        padding: 0.75rem 0.5rem;
        background: #f8f9fa;
    }

    .table-borderless td {
        padding: 0.75rem 0.5rem;
        color: #212529;
        font-size: 0.875rem;
        vertical-align: middle;
    }

    .total-row {
        background-color: #f8f9fa;
        border-top: 2px solid #e9ecef;
        font-weight: 600;
    }

    .info-note {
        margin-top: 1rem;
        padding: 0.75rem;
        background: #e7f5ff;
        border-radius: 0.5rem;
        color: #0d6efd;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
    }

    .info-note i {
        font-size: 1rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .info-card {
            margin-bottom: 1rem;
        }
    }

    /* Basic Modal Styling */
    .modal-content {
        border-radius: 0.5rem;
    }

    .modal-header {
        border-bottom: none;
        padding: 1.25rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: none;
        padding: 1.25rem;
    }

    /* Table Styling */
    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
        padding: 0.5rem;
    }

    /* Info Section Card Styling */
    .info-section-card {
        background: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        height: 100%;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e9ecef;
    }

    .section-header i {
        color: #0d6efd;
        font-size: 1.25rem;
    }

    .section-header h6 {
        margin: 0;
        color: #212529;
        font-weight: 600;
    }

    .info-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 0.5rem;
        transition: background-color 0.2s ease;
    }

    .info-item:hover {
        background: #e9ecef;
    }

    .info-icon {
        width: 32px;
        height: 32px;
        background: #e7f5ff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .info-icon i {
        color: #0d6efd;
        font-size: 0.875rem;
    }

    .info-content {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
    }

    .info-value {
        font-size: 0.875rem;
        color: #212529;
        font-weight: 500;
    }

    .contact-link {
        color: #0d6efd;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .contact-link:hover {
        color: #0a58ca;
        text-decoration: none;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .info-section-card {
            margin-bottom: 1rem;
        }
    }

    /* Stats Cards Styling */
    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        height: 100%;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stats-icon i {
        font-size: 1.5rem;
        color: white;
    }

    .stats-icon.total {
        background: linear-gradient(135deg, #0d6efd, #0a58ca);
    }

    .stats-icon.pending {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
    }

    .stats-icon.progress {
        background: linear-gradient(135deg, #0dcaf0, #0d6efd);
    }

    .stats-icon.completed {
        background: linear-gradient(135deg, #198754, #146c43);
    }

    .stats-content {
        flex: 1;
    }

    .stats-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        display: block;
        margin-bottom: 0.25rem;
    }

    .stats-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: #212529;
        margin: 0;
        line-height: 1.2;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .stats-card {
            margin-bottom: 1rem;
        }
        
        .stats-icon {
            width: 40px;
            height: 40px;
        }
        
        .stats-icon i {
            font-size: 1.25rem;
        }
        
        .stats-value {
            font-size: 1.25rem;
        }
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
                url: "{{ route('transactions.sync') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    action: 'add_new'
                },
                success: function(response) {
                    if (response.new_count > 0) {
                        // Reload table data silently if new transactions were added
                        $('#transactionsTable').DataTable().ajax.reload(null, false);
                        
                        // Update the stats boxes with fresh counts using IDs
                        $('#totalTransactions').text(parseInt($('#totalTransactions').text()) + response.new_count);
                        $('#pendingTransactions').text(parseInt($('#pendingTransactions').text()) + response.new_count);
                        $('#inProgressTransactions').text(parseInt($('#inProgressTransactions').text()) + response.new_count);
                        $('#completedTransactions').text(parseInt($('#completedTransactions').text()) + response.new_count);

                        // Show toast notification
                        showToast(`${response.new_count} new transaction${response.new_count > 1 ? 's' : ''} added`);
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

        // Show loading overlay only on first load
        $('.dataTables-loading-overlay').show();

        // Initialize DataTable
        var table = $('#transactionsTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            ajax: {
                url: '{{ route("transactions.datatable") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function(d) {
                    return d;
                },
                error: function(xhr, error, thrown) {
                    console.error('DataTables error:', error, thrown);
                    console.error('Response:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while loading the data. Please try again.'
                    });
                },
                beforeSend: function() {
                    $('.loading-overlay').addClass('active');
                },
                complete: function() {
                    setTimeout(function() {
                        $('.loading-overlay').removeClass('active');
                    }, 500);
                }
            },
            columnDefs: [
                {
                    targets: [0, 7, 8, 9],
                    className: 'd-none d-md-table-cell'
                },
                {
                    targets: [3, 4, 5, 6],
                    className: 'd-none d-lg-table-cell'
                },
                {
                    targets: [1],
                    className: 'all'
                },
                {
                    targets: [2],
                    className: 'all date-column',
                    width: '100px'
                },
                {
                    targets: [3],
                    className: 'd-none d-lg-table-cell',
                    width: '150px'
                }
            ],
            columns: [
                { data: 'transaction_id', name: 'transaction_id' },
                { 
                    data: 'customer', 
                    name: 'customer',
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex align-items-center gap-2">
                                <div>
                                    <h6 class="mb-0">${row.firstname} ${row.lastname}</h6>
                                    <small class="text-muted">${row.email}</small>
                                </div>
                            </div>
                        `;
                    }
                },
                { 
                    data: 'date', 
                    name: 'date',
                    render: function(data) {
                        return data ? moment(data).format('MMM D, YYYY') : 'N/A';
                    }
                },
                { 
                    data: 'services', 
                    name: 'services',
                    render: function(data) {
                        let services = data;
                        if (typeof data === 'string') {
                            try {
                                services = JSON.parse(data);
                            } catch (e) {
                                services = [{ name: data }];
                            }
                        }
                        const serviceNames = Array.isArray(services) 
                            ? services.map(service => service.name || service).join('\n')
                            : services;
                        return `<span class="badge bg-primary bg-opacity-10 text-primary service-badge">${serviceNames}</span>`;
                    }
                },
                { data: 'pickup_location', name: 'pickup_location' },
                { data: 'delivery_location', name: 'delivery_location' },
                { data: 'miles', name: 'miles' },
                { 
                    data: 'grand_total', 
                    name: 'grand_total',
                    render: function(data) {
                        return `<span class="fw-semibold">$${parseFloat(data).toFixed(2)}</span>`;
                    }
                },
                { 
                    data: 'assigned_agent_company_name', 
                    name: 'assigned_agent_company_name',
                    render: function(data) {
                        return data || 'N/A';
                    }
                },
                { 
                    data: 'status', 
                    name: 'status',
                    render: function(data) {
                        const statusClass = {
                            'completed': 'bg-success-subtle text-success',
                            'in_progress': 'bg-info-subtle text-info',
                            'cancelled': 'bg-danger-subtle text-danger',
                            'pending': 'bg-warning-subtle text-warning'
                        }[data] || 'bg-warning-subtle text-warning';
                        return `<span class="badge ${statusClass} rounded-pill px-3 status-badge" data-old-status="${data}">
                            ${data.charAt(0).toUpperCase() + data.slice(1)}
                        </span>`;
                    }
                },
                {
                    data: 'id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-pill px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu shadow-sm border-0">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#transactionModal${data}">
                                        <i class="fas fa-eye me-2 text-muted"></i> View Details
                                    </a></li>
                                    <li><a class="dropdown-item" href="/leads/${data}/edit">
                                        <i class="fas fa-edit me-2 text-primary"></i> Edit
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="sendQuote('${data}')">
                                        <i class="fas fa-paper-plane me-2 text-info"></i> Send Quote
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="updateStatus('${data}', 'pending')">
                                        <i class="fas fa-clock me-2 text-warning"></i> Mark as Pending
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="updateStatus('${data}', 'in_progress')">
                                        <i class="fas fa-spinner me-2 text-info"></i> Mark In Progress
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="updateStatus('${data}', 'completed')">
                                        <i class="fas fa-check me-2 text-success"></i> Mark Completed
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="updateStatus('${data}', 'cancelled')">
                                        <i class="fas fa-times me-2 text-danger"></i> Cancel
                                    </a></li>
                                </ul>
                            </div>
                        `;
                    }
                }
            ],
            createdRow: function(row, data, dataIndex) {
                $(row).attr('data-id', data.id);
            },
            order: [[0, 'desc']],
            scrollX: false,
            autoWidth: false,
            scrollCollapse: false,
            deferRender: true,
            stateSave: true,
            pagingType: 'simple_numbers',
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search transactions...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                infoFiltered: "(filtered from _MAX_ total entries)",
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: "No transactions found",
                zeroRecords: "No matching transactions found"
            },
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 text-end'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
        });

        // Perform silent sync after DataTable initialization
        silentSync();

        // Optimize window resize handling
        let resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
            table.columns.adjust();
            }, 250);
        });

        // Add sync button functionality
        $('#syncTransactions').on('click', function() {
            var button = $(this);
            button.prop('disabled', true);
            button.html('<i class="fas fa-spinner fa-spin"></i> Syncing...');

            $.ajax({
                url: "{{ route('transactions.sync') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    action: 'add_new'
                },
                success: function(response) {
                    if (response.new_count > 0) {
                        table.ajax.reload();
                    Swal.fire({
                            icon: 'success',
                        title: 'Success!',
                            text: `Added ${response.new_count} new transactions`
                    });
            } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'No New Transactions',
                            text: 'All transactions are up to date'
                        });
                    }
                },
                error: function(xhr) {
                Swal.fire({
                        icon: 'error',
                    title: 'Error!',
                        text: xhr.responseJSON?.message || 'Failed to sync transactions data'
                    });
                },
                complete: function() {
                    button.prop('disabled', false);
                    button.html('<i class="fas fa-sync-alt me-1"></i> Sync Transactions');
                }
            });
        });

        // Update Status function
        window.updateStatus = function(transactionId, status) {
            // Show loading overlay immediately
            $('.loading-overlay').addClass('active');

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
                    // Get the current DataTable instance
                    const table = $('#transactionsTable').DataTable();
                    
                    // Get current page and search state
                    const currentPage = table.page();
                    const currentSearch = table.search();
                    
                    // Make an AJAX call to get updated counts
                    fetch('/transactions/get-counts')
                        .then(response => response.json())
                        .then(counts => {
                            // Update the stats boxes with fresh counts using IDs
                            $('#totalTransactions').text(counts.total);
                            $('#pendingTransactions').text(counts.pending);
                            $('#inProgressTransactions').text(counts.in_progress);
                            $('#completedTransactions').text(counts.completed);
                        });
                    
                    // Reload the table data
                    table.ajax.reload(null, false); // false = don't reset paging
                    
                    // Restore the current page and search
                    table.page(currentPage).draw(false);
                    table.search(currentSearch).draw(false);

                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
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
                // Remove loading overlay when the process is complete
                $('.loading-overlay').removeClass('active');
            });
            
        };

        let quoteEditor;
        let currentTransactionId;

        // Initialize CKEditor for quote
        ClassicEditor
            .create(document.querySelector('#quoteEditor'), {
                toolbar: [
                    'heading',
                    '|',
                    'bold', 'italic', 'strikethrough', 'underline',
                    '|',
                    'bulletedList', 'numberedList',
                    '|',
                    'alignment',
                    '|',
                    'link', 'blockQuote', 'insertTable', 'sourceEditing', 'htmlEmbed',
                    '|',
                    'undo', 'redo'
                ],
                htmlSupport: {
                    allow: [
                        {
                            name: /.*/,
                            attributes: true,
                            classes: true,
                            styles: true
                        }
                    ]
                }
            })
            .then(newEditor => {
                quoteEditor = newEditor;
                
                // Handle placeholder insertion for quote editor
                document.querySelectorAll('#sendQuoteModal .placeholder-item').forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        const placeholder = this.dataset.placeholder;
                        quoteEditor.model.change(writer => {
                            const insertPosition = quoteEditor.model.document.selection.getFirstPosition();
                            quoteEditor.model.insertContent(writer.createText(placeholder), insertPosition);
                        });
                    });
                });
            })
            .catch(error => {
                console.error(error);
            });

        // Update sendQuote function
        window.sendQuote = function(transactionId) {
            currentTransactionId = transactionId;
            
            // Show loading state
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Fetch transaction data
            fetch(`/transactions/${transactionId}`)
                .then(response => response.json())
                .then(data => {
                    Swal.close();
                    // Show template selection modal
                    const templateModal = new bootstrap.Modal(document.getElementById('templateSelectionModal'));
                    templateModal.show();
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to load transaction data'
                    });
                });
        };

        // Handle template selection
        document.getElementById('useTemplateBtn').addEventListener('click', function() {
            const selectedOption = document.getElementById('templateSelect').selectedOptions[0];
            if (!selectedOption.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: 'Please select a template'
                });
                return;
            }

            // Close template selection modal
            const templateModal = bootstrap.Modal.getInstance(document.getElementById('templateSelectionModal'));
            templateModal.hide();

            // Show send quote modal
            const quoteModal = new bootstrap.Modal(document.getElementById('sendQuoteModal'));
            quoteModal.show();

            // Populate email and subject
            document.getElementById('quoteTo').value = '{{ $transaction->email }}';
            document.getElementById('quoteSubject').value = selectedOption.dataset.subject;
            quoteEditor.setData(selectedOption.dataset.content);
        });

        // Handle send quote form submission
        document.getElementById('sendQuoteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('transaction_id', currentTransactionId);
            formData.set('content', quoteEditor.getData());

            // Show loading state
            Swal.fire({
                title: 'Sending...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('/transactions/send-quote', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('sendQuoteModal'));
                    modal.hide();

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Quote sent successfully',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(data.message || 'Failed to send quote');
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

        // Handle modal hidden events
        document.getElementById('sendQuoteModal').addEventListener('hidden.bs.modal', function () {
            quoteEditor.setData('');
            this.querySelector('form').reset();
        });
    });
    </script>

@endsection