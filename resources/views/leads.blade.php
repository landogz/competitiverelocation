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
<meta name="transaction-id" content="{{ $transaction->id }}">
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
                <form id="leadForm" action="{{ isset($transaction) ? route('leads.update', $transaction->id) : route('leads.store') }}" method="POST">
                        @csrf
                        @if(isset($transaction))
                            @method('PUT')
                        @endif
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">First Name</label>
                            <input class="form-control form-control-sm" type="text" name="firstname" value="{{ $transaction->firstname ?? '' }}">                               
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Last Name</label>
                            <input class="form-control form-control-sm" type="text" name="lastname" value="{{ $transaction->lastname ?? '' }}">                               
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Email</label>
                            <input class="form-control form-control-sm" type="email" name="email" value="{{ $transaction->email ?? '' }}">                               
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Phone Number</label>
                            <input class="form-control form-control-sm" type="text" name="phone" value="{{ $transaction->phone ?? '' }}" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="By providing a telephone number and submitting the form, you are consenting to be contacted by SMS text message (our message frequency may vary). Message & data rates may apply. Reply STOP to opt-out of further messaging. Reply HELP for more information. See our Privacy Policy.">                               
                    </div>
                    <hr>
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Lead Source</label>
                            <select id="lead_source" name="lead_source" class="form-select">
                                <option value="">Select Source</option>
                                @foreach($leadSources as $id => $name)
                                    <option value="{{ $id }}" {{ ($transaction->lead_source ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                        </select>                                    
                        </div>
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Lead Type</label>
                            <select id="lead_type" name="lead_type" class="form-select">
                                <option value="">Select Type</option>
                                <option value="local" {{ ($transaction->lead_type ?? '') == 'local' ? 'selected' : '' }}>Local</option>
                                <option value="long_distance" {{ ($transaction->lead_type ?? '') == 'long_distance' ? 'selected' : '' }}>Long Distance</option>
                        </select>                                    
                        </div>
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Assigned Agent</label>
                            <select id="assigned" name="assigned_agent" class="form-select">
                                <option value="">Select Agent</option>
                                @foreach($agents as $id => $companyName)
                                    <option value="{{ $id }}" {{ ($transaction->assigned_agent ?? '') == $id ? 'selected' : '' }}>{{ $companyName }}</option>
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
                        <!-- <button type="submit" class="btn btn-success btn-xl">Save Data</button> -->
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
                                                @php
                                                    $serviceName = 'N/A';
                                                    if (isset($transaction->services) && is_array($transaction->services) && count($transaction->services) > 0) {
                                                        $serviceName = $transaction->services[0]['name'] ?? 'N/A';
                                                    } elseif (isset($transaction->services) && is_string($transaction->services)) {
                                                        $decoded = json_decode($transaction->services, true);
                                                        if (is_array($decoded) && count($decoded) > 0) {
                                                            $serviceName = $decoded[0]['name'] ?? 'N/A';
                                                        }
                                                    }
                                                @endphp
                                                <input type="text" class="form-control" name="service" value="{{ $serviceName ?? '' }}" placeholder="Enter Service">
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
                                                <input type="number" class="form-control" name="no_of_items" value="{{ $transaction->no_of_items ?? '' }}" placeholder="No. of Moving">
                                            </div>                     
                                        </div>
                                        <div class="col-lg-3">   
                                            <div class="mb-3">
                                                <label class="form-label">Pictures of Removal Items / Receipt</label>
                                                @if(!isset($transaction))
                                                <button type="button" class="btn rounded-pill btn-success btn-xl mb-2" id="sendMailBtn" data-transaction-id="{{ $transaction->id ?? '' }}">Send Mail</button>
                                                <input type="file" id="image_upload" name="uploaded_image" style="display: none;" multiple>
                                                @endif
                                                @if(isset($transaction) && $transaction->uploaded_image)
                                                <div class="d-flex gap-2 flex-wrap mt-2">
                                                    @php
                                                        $images = explode(',', $transaction->uploaded_image);
                                                    @endphp
                                                    @foreach($images as $image)
                                                        @if(trim($image))
                                                            <a href="{{ trim($image) }}" target="_blank" rel="noopener noreferrer">
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
                                                <input type="text" class="form-control" name="pickup_location" value="{{ $transaction->pickup_location ?? '' }}" placeholder="Enter Pick-up Address">
                                            </div>                     
                                        </div>
                                        <div class="col-lg-5">   
                                            <div class="mb-3">
                                                <label class="form-label">Drop Off Address</label>
                                                <input type="text" class="form-control" name="delivery_location" value="{{ $transaction->delivery_location ?? '' }}" placeholder="Enter Drop Off Address">
                                            </div>                                          
                                        </div>
                                        <div class="col-lg-2">   
                                            <div class="mb-3">
                                                <label class="form-label">Calculated Miles</label>
                                                <input type="number" class="form-control" name="miles" value="{{ $transaction->miles ?? '' }}" placeholder="Miles" readonly>
                                                <div id="distance_info" class="text-muted small mt-1"></div>
                                            </div>                                          
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label class="form-label">Sales Reps Name</label>
                                                <input type="text" class="form-control" name="sales_name" value="{{ $transaction->sales_name ?? '' }}" placeholder="Sales Reps Name">
                                            </div>                     
                                        </div>
                                        <div class="col-lg-4">   
                                            <div class="mb-3">
                                                <label class="form-label">Sales Reps Email</label>
                                                <input type="email" class="form-control" name="sales_email" value="{{ $transaction->sales_email ?? '' }}" placeholder="Sales Reps Email">
                                            </div>                                          
                                        </div>
                                        <div class="col-lg-4">   
                                            <div class="mb-3">
                                                <label class="form-label">Store Location</label>
                                                <input type="text" class="form-control" name="sales_location" value="{{ $transaction->sales_location ?? '' }}" placeholder="Store Location">
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
                                            <button type="button" class="btn btn-info btn-xl"  data-bs-toggle="modal" data-bs-target="#setinventory">SET INVENTORY</button>
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
                                                        <input type="text" class="form-control" name="subtotal" value="{{ isset($transaction) ? number_format($transaction->subtotal, 2) : '0.00' }}" readonly>                   
                                                    </div>
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">Added Mile Rate</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" class="form-control" name="added_mile_rate" value="{{ isset($transaction) ? number_format($transaction->added_mile_rate, 2) : '0.00' }}" readonly>                   
                                                    </div>
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">Software Management Fee</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" class="form-control" name="software_fee" value="{{ isset($transaction) ? number_format($transaction->software_fee, 2) : '0.00' }}" readonly>                   
                                                    </div>
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">Truck Fee</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" class="form-control" name="truck_fee" value="{{ isset($transaction) ? number_format($transaction->truck_fee, 2) : '0.00' }}" readonly>                   
                                                    </div>
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">Estimated Total</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" class="form-control" name="grand_total" value="{{ isset($transaction) ? number_format($transaction->grand_total, 2) : '0.00' }}" readonly>                   
                                                    </div>
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">DownPayment</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" class="form-control" name="downpayment" value="{{ isset($transaction) ? number_format($transaction->downpayment, 2) : '0.00' }}" readonly>                   
                                                    </div>
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">Remaining Balance</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" class="form-control" name="remaining_balance" value="{{ isset($transaction) ? number_format($transaction->grand_total - $transaction->downpayment, 2) : '0.00' }}" readonly>                   
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
                                            <input type="text" class="form-control" name="insurance_number" value="{{ $transaction->insurance_number ?? '' }}" placeholder="Enter Insurance Number">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Insurance Document</label>
                                            <input type="file" class="form-control" name="insurance_document" accept=".pdf,.doc,.docx">
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
                        document.querySelector('input[name="miles"]').value = Math.round(distance);
                        
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

function calculateFees() {
    // Get the service value
    const serviceInput = document.querySelector('[name="service"]');
    const service = serviceInput ? serviceInput.value.toUpperCase() : '';
    const isMovingService = service.includes('MOVING') || service === 'MOVING SERVICES';
    
    // Get the subtotal from the service rate or rate input
    const serviceRateInput = document.querySelector('[name="service_rate"]');
    let subtotal = serviceRateInput ? parseFloat(serviceRateInput.value) || 0 : 0;
    
    // If no service rate, try to get from crew rate and no_of_crew
    if (subtotal === 0) {
        const crewRateInput = document.querySelector('[name="crew_rate"]');
        const noOfCrewInput = document.querySelector('[name="no_of_items"]');
        const crewRate = crewRateInput ? parseFloat(crewRateInput.value) || 0 : 0;
        const noOfCrew = noOfCrewInput ? parseFloat(noOfCrewInput.value) || 0 : 0;
        subtotal = crewRate * noOfCrew;
    }

    // If still no subtotal, try to get from the subtotal input directly
    if (subtotal === 0) {
        const subtotalInput = document.querySelector('[name="subtotal"]');
        if (subtotalInput) {
            const subtotalValue = subtotalInput.value.replace(/[^0-9.-]+/g, '');
            subtotal = parseFloat(subtotalValue) || 0;
        }
    }

    // Get miles for added mile rate calculation
    const milesInput = document.querySelector('[name="miles"]');
    const distanceInMiles = milesInput ? parseFloat(milesInput.value) || 0 : 0;
    let addedMileRate = 0;

    // Calculate added mile rate for distances over 12.5 miles
    if (distanceInMiles > 12.5) {
        addedMileRate = distanceInMiles * 0.89; // $0.89 per mile charge
    }

    // Calculate fees based on service type
    let truckFee = 0;
    let softwareFee = 0;
    let grandTotal = subtotal + addedMileRate;
    let downPayment = 0;
    let remainingBalance = 0;

    if (isMovingService) {
        truckFee = 198.80;
        softwareFee = (subtotal + truckFee) * 0.12;
        grandTotal = subtotal + softwareFee + truckFee + addedMileRate;
        downPayment = grandTotal * 0.315;
        remainingBalance = grandTotal - downPayment;
    }

    // Show/hide fields based on service type
    const addedMileRateField = document.querySelector('[name="added_mile_rate"]').closest('.mb-3');
    const truckFeeField = document.querySelector('[name="truck_fee"]').closest('.mb-3');
    const softwareFeeField = document.querySelector('[name="software_fee"]').closest('.mb-3');
    const downPaymentField = document.querySelector('[name="downpayment"]').closest('.mb-3');
    const remainingBalanceField = document.querySelector('[name="remaining_balance"]').closest('.mb-3');

    // Always show Added Mile Rate
    addedMileRateField.style.display = 'block';

    if (isMovingService) {
        truckFeeField.style.display = 'block';
        softwareFeeField.style.display = 'block';
        downPaymentField.style.display = 'block';
        remainingBalanceField.style.display = 'block';
    } else {
        truckFeeField.style.display = 'none';
        softwareFeeField.style.display = 'none';
        downPaymentField.style.display = 'none';
        remainingBalanceField.style.display = 'none';
    }

    // Update the fields with formatted values
    document.querySelector('[name="subtotal"]').value = subtotal.toFixed(2);
    document.querySelector('[name="added_mile_rate"]').value = addedMileRate.toFixed(2);
    document.querySelector('[name="software_fee"]').value = softwareFee.toFixed(2);
    document.querySelector('[name="truck_fee"]').value = truckFee.toFixed(2);
    document.querySelector('[name="grand_total"]').value = grandTotal.toFixed(2);
    document.querySelector('[name="downpayment"]').value = downPayment.toFixed(2);
    document.querySelector('[name="remaining_balance"]').value = remainingBalance.toFixed(2);
}

// Add event listeners to recalculate when service-related fields change
document.addEventListener('DOMContentLoaded', function() {
    const serviceInputs = document.querySelectorAll('[name="service"], [name="service_rate"], [name="crew_rate"], [name="no_of_items"], [name="miles"]');
    serviceInputs.forEach(input => {
        input.addEventListener('change', calculateFees);
        input.addEventListener('input', calculateFees);
    });

    // Initial calculation
    calculateFees();
});
</script>
<style>
#toast-container {
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.custom-toast {
    display: flex;
    align-items: center;
    background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
    color: #222;
    padding: 16px 24px;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(34,197,94,0.18);
    font-size: 1rem;
    font-weight: 500;
    min-width: 240px;
    max-width: 340px;
    opacity: 0;
    transform: translateY(-20px);
    transition: opacity 0.3s, transform 0.3s;
    position: relative;
    overflow: hidden;
    animation: toast-in 0.4s forwards;
}
.custom-toast .toast-icon {
    font-size: 1.5rem;
    margin-right: 12px;
    color: #13c26b;
    flex-shrink: 0;
}
.custom-toast .toast-close {
    background: none;
    border: none;
    color: #333;
    font-size: 1.2rem;
    position: absolute;
    top: 8px;
    right: 12px;
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.2s;
}
.custom-toast .toast-close:hover {
    opacity: 1;
}
@keyframes toast-in {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
@keyframes toast-out {
    to {
        opacity: 0;
        transform: translateY(-20px);
    }
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
        if (!transactionId) {
            console.error('No transaction ID found');
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
                    
                    // Clear and repopulate the added items table
                    const addedItemsContainer = document.getElementById('added-inventory-items');
                    if (addedItemsContainer) {
                        addedItemsContainer.innerHTML = '';
                        
                        data.data.forEach(item => {
                            totalVolume += parseFloat(item.total_volume);
                            
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
    sentEmailsTable = $('#sentEmailsTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: `/leads/${currentTransaction.id}/sent-emails`,
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
            // { data: 'recipient' },
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

@endsection
