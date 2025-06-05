@extends('includes.app')

@section('title', 'Transaction Calendar')

@section('content')

<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>


<link href="https://unpkg.com/lightbox2@2.11.3/dist/css/lightbox.min.css" rel="stylesheet">
  <script src="https://unpkg.com/lightbox2@2.11.3/dist/js/lightbox-plus-jquery.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Transaction Calendar</h4>
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-primary btn-sm" id="refreshCalendar">
                        <i class="fas fa-sync-alt me-1"></i> Refresh Calendar
                    </button>
                </div>                                
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div><!--end row-->
    
    @if(Auth::user() && Auth::user()->privilege === 'agent')
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <div>You are viewing only transactions assigned to you as an agent.</div>
            </div>
            
            @if(App\Models\Transaction::where('assigned_agent', Auth::user()->agent_id)->count() === 0)
            <div class="alert alert-warning d-flex align-items-center mt-2" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div>
                    <strong>No transactions found!</strong> There are currently no transactions assigned to your agent account.
                    @if(App\Models\Transaction::count() > 0)
                        The system has {{ App\Models\Transaction::count() }} total transaction(s).
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Status Color Legend -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <span class="legend-dot bg-success"></span> <span class="me-3">Completed</span>
                <span class="legend-dot bg-warning"></span> <span class="me-3">In Progress</span>
                <span class="legend-dot bg-danger"></span> <span class="me-3">Cancelled</span>
                <span class="legend-dot bg-primary"></span> <span class="me-3">Lead</span>
                <span class="legend-dot" style="background-color: #fd7e14;"></span> <span class="me-3">Pending</span>
                <span class="legend-dot bg-secondary"></span> <span>Other</span>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Transaction Calendar</h4>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="calendar-prev">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="calendar-today">Today</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="calendar-next">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id='calendar'></div>
                    <div style='clear:both'></div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div> <!-- end col -->
    </div> <!-- end row -->
</div><!-- container -->

<!-- Transaction Details Modal -->
<div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="transactionModalLabel">
                    <i class="fas fa-file-invoice-dollar me-2"></i>Transaction Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light p-0">
                <!-- Header Info Section -->
                <div class="bg-white p-4 mb-0 border-bottom">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <div class="event-date rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px; flex-direction: column;">
                                <h3 class="mb-0 fw-bold" id="eventDay">15</h3>
                                <p class="mb-0 small text-uppercase" id="eventMonth">Apr</p>
                        </div>
                    </div>
                        <div class="col-md-6">
                            <h4 id="eventTitle" class="fw-bold mb-2 text-primary">Customer Name</h4>
                            <p class="text-muted mb-1 d-flex align-items-center">
                                <i class="far fa-calendar me-2 text-secondary"></i> 
                                <span id="eventTime" class="text-dark">Date & Time</span>
                            </p>
                            <p class="text-muted mb-1 d-flex align-items-center">
                                <i class="fas fa-truck me-2 text-secondary"></i> 
                                <span id="eventService" class="text-dark">Service</span>
                            </p>
                    </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex flex-column align-items-md-end">
                                <span id="transactionStatus" class="badge mb-2 fs-6 px-3 py-2">Status</span>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user me-2 text-secondary"></i>
                                    <span id="eventAgent" class="text-dark">Agent</span>
                </div>
                </div>
                    </div>
                </div>
            </div>
                
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs nav-fill px-3 pt-3 bg-white" id="transactionTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-4" id="customer-tab" data-bs-toggle="tab" data-bs-target="#customer-tab-pane" type="button" role="tab" aria-controls="customer-tab-pane" aria-selected="true">
                            <i class="fas fa-user me-2"></i>Customer
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4" id="move-tab" data-bs-toggle="tab" data-bs-target="#move-tab-pane" type="button" role="tab" aria-controls="move-tab-pane" aria-selected="false">
                            <i class="fas fa-truck-loading me-2"></i>Move Details
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4" id="financial-tab" data-bs-toggle="tab" data-bs-target="#financial-tab-pane" type="button" role="tab" aria-controls="financial-tab-pane" aria-selected="false">
                            <i class="fas fa-dollar-sign me-2"></i>Financial & Services
                        </button>
                    </li>
                </ul>
                
                <!-- Tab Content -->
                <div class="tab-content p-4 bg-white" id="transactionTabsContent">
                    <!-- Customer Info Tab -->
                    <div class="tab-pane fade show active" id="customer-tab-pane" role="tabpanel" aria-labelledby="customer-tab" tabindex="0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 fw-bold"><i class="fas fa-user-circle me-2 text-primary"></i>Customer Information</h6>
            </div>
                                    <div class="card-body">
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Transaction ID:</span> 
                                            <span class="fw-medium" id="transactionId"></span>
                                        </p>
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Customer:</span> 
                                            <span class="fw-medium" id="customerName"></span>
                                        </p>
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Email:</span> 
                                            <span class="fw-medium" id="customerEmail"></span>
                                        </p>
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Phone:</span> 
                                            <span class="fw-medium" id="customerPhone"></span>
                                        </p>
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Lead Source:</span> 
                                            <span class="fw-medium" id="leadSource"></span>
                                        </p>
                                        <p class="mb-0 d-flex justify-content-between">
                                            <span class="text-muted">Lead Type:</span> 
                                            <span class="fw-medium" id="leadType"></span>
                                        </p>
        </div>
    </div>
</div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 fw-bold"><i class="fas fa-briefcase me-2 text-primary"></i>Sales Information</h6>
            </div>
                                    <div class="card-body">
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Assigned Agent:</span> 
                                            <span class="fw-medium" id="assignedAgent"></span>
                                        </p>
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Sales Rep:</span> 
                                            <span class="fw-medium" id="salesName"></span>
                                        </p>
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Sales Email:</span> 
                                            <span class="fw-medium" id="salesEmail"></span>
                                        </p>
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Sales Location:</span> 
                                            <span class="fw-medium" id="salesLocation"></span>
                                        </p>
                                        <p class="mb-0 d-flex justify-content-between">
                                            <span class="text-muted">Created:</span> 
                                            <span class="fw-medium" id="createdDate"></span>
                                        </p>
                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Uploaded Images Gallery -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light py-3">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-image me-2 text-primary"></i>Uploaded Images</h6>
                            </div>
                            <div class="card-body">
                                <div id="uploadedImagesGallery" class="uploaded-images-gallery"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Move Details Tab -->
                    <div class="tab-pane fade" id="move-tab-pane" role="tabpanel" aria-labelledby="move-tab" tabindex="0">
                        <div class="row">
                        <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 fw-bold"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Location Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Pickup Location:</span> 
                                            <span class="fw-medium" id="pickupLocation"></span>
                                        </p>
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Delivery Location:</span> 
                                            <span class="fw-medium" id="deliveryLocation"></span>
                                        </p>
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Total Miles:</span> 
                                            <span class="fw-medium" id="totalMiles"></span>
                                        </p>
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Additional Miles:</span> 
                                            <span class="fw-medium" id="additionalMiles"></span>
                                        </p>
                                        <p class="mb-0 d-flex justify-content-between">
                                            <span class="text-muted">Mile Rate:</span> 
                                            <span class="fw-medium" id="mileRate"></span>
                                        </p>
                                    </div>
                                </div>
                        </div>
                        <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 fw-bold"><i class="fas fa-box me-2 text-primary"></i>Resource Details</h6>
                        </div>
                                    <div class="card-body">
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Items Count:</span> 
                                            <span class="fw-medium" id="itemsCount"></span>
                                        </p>
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Crew Count:</span> 
                                            <span class="fw-medium" id="crewCount"></span>
                                        </p>
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Crew Rate:</span> 
                                            <span class="fw-medium" id="crewRate"></span>
                                        </p>
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Delivery Rate:</span> 
                                            <span class="fw-medium" id="deliveryRate"></span>
                                        </p>
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Insurance #:</span> 
                                            <span class="fw-medium" id="insuranceNumber"></span>
                                        </p>
                                        <p class="mb-0 d-flex justify-content-between">
                                            <span class="text-muted">Insurance Document:</span> 
                                            <span>
                                                <a href="#" id="insuranceDocLink" class="btn btn-sm btn-outline-primary d-none">
                                                    <i class="fas fa-file-pdf me-1"></i> View
                                                </a>
                                                <span id="noInsuranceDoc" class="badge bg-light text-dark d-none">No document</span>
                                            </span>
                                        </p>
                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Financial Tab -->
                    <div class="tab-pane fade" id="financial-tab-pane" role="tabpanel" aria-labelledby="financial-tab" tabindex="0">
                        <!-- Services Section - Moved to the top -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light py-3">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-concierge-bell me-2 text-primary"></i>Service Details</h6>
                            </div>
                            <div class="card-body">
                                <div id="servicesContainer">
                                    <!-- Services will be added here dynamically -->
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                        <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 fw-bold"><i class="fas fa-receipt me-2 text-primary"></i>Service Charges</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Service Rate:</span> 
                                            <span class="fw-medium" id="serviceRate"></span>
                                        </p>
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Subtotal:</span> 
                                            <span class="fw-medium" id="subtotal"></span>
                                        </p>
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Software Fee:</span> 
                                            <span class="fw-medium" id="softwareFee"></span>
                                        </p>
                                        <p class="mb-0 d-flex justify-content-between">
                                            <span class="text-muted">Truck Fee:</span> 
                                            <span class="fw-medium" id="truckFee"></span>
                                        </p>
                                    </div>
                                </div>
                        </div>
                        <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 fw-bold"><i class="fas fa-credit-card me-2 text-primary"></i>Payment Details</h6>
                        </div>
                                    <div class="card-body">
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Downpayment:</span> 
                                            <span class="fw-medium" id="downpayment"></span>
                                        </p>
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Coupon Code:</span> 
                                            <span class="fw-medium" id="couponCode"></span>
                                        </p>
                                        <p class="mb-2 d-flex justify-content-between">
                                            <span class="text-muted">Payment ID:</span> 
                                            <span class="fw-medium" id="paymentId"></span>
                                        </p>
                                        <div class="mt-3 p-3 bg-primary bg-opacity-10 rounded-3 border border-primary border-opacity-25">
                                            <p class="mb-0 d-flex justify-content-between align-items-center">
                                                <span class="fw-bold text-primary fs-6">Grand Total:</span> 
                                                <span class="fw-bold text-primary fs-5" id="grandTotal"></span>
                                            </p>
                    </div>
                    </div>
                    </div>
            </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top-0">
                <a href="#" id="viewTransactionBtn" class="btn btn-primary me-2">
                    <i class="fas fa-edit me-1"></i> View Transaction
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize FullCalendar
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            initialDate: new Date(), // This already looks correct
            headerToolbar: {
                left: 'dayGridMonth,timeGridWeek,timeGridDay',
                center: 'title',
                right: ''
            },
            events: '/calendar/events', // Endpoint to fetch transaction events
            eventClick: function(info) {
                // Prevent default behavior (redirection)
                info.jsEvent.preventDefault();
                info.jsEvent.stopPropagation();
                
                // Remove any href attribute that might cause navigation
                if (info.el.tagName === 'A') {
                    info.el.removeAttribute('href');
                }
                
                // Open the transaction modal
                openTransactionModal(info.event);
                return false; // Ensure no further propagation
            },
            dateClick: function(info) {
                // Optional: Add functionality for date clicks
            },
            eventDidMount: function(info) {
                // Add tooltips to events
                info.el.title = info.event.title;
                // Add custom styling to events
                info.el.classList.add('fc-event-modern');
                // Add status-based styling and color
                if (info.event.extendedProps.status) {
                    const status = info.event.extendedProps.status.toLowerCase();
                    if (status === 'completed') {
                        info.el.style.backgroundColor = '#1cc88a'; // Green
                        info.el.style.borderLeft = '4px solid #169a6c';
                        info.el.style.color = '#fff';
                    } else if (status === 'in_progress') {
                        info.el.style.backgroundColor = '#f6c23e'; // Yellow
                        info.el.style.borderLeft = '4px solid #dda20a';
                        info.el.style.color = '#fff';
                    } else if (status === 'cancelled') {
                        info.el.style.backgroundColor = '#e74a3b'; // Red
                        info.el.style.borderLeft = '4px solid #be2617';
                        info.el.style.color = '#fff';
                    } else if (status === 'lead') {
                        info.el.style.backgroundColor = '#4e73df'; // Blue
                        info.el.style.borderLeft = '4px solid #2e59d9';
                        info.el.style.color = '#fff';
                    } else if (status === 'pending') {
                        info.el.style.backgroundColor = '#fd7e14'; // Orange
                        info.el.style.borderLeft = '4px solid #d66a0a';
                        info.el.style.color = '#fff';
                    } else {
                        info.el.style.backgroundColor = '#858796';
                        info.el.style.borderLeft = '4px solid #6c757d';
                        info.el.style.color = '#fff';
                    }
                } else {
                    info.el.style.backgroundColor = '#858796';
                    info.el.style.borderLeft = '4px solid #6c757d';
                    info.el.style.color = '#fff';
                }
            },
            eventContent: function(arg) {
                // Get status for color
                const status = arg.event.extendedProps.status ? arg.event.extendedProps.status.toLowerCase() : 'other';
                let bg = '#858796', border = '#6c757d', text = '#fff';
                if (status === 'completed') { bg = '#1cc88a'; border = '#169a6c'; }
                else if (status === 'in_progress') { bg = '#f6c23e'; border = '#dda20a'; text = '#fff'; }
                else if (status === 'cancelled') { bg = '#e74a3b'; border = '#be2617'; text = '#fff'; }
                else if (status === 'lead') { bg = '#4e73df'; border = '#2e59d9'; text = '#fff'; }
                else if (status === 'pending') { bg = '#fd7e14'; border = '#d66a0a'; text = '#fff'; }
                
                // Remove any URL from the event to prevent navigation
                if (arg.event.url) {
                    delete arg.event.url;
                }
                
                // Main event container
                const wrap = document.createElement('div');
                wrap.className = 'fc-event-pro';
                wrap.style.background = bg;
                wrap.style.borderLeft = `4px solid ${border}`;
                wrap.style.color = text;
                // Title
                const title = document.createElement('div');
                title.className = 'fc-event-pro-title';
                title.textContent = arg.event.title;
                wrap.appendChild(title);
                // (No time)
                // Status badge
                const badge = document.createElement('span');
                badge.className = `fc-event-pro-badge fc-event-pro-badge-${status}`;
                badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                wrap.appendChild(badge);
                return { domNodes: [wrap] };
            },
            loading: function(isLoading) {
                if (isLoading) {
                    // Show loading state
                    Swal.fire({
                        title: 'Loading Calendar',
                        text: 'Please wait while we load your transactions...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                } else {
                    // Close loading state
                    Swal.close();
                }
            }
        });
        
        calendar.render();
        
        // Function to open transaction modal
        function openTransactionModal(event) {
            const props = event.extendedProps;
            const startDate = new Date(event.start);
            
            // Set the view transaction button href
            const viewTransactionBtn = document.getElementById('viewTransactionBtn');
            if (props.transaction_id) {
                viewTransactionBtn.href = `/leads/${props.transaction_id}/edit`;
            } else {
                viewTransactionBtn.href = '#';
                viewTransactionBtn.classList.add('disabled');
            }
            
            // Format date for display
            const day = startDate.getDate();
            const month = startDate.toLocaleString('default', { month: 'short' });
            const formattedDate = startDate.toLocaleDateString();
            const formattedTime = startDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            // Update modal content with transaction data
            document.getElementById('eventDay').textContent = day;
            document.getElementById('eventMonth').textContent = month;
            document.getElementById('eventTitle').textContent = event.title;
            document.getElementById('eventTime').textContent = `${formattedDate} ${formattedTime}`;
            document.getElementById('eventService').textContent = props.service || 'N/A';
            
            // Display only the agent's company name or 'Unassigned'
            document.getElementById('eventAgent').textContent = props.agent_company ? props.agent_company : 'Unassigned';
            
            // Customer tab data
            document.getElementById('transactionId').textContent = props.transaction_id || 'N/A';
            document.getElementById('customerName').textContent = event.title || 'N/A'; // Already set to firstname + lastname
            document.getElementById('customerEmail').textContent = props.email || 'N/A';
            document.getElementById('customerPhone').textContent = props.phone || 'N/A';
            document.getElementById('leadSource').textContent = props.lead_source || 'N/A';
            document.getElementById('leadType').textContent = props.lead_type || 'N/A';
            document.getElementById('assignedAgent').textContent = props.agent_company ? props.agent_company : 'Unassigned';
            document.getElementById('salesName').textContent = props.sales_name || 'N/A';
            document.getElementById('salesEmail').textContent = props.sales_email || 'N/A';
            document.getElementById('salesLocation').textContent = props.sales_location || 'N/A';
            
            // Format created date
            if (props.created_at) {
                const dateObj = new Date(props.created_at.replace(/-/g, '/'));
                const options = { year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true };
                let formatted = dateObj.toLocaleString('en-US', options);
                formatted = formatted.replace(',', '').replace(' AM', 'am').replace(' PM', 'pm');
                document.getElementById('createdDate').textContent = formatted;
            } else {
                document.getElementById('createdDate').textContent = 'N/A';
            }
            
            // Set status with appropriate styling
            const statusElement = document.getElementById('transactionStatus');
            statusElement.textContent = props.status || 'N/A';
            
            // Update status badge color
            statusElement.className = 'badge mb-2 fs-6 px-3 py-2'; // Reset class
            if (props.status) {
                const status = props.status.toLowerCase();
                if (status === 'completed') {
                    statusElement.classList.add('bg-success');
                } else if (status === 'in_progress') {
                    statusElement.classList.add('bg-warning');
                } else if (status === 'cancelled') {
                    statusElement.classList.add('bg-danger');
                } else if (status === 'lead') {
                    statusElement.classList.add('bg-primary');
                } else if (status === 'pending') {
                    statusElement.classList.add('bg-warning');
                } else {
                    statusElement.classList.add('bg-secondary');
                }
            } else {
                statusElement.classList.add('bg-secondary');
            }
            
            // Move details tab data
            document.getElementById('pickupLocation').textContent = props.pickup_location || 'N/A';
            document.getElementById('deliveryLocation').textContent = props.delivery_location || 'N/A';
            document.getElementById('totalMiles').textContent = props.miles ? `${props.miles} miles` : 'N/A';
            document.getElementById('additionalMiles').textContent = props.add_mile ? `${props.add_mile} miles` : 'N/A';
            document.getElementById('mileRate').textContent = props.mile_rate ? `$${props.mile_rate}` : 'N/A';
            document.getElementById('itemsCount').textContent = props.no_of_items || 'N/A';
            document.getElementById('crewCount').textContent = props.no_of_crew || 'N/A';
            document.getElementById('crewRate').textContent = props.crew_rate ? `$${props.crew_rate}` : 'N/A';
            document.getElementById('deliveryRate').textContent = props.delivery_rate ? `$${props.delivery_rate}` : 'N/A';
            document.getElementById('insuranceNumber').textContent = props.insurance_number || 'N/A';
            
            // Handle insurance document link
            const insuranceDocLink = document.getElementById('insuranceDocLink');
            const noInsuranceDoc = document.getElementById('noInsuranceDoc');
            
            if (props.insurance_document) {
                insuranceDocLink.href = props.insurance_document;
                insuranceDocLink.classList.remove('d-none');
                noInsuranceDoc.classList.add('d-none');
            } else {
                insuranceDocLink.classList.add('d-none');
                noInsuranceDoc.classList.remove('d-none');
            }
            
            // Financial tab data
            document.getElementById('serviceRate').textContent = props.service_rate ? `$${props.service_rate}` : 'N/A';
            document.getElementById('subtotal').textContent = props.subtotal ? `$${props.subtotal}` : 'N/A';
            document.getElementById('softwareFee').textContent = props.software_fee ? `$${props.software_fee}` : 'N/A';
            document.getElementById('truckFee').textContent = props.truck_fee ? `$${props.truck_fee}` : 'N/A';
            document.getElementById('downpayment').textContent = props.downpayment ? `$${props.downpayment}` : 'N/A';
            document.getElementById('couponCode').textContent = props.coupon_code || 'N/A';
            document.getElementById('paymentId').textContent = props.payment_id || 'N/A';
            document.getElementById('grandTotal').textContent = props.grand_total ? `$${props.grand_total}` : 'N/A';
            
            // Services tab data
            const servicesContainer = document.getElementById('servicesContainer');
            servicesContainer.innerHTML = '';
            
            if (props.services && Array.isArray(props.services) && props.services.length > 0) {
                // Create a table element
                const table = document.createElement('table');
                table.className = 'table table-borderless';
                
                // Create table header
                const thead = document.createElement('thead');
                const headerRow = document.createElement('tr');
                ['Service', 'Rate', 'Crew Rate', 'Crew Count', 'Delivery Cost', 'Subtotal'].forEach(heading => {
                    const th = document.createElement('th');
                    th.textContent = heading;
                    th.className = heading === 'Subtotal' ? 'text-end' : '';
                    headerRow.appendChild(th);
                });
                thead.appendChild(headerRow);
                table.appendChild(thead);
                
                // Create table body
                const tbody = document.createElement('tbody');
                let totalSubtotal = 0;
                
                props.services.forEach(service => {
                    const tr = document.createElement('tr');
                    
                    // Service name
                    const tdName = document.createElement('td');
                    tdName.textContent = service.name || 'N/A';
                    tdName.className = 'fw-medium';
                    tr.appendChild(tdName);
                    
                    // Rate
                    const tdRate = document.createElement('td');
                    tdRate.textContent = service.rate || '$0.00';
                    tr.appendChild(tdRate);
                    
                    // Crew Rate
                    const tdCrewRate = document.createElement('td');
                    tdCrewRate.textContent = service.crew_rate || '$0.00';
                    tr.appendChild(tdCrewRate);
                    
                    // Number of Crew
                    const tdCrewCount = document.createElement('td');
                    tdCrewCount.textContent = service.no_of_crew || '0';
                    tr.appendChild(tdCrewCount);
                    
                    // Delivery Cost
                    const tdDeliveryCost = document.createElement('td');
                    tdDeliveryCost.textContent = service.delivery_cost || '$0.00';
                    tr.appendChild(tdDeliveryCost);
                    
                    // Subtotal
                    const tdSubtotal = document.createElement('td');
                    tdSubtotal.textContent = service.subtotal || '$0.00';
                    tdSubtotal.className = 'text-end fw-medium';
                    tr.appendChild(tdSubtotal);
                    
                    tbody.appendChild(tr);
                    
                    // Calculate total
                    if (service.subtotal) {
                        const subtotalValue = parseFloat(service.subtotal.replace('$', '').replace(',', ''));
                        if (!isNaN(subtotalValue)) {
                            totalSubtotal += subtotalValue;
                        }
                    }
                });
                
                // Add total row
                const totalRow = document.createElement('tr');
                totalRow.className = 'border-top';
                
                // Empty cells for spacing
                for (let i = 0; i < 5; i++) {
                    const td = document.createElement('td');
                    if (i === 4) {
                        td.textContent = 'Total:';
                        td.className = 'text-end fw-bold text-muted';
                    }
                    totalRow.appendChild(td);
                }
                
                // Total amount
                const tdTotal = document.createElement('td');
                tdTotal.textContent = `$${totalSubtotal.toFixed(2)}`;
                tdTotal.className = 'text-end fw-bold text-primary';
                totalRow.appendChild(tdTotal);
                
                tbody.appendChild(totalRow);
                table.appendChild(tbody);
                servicesContainer.appendChild(table);
                            } else {
                const noServices = document.createElement('div');
                noServices.className = 'text-center py-3 text-muted';
                noServices.innerHTML = '<i class="fas fa-info-circle me-2"></i> No services information available';
                servicesContainer.appendChild(noServices);
            }
            
            // Render uploaded images gallery with lightbox
            const uploadedImagesGallery = document.getElementById('uploadedImagesGallery');
            uploadedImagesGallery.innerHTML = '';
            let imageUrls = [];
            if (props.uploaded_image && typeof props.uploaded_image === 'string') {
                imageUrls = props.uploaded_image.split(',').map(url => url.trim()).filter(url => url.length > 0);
            }
            if (imageUrls.length > 0) {
                const galleryContainer = document.createElement('div');
                galleryContainer.className = 'uploaded-images-gallery';
                
                imageUrls.forEach(function(imgUrl, idx) {
                    const a = document.createElement('a');
                    a.href = imgUrl;
                    a.className = 'lightbox-image';
                    a.setAttribute('data-lightbox', 'gallery-' + (props.transaction_id || 'calendar'));
                    a.setAttribute('data-title', event.title + ' - Uploaded Image ' + (idx + 1));
                    
                    const img = document.createElement('img');
                    img.src = imgUrl;
                    img.alt = 'Uploaded Image ' + (idx + 1);
                    img.className = 'img-thumbnail';
                    img.style.height = '100%';
                    img.style.width = '100%';
                    img.style.objectFit = 'cover';
                    
                    a.appendChild(img);
                    galleryContainer.appendChild(a);
                });
                
                uploadedImagesGallery.appendChild(galleryContainer);
                
                // Initialize lightbox for these new elements
                if (typeof lightbox !== 'undefined') {
                    lightbox.option({
                        'resizeDuration': 200,
                        'wrapAround': true,
                        'albumLabel': 'Image %1 of %2',
                        'fadeDuration': 300,
                        'fitImagesInViewport': true
                    });
                } else {
                    console.warn('Lightbox library not loaded. Images may not open in lightbox view.');
                }
            } else {
                uploadedImagesGallery.innerHTML = '<div class="text-center py-3 text-muted"><i class="fas fa-image me-2"></i>No images uploaded for this transaction.</div>';
            }
            
            // Show modal
            var transactionModal = new bootstrap.Modal(document.getElementById('transactionModal'));
            transactionModal.show();
        }
        
        // Refresh Calendar button handler
        document.getElementById('refreshCalendar').addEventListener('click', function() {
            // Show loading indicator
            Swal.fire({
                title: 'Refreshing Calendar',
                text: 'Please wait while we refresh the calendar data...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Refresh calendar data
            calendar.refetchEvents().then(() => {
                // Close loading indicator
                Swal.close();
                
                // Show success notification
                Swal.fire({
                    title: 'Success!',
                    text: 'Calendar has been refreshed with the latest transaction data.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            }).catch(error => {
                console.error('Error refreshing calendar:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to refresh calendar data. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });
        
        // Calendar navigation buttons
        document.getElementById('calendar-prev').addEventListener('click', function() {
            calendar.prev();
        });
        
        document.getElementById('calendar-next').addEventListener('click', function() {
            calendar.next();
        });
        
        document.getElementById('calendar-today').addEventListener('click', function() {
            calendar.today();
        });
    });
</script>

<style>
    /* Legend Dots */
    .legend-dot {
        display: inline-block;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        margin-right: 6px;
        vertical-align: middle;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }

    /* Modern Calendar Styling */
    .fc {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fc;
        border-radius: 14px;
        box-shadow: 0 0 24px rgba(0, 0, 0, 0.07);
        padding: 18px;
        border: 1px solid #e3e6f0;
    }
    .fc-toolbar-title {
        font-weight: 700;
        color: #2e59d9;
        font-size: 1.6rem !important;
        letter-spacing: 0.5px;
    }
    .fc-col-header-cell {
        padding: 12px 0;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
        color: #4e73df;
        background: #f4f7fa;
        border-bottom: 2px solid #e3e6f0;
    }
    .fc-daygrid-day {
        transition: background-color 0.3s ease;
        background: #fff;
    }
    .fc-daygrid-day:hover {
        background-color: #f0f4ff;
    }
    .fc-day-today {
        background-color: #eaf1ff !important;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(78, 115, 223, 0.08);
    }
    .fc-day-today .fc-daygrid-day-number {
        background-color: #4e73df;
        color: white;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 6px auto 0 auto;
        font-size: 1.1rem;
    }
    .fc-event {
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 7px;
        border: none;
        padding: 5px 10px;
        margin: 3px 0;
        font-size: 0.97rem;
        font-weight: 500;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        letter-spacing: 0.01em;
    }
    .fc-event:hover {
        transform: translateY(-2px) scale(1.03);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.13);
        z-index: 2;
    }
    .fc-daygrid-event {
        white-space: normal;
        align-items: center;
    }
    .fc-daygrid-event-dot {
        display: none;
    }
    .fc-daygrid-event-time {
        padding-left: 0;
    }
    .fc-daygrid-event-title {
        padding-left: 0;
    }
    .fc-daygrid-day-events {
        margin-top: 6px;
    }
    .fc-daygrid-day-number {
        font-weight: 600;
        color: #4e73df;
        font-size: 1rem;
    }
    
    /* Modal Styling */
    .modal-content {
        border-radius: 10px;
        border: none;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
    }
    
    .modal-header {
        border-bottom: 1px solid #e3e6f0;
        padding: 1.5rem;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .modal-footer {
        border-top: 1px solid #e3e6f0;
        padding: 1.5rem;
    }
    
    .event-date {
        background: linear-gradient(135deg, #4e73df, #2e59d9);
        color: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.2);
    }
    
    .event-date h3 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0;
    }
    
    .event-date p {
        font-size: 1.2rem;
        margin-bottom: 0;
        opacity: 0.9;
    }
    
    /* Tab Styling */
    .nav-tabs .nav-link {
        color: #555;
        font-weight: 500;
        border: none;
        border-bottom: 2px solid transparent;
    }
    
    .nav-tabs .nav-link.active {
        color: #4e73df;
        background-color: transparent;
        border-bottom: 2px solid #4e73df;
    }
    
    .nav-tabs .nav-link:hover:not(.active) {
        border-bottom: 2px solid #e3e6f0;
    }
    
    /* Card Styling */
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08) !important;
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    /* Button Styling */
    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
        box-shadow: 0 2px 5px rgba(78, 115, 223, 0.2);
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2e59d9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(78, 115, 223, 0.3);
    }
    
    .btn-outline-primary {
        color: #4e73df;
        border-color: #4e73df;
        transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
        background-color: #4e73df;
        border-color: #4e73df;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(78, 115, 223, 0.3);
    }
    
    .btn-secondary {
        background-color: #858796;
        border-color: #858796;
        box-shadow: 0 2px 5px rgba(133, 135, 150, 0.2);
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        background-color: #717384;
        border-color: #717384;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(133, 135, 150, 0.3);
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .fc-toolbar {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .fc-toolbar-chunk {
            margin-bottom: 10px;
        }
        
        .event-date {
            margin-bottom: 20px;
        }
    }
    
    /* Professional Event List */
    .fc-event-pro {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0px;
        border-radius: 5px;
        padding: 3px 8px 3px 7px;
        margin-bottom: 2px;
        min-width: 0;
        position: relative;
        background: none !important;
        box-shadow: none !important;
        border: none !important;
        transition: background 0.2s;
        cursor: pointer;
    }
    .fc-event-pro:hover {
        background: #f4f7fa !important;
        color: #222 !important;
    }
    .fc-event-pro-title {
        font-weight: 600;
        font-size: 0.93rem;
        line-height: 1.2;
        word-break: break-word;
        margin-bottom: 1px;
    }
    .fc-event-pro-time {
        font-size: 0.92rem;
        color: #e3e6f0;
        font-weight: 400;
    }
    .fc-event-pro-badge {
        font-size: 0.65rem;
        font-weight: 600;
        border-radius: 5px;
        padding: 1px 6px;
        margin-top: 1px;
        margin-left: 0;
        background: rgba(255,255,255,0.18);
        color: #fff;
        letter-spacing: 0.03em;
        text-transform: capitalize;
        box-shadow: 0 1px 2px rgba(44,62,80,0.07);
    }
    .fc-event-pro-badge-completed {
        background: #169a6c;
    }
    .fc-event-pro-badge-in_progress {
        background: #dda20a;
        color: #fff;
    }
    .fc-event-pro-badge-cancelled {
        background: #be2617;
    }
    .fc-event-pro-badge-lead {
        background: #2e59d9;
    }
    .fc-event-pro-badge-pending {
        background: #d66a0a;
    }
    .fc-event-pro-badge-other {
        background: #6c757d;
    }

    /* Services Tab Professional Styling */
    #financial-tab-pane {
        padding: 1.5rem;
    }
    #financial-tab-pane .table {
        margin-bottom: 0;
    }
    #financial-tab-pane .table th {
        font-weight: 600;
        color: #555;
        border-top: none;
        border-bottom: 1px solid #e3e6f0;
        background-color: #f8f9fc;
        padding: 0.75rem;
        font-size: 0.9rem;
    }
    #financial-tab-pane .table td {
        padding: 0.75rem;
        vertical-align: middle;
        color: #555;
        font-size: 0.95rem;
    }
    #financial-tab-pane .table-light {
        background-color: #f8f9fc;
    }
    #financial-tab-pane .table-light td {
        border-top: 1px solid #e3e6f0;
    }
    #financial-tab-pane p.mb-2 {
        font-size: 0.95rem;
    }
    #financial-tab-pane .card-header h6 {
        font-size: 0.95rem;
    }
    #financial-tab-pane .border-top {
        border-top: 1px solid #e3e6f0 !important;
        background-color: #f8f9fc;
    }
    #financial-tab-pane .border-top td {
        padding-top: 0.85rem;
        padding-bottom: 0.85rem;
    }

    /* Add styles for uploaded images gallery */
    .uploaded-images-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        padding: 10px 0;
    }
    .lightbox-image {
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        overflow: hidden;
        width: 120px;
        height: 120px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(44,62,80,0.1);
        transition: all 0.3s ease;
        position: relative;
    }
    .lightbox-image:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(44,62,80,0.15);
    }
    .lightbox-image::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.03);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .lightbox-image:hover::after {
        opacity: 1;
    }
    .lightbox-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .lightbox-image:hover img {
        transform: scale(1.05);
    }

    /* Lightbox overrides */
    .lb-outerContainer {
        border-radius: 8px;
        background-color: #fff;
    }
    .lb-dataContainer {
        border-radius: 0 0 8px 8px;
    }
    .lb-number {
        color: #4e73df !important;
    }
    .lb-caption {
        font-weight: 600 !important;
        color: #2e59d9 !important;
    }
    .lb-data .lb-close {
        opacity: 0.8;
    }
    .lb-data .lb-close:hover {
        opacity: 1;
    }
</style>