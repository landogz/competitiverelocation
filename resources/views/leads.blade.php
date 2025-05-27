@extends('includes.app')

@section('title', 'Dashboard')

<!-- Required Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.ckeditor.com/4.19.1/standard-all/ckeditor.js"></script>
<!-- Select2 CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- DataTables CSS and JS -->
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<!-- Moment.js for date formatting -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>


<link href="https://unpkg.com/lightbox2@2.11.3/dist/css/lightbox.min.css" rel="stylesheet">
  <script src="https://unpkg.com/lightbox2@2.11.3/dist/js/lightbox-plus-jquery.min.js"></script>



<style>
    /* CKEditor custom styling */
    .cke {
        visibility: hidden;
    }
    
    .cke_top {
        border-bottom: 1px solid #e5e7eb !important;
        background: #f9fafb !important;
    }
    
    .cke_chrome {
        border-color: #e5e7eb !important;
        box-shadow: none !important;
        border-radius: 8px !important;
    }
    
    .cke_bottom {
        border-top: 1px solid #e5e7eb !important;
        background: #f9fafb !important;
    }
    
    .cke_editable {
        padding: 10px;
        min-height: 200px;
        font-size: 14px;
        line-height: 1.6;
    }
    
    /* Hide CKEditor notifications */
    .cke_notifications_area {
        display: none !important;
    }
    
    /* CKEditor Table styles */
    .cke_editable table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
        border: 2px solid #bbb;
        table-layout: fixed;
    }
    
    .cke_editable table th {
        padding: 10px;
        background-color: #f2f2f2;
        border: 1px solid #ccc;
        font-weight: bold;
        text-align: left;
    }
    
    .cke_editable table td {
        padding: 10px;
        border: 1px solid #ccc;
        vertical-align: top;
    }
    
    .cke_editable table tr:nth-child(even) {
        background-color: #fafafa;
    }
</style>
@section('content')
<meta name="transaction-id" content="{{ $transaction->id ?? '' }}">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">{{ isset($transaction) ? 'Edit Transaction' : 'Customer Registration' }}</h4>                               
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div><!--end row-->
    <div class="row">                        
        <div class="col-md-12 col-lg-3">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">Lead Information</h4>                      
                        </div><!--end col-->
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                <form id="leadForm" action="{{ isset($transaction) ? route('leads.update', $transaction->id) : route('leads.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($transaction))
                            @method('PUT')
                        @endif
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">First Name <span class="text-danger">*</span></label>
                        <input class="form-control form-control-sm" type="text" name="firstname" id="firstname" value="{{ $transaction->firstname ?? '' }}" required>                               
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Last Name <span class="text-danger">*</span></label>
                        <input class="form-control form-control-sm" type="text" name="lastname" id="lastname" value="{{ $transaction->lastname ?? '' }}" required>                               
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Email <span class="text-danger">*</span></label>
                        <input class="form-control form-control-sm" type="email" name="email" id="email" value="{{ $transaction->email ?? '' }}" required>                               
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Phone Number <span class="text-danger">*</span></label>
                        <input class="form-control form-control-sm" type="text" name="phone" id="phone" value="{{ $transaction->phone ?? '' }}" required>                               
                    </div>
                    <hr>
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Lead Source <span class="text-danger">*</span></label>
                        <select id="lead_source" name="lead_source" class="form-select" required>
                            <option value="">Select Source</option>
                            @foreach($leadSources as $id => $name)
                                <option value="{{ $id }}" {{ (isset($transaction) && $transaction->lead_source == $id) ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>                                    
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Lead Type <span class="text-danger">*</span></label>
                        <select id="lead_type" name="lead_type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="local" {{ (isset($transaction) && $transaction->lead_type == 'local') ? 'selected' : '' }}>Local</option>
                            <option value="long_distance" {{ (isset($transaction) && $transaction->lead_type == 'long_distance') ? 'selected' : '' }}>Long Distance</option>
                            <option value="international" {{ (isset($transaction) && $transaction->lead_type == 'international') ? 'selected' : '' }}>International</option>
                        </select>                                    
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Assigned Agent <span class="text-danger">*</span></label>
                        <select id="assigned_agent" name="assigned_agent" class="form-select" required>
                            <option value="">Select Agent</option>
                            @foreach($agents as $id => $companyName)
                                <option value="{{ $id }}" {{ (isset($transaction) && $transaction->assigned_agent == $id) ? 'selected' : '' }}>{{ $companyName }}</option>
                            @endforeach
                        </select>                                    
                    </div>
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col--> 
        <div class="col-md-12 col-lg-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0"></h4>
                    @if(!isset($transaction))
                        <button type="button" id="saveButton" class="btn btn-success btn-xl" onclick="saveTransactionData()">Save Data</button>
                    @endif
                </div>
                <div class="card-body pt-0">
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills nav-justified" role="tablist">
                        <li class="nav-item waves-effect waves-light" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#home-1" role="tab" aria-selected="true">Contact Info</a>
                        </li>
                        <li class="nav-item waves-effect waves-light" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#profile-1" role="tab" aria-selected="false" tabindex="-1">Local Info</a>
                        </li>
                        <li class="nav-item waves-effect waves-light" role="presentation">  
                            <a class="nav-link" data-bs-toggle="tab" href="#settings-1" role="tab" aria-selected="false" tabindex="-1">Insurance</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane p-0 active show" id="home-1" role="tabpanel">
                                <div class="card-body pt-0">
                                    <hr>
                                    <div class="row mb-3">
                                        <div id="map" style="width: 100%; height: 250px; margin-top: 20px;"></div>
                                    </div>                                    

                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label class="form-label">Service</label>
                                                    <button type="button" id="selectServiceBtn" class="btn btn-primary w-100">
                                                        Select Service
                                                    </button>
                                                    <input type="hidden" name="service" id="service" value="">
                                                
                                                    @php
                                                        $serviceName = 'N/A';
                                                    if (isset($transaction->services)) {
                                                        if (is_array($transaction->services) && count($transaction->services) > 0) {
                                                            $serviceName = $transaction->services[0]['name'] ?? 'N/A';
                                                        } elseif (is_string($transaction->services)) {
                                                            $decoded = json_decode($transaction->services, true);
                                                            if (is_array($decoded) && count($decoded) > 0) {
                                                                $serviceName = $decoded[0]['name'] ?? 'N/A';
                                                            }
                                                            }
                                                        }
                                                    @endphp
                                                <input type="text" class="form-control mt-2" name="service_display" id="service_display" value="{{ $serviceName }}" placeholder="Selected Service" readonly>
                                            </div>                  
                                        </div>                     
                                        <div class="col-lg-3">   
                                            <div class="mb-3">
                                                <label class="form-label">Move Date</label>
                                                <input type="date" class="form-control" name="date" value="{{ isset($transaction) ? $transaction->date->format('Y-m-d') : '' }}" placeholder="Enter Move Date">
                                            </div>                                          
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label class="form-label">No. of Moving or Delivery or Removal</label>
                                                <input type="number" class="form-control" name="no_of_items" id="no_of_items" value="{{ $transaction->no_of_items ?? '' }}" placeholder="No. of Moving" min="1">
                                            </div>                     
                                        </div>
                                        <div class="col-lg-3">   
                                            <div class="mb-3">
                                                <label class="form-label">Pictures of Removal Items / Receipt</label>
                                                @if(!isset($transaction))
                                                <div id="uploadSection">
                                                    <button type="button" class="btn rounded-pill btn-success btn-xl mb-2" id="uploadBtn" onclick="document.getElementById('image_upload').click()">
                                                        <i class="fas fa-upload me-2"></i>Upload Images
                                                    </button>
                                                    <input type="file" id="image_upload" name="uploaded_image[]" style="display: none;" multiple accept="image/*">
                                                    <div id="imagePreview" class="d-flex gap-2 flex-wrap mt-2"></div>
                                                    <small class="text-muted" id="uploadStatus"></small>
                                                </div>
                                                @endif
                                                @if(isset($transaction) && $transaction->uploaded_image)
                                                <div class="d-flex gap-2 flex-wrap mt-2">
                                                    @php
                                                        $images = explode(',', $transaction->uploaded_image);
                                                    @endphp
                                                    @foreach($images as $image)
                                                        @if(trim($image))
                                                            <a href="{{ trim($image) }}" class="lightbox-image" data-lightbox="uploaded-images" data-title="Uploaded Image" >
                                                                <img src="{{ trim($image) }}" alt="Transaction Image" class="img-thumbnail" style="height: 50px; object-fit: cover;">
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                @endif
                                            </div>                                          
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <div class="mb-3">
                                                <label class="form-label">Pick-up Address</label>
                                                <input type="text" class="form-control" name="pickup_location" id="pickup_location" value="{{ $transaction->pickup_location ?? 'Tampo (Pob.), Botolan, Zambales, Philippines' }}" placeholder="Enter Pick-up Address">
                                            </div>                     
                                        </div>
                                        <div class="col-lg-5">   
                                            <div class="mb-3">
                                                <label class="form-label">Drop Off Address</label>
                                                <input type="text" class="form-control" name="delivery_location" id="delivery_location" value="{{ $transaction->delivery_location ?? 'San Juan, Botolan, Zambales, Philippines' }}" placeholder="Enter Drop Off Address">
                                            </div>                                          
                                        </div>
                                        <div class="col-lg-2">   
                                            <div class="mb-3">
                                                <label class="form-label">Calculated Miles</label>
                                                <input type="number" class="form-control" name="miles" id="miles" value="{{ $transaction->miles ?? '' }}" placeholder="Miles" readonly>
                                                <div id="distance_info" class="text-muted small mt-1"></div>
                                            </div>                                          
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label class="form-label">Sales Reps Name</label>
                                                <input type="text" class="form-control" name="sales_name" id="sales_name" value="{{ $transaction->sales_name ?? '' }}" placeholder="Sales Reps Name">
                                            </div>                     
                                        </div>
                                        <div class="col-lg-4">   
                                            <div class="mb-3">
                                                <label class="form-label">Sales Reps Email</label>
                                                <input type="email" class="form-control" name="sales_email" id="sales_email" value="{{ $transaction->sales_email ?? '' }}" placeholder="Sales Reps Email">
                                            </div>                                          
                                        </div>
                                        <div class="col-lg-4">   
                                            <div class="mb-3">
                                                <label class="form-label">Store Location</label>
                                                <input type="text" class="form-control" name="sales_location" id="sales_location" value="{{ $transaction->sales_location ?? '' }}" placeholder="Store Location">
                                            </div>                                          
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="tab-pane p-3" id="profile-1" role="tabpanel">
                            <div class="card-body pt-0">
                                <hr>                                   
                                <div class="row">
                                    <div class="col-lg-9">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <button type="button" class="btn btn-warning btn-xl" data-bs-toggle="modal" data-bs-target="#cheatsheet">SHOW CHEAT SHEET</button>
                                            @if(isset($transaction))
                                            <button type="button" class="btn btn-info btn-xl"  data-bs-toggle="modal" data-bs-target="#setinventory">SET INVENTORY</button>
                                            @endif
                                        </div>    
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="total_volume" class="form-label">Total Volume From Inventory</label>
                                                    <input type="text" class="form-control" id="total_volume" name="total_volume" value="0" readonly>                   
                                                </div>
                                            </div><!--end col-->  
                                            <div class="col-lg-6">   
                                                <div class="mb-3">
                                                    <label for="total_weight" class="form-label">Total Weight From Inventory</label>
                                                    <input type="text" class="form-control" id="total_weight" name="total_weight" value="0" readonly>                                         
                                                </div>
                                            </div><!--end col-->
                                        </div>      
                                        
                                        <!-- Added Inventory Items Table -->
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <h5>Added Inventory Items</h5>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered mb-0 table-centered" id="added-inventory-table">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Item</th>
                                                                <th>Category</th>
                                                                <th>Cubic Feet</th>
                                                                <th>Quantity</th>
                                                                <th>Total Volume</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="added-inventory-items">
                                                            <!-- Items will be added here dynamically -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end col-->    
                                    <div class="col-lg-3">
                                        <div class="card">
                                            <div class="card-body pt-0">
                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">Subtotal</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" class="form-control" name="subtotal" id="subtotal" value="{{ isset($transaction) ? number_format($transaction->subtotal, 2) : '0.00' }}" readonly>                   
                                                    </div>
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">Added Mile Rate</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" class="form-control" name="added_mile_rate" id="added_mile_rate" value="{{ isset($transaction) ? number_format($transaction->mile_rate, 2) : '0.00' }}" readonly>                   
                                                    </div>
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">Software Management Fee</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" class="form-control" name="software_fee" id="software_fee" value="{{ isset($transaction) ? number_format($transaction->software_fee, 2) : '0.00' }}" readonly>                   
                                                    </div>
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">Truck Fee</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" class="form-control" name="truck_fee" id="truck_fee" value="{{ isset($transaction) ? number_format($transaction->truck_fee, 2) : '0.00' }}" readonly>                   
                                                    </div>
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">Estimated Total</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" class="form-control" name="grand_total" id="grand_total" value="{{ isset($transaction) ? number_format($transaction->grand_total, 2) : '0.00' }}" readonly>                   
                                                    </div>
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">DownPayment</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" class="form-control" name="downpayment" id="downpayment" value="{{ isset($transaction) ? number_format($transaction->downpayment, 2) : '0.00' }}" readonly>                   
                                                    </div>
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">Remaining Balance</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" class="form-control" name="remaining_balance" id="remaining_balance" value="{{ isset($transaction) ? number_format($transaction->grand_total - $transaction->downpayment, 2) : '0.00' }}" readonly>                   
                                                    </div>
                                                </div>
                                            </div><!--end card-body-->   
                                    </div><!--end col-->                                    
                                </div> <!--end row-->
                               
                            </div>
                            </div>
                        </div>
                        <div class="tab-pane p-3" id="settings-1" role="tabpanel">
                            <div class="card-body pt-0">
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Insurance Number</label>
                                            <input type="text" class="form-control" name="insurance_number" id="insurance_number" value="{{ $transaction->insurance_number ?? '' }}" placeholder="Enter Insurance Number" {{ !isset($transaction) ? 'disabled' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Insurance Document</label>
                                            <input type="file" class="form-control" name="insurance_document" id="insurance_document" accept=".pdf,.doc,.docx" {{ !isset($transaction) ? 'disabled' : '' }}>
                                            @if(isset($transaction) && $transaction->insurance_document)
                                                <div class="mt-2">
                                                    <a href="{{ $transaction->insurance_document }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fas fa-file-alt"></i> View Current Document
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                    </div>
            </div><!--end card-->                             
        </div> <!--end col-->           
    </div><!--end row-->       
    <div class="row">
        <div class="col-md-12 col-lg-12">
            @if(isset($transaction))
            <div class="card">
                <div class="card-body pt-0">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item active" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#sentemails" role="tab" aria-selected="true">SENT EMAILS</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#payments" role="tab" aria-selected="false" tabindex="-1">PAYMENTS</a>
                        </li>  
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane p-0 pt-3 active show" id="sentemails" role="tabpanel">
                            
                        <button type="button" class="btn rounded-pill btn-success btn-xl mb-2" id="sendMailBtn" data-transaction-id="{{ $transaction->id ?? '' }}">
                                    <i class="fas fa-envelope me-2"></i>Send Email
                                </button>

                            <div class="table-responsive">
                                <table class="table table-bordered mb-0 table-centered" id="sentEmailsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Subject</th>
                                            <!-- <th>Recipient</th> -->
                                            <th>Template</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane p-0 pt-3" id="payments" role="tabpanel">
                                    <button type="button" class="btn rounded-pill btn-success btn-xl mb-2">Add Payment</button>
                                    <button type="button" class="btn rounded-pill btn-success btn-xl mb-2">Send Deposit Needed</button>
                            <p class="mb-0 text-muted">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0 table-centered">
                                        <thead class="table-light">
                                        <tr>
                                            <th>Trans #</th>
                                            <th>Message</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center" style="width:50px;">Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>#124781</td>   
                                            <td>test</td>                                
                                            <td>25/11/2018</td>
                                            <td><span class="badge bg-success">Approved</span></td>
                                        </tr>
                                        </tbody>
                                    </table><!--end /table-->
                                </div>
                            </p>
                        </div>    
                    </div>              
                </div>
            </div><!--end card-->                             
            @endif
        </div> <!--end col-->   
    </div>                                  
</div>

<div class="modal fade cheatsheet" id="cheatsheet" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title m-0" id="myExtraLargeModalLabel">Cheat Sheet</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div><!--end modal-header-->
            <div class="modal-body">
                    <div class="row">       
                        <table class="table table-bordered text-center">
                            <thead class="table-warning">                                                          
                                <tr>
                                    <td colspan="7" class="bg-danger text-white"><h5>Hourly Rate (Use for fullsize or house moves over 6000lbs. Also use if traveletween customers locations are over and hour away)</h5> </td>
                                </tr>
                                <tr>
                                    <th>Weight</th>
                                    <th>Men</th>
                                    <th></th>
                                    <th>Price</th>
                                    <th>Travel under 60 miles</th>
                                    <th>Travel over 60 miles</th>
                                    <th>Additional Time</th>
                                </tr>
                            </thead>
                            <tbody>      
                                <tr>
                                    <td>Under 2000lbs</td>
                                    <td>2</td>
                                    <td>2</td>
                                    <td>See VS</td>
                                    <td>1 Hour</td>
                                    <td>1.5+ Hours</td>
                                    <td>Hourly Rate</td>
                                </tr>
                                <tr>
                                    <td>2001-2400lbs</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>See VS</td>
                                    <td>1 Hour</td>
                                    <td>1.5+ Hours</td>
                                    <td>Hourly Rate</td>
                                </tr>
                                <tr>
                                    <td>2401-3000lbs</td>
                                    <td>2</td>
                                    <td>4</td>
                                    <td>See VS</td>
                                    <td>1 Hour</td>
                                    <td>1.5+ Hours</td>
                                    <td>Hourly Rate</td>
                                </tr>
                                <tr>
                                    <td>2001-2400lbs (2nd+ Floor)</td>
                                    <td>3</td>
                                    <td>3</td>
                                    <td>See VS</td>
                                    <td>1 Hour</td>
                                    <td>1.5+ Hours</td>
                                    <td>Hourly Rate</td>
                                </tr>
                                <tr>
                                    <td>2401-3500lbs</td>
                                    <td>3</td>
                                    <td>3</td>
                                    <td>See VS</td>
                                    <td>1 Hour</td>
                                    <td>1.5+ Hours</td>
                                    <td>Hourly Rate</td>
                                </tr>
                                <tr>
                                    <td>16,000+ lbs</td>
                                    <td colspan="6" class="bg-danger text-white">Needs Management approval before quote is given</td>
                                </tr>
                                <tr>
                                    <td>20,000+ lbs (Commercial)</td>
                                    <td colspan="6" class="bg-danger text-white">Needs Management approval before quote is given</td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="bg-warning text-uppercase fw-bold text-center">
                                        Make sure to add the time it takes between the customer's two locations. This is added in the hourly labor rate, not travel time.
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="bg-primary text-white fw-bold">Any stairs on either side add 1 hour for each set on each side</td>
                                    <td colspan="2" class="bg-warning fw-bold">Each additional man is $35.00/hour</td>
                                    <td colspan="3" class="bg-purple text-white fw-bold">Each additional truck is $28.00/hour</td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered text-center">
                            <thead>
                                <tr class="bg-danger text-white">
                                    <th colspan="5" class="py-4 text-uppercase">
                                        Labor only (If no truck is needed. Time needed is same as hours listed above with package and hourly rates)
                                    </th>
                                </tr>
                                <tr class="bg-warning text-uppercase">
                                    <th>Men</th>
                                    <th>Price</th>
                                    <th>Travel under 60 miles</th>
                                    <th>Travel over 60 miles</th>
                                    <th>Additional Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2 Men</td>
                                    <td>$70.00/hour</td>
                                    <td>1 Hour</td>
                                    <td>1.5 + Hours</td>
                                    <td>Hourly Rate</td>
                                </tr>
                                <tr>
                                    <td>3 Men</td>
                                    <td></td>
                                    <td>1 Hour</td>
                                    <td >1.5 + Hours</td>
                                    <td>Hourly Rate</td>
                                </tr>
                                <tr>
                                    <td>4 Men</td>
                                    <td></td>
                                    <td>1 Hour</td>
                                    <td>1.5 + Hours</td>
                                    <td>Hourly Rate</td>
                                </tr>
                                <tr>
                                    <td>5 Men</td>
                                    <td></td>
                                    <td>1 Hour</td>
                                    <td>1.5 + Hours</td>
                                    <td>Hourly Rate</td>
                                </tr>
                            </tbody>
                        </table>         
                    </div>
            </div><!--end modal-body-->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div><!--end modal-footer-->
        </div><!--end modal-content-->
    </div><!--end modal-dialog-->
</div><!--end modal-->



<div class="modal fade" id="setinventory" tabindex="-1" aria-labelledby="setinventory" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="setinventory">Inventory Tool Form</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row text-center">
                <h3>Inventory Tool Form</h3>
                <p>Set all Inventory information below, charges are included on Local Information record.</p>
            </div>
            <table class="table datatable table-bordered mb-0 table-centered" id="datatable_1">
                <thead class="table-light">
                  <tr>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Cubic Feet</th>
                    <th>Quantity</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($inventoryItems as $item)
                    <tr>
                        <td>{{ $item->item }}</td>
                        <td>{{ $item->category->name }}</td>
                        <td>{{ $item->cubic_ft }}</td>
                        <td>
                            <input type="number" class="form-control form-control-sm quantity-input" 
                                   data-item-id="{{ $item->id }}"
                                   value="{{ isset($transaction) && $transaction->inventoryItems->contains($item->id) ? $transaction->inventoryItems->find($item->id)->pivot->quantity : 0 }}"
                                   min="0">
                        </td>
                    </tr>                                                                         
                    @endforeach
                </tbody>
              </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfJAEOOESD7IvZy6qBYvOz7opcYVnDksw&libraries=places"></script>

<script>
	let pickupAutocomplete, dropoffAutocomplete;
let map, directionsService, directionsRenderer, distanceService;
let pickupMarker, dropoffMarker;

// Initialize Google Map and Autocomplete
function initMap() {
    // Initialize the map
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 14.7011, lng: 120.5135 }, // Center on Botolan area
        zoom: 12,
        mapTypeControl: true,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
            position: google.maps.ControlPosition.TOP_LEFT,
        },
    });

    // Initialize services
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({
        map: map,
        suppressMarkers: true, // We'll create our own markers
        polylineOptions: {
            strokeColor: '#2196F3',
            strokeWeight: 5
        }
    });

    // Create markers
    pickupMarker = new google.maps.Marker({
        map: map,
        icon: {
            url: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png',
            labelOrigin: new google.maps.Point(16, -10)
        },
        label: {
            text: 'A',
            color: '#ffffff',
            fontWeight: 'bold'
        }
    });

    dropoffMarker = new google.maps.Marker({
        map: map,
        icon: {
            url: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png',
            labelOrigin: new google.maps.Point(16, -10)
        },
        label: {
            text: 'B',
            color: '#ffffff',
            fontWeight: 'bold'
        }
    });

    // Set up autocomplete for pickup and dropoff addresses
    pickupAutocomplete = new google.maps.places.Autocomplete(
        document.querySelector('input[name="pickup_location"]'),
        { componentRestrictions: { country: 'ph' } }
    );
    dropoffAutocomplete = new google.maps.places.Autocomplete(
        document.querySelector('input[name="delivery_location"]'),
        { componentRestrictions: { country: 'ph' } }
    );

    // Add listeners for address changes
    pickupAutocomplete.addListener('place_changed', () => {
        const place = pickupAutocomplete.getPlace();
        if (place.geometry) {
            pickupMarker.setPosition(place.geometry.location);
            calculateRoute();
        }
    });

    dropoffAutocomplete.addListener('place_changed', () => {
        const place = dropoffAutocomplete.getPlace();
        if (place.geometry) {
            dropoffMarker.setPosition(place.geometry.location);
            calculateRoute();
        }
    });

    // If we have initial addresses, calculate the route
    const pickup = document.querySelector('input[name="pickup_location"]').value;
    const dropoff = document.querySelector('input[name="delivery_location"]').value;
    if (pickup && dropoff) {
        calculateRoute();
    }
}

function calculateRoute() {
    const pickup = document.querySelector('input[name="pickup_location"]').value;
    const dropoff = document.querySelector('input[name="delivery_location"]').value;

    if (pickup && dropoff) {
        directionsService.route(
            {
                origin: pickup,
                destination: dropoff,
                travelMode: google.maps.TravelMode.DRIVING,
            },
            (response, status) => {
                if (status === 'OK') {
                    directionsRenderer.setDirections(response);
                    const route = response.routes[0];
                    if (route && route.legs[0]) {
                        const distance = route.legs[0].distance.value / 1609.34; // Convert meters to miles
                        const roundTripDistance = distance * 2; // Multiply by 2 for round trip
                        document.querySelector('input[name="miles"]').value = roundTripDistance.toFixed(2); // Show 2 decimal places
                        
                        // Update distance info
                        const distanceText = route.legs[0].distance.text;
                        const durationText = route.legs[0].duration.text;
                        document.getElementById('distance_info').textContent = 
                            `Distance: ${distanceText} - (Round Trip)`;

                        // Update marker positions
                        pickupMarker.setPosition(route.legs[0].start_location);
                        dropoffMarker.setPosition(route.legs[0].end_location);

                        // Fit bounds to show both markers
                        const bounds = new google.maps.LatLngBounds();
                        bounds.extend(route.legs[0].start_location);
                        bounds.extend(route.legs[0].end_location);
                        map.fitBounds(bounds);

                        // Calculate Added Miles and Added Mile Rate based on new rule
                        const addedMiles = roundTripDistance;
                        let addedMileRate = 0;
                        
                        // If round trip distance is over 12.5 miles, charge $3.41 per mile
                        if (addedMiles > 12.5) {
                            addedMileRate = addedMiles * 0.89;
                        }

                        // Update the fields
                        document.getElementById('added-miles').value = addedMiles.toFixed(2);
                        document.getElementById('added-mile-rate').value = addedMileRate.toFixed(2);

                        // Update grand total with new mile rate
                        updateGrandTotal();
                    }
                }
            }
        );
    }
}

// Load the Google Maps API
window.onload = function () {
    initMap();
};

</script>
<style>
#toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

.custom-toast {
    background: #fff;
    border-left: 4px solid #28a745;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    color: #333;
    padding: 12px 24px;
    margin-bottom: 10px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    min-width: 250px;
    max-width: 350px;
    animation: slideIn 0.3s ease-in-out;
}

.custom-toast.error {
    border-left-color: #dc3545;
}

.custom-toast .toast-icon {
    margin-right: 12px;
    font-size: 20px;
}

.custom-toast .toast-message {
    flex-grow: 1;
}

.custom-toast .toast-close {
    background: none;
    border: none;
    color: #666;
    font-size: 18px;
    padding: 0;
    margin-left: 12px;
    cursor: pointer;
    opacity: 0.7;
}

.custom-toast .toast-close:hover {
    opacity: 1;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.custom-toast.hide {
    animation: slideOut 0.3s ease-in-out forwards;
}

/* Inventory Tool Form Modal Table Styles */
#datatable_1 tbody tr {
    transition: background-color 0.2s ease;
}

#datatable_1 tbody tr:hover {
    background-color: rgba(67, 233, 123, 0.1) !important;
    cursor: pointer;
}

#datatable_1 tbody tr:hover td {
    background-color: transparent !important;
}
</style>
<div id="toast-container"></div>
<script>
// Define sendEmail function globally
let currentTransaction = {};
let ckeditorEmailEditor;
let sentEmailsTable;

function reloadSentEmailsTable() {
    if (sentEmailsTable) {
        sentEmailsTable.ajax.reload();
    }
}

function sendEmail(transactionId) {
    if (!transactionId) {
        showToast('No transaction ID found', 'error');
        return;
    }

    // Get transaction data
    fetch(`/leads/${transactionId}`)
        .then(response => response.json())
        .then(data => {
            // Flatten if nested under 'data' property
            if (data && typeof data === 'object' && data.data && typeof data.data === 'object') {
                data = data.data;
            }
            currentTransaction = data; // Store for placeholder replacement
            // Format date fields for display in templates
            if (currentTransaction.date) {
                currentTransaction.date_formatted = moment(currentTransaction.date).format('MMMM D, YYYY');
            }
            if (currentTransaction.created_at) {
                currentTransaction.created_at_formatted = moment(currentTransaction.created_at).format('MMMM D, YYYY');
            }
            document.getElementById('emailRecipient').value = data.email;
            document.getElementById('emailSubject').value = '';
            document.getElementById('emailMessage').value = '';
            document.getElementById('emailTemplateSelect').value = '';
            if (ckeditorEmailEditor) {
                ckeditorEmailEditor.setData('');
            }
            
            // Show modal using Bootstrap 5
            const modal = new bootstrap.Modal(document.getElementById('sendEmailModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error loading transaction data', 'error');
        });
}

// Helper: Replace placeholders in a string with transaction data
function replacePlaceholders(str, data) {
    if (!str) return '';
    return str.replace(/\{([^}]+)\}/g, function(match, key) {
        // Try direct key
        if (typeof data[key] !== 'undefined') return data[key];
        // Try snake_case to camelCase
        const camelKey = key.replace(/_([a-z])/g, g => g[1].toUpperCase());
        if (typeof data[camelKey] !== 'undefined') return data[camelKey];
        // Try nested keys (e.g., data.pickup.location)
        const keys = key.split('.');
        let value = data;
        for (const k of keys) {
            if (value == null) return match;
            value = value[k];
        }
        return (value !== undefined && value !== null) ? value : match;
    });
}

// Function to insert text into CKEditor
function insertTextIntoCKEditor(editor, text) {
    if (!editor) return;
    
    try {
        // This is the correct way to insert text in CKEditor 4
        var selection = editor.getSelection();
        if (selection) {
            var range = selection.getRanges()[0];
            if (range) {
                range.insertNode(new CKEDITOR.dom.text(text));
                range.select();
            }
        }
    } catch (error) {
        console.error('Error inserting text into CKEditor:', error);
        // Fallback methods
        try {
            editor.insertText(text);
        } catch (e) {
            try {
                editor.insertHtml(text);
            } catch (e2) {
                console.error('All methods to insert text failed');
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Get transaction ID from the page
    const transactionId = document.querySelector('meta[name="transaction-id"]')?.content;
    if (transactionId) {
        currentTransaction.id = transactionId;
    }

    // Add event listeners to the quantity-input fields
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('blur', updateInventoryItem);
        input.addEventListener('change', updateInventoryItem);
    });

    // Add event listeners to lead information and contact info fields
    const leadInfoFields = [
        'firstname', 'lastname', 'email', 'phone', 'lead_source', 
        'lead_type', 'assigned_agent', 'service', 'date', 'no_of_items',
        'pickup_location', 'delivery_location', 'sales_name', 'sales_email', 
        'sales_location', 'insurance_number'
    ];
    
    leadInfoFields.forEach(fieldName => {
        const element = document.querySelector(`[name="${fieldName}"]`);
        if (element) {
            element.addEventListener('blur', function() {
                updateTransactionField(fieldName, this.value);
            });
            
            // For select elements, use change event
            if (element.tagName === 'SELECT') {
                element.addEventListener('change', function() {
                    updateTransactionField(fieldName, this.value);
                });
            }
        }
    });
    
    // Function to update a single transaction field
    function updateTransactionField(field, value) {
        const transactionId = document.querySelector('meta[name="transaction-id"]')?.content;
        
        // If no transaction ID exists, just return without error
        if (!transactionId) {
            return;
        }
        
        fetch(`/leads/${transactionId}/update-field`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                field: field,
                value: value
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Quietly show success message in toast
                showToast(`Updated ${field.replace('_', ' ')}`, 'success');
                
                // If field might affect calculations, recalculate fees
                if (['service', 'no_of_items', 'miles', 'pickup_location', 'delivery_location'].includes(field)) {
                    if (typeof calculateFees === 'function') {
                        calculateFees();
                    }
                    
                    // If address fields changed, recalculate route
                    if (['pickup_location', 'delivery_location'].includes(field)) {
                        if (typeof calculateRoute === 'function') {
                            calculateRoute();
                        }
                    }
                }
            } else {
                throw new Error(data.message || 'Failed to update field');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(`Error updating ${field.replace('_', ' ')}`, 'error');
        });
    }

    // Function to update inventory item quantity when it changes
    function updateInventoryItem(event) {
        const itemId = event.target.dataset.itemId;
        const quantity = parseInt(event.target.value) || 0;
        
        if (!transactionId) {
            console.error('No transaction ID found');
            return;
        }
        
        fetch(`/leads/${transactionId}/update-inventory`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                item_id: itemId,
                quantity: quantity
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update the total volume display
                updateInventorySummary();
                
                // Show success toast if quantity > 0
                if (quantity > 0) {
                    showToast('Inventory item updated', 'success');
                }
            } else {
                throw new Error(data.message || 'Failed to update inventory item');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error updating inventory item', 'error');
        });
    }
    
    // Function to fetch and update inventory summary
    function updateInventorySummary() {
        fetch(`/leads/${transactionId}/added-inventory-items`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update total volume and weight
                    let totalVolume = 0;
                    let totalWeight = 0;
                    const WEIGHT_PER_CUBIC_FOOT = 7; // lbs per cubic foot
                    
                    // Clear and repopulate the added items table
                    const addedItemsContainer = document.getElementById('added-inventory-items');
                    if (addedItemsContainer) {
                        addedItemsContainer.innerHTML = '';
                        
                        data.data.forEach(item => {
                            totalVolume += parseFloat(item.total_volume);
                            totalWeight += parseFloat(item.total_volume) * WEIGHT_PER_CUBIC_FOOT;
                            
                            // Add row to the table
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${item.name}</td>
                                <td>${item.category}</td>
                                <td>${parseFloat(item.cubic_ft).toFixed(2)}</td>
                                <td>${item.quantity}</td>
                                <td>${parseFloat(item.total_volume).toFixed(2)}</td>
                            `;
                            addedItemsContainer.appendChild(row);
                        });
                    }
                    
                    // Update the total volume and weight inputs
                    document.getElementById('total_volume').value = totalVolume.toFixed(2);
                    document.getElementById('total_weight').value = totalWeight.toFixed(2);
                    
                    // Update fees based on new volumes
                    if (typeof calculateFees === 'function') {
                        calculateFees();
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching inventory summary:', error);
            });
    }
    
    // Helper function to show toast messages
    function showToast(message, type = 'success') {
        const toastContainer = document.getElementById('toast-container');
        if (!toastContainer) return;
        
        const toast = document.createElement('div');
        toast.className = 'custom-toast';
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            </div>
            <div class="toast-message">${message}</div>
            <button class="toast-close">&times;</button>
        `;
        
        toastContainer.appendChild(toast);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.style.animation = 'toast-out 0.3s forwards';
            setTimeout(() => {
                toastContainer.removeChild(toast);
            }, 300);
        }, 3000);
        
        // Close button
        toast.querySelector('.toast-close').addEventListener('click', () => {
            toast.style.animation = 'toast-out 0.3s forwards';
            setTimeout(() => {
                toastContainer.removeChild(toast);
            }, 300);
        });
    }
    
    // Initialize inventory summary on page load
    if (transactionId) {
        updateInventorySummary();
    }

    // Initialize DataTable for sent emails
    if (transactionId) {
        sentEmailsTable = $('#sentEmailsTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: `/leads/${transactionId}/sent-emails`,
                dataSrc: 'data'
            },
            columns: [
                { 
                    data: 'created_at',
                    render: function(data) {
                        return moment(data).format('MM/DD/YYYY hh:mm A');
                    }
                },
                { data: 'subject' },
                { data: 'template.name' },
                { 
                    data: 'status',
                    render: function(data) {
                        const statusClass = data === 'sent' ? 'success' : 
                                         data === 'failed' ? 'danger' : 'warning';
                        return `<span class="badge bg-${statusClass}">${data}</span>`;
                    }
                }
            ],
            order: [[0, 'desc']], // Sort by date column in descending order
            pageLength: 10,
            language: {
                emptyTable: "No emails sent yet"
            }
        });
    } else {
        // For new customers, show a message instead of initializing DataTable
        $('#sentEmailsTable').html('<div class="alert alert-info m-3">No emails sent yet for this customer.</div>');
    }

    // Initialize CKEditor
    ckeditorEmailEditor = CKEDITOR.replace('quillEmailEditor', {
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
    
    // Update hidden field when CKEditor content changes
    ckeditorEmailEditor.on('change', function() {
        document.getElementById('emailMessage').value = ckeditorEmailEditor.getData();
    });

    // Initialize Select2 for email template dropdown
    if ($.fn.select2) {
        $('#emailTemplateSelect').select2({
            dropdownParent: $('#sendEmailModal'),
            width: '100%',
            placeholder: 'Select a template',
            allowClear: true
        });
    } else {
        console.error('Select2 is not loaded properly');
    }

    // When template is selected, load subject/content and replace placeholders
    $('#emailTemplateSelect').on('change', function() {
        const selected = this.selectedOptions[0];
        if (!selected || !selected.value) {
            $('#emailSubject').val('');
            ckeditorEmailEditor.setData('');
            return;
        }
        // Replace placeholders in subject/content
        const subject = replacePlaceholders(selected.dataset.subject, currentTransaction);
        const content = replacePlaceholders($('<textarea/>').html(selected.dataset.content).text(), currentTransaction);
        $('#emailSubject').val(subject);
        ckeditorEmailEditor.setData(content);
    });

    // Add click event listener to the send email button
    const sendMailBtn = document.getElementById('sendMailBtn');
    if (sendMailBtn) {
        sendMailBtn.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-transaction-id');
            if (!transactionId) {
                showToast('No transaction ID found', 'error');
                return;
            }

            // Get transaction data
            fetch(`/leads/${transactionId}`)
                .then(response => response.json())
                .then(data => {
                    // Flatten if nested under 'data' property
                    if (data && typeof data === 'object' && data.data && typeof data.data === 'object') {
                        data = data.data;
                    }
                    currentTransaction = data; // Store for placeholder replacement
                    // Format date fields for display in templates
                    if (currentTransaction.date) {
                        currentTransaction.date_formatted = moment(currentTransaction.date).format('MMMM D, YYYY');
                    }
                    if (currentTransaction.created_at) {
                        currentTransaction.created_at_formatted = moment(currentTransaction.created_at).format('MMMM D, YYYY');
                    }
                    document.getElementById('emailRecipient').value = data.email;
                    document.getElementById('emailSubject').value = '';
                    document.getElementById('emailMessage').value = '';
                    document.getElementById('emailTemplateSelect').value = '';
                    ckeditorEmailEditor.setData('');
                    
                    // Show modal using Bootstrap 5
                    const modal = new bootstrap.Modal(document.getElementById('sendEmailModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error loading transaction data', 'error');
                });
        });
    }

    // Handle send email button click
    document.getElementById('sendEmailBtn').addEventListener('click', function() {
        const transactionId = currentTransaction.id;
        const formData = {
            recipient: document.getElementById('emailRecipient').value,
            subject: document.getElementById('emailSubject').value,
            message: ckeditorEmailEditor.getData(),
            template_id: document.getElementById('emailTemplateSelect').value,
            _token: document.querySelector('meta[name="csrf-token"]').content
        };

        // Update hidden input for form submission compatibility with previous Quill implementation
        document.getElementById('emailMessage').value = formData.message;

        // Show loading state
        Swal.fire({
            title: 'Sending Email...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        // Send email
        fetch(`/leads/${transactionId}/send-email`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    try {
                        const data = JSON.parse(text);
                        throw new Error(data.message || 'Server error occurred');
                    } catch (e) {
                        throw new Error(text || 'Server error occurred');
                    }
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Email sent successfully',
                    timer: 1500,
                    showConfirmButton: false
                });
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('sendEmailModal'));
                if (modal) {
                    modal.hide();
                }
                
                // Reset form fields
                document.getElementById('emailRecipient').value = '';
                document.getElementById('emailSubject').value = '';
                document.getElementById('emailTemplateSelect').value = '';
                if (ckeditorEmailEditor) {
                    ckeditorEmailEditor.setData('');
                }
                
                // Reload the sent emails table
                reloadSentEmailsTable();
            } else {
                throw new Error(data.message || 'Failed to send email');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.message || 'Something went wrong. Please try again.'
            });
        });
    });

    // Handle form submission
    const leadForm = document.getElementById('leadForm');
    if (leadForm) {
        leadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            Swal.fire({
                title: 'Saving...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            // Get form data
            const formData = new FormData(this);
            
            // Convert FormData to JSON
            const jsonData = {};
            formData.forEach((value, key) => {
                // Handle arrays (like services)
                if (key.endsWith('[]')) {
                    const baseKey = key.slice(0, -2);
                    if (!jsonData[baseKey]) {
                        jsonData[baseKey] = [];
                    }
                    jsonData[baseKey].push(value);
                } else {
                    jsonData[key] = value;
                }
            });

            // Send AJAX request
            fetch(this.action, {
                method: this.method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(jsonData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Failed to save transaction');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'Transaction saved successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Redirect to edit page with the transaction ID
                        window.location.href = `/leads/${data.transaction.id}/edit`;
                    });
                } else {
                    throw new Error(data.message || 'Failed to save transaction');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message || 'Something went wrong. Please try again.'
                });
            });
        });
    }
});
</script>

<!-- Send Email Modal -->
<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="sendEmailModalLabel"><i class="fas fa-envelope me-2"></i>Send Email</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendEmailForm">
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
                                        <textarea id="quillEmailEditor" name="message" style="height: 250px;"></textarea>
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

<!-- Add Service Modal -->
<div class="modal fade" id="serviceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="serviceModalLabel">
    <div class="modal-dialog modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="serviceModalLabel">
                    <i class="fas fa-cogs me-2"></i>Select Service
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Distance Information -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title text-muted mb-3">Distance Information</h6>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label">Added Miles</label>
                                        <input id="added-miles" name="added-miles" class="form-control" type="text" placeholder="0" disabled/>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Added Mile Rate</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input id="added-mile-rate" name="added-mile-rate" class="form-control" type="text" placeholder="0.00" disabled/>
                                        </div>
                                    </div>
                                </div>

                                   <!-- Service Actions -->
                <div class="row mb-4 mt-4">
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-primary btn-services" id="add-college-room-move">
                                <i class="fas fa-graduation-cap me-2"></i>COLLEGE ROOM MOVE
                            </button>
                            <button class="btn btn-primary btn-services" id="add-removal">
                                <i class="fas fa-trash me-2"></i>REMOVAL
                            </button>
                            <button class="btn btn-primary btn-services" id="add-delivery">
                                <i class="fas fa-truck me-2"></i>DELIVERY
                            </button>
                            <button class="btn btn-primary btn-services" id="add-hoisting">
                                <i class="fas fa-crane me-2"></i>HOISTING
                            </button>
                            <button class="btn btn-primary btn-services" id="add-mattress-removal">
                                <i class="fas fa-bed me-2"></i>MATTRESS REMOVAL
                            </button>
                            <button class="btn btn-primary btn-services" id="add-re-arranging-service">
                                <i class="fas fa-sort me-2"></i>RE ARRANGING SERVICE
                            </button>
                            <button class="btn btn-primary btn-services" id="add-cleaning-services">
                                <i class="fas fa-broom me-2"></i>CLEANING SERVICES
                            </button>
                            <button class="btn btn-primary btn-services" id="add-exterminator-washing-replacing-moving-blankets">
                                <i class="fas fa-bug me-2"></i>Exterminator, Washing and Replacing Moving Blankets
                            </button>
                            <button class="btn btn-primary btn-services" id="add-moving-services">
                                <i class="fas fa-boxes me-2"></i>MOVING SERVICES
                            </button>
                        </div>
                    </div>
                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" style="display:none;">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title text-muted mb-3">Service Details</h6>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Service Type</label>
                                        <select id="service-select" class="form-control">
                                            <option value="">Select Here</option>
                                            <option value="college-room-move" data-rate="325">COLLEGE ROOM MOVE</option>
                                            <option value="removal" data-rate="125">REMOVAL</option>
                                            <option value="delivery" data-rate="0">DELIVERY</option>
                                            <option value="hoisting" data-rate="350">HOISTING</option>
                                            <option value="mattress-removal" data-rate="125">MATTRESS REMOVAL</option>
                                            <option value="re-arranging-service" data-rate="150">RE ARRANGING SERVICE</option>
                                            <option value="cleaning-services" data-rate="135">CLEANING SERVICES</option>
                                            <option value="exterminator-washing-replacing-moving-blankets" data-rate="650">Exterminator, Washing and Replacing Moving Blankets</option>
                                            <option value="moving-services" data-rate="0">MOVING SERVICES</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Service Rate</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input id="service-rate" class="form-control" type="text" placeholder="0.00" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Crew Rate</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input id="crew-rate" class="form-control" type="text" value="50.00" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Number of Crew</label>
                                        <input id="no-of-crew" class="form-control" type="number" placeholder="Enter number of crew" min="1"/>
                                    </div>

						    <button class="btn btn-primary mt-3" id="add-service-inner"  style="display:block;">Add Service</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

             

                <!-- Services Table -->
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title text-muted mb-3">Selected Services</h6>
                        <div class="table-responsive">
                            <table class="table table-hover" id="services-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Service</th>
                                        <th>Rate</th>
                                        <th>Crew</th>
                                        <th>Crew Rate</th>
                                        <th class="text-danger">Purchased Amount</th>
                                        <th>Delivery Cost</th>
                                        <th>Subtotal</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($transaction) && $transaction->services)
                                        @php
                                            $services = is_string($transaction->services) ? json_decode($transaction->services, true) : $transaction->services;
                                        @endphp
                                        @foreach($services as $service)
                                            <tr>
                                                <td style="vertical-align: middle;">{{ $service['name'] }}</td>
                                                <td style="vertical-align: middle;">{{ $service['rate'] }}</td>
                                                <td style="vertical-align: middle;">{{ $service['no_of_crew'] }}</td>
                                                <td style="vertical-align: middle;">{{ $service['crew_rate'] }}</td>
                                                <td style="vertical-align: middle;">
                                                    <input style="color:red;" type="number" class="form-control purchased-amount" 
                                                        value="{{ $service['purchased_amount'] ?? '' }}" 
                                                        {{ $service['name'] === 'DELIVERY' || $service['name'] === 'REMOVAL AND DELIVERY' ? '' : 'disabled' }} 
                                                        placeholder="{{ $service['name'] === 'DELIVERY' || $service['name'] === 'REMOVAL AND DELIVERY' ? 'Enter Amount' : 'Not Applicable' }}" />
                                                </td>
                                                <td style="vertical-align: middle;">{{ $service['delivery_cost'] }}</td>
                                                <td style="vertical-align: middle;">{{ $service['subtotal'] }}</td>
                                                <td style="vertical-align: middle;">
                                                    <button class="btn btn-danger btn-sm delete-row" style="padding: 5px 16px !important;font-size: 14px !important;">Delete</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="6" class="text-end"><strong>Grand Total:</strong></td>
                                        <td colspan="2" id="total-amount" class="fw-bold">
                                            @if(isset($transaction) && $transaction->services)
                                                @php
                                                    $total = 0;
                                                    foreach($services as $service) {
                                                        $subtotal = floatval(str_replace(['$', ','], '', $service['subtotal']));
                                                        $total += $subtotal;
                                                    }
                                                @endphp
                                                ${{ number_format($total, 2) }}
                                            @else
                                                $0.00
                                            @endif
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Summary Section -->
                <div class="row mt-4" style="display:none;">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title text-muted mb-3">Payment Summary</h6>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Grand Total</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input id="grand-total-service" name="grand-total-service" class="form-control" type="text" placeholder="0.00" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Down Payment</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input id="downpayment-service" name="downpayment-service" class="form-control" type="text" placeholder="0.00" disabled/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelServiceBtn">Cancel</button>
                <button type="button" class="btn btn-primary" id="add-service" data-bs-dismiss="modal">Add Service</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="customModal_moving_services" tabindex="-1" aria-labelledby="movingServicesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="movingServicesModalLabel">
                    <i class="fas fa-truck-moving me-2"></i>Moving Services
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Note:</strong> 3 hours minimum (2 hours labor, 1 hour travel)
                </div>
                
                <div class="row g-4">
                    <!-- Crew Selection -->
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Select Crew Size</h6>
							 </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-primary btn-moving" id="add-2men">
                                        <i class="fas fa-users me-2"></i>2 MEN CREW
                                    </button>
                                    <button class="btn btn-outline-primary btn-moving" id="add-3men">
                                        <i class="fas fa-users me-2"></i>3 MEN CREW
                                    </button>
                                    <button class="btn btn-outline-primary btn-moving" id="add-4men">
                                        <i class="fas fa-users me-2"></i>4 MEN CREW
                                    </button>
                                    <button class="btn btn-outline-primary btn-moving" id="add-5men">
                                        <i class="fas fa-users me-2"></i>5 MEN CREW
                                    </button>
                                    <button class="btn btn-outline-primary btn-moving" id="add-3menspecial">
                                        <i class="fas fa-star me-2"></i>3 MEN SPECIAL
                                    </button>
						 </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service Details -->
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Service Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Additional Hours</label>
        									<div class="input-group">
                                        <button type="button" class="btn btn-outline-secondary" id="decrease-hours">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                                <input id="added-hours" name="added-hours" 
                                            class="form-control text-center" 
                                                    type="number" 
                                                    placeholder="Additional Hours" 
                                                    min="2" step="1" value="2">
                                        <button type="button" class="btn btn-outline-secondary" id="increase-hours">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                            </div>
        							</div>

                                <div class="mb-3">
                                    <label class="form-label">Number of Crew</label>
                                    <input id="numbercrew" name="numbercrew" 
                                        class="form-control" 
                                        type="number" 
                                        placeholder="Number of Crew" 
                                        readonly>
							  </div>

                                <div class="mb-3">
                                    <label class="form-label">Total Cost</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input id="total-hours-added" name="total-hours-added" 
                                            class="form-control" 
                                            type="text" 
                                            placeholder="Total Cost" 
                                            disabled>
							  </div>
                                </div>

                                <input id="men-selected" name="men-selected" 
                                    class="form-control" 
                                    type="text" 
                                    placeholder="men-selected" 
                                    disabled 
                                    style="display:none;">

                                <button class="btn btn-primary w-100 mt-3" id="add-sevice-moving">
                                    <i class="fas fa-plus-circle me-2"></i>Add Service
                                </button>
                            </div>
                        </div>
							  </div>
						  </div>
						 </div>
					</div>
  </div>
</div>

<style>
/* Modern Modal Styles */
#customModal_moving_services .modal-content {
    border-radius: 1rem;
    overflow: hidden;
}

#customModal_moving_services .modal-header {
    border-bottom: none;
    padding: 1.5rem;
}

#customModal_moving_services .modal-body {
    padding: 1.5rem;
}

#customModal_moving_services .card {
    border-radius: 0.75rem;
    transition: transform 0.2s ease-in-out;
}

#customModal_moving_services .card:hover {
    transform: translateY(-2px);
}

#customModal_moving_services .btn-moving {
    padding: 0.75rem 1rem;
    font-weight: 500;
    border-radius: 0.5rem;
    transition: all 0.2s ease-in-out;
}

#customModal_moving_services .btn-moving:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

#customModal_moving_services .form-control {
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    border: 1px solid #e0e0e0;
}

#customModal_moving_services .form-control:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.15);
}

#customModal_moving_services .input-group-text {
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
}

#customModal_moving_services .alert {
    border-radius: 0.75rem;
    border: none;
    background-color: rgba(13,110,253,0.1);
    color: var(--bs-primary);
}

#customModal_moving_services .btn-primary {
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    border-radius: 0.5rem;
    background: linear-gradient(45deg, var(--bs-primary), #0d6efd);
    border: none;
    transition: all 0.2s ease-in-out;
}

#customModal_moving_services .btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(13,110,253,0.2);
}
</style>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const noOfItemsInput = document.getElementById('no_of_items');
    const uploadBtn = document.getElementById('uploadBtn');
    const imageUpload = document.getElementById('image_upload');
    const imagePreview = document.getElementById('imagePreview');
    const uploadStatus = document.getElementById('uploadStatus');

    // Show/hide upload section based on number of items
    noOfItemsInput.addEventListener('input', function() {
        const itemsValue = parseInt(this.value, 10);
        if (itemsValue > 0) {
            uploadBtn.style.display = 'inline-block';
            uploadStatus.textContent = `Please upload up to ${itemsValue} image(s)`;
        } else {
            uploadBtn.style.display = 'none';
            imagePreview.innerHTML = '';
            uploadStatus.textContent = '';
        }
    });

    // Handle file selection
    imageUpload.addEventListener('change', function(e) {
        const itemsValue = parseInt(noOfItemsInput.value, 10);
        const files = e.target.files;
        const fileCount = files.length;

        if (fileCount > itemsValue) {
            Swal.fire({
                title: 'Too Many Files',
                text: `You can only upload up to ${itemsValue} image(s)`,
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            this.value = '';
            imagePreview.innerHTML = '';
            uploadStatus.textContent = '';
            return;
        }

        // Clear previous previews
        imagePreview.innerHTML = '';

        // Show previews
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'position-relative';
                    previewDiv.style.width = '100px';
                    previewDiv.style.height = '100px';
                    previewDiv.style.margin = '5px';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail';
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';

                    const removeBtn = document.createElement('button');
                    removeBtn.innerHTML = '×';
                    removeBtn.className = 'btn btn-danger btn-sm position-absolute';
                    removeBtn.style.top = '-10px';
                    removeBtn.style.right = '-10px';
                    removeBtn.style.borderRadius = '50%';
                    removeBtn.style.width = '25px';
                    removeBtn.style.height = '25px';
                    removeBtn.style.padding = '0';
                    removeBtn.style.lineHeight = '1';
                    removeBtn.onclick = function() {
                        previewDiv.remove();
                        updateFileList();
                    };

                    previewDiv.appendChild(img);
                    previewDiv.appendChild(removeBtn);
                    imagePreview.appendChild(previewDiv);
                };
                reader.readAsDataURL(file);
            }
        });

        uploadStatus.textContent = `${fileCount} image(s) selected`;
    });

    // Function to update the file list after removal
    function updateFileList() {
        const itemsValue = parseInt(noOfItemsInput.value, 10);
        const remainingImages = imagePreview.children.length;
        uploadStatus.textContent = `${remainingImages} image(s) selected`;
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const selectServiceBtn = document.getElementById('selectServiceBtn');
    const serviceModal = document.getElementById('serviceModal');
    
    if (selectServiceBtn) {
        selectServiceBtn.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default button behavior
            
            // Get the required field values
            const moveDate = document.querySelector('input[name="date"]').value;
            const noOfItems = document.querySelector('input[name="no_of_items"]').value;
            const pickupLocation = document.querySelector('input[name="pickup_location"]').value;
            const deliveryLocation = document.querySelector('input[name="delivery_location"]').value;
            
            // Check if any required field is empty
            if (!moveDate || !noOfItems || !pickupLocation || !deliveryLocation) {
                // Show error message
                Swal.fire({
                    icon: 'warning',
                    title: 'Required Fields',
                    html: 'Please fill in the following fields before selecting a service:<br><br>' +
                          (!moveDate ? '• Move Date<br>' : '') +
                          (!noOfItems ? '• No. of Moving or Delivery or Removal<br>' : '') +
                          (!pickupLocation ? '• Pick-up Address<br>' : '') +
                          (!deliveryLocation ? '• Drop Off Address<br>' : ''),
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                });
                return; // Stop execution if validation fails
            }
            
            // All required fields are filled, manually show the modal
            const modal = new bootstrap.Modal(serviceModal);
            modal.show();
        });
    }
});
</script>


<script>


document.getElementById("increase-hours").addEventListener("click", function() {
    let input = document.getElementById("added-hours");
    input.stepUp();  // Increases by 1
    input.dispatchEvent(new Event('change')); // Manually trigger onchange
});

document.getElementById("decrease-hours").addEventListener("click", function() {
    let input = document.getElementById("added-hours");
    if (input.value > input.min) {
        input.stepDown();  // Decreases by 1
        input.dispatchEvent(new Event('change')); // Manually trigger onchange
    }
});

// Ensure minimum value enforcement on manual input
document.getElementById('added-hours').addEventListener('change', function() {
    let value = parseInt(this.value);
    if (isNaN(value) || value < 2) {
        this.value = 2;  // Reset to min value if invalid
    }
});


    const serviceSelect = document.getElementById("service-select");
 const addServiceButton = document.getElementById("add-service");
 const addServiceButtonInner = document.getElementById("add-service-inner");

	// Attach a single click event listener for all buttons with the class 'btn-services'
	// document.querySelectorAll(".btn-services").forEach((button) => {
	//   button.addEventListener("click", (event) => {
	// 	  event.preventDefault();
	// 		const serviceSelect = document.getElementById("service-select");
	// 		const addServiceButton = document.getElementById("add-service");
			
	// 		// Check if required elements exist
	// 		if (!serviceSelect || !addServiceButton) {
	// 			console.warn('Required elements not found');
	// 			return;
	// 		}

	// 	// Check if the button is NOT the "add-moving-services" button
	// 	if (button.id !== "add-moving-services") {
	// 	  // Extract the service value from the button's id
	// 	  const serviceValue = button.id.replace("add-", ""); // Remove "add-" prefix to match the option value

	// 	  // Set the dropdown value to match the service
	// 	  serviceSelect.value = serviceValue;

	// 	  // Dispatch the change event to trigger any related listeners
	// 	  const event = new Event("change");
	// 	  serviceSelect.dispatchEvent(event);

	// 	  // Simulate clicking the "Add Service" button
	// 	  addServiceButton.click();
	// 		} else if (button.id === "add-sevice-moving") {
	// 			// Handle moving services case
	// 	} else {
	// 	  // Handle the specific case for "add-moving-services" button
	// 			const customModal = document.getElementById("customModal_moving_services");
	// 			if (customModal) {
	// 				customModal.style.display = "flex";
	// 			}
	// 	}
	//   });
	// });



document.addEventListener("DOMContentLoaded", () => {
    const serviceSelect = document.getElementById("service-select");
    const serviceRate = document.getElementById("service-rate");
    const noOfCrew = document.getElementById("no-of-crew");
    const noOfItems = document.getElementById("no_of_items");
    const crewRate = document.getElementById("crew-rate");
    const addServiceBtn = document.getElementById("add-service");
    const servicesTableBody = document.querySelector("#services-table tbody");
    const totalAmountCell = document.getElementById("total-amount");

    let totalAmount = 0;

     if (serviceSelect && serviceRate) {
    serviceSelect.addEventListener("change", () => {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        
        // Check if the selected option exists and has the "data-rate" attribute
        const rate = selectedOption && selectedOption.hasAttribute("data-rate") 
            ? selectedOption.getAttribute("data-rate") 
            : 0;

        serviceRate.value = rate;
    });
}

    // Function to calculate equivalent delivery cost
    function calculateDeliveryCost(subTotal) {
        if (subTotal < 750) return 79.99;
        if (subTotal >= 750 && subTotal < 1500) return 159.99;
        if (subTotal >= 1500 && subTotal < 2000) return 179.99;
        if (subTotal >= 2000 && subTotal < 2750) return 194.99;
        if (subTotal >= 2750 && subTotal < 4000) return 224.99;
        if (subTotal >= 4000 && subTotal < 6000) return 284.99;
        if (subTotal >= 6000 && subTotal < 8000) return 314.99;
        if (subTotal >= 8000 && subTotal < 10000) return 334.99;
        if (subTotal >= 10000) return 384.99;
        return 0;
    }

 // Function to recalculate total from all subtotals in the table
    function recalculateTotal() {		
		
		let containsMovingServices = false; 
		
    const addedMileRate = parseFloat(document.getElementById("added-mile-rate").value) || 0;
        let total = 0;
        servicesTableBody.querySelectorAll("tr").forEach((row) => {
            const subtotalCell = row.cells[6]; // Subtotal is in the 6th column (index 5)
            const subtotal = parseFloat(subtotalCell.textContent.replace("$", "")) || 0;
            total += subtotal;
			
		 // Check if the row contains "MOVING SERVICES"
        const serviceName = row.cells[0].textContent.trim(); // Assuming service name is in the first column
        if (serviceName === "MOVING SERVICES") {
            containsMovingServices = true;
        }
        
		});
		
		
		 // If "MOVING SERVICES" is found, apply transaction fee and truck fee
			if (containsMovingServices) {
				const truckFee = 198.80; // Fixed Truck Fee
		//		const transactionFee = (total + 198.80) * 0.12; // 12% of Grand Total
		//		total += transactionFee + truckFee; // Add both fees to Grand Total
			}
		else{
			//total += addedMileRate;
		}
		
        totalAmountCell.textContent = `$${total.toFixed(2)}`;
    }
	 
    // Function to handle adding a service
    function addServiceHandler(event) {
  
    event.preventDefault(); // Prevent page refresh
  
  const serviceSelect = document.getElementById("service-select");
        const serviceName = serviceSelect.options[serviceSelect.selectedIndex].text;
        const serviceValue = serviceSelect.value;
        const rate = parseFloat(serviceRate.value) || 0;
        const noOfItems = document.getElementById("no_of_items");
        const noofitems = noOfItems ? parseFloat(noOfItems.value) || 0 : 0;
        const crewCount = parseInt(noOfCrew.value) || 0;
        const ratePerCrew = parseFloat(crewRate.value) || 0;
        const addedMileRate = parseFloat(document.getElementById("added-mile-rate").value) || 0;

        let removaladd = 0;
        let subtotal = 0;


    // Validation: Check if required fields are filled
    if (serviceSelect.selectedIndex === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Service Required',
            text: 'Please select a service',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6',
        });
        return; // Stop execution if validation fails
    }


        const isEditable =
            serviceValue === "delivery" || serviceValue === "removal-and-delivery";
        const purchasedAmountInput = isEditable
            ? `<input style="color:red;" type="number" class="form-control purchased-amount" placeholder="Enter Amount" />`
            : `<input style="color:red;" type="number" class="form-control purchased-amount" placeholder="Not Applicable" disabled />`;
		
        const deliveryCost = calculateDeliveryCost(purchasedAmountInput.value);
//         const subtotal = rate +  ratePerCrew + deliveryCost + removaladd;

		if(serviceValue === "removal"){
			if(noofitems < 4){
				removaladd = (noofitems - 1 ) * 75;
        		subtotal =  rate +  ratePerCrew + deliveryCost + removaladd;
			}
			else{
				removaladd = (noofitems ) * 75;
        		subtotal = ratePerCrew + deliveryCost + removaladd;
			}
		}
		else{
        		subtotal =  rate +  ratePerCrew + deliveryCost;
		}


        totalAmount += subtotal;

        // Add row to table
        const row = document.createElement("tr");
        row.innerHTML = `
            <td style="vertical-align: middle;">${serviceName}</td>
            <td style="vertical-align: middle;">$${rate.toFixed(2)}</td>
            <td style="vertical-align: middle;">${crewCount}</td>
            <td style="vertical-align: middle;">$${ratePerCrew.toFixed(2)}</td>
            <td style="vertical-align: middle;">${purchasedAmountInput}</td>
            <td style="vertical-align: middle;">$${deliveryCost.toFixed(2)}</td>
            <td style="vertical-align: middle;">$${subtotal.toFixed(2)}</td>
            <td style="vertical-align: middle;"><button class="btn btn-danger btn-sm delete-row" style="padding: 5px 16px !important;font-size: 14px !important;">Delete</button></td>
        `;
        servicesTableBody.appendChild(row);

       recalculateTotal();
		updateDeliveryRate();

        // Reset form fields
        serviceSelect.value = "";
        serviceRate.value = "";
        noOfCrew.value = "";
        
        // Update financial data fields
        updateFinancialData();
        
        // Close the modal - try multiple approaches to ensure it closes
        // try {
        //     // Try using Bootstrap's Modal.getInstance method
        //     const modalInstance = bootstrap.Modal.getInstance(document.getElementById('serviceModal'));
        //     if (modalInstance) {
        //         modalInstance.hide();
        //     } else {
        //         // If that fails, try jQuery method
        //         $('#serviceModal').modal('hide');
        //     }
            
        //     // As a fallback, also try to remove the modal backdrop and reset body classes
        //     const backdrop = document.querySelector('.modal-backdrop');
        //     if (backdrop) {
        //         backdrop.remove();
        //     }
        //     document.body.classList.remove('modal-open');
        //     document.body.style.overflow = '';
        //     document.body.style.paddingRight = '';
        // } catch (e) {
        //     console.error('Error closing modal:', e);
        // }
  
    }
    
    // Add event listeners to both Add Service buttons
    addServiceBtn.addEventListener("click", updateFinancialData);
    if (addServiceButtonInner) {
        addServiceButtonInner.addEventListener("click", addServiceHandler);
    }
    
    // Function to update all financial data fields
    function updateFinancialData() {
        const totalAmount = parseFloat(totalAmountCell.textContent.replace("$", "")) || 0;
        const addedMileRate = parseFloat(document.getElementById("added-mile-rate").value) || 0;
        
        // Check if we have moving services
        let containsMovingServices = false;
        servicesTableBody.querySelectorAll("tr").forEach((row) => {
            const serviceName = row.cells[0].textContent.trim();
            if (serviceName === "MOVING SERVICES") {
                containsMovingServices = true;
            }
        });
        
        // Calculate fees
        const softwareFee = totalAmount * 0.05; // 5% software management fee
        const truckFee = containsMovingServices ? 198.80 : 0;
        const grandTotal = totalAmount + softwareFee + truckFee;
        const downPayment = containsMovingServices ? grandTotal * 0.3 : 0; // 30% down payment for moving services
        const remainingBalance = grandTotal;
        
        // Update the fields
        document.querySelector('input[name="subtotal"]').value = totalAmount.toFixed(2);
        document.querySelector('input[name="added_mile_rate"]').value = addedMileRate.toFixed(2);
        document.querySelector('input[name="software_fee"]').value = softwareFee.toFixed(2);
        document.querySelector('input[name="truck_fee"]').value = truckFee.toFixed(2);
        document.querySelector('input[name="grand_total"]').value = grandTotal.toFixed(2);
        document.querySelector('input[name="downpayment"]').value = downPayment.toFixed(2);
        document.querySelector('input[name="remaining_balance"]').value = remainingBalance.toFixed(2);
    }

    // Delete a row from the table
    servicesTableBody.addEventListener("click", (e) => {
        if (e.target.classList.contains("delete-row")) {
            const row = e.target.closest("tr");
            row.remove();
            
            recalculateTotal();
            updateDeliveryRate();
            updateFinancialData();
        }
    });

    // Update total and equivalent delivery cost when "Purchased Amount" is edited
    servicesTableBody.addEventListener("input", (e) => {
        if (e.target.classList.contains("purchased-amount")) {
            const row = e.target.closest("tr");
            const purchasedAmount = parseFloat(e.target.value) || 0;
            const rate = parseFloat(row.cells[1].textContent.replace("$", "")) || 0;
            const crewCount = parseInt(row.cells[2].textContent) || 0;
            const crewRate = parseFloat(row.cells[3].textContent.replace("$", "")) || 0;

            const newDeliveryCost = calculateDeliveryCost(purchasedAmount);
            const newSubtotal = rate +  crewRate + newDeliveryCost;

            // Update subtotal and delivery cost
            row.cells[6].textContent = `$${newSubtotal.toFixed(2)}`;
            row.cells[5].textContent = `$${newDeliveryCost.toFixed(2)}`;

            // Recalculate total
            recalculateTotal();
   updateDeliveryRate();
            updateFinancialData();
        }
    });
});
    </script>
<script>
  


// // Create a mapping of services to their corresponding rates
const serviceRates = {
    "removal-and-delivery": 75.00,
    "college-room-move": 325.00,
    "removal": 125.00,
    "delivery": 0.00,
    "hoisting": 350.00,
    "mattress-removal": 125.00,
    "re-arranging-service": 150.00,
    "cleaning-services": 135.00,
    "exterminator-washing-replacing-moving-blankets": 650.00,
    "moving-services": 650.00
};

const serviceCrewCount = {
    "removal-and-delivery": 2,
    "college-room-move": 2,
    "removal": 2,
    "delivery": 2,
    "hoisting": 4,
    "mattress-removal": 2,
    "re-arranging-service": 2,
    "cleaning-services": 2,
    "exterminator-washing-replacing-moving-blankets": 2,
    "moving-services": 2
};


// // Function to update Delivery Rate based on Sub Total
function updateDeliveryRate() {
    const addedMileRate = parseFloat(document.getElementById("added-mile-rate")?.value) || 0;
    const servicesTableBody = document.querySelector("#services-table tbody");
    let tableSubTotal = 0;
    let hasDeliveryService = false;

    // Check if services table exists
    if (!servicesTableBody) {
        console.warn('Services table body not found');
        return;
    }

    // Loop through all rows in the table
    servicesTableBody.querySelectorAll("tr").forEach((row) => {
        const serviceCell = row.cells[0]; // Service column (1st column, index 0)
        const subtotalCell = row.cells[6]; // Subtotal column (7th column, index 6)
        const subtotal = parseFloat(subtotalCell.textContent.replace("$", "")) || 0;

        // Check if the service matches "delivery" or "removal-and-delivery"
        if (serviceCell.textContent.trim() === "DELIVERY" || serviceCell.textContent.trim() === "REMOVAL AND DELIVERY") {
            hasDeliveryService = true;
        }

        // Add subtotal to the total
        tableSubTotal += subtotal;
    });

    // Add the added-mile-rate to the subtotal
    const subTotal = tableSubTotal + addedMileRate;

    // Update delivery rate if element exists
    const deliveryRateElement = document.getElementById("delivery-rate");
    if (deliveryRateElement) {
        deliveryRateElement.value = hasDeliveryService ? "0.00" : "0.00";
    }
    
    updateGrandTotal();
}

function updateGrandTotal() {
    const addedMileRate = parseFloat(document.getElementById("added-mile-rate")?.value) || 0;
    const servicesTableBody = document.querySelector("#services-table tbody");
    let tableSubTotal = 0;
    let containsMovingServices = false; 

    // Loop through all rows in the table to calculate the subtotal
    servicesTableBody.querySelectorAll("tr").forEach((row) => {
        const subtotalCell = row.cells[6]; // Subtotal column (7th column, index 6)
        const subtotal = parseFloat(subtotalCell.textContent.replace("$", "")) || 0;
        tableSubTotal += subtotal;
        
        // Check if the row contains "MOVING SERVICES"
        const serviceName = row.cells[0].textContent.trim(); // Assuming service name is in the first column
        if (serviceName === "MOVING SERVICES") {
            containsMovingServices = true;
        }
    });

    // If "MOVING SERVICES" is found, apply transaction fee and truck fee
    if (containsMovingServices) {
        const truckFee = 198.80; // Fixed Truck Fee
        const transactionFee = (tableSubTotal + truckFee) * 0.12; // 12% of Grand Total
        tableSubTotal += transactionFee + truckFee; // Add both fees to Grand Total
    } else {
        tableSubTotal += addedMileRate;
    }

    
    document.getElementById("grand-total-service").value = tableSubTotal.toFixed(2);
    updatePaymentSummary(tableSubTotal);
}

function updatePaymentSummary(total) {
    // Update grand total
    const grandTotalElement = document.getElementById("grand-total-service");
    if (grandTotalElement) {
        grandTotalElement.value = total.toFixed(2);
    }

    // Check if moving services are present
    const servicesTableBody = document.querySelector("#services-table tbody");
    let containsMovingServices = false;
    
        servicesTableBody.querySelectorAll("tr").forEach((row) => {
            const serviceName = row.cells[0].textContent.trim();
            if (serviceName === "MOVING SERVICES") {
                containsMovingServices = true;
            }
        });

    // Update downpayment (30% of total) only if moving services are present
    const downpaymentElement = document.getElementById("downpayment-service");
    if (downpaymentElement) {
        if (containsMovingServices) {
            downpaymentElement.value = (total * 0.3).toFixed(2);
        } else {
            downpaymentElement.value = "0.00";
        }
    }
}

// Add event listener for changes in the Service selection
document.getElementById("service-select").addEventListener("change", function() {

    const selectedService = this.value;
    const serviceRateInput = document.getElementById("service-rate");
    const crewCountInput = document.getElementById("no-of-crew");
    const crewRateInput = document.getElementById("crew-rate");
    const items = document.getElementById("no_of_items");
    const itemsValue = items ? parseInt(items.value) || 0 : 0;

    // If a valid service is selected, update the rate and number of crew
    if (serviceRates[selectedService]) {
        crewCountInput.value = serviceCrewCount[selectedService];
		
 		 if (selectedService !== "college-room-move" && selectedService !== "removal" && selectedService !== "removal-and-delivery" && selectedService !== "mattress-removal"  && selectedService !== "cleaning-services"  && selectedService !== "exterminator-washing-replacing-moving-blankets") {
       		 crewRateInput.value = (serviceCrewCount[selectedService] * 48.75).toFixed(2); // Crew rate based on number of crew
        	 serviceRateInput.value = serviceRates[selectedService].toFixed(2); 
		 }		
		else{
			if(itemsValue > 1){
				const servicetotal = serviceRates[selectedService] + (itemsValue * 75) 
				serviceRateInput.value = servicetotal.toFixed(2);
			}
			else{
				serviceRateInput.value = serviceRates[selectedService].toFixed(2) ; 
			}
			crewRateInput.value = 0;
            }
        } else {
        serviceRateInput.value = "";
        crewCountInput.value = "";
        crewRateInput.value = "";
    }

    // Recalculate Sub Total, Delivery Rate, and Grand Total
//     updateSubTotal();
     updateDeliveryRate();
     updateGrandTotal();
});

// Add event listener for changes in the Number of Crew input
document.getElementById("no-of-crew").addEventListener("input", function() {
    const selectedService = document.getElementById("service-select").value;
    const crewCount = parseInt(this.value) || 0;
    const crewRateInput = document.getElementById("crew-rate");

    // Calculate the crew rate (number of crew * 48.75)
    if (serviceCrewCount[selectedService]) {
        const crewRate = crewCount * 48.75;
        crewRateInput.value = crewRate.toFixed(2);
    }

    // Recalculate Sub Total, Delivery Rate, and Grand Total
//     updateSubTotal();
    updateDeliveryRate();
    updateGrandTotal();
});
	
	
 // Define base costs for each crew size
  const crewCosts = {
    '2men': 175,
    '3men': 201.50,
    '4men': 253.50,
    '5men': 316.88,
    '3menspecial': 125
  };
	
	  const crewNumber = {
    '2men': 2,
    '3men': 3,
    '4men': 4,
    '5men': 5,
    '3menspecial': 3
  };

  // Reference to the total hours added field
  const totalHoursField = document.getElementById('total-hours-added');
  const numbercrew = document.getElementById('numbercrew');

  // Track selected base cost
  let baseCost = 0;

  // Attach event listeners to crew buttons
  document.querySelectorAll('.btn-moving').forEach(buttons => {
    buttons.addEventListener('click', () => {
      // Get the crew size from the button ID (e.g., '2men', '3men', etc.)
      const crewSize = buttons.id.replace('add-', '');
	  totalHoursField.value=0;
	  
	  
	   document.getElementById('added-hours').value = '2';
	   
	   document.getElementById('men-selected').value = crewSize;
	   
		if(crewSize == "3menspecial")
		{
		    numbercrew.value=crewNumber[crewSize];
            baseCost = crewCosts[crewSize];
            // Update total cost display
            totalHoursField.value = (baseCost * document.getElementById('added-hours').value).toFixed(2); // Show the base cost in the total cost field
		}
		else{
		    
      // Update the base cost based on selected crew size
      if (crewCosts[crewSize]) {		  
		numbercrew.value=crewNumber[crewSize];
        baseCost = crewCosts[crewSize];
        // Update total cost display
        totalHoursField.value = (baseCost * crewNumber[crewSize]).toFixed(2); // Show the base cost in the total cost field
      }
		}
    });
  });

  // Attach event listener to added hours input
  document.getElementById('added-hours').addEventListener('change', (e) => {
    // Get the number of additional hours
		let addedHours = parseInt(e.target.value, 10);

	  // If addedHours is NaN, less than 1, or negative, reset to 0
	  if (isNaN(addedHours) || addedHours < 2) {
		addedHours = 2;
		e.target.value = '2';  // Clear input if it's invalid, negative, or less than 1
	  }

	  // If a valid number of hours (whole number >= 1) is entered, calculate total cost
	  if (addedHours >= 2) {
  	    const totalCost = (baseCost * addedHours).toFixed(2);
		totalHoursField.value = totalCost; // Update the total cost field
	  } else {
		totalHoursField.value = baseCost.toFixed(2); // Show only the base cost if no valid hours are entered
	  }
  });
	
	
	
//   document.getElementById('add-sevice-moving').addEventListener('click', function (e) {
		
// 	const serviceSelect = document.getElementById("service-select");
	  
// 	  if (document.getElementById('numbercrew').value === '' || document.getElementById('numbercrew').value === '0') {
// 		Swal.fire({
// 			title: 'No Crew Selected',
// 			text: 'Please select the number of crew before proceeding.',
// 			icon: 'warning',
// 			confirmButtonText: 'OK',
// 		});

// 		return;
// 	}

// 		  // Set the dropdown value to match the service
// 		  serviceSelect.value = 'moving-services';

// 		  // Dispatch the change event to trigger any related listeners
// 		  const event = new Event("change");
// 		  serviceSelect.dispatchEvent(event);
	  
// 	  		document.getElementById('no-of-crew').value = document.getElementById('numbercrew').value;
// 	  		document.getElementById('crew-rate').value = document.getElementById('total-hours-added').value;
	  
// 		  // Simulate clicking the "Add Service" button
//  		  addServiceButton.click();	  
//   		  document.getElementById('customModal_moving_services').style.display = 'none';
// });


</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap modals
    const serviceModalElement = document.getElementById('serviceModal');
    const serviceModal = new bootstrap.Modal(serviceModalElement, {
        keyboard: false,
        backdrop: 'static'
    });

    // Initialize moving services modal with focus trap disabled
    const movingServicesModalElement = document.getElementById('customModal_moving_services');
    const movingServicesModal = new bootstrap.Modal(movingServicesModalElement, {
        keyboard: false,
        backdrop: 'static',
        focus: false // Disable focus trap
    });

    // Define base costs for each crew size
    const crewCosts = {
        '2men': 175,
        '3men': 201.50,
        '4men': 253.50,
        '5men': 316.88,
        '3menspecial': 125
    };
    
    
    const crewNumber = {
        '2men': 2,
        '3men': 3,
        '4men': 4,
        '5men': 5,
        '3menspecial': 3
    };

    // Track selected base cost
    let baseCost = 0;

    // Handle crew selection buttons
    document.querySelectorAll('.btn-moving').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default button behavior
            
            // Get the crew size from the button ID
            const crewSize = this.id.replace('add-', '');
            
            // Reset hours input
            document.getElementById('added-hours').value = '2';
            
            // Store selected crew
            document.getElementById('men-selected').value = crewSize;
            
            // Update crew number and cost
            if (crewSize === '3menspecial') {
                document.getElementById('numbercrew').value = crewNumber[crewSize];
                baseCost = crewCosts[crewSize];
                document.getElementById('total-hours-added').value = (baseCost * 2).toFixed(2);
            } else if (crewCosts[crewSize]) {
                document.getElementById('numbercrew').value = crewNumber[crewSize];
                baseCost = crewCosts[crewSize];
                document.getElementById('total-hours-added').value = (baseCost * crewNumber[crewSize]).toFixed(2);
            }
        });
    });

    // Handle hours input changes
    document.getElementById('added-hours').addEventListener('change', function(e) {
        let addedHours = parseInt(this.value, 10);
        
        // Enforce minimum value
        if (isNaN(addedHours) || addedHours < 2) {
            addedHours = 2;
            this.value = '2';
        }
        
        // Calculate total cost
        const totalCost = (baseCost * addedHours).toFixed(2);
        document.getElementById('total-hours-added').value = totalCost;
    });
    
    // Function to reset moving services modal state
    function resetMovingServicesModal() {
        document.getElementById('added-hours').value = '2';
        document.getElementById('numbercrew').value = '';
        document.getElementById('total-hours-added').value = '';
        document.getElementById('men-selected').value = '';
        baseCost = 0;
    }

    // Handle moving services modal cleanup
    movingServicesModalElement.addEventListener('hidden.bs.modal', function () {
        resetMovingServicesModal();
    });

    // Handle service button clicks
    document.querySelectorAll('.btn-services').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const serviceSelect = document.getElementById("service-select");
            const addServiceButton = document.getElementById("add-service-inner");    
            
            if (!serviceSelect || !addServiceButton) {
                console.warn('Required elements not found');
                return;
            }

            if (this.id === 'add-moving-services') {
                resetMovingServicesModal();
                movingServicesModal.show();
            } else {
                const serviceValue = this.id.replace("add-", "");
                serviceSelect.value = serviceValue;
                serviceSelect.dispatchEvent(new Event("change"));
                addServiceButton.click();
            }
        });
    });

    // Handle add service moving button
    document.getElementById('add-sevice-moving').addEventListener('click', function (e) {
        e.preventDefault();
        const serviceSelect = document.getElementById("service-select");
        const addServiceButton = document.getElementById("add-service-inner");    
        
        if (document.getElementById('numbercrew').value === '' || document.getElementById('numbercrew').value === '0') {
            Swal.fire({
                title: 'No Crew Selected',
                text: 'Please select the number of crew before proceeding.',
                icon: 'warning',
                confirmButtonText: 'OK',
            });
            return;
        }

        // Set the dropdown value to match the service
        serviceSelect.value = 'moving-services';
        serviceSelect.dispatchEvent(new Event("change"));
        
        document.getElementById('no-of-crew').value = document.getElementById('numbercrew').value;
        document.getElementById('crew-rate').value = document.getElementById('total-hours-added').value;
        
        
        // Close only the moving services modal
        const movingServicesModal = bootstrap.Modal.getInstance(document.getElementById('customModal_moving_services'));
        if (movingServicesModal) {
            movingServicesModal.hide();
        }
        
        // Reset the moving services modal state
        document.getElementById('added-hours').value = '2';
        document.getElementById('numbercrew').value = '';
        document.getElementById('total-hours-added').value = '';
        document.getElementById('men-selected').value = '';
        baseCost = 0;

        
        // Add the service
         addServiceButtonInner.click();
    });

    // ... rest of your existing code ...
});

// Add this new function for saving transactions
function saveTransactionData() {
    // Validate required fields
    const requiredFields = {
        'firstname': 'First Name',
        'lastname': 'Last Name',
        'email': 'Email',
        'phone': 'Phone Number',
        'lead_source': 'Lead Source',
        'lead_type': 'Lead Type',
        'assigned_agent': 'Assigned Agent',
        'no_of_items': 'No. of Moving or Delivery or Removal',
        'pickup_location': 'Pick-up Address',
        'delivery_location': 'Drop Off Address',
        'sales_name': 'Sales Reps Name',
        'sales_email': 'Sales Reps Email',
        'sales_location': 'Store Location'
    };

    // Check each required field
    const missingFields = [];
    for (const [fieldId, fieldName] of Object.entries(requiredFields)) {
        const value = $(`#${fieldId}`).val();
        if (!value || value.trim() === '') {
            missingFields.push(fieldName);
        }
    }

    // Check if any services are added in the table
    const servicesTable = $('#services-table tbody');
    if (!servicesTable || servicesTable.find('tr').length === 0) {
        missingFields.push('Service (Please add at least one service)');
    }

    // If there are missing fields, show error and return
    if (missingFields.length > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Required Fields Missing',
            html: `Please fill in the following required fields:<br><br>${missingFields.join('<br>')}`,
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
        });
        return;
    }

    // Validate email format
    const email = $('#email').val();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Email Format',
            text: 'Please enter a valid email address.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
        });
        return;
    }

    // Validate phone number format (basic validation)
    const phone = $('#phone').val();
    if (phone.length < 10) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Phone Number',
            text: 'Please enter a valid phone number (minimum 10 digits).',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
        });
        return;
    }

    // Validate move date is not in the past
    const moveDate = new Date($('input[name="date"]').val());
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    if (moveDate < today) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Move Date',
            text: 'Move date cannot be in the past.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
        });
        return;
    }

    // Validate number of items is positive
    const noOfItems = parseInt($('#no_of_items').val());
    if (isNaN(noOfItems) || noOfItems <= 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Number of Items',
            text: 'Please enter a valid number of items (must be greater than 0).',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
        });
        return;
    }

    // If all validations pass, proceed with saving
    // Format services array
    const services = [];
    $('#services-table tbody tr').each(function() {
        const row = $(this);
        const serviceName = row.find('td:first').text().trim();
        const rate = parseFloat(row.find('td:eq(1)').text().replace('$', '')) || 0;
        const noOfCrew = parseInt(row.find('td:eq(2)').text()) || 0;
        const crewRate = parseFloat(row.find('td:eq(3)').text().replace('$', '')) || 0;
        const purchasedAmount = row.find('.purchased-amount').val() || '';
        const deliveryCost = parseFloat(row.find('td:eq(5)').text().replace('$', '')) || 0;
        const subtotal = parseFloat(row.find('td:eq(6)').text().replace('$', '')) || 0;

        services.push({
            id: null,
            name: serviceName,
            rate: '$' + rate.toFixed(2),
            subtotal: '$' + subtotal.toFixed(2),
            crew_rate: '$' + crewRate.toFixed(2),
            no_of_crew: noOfCrew.toString(),
            delivery_cost: '$' + deliveryCost.toFixed(2),
            purchased_amount: purchasedAmount
        });
    });

    // Prepare form data
    const formData = new FormData();
    
    // Helper function to safely get form values
    const getFormValue = (selector, defaultValue = '') => {
        const value = $(selector).val();
        return value !== undefined && value !== null ? value : defaultValue;
    };

    // Helper function to safely get numeric values
    const getNumericValue = (selector, defaultValue = 0) => {
        const value = $(selector).val();
        return value !== undefined && value !== null && !isNaN(value) ? parseFloat(value) : defaultValue;
    };

    // Basic information
    formData.append('firstname', getFormValue('#firstname'));
    formData.append('lastname', getFormValue('#lastname'));
    formData.append('email', getFormValue('#email'));
    formData.append('phone', getFormValue('#phone'));
    formData.append('lead_source', getFormValue('#lead_source'));
    formData.append('lead_type', getFormValue('#lead_type', 'local'));
    formData.append('assigned_agent', getFormValue('#assigned_agent'));
    
    // Sales information
    formData.append('sales_name', getFormValue('#sales_name'));
    formData.append('sales_email', getFormValue('#sales_email'));
    formData.append('sales_location', getFormValue('#sales_location'));
    
    // Handle date properly
    const formattedDate = `${moveDate.toISOString().split('T')[0]} 00:00:00`;
        formData.append('date', formattedDate);
    
    // Location information
    formData.append('pickup_location', getFormValue('#pickup_location'));
    formData.append('delivery_location', getFormValue('#delivery_location'));
    
    // Numeric values
    formData.append('miles', getNumericValue('#miles'));
    formData.append('add_mile', getNumericValue('#add_miles', 0));
    formData.append('mile_rate', getNumericValue('#added-mile-rate', 0));
    formData.append('service_rate', getNumericValue('#service_rate'));
    formData.append('no_of_items', getNumericValue('#no_of_items'));
    formData.append('no_of_crew', getNumericValue('#no_of_crew'));
    formData.append('crew_rate', getNumericValue('#crew_rate'));
    formData.append('delivery_rate', getNumericValue('#delivery_rate'));
    formData.append('subtotal', getNumericValue('#subtotal'));
    formData.append('software_fee', getNumericValue('#software_fee'));
    formData.append('truck_fee', getNumericValue('#truck_fee'));
    formData.append('downpayment', getNumericValue('#downpayment'));
    formData.append('grand_total', getNumericValue('#grand_total'));
    
    // Service information
    formData.append('service', getFormValue('#service'));
    
    // Additional information
    formData.append('coupon_code', getFormValue('#coupon_code'));
    formData.append('payment_id', getFormValue('#payment_id'));
    formData.append('status', getFormValue('#status', 'pending'));
    
    // Handle uploaded images
    const imageUpload = document.getElementById('image_upload');
    const uploadedImages = [];
    
    if (imageUpload && imageUpload.files) {
        Array.from(imageUpload.files).forEach(file => {
            uploadedImages.push(file);
        });
    }
    
    // Add each file to formData
    uploadedImages.forEach((file, index) => {
        formData.append(`uploaded_image[${index}]`, file);
    });
    
    // Handle services array - ensure it's properly stringified
    if (services.length > 0) {
    formData.append('services', JSON.stringify(services));
    }

    // Show loading state
    $('#saveButton').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

    // Send AJAX request
    $.ajax({
        url: '/transaction/save',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
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

                // Redirect to edit mode
                if (response.transaction) {
                    window.location.href = `/leads/${response.transaction.id}/edit`;
                }
            } else {
                throw new Error(response.message || 'Failed to save transaction');
            }
        },
        error: function(xhr) {
            let errorMessage = 'Failed to save transaction';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: errorMessage
            });
        },
        complete: function() {
            // Reset button state
            $('#saveButton').prop('disabled', false).html('Save Transaction');
        }
    });
}
// ... existing code ...
</script>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Service selection handling
    const selectServiceBtn = document.getElementById('selectServiceBtn');
    const serviceInput = document.getElementById('service');
    const serviceDisplay = document.getElementById('service_display');
    const servicesTableBody = document.querySelector("#services-table tbody");
    
    // Debug logging
    console.log('Service Input Value:', serviceInput?.value);
    
    if (selectServiceBtn) {
        selectServiceBtn.addEventListener('click', function() {
            // Open the service selection modal
            const serviceModal = new bootstrap.Modal(document.getElementById('serviceModal'));
            serviceModal.show();
        });
    }

    // Function to update service display and populate services table
    window.updateServiceDisplay = function(serviceData) {
        console.log('Updating service display with data:', serviceData);
        
        if (serviceData && serviceData.name) {
            // Store the service data
            const currentServices = serviceInput.value ? JSON.parse(serviceInput.value) : [];
            if (!Array.isArray(currentServices)) {
                currentServices = [];
            }
            currentServices.push(serviceData);
            serviceInput.value = JSON.stringify(currentServices);
            
            // Update display field
            serviceDisplay.value = serviceData.name;
            
            // Add service to the table
            if (servicesTableBody) {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td style="vertical-align: middle;">${serviceData.name}</td>
                    <td style="vertical-align: middle;">$${parseFloat(serviceData.rate || 0).toFixed(2)}</td>
                    <td style="vertical-align: middle;">${serviceData.no_of_crew || 0}</td>
                    <td style="vertical-align: middle;">$${parseFloat(serviceData.crew_rate || 0).toFixed(2)}</td>
                    <td style="vertical-align: middle;">
                        <input style="color:red;" type="number" class="form-control purchased-amount" 
                            value="${serviceData.purchased_amount || ''}" 
                            ${serviceData.name === 'DELIVERY' || serviceData.name === 'REMOVAL AND DELIVERY' ? '' : 'disabled'} 
                            placeholder="${serviceData.name === 'DELIVERY' || serviceData.name === 'REMOVAL AND DELIVERY' ? 'Enter Amount' : 'Not Applicable'}" />
                    </td>
                    <td style="vertical-align: middle;">$${parseFloat(serviceData.delivery_cost || 0).toFixed(2)}</td>
                    <td style="vertical-align: middle;">$${parseFloat(serviceData.subtotal || 0).toFixed(2)}</td>
                    <td style="vertical-align: middle;">
                        <button class="btn btn-danger btn-sm delete-row" style="padding: 5px 16px !important;font-size: 14px !important;">Delete</button>
                    </td>
                `;
                servicesTableBody.appendChild(row);
                
                // Update total amount
                updateTotalAmount();
            }
        }
    };

    // Function to update total amount
    function updateTotalAmount() {
        let total = 0;
        servicesTableBody.querySelectorAll("tr").forEach((row) => {
            const subtotalCell = row.cells[6];
            const subtotal = parseFloat(subtotalCell.textContent.replace("$", "")) || 0;
            total += subtotal;
        });
        
        const totalAmountCell = document.getElementById("total-amount");
        if (totalAmountCell) {
            totalAmountCell.textContent = `$${total.toFixed(2)}`;
        }
    }

    // Initialize service display and table if there's existing data
    if (serviceInput && serviceInput.value) {
        try {
            console.log('Parsing service data:', serviceInput.value);
            const serviceData = JSON.parse(serviceInput.value);
            console.log('Parsed service data:', serviceData);
            
            if (Array.isArray(serviceData) && serviceData.length > 0) {
                // Clear existing table rows
                if (servicesTableBody) {
                    servicesTableBody.innerHTML = '';
                }
                
                // Add each service to the table
                serviceData.forEach(service => {
                    console.log('Adding service to table:', service);
                    updateServiceDisplay(service);
                });
            }
        } catch (e) {
            console.error('Error parsing service data:', e);
        }
    }

    // Add event listener for delete buttons
    if (servicesTableBody) {
        servicesTableBody.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-row')) {
                const row = e.target.closest('tr');
                row.remove();
                updateTotalAmount();
            }
        });
    }
});
// ... existing code ...
</script>
@endpush

@endsection


