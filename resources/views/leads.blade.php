@extends('includes.app')

@section('title', 'Dashboard')

@section('content')

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
                        <button type="submit" class="btn btn-success btn-xl">Save Data</button>
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
                            <a class="nav-link" data-bs-toggle="tab" href="#settings-1" role="tab" aria-selected="false" tabindex="-1">Moving Supplies</a>
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
                                                <button type="button" class="btn rounded-pill btn-success btn-xl mb-2" onclick="document.getElementById('image_upload').click()">Click here to open images</button>
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
                                                    <label for="exampleInputEmail1" class="form-label">Total Volume From Inventory</label>
                                                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Total Volume From Inventory">                   
                                                </div>
                                            </div><!--end col-->  
                                            <div class="col-lg-6">   
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1" class="form-label">Total Weight From Inventory</label>
                                                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Total Weight From Inventory">                                         
                                                </div>
                                            </div><!--end col-->
                                        </div>      
                                    </div><!--end col-->    
                                    <div class="col-lg-3">
                                        <div class="card">
                                            <div class="card-body pt-0">
                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">Subtotal</label>
                                                    <input type="text" class="form-control" name="subtotal" value="{{ isset($transaction) ? number_format($transaction->subtotal, 2) : '0.00' }}" readonly>                   
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">Software Management Fee</label>
                                                    <input type="text" class="form-control" name="software_fee" value="{{ isset($transaction) ? number_format($transaction->software_fee, 2) : '0.00' }}" readonly>                   
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">Truck Fee</label>
                                                    <input type="text" class="form-control" name="truck_fee" value="{{ isset($transaction) ? number_format($transaction->truck_fee, 2) : '0.00' }}" readonly>                   
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">Estimated Total</label>
                                                    <input type="text" class="form-control" name="grand_total" value="{{ isset($transaction) ? number_format($transaction->grand_total, 2) : '0.00' }}" readonly>                   
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">DownPayment</label>
                                                    <input type="text" class="form-control" name="downpayment" value="{{ isset($transaction) ? number_format($transaction->downpayment, 2) : '0.00' }}" readonly>                   
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label class="form-label">Remaining Balance</label>
                                                    <input type="text" class="form-control" name="remaining_balance" value="{{ isset($transaction) ? number_format($transaction->grand_total - $transaction->downpayment, 2) : '0.00' }}" readonly>                   
                                                </div>
                                            </div><!--end card-body-->   
                                    </div><!--end col-->                                    
                                </div> <!--end row-->
                               
                            </div>
                            </div>
                        </div>
                        <div class="tab-pane p-3" id="settings-1" role="tabpanel">
                            <p class="text-muted mb-0">
                                Trust fund seitan letterpress, keytar raw denim keffiyeh etsy.
                            </p>
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
                        <div class="tab-pane p-0  pt-3 active show" id="sentemails" role="tabpanel">                            
                            <button type="button" class="btn rounded-pill btn-success btn-xl mb-2">Send Mail</button>
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
                    <th>Date</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th  style="width:120px;">Actions </th>
                  </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1/22/2025</td>
                        <td>Michael Borrero</td>
                        <td>(856) 498-2046</td>     
                        <td>mikeborrero@vinelandcity.org </td>        
                        <td>Keller Williams Prime Realty</td>                                    
                        <td class="text-center">                                                
                            <button type="button" class="btn btn-sm btn-warning"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View Logs"><i class="fas fa-eye"></i></button>  
                            <button type="button" class="btn btn-sm btn-info"   data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Add Logs"><i class="fas fa-plus"></i></button>    
                        </td>
                    </tr>                                                                         
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

    // Calculate fees based on service type
    let truckFee = 0;
    let softwareFee = 0;
    let grandTotal = subtotal;
    let downPayment = 0;
    let remainingBalance = 0;

    if (isMovingService) {
        truckFee = 198.80;
        softwareFee = (subtotal + truckFee) * 0.12;
        grandTotal = subtotal + softwareFee + truckFee;
        downPayment = grandTotal * 0.315;
        remainingBalance = grandTotal - downPayment;
    }

    // Update the fields with formatted values
    document.querySelector('[name="subtotal"]').value = subtotal.toFixed(2);
    document.querySelector('[name="software_fee"]').value = softwareFee.toFixed(2);
    document.querySelector('[name="truck_fee"]').value = truckFee.toFixed(2);
    document.querySelector('[name="grand_total"]').value = grandTotal.toFixed(2);
    document.querySelector('[name="downpayment"]').value = downPayment.toFixed(2);
    document.querySelector('[name="remaining_balance"]').value = remainingBalance.toFixed(2);
}

// Add event listeners to recalculate when service-related fields change
document.addEventListener('DOMContentLoaded', function() {
    const serviceInputs = document.querySelectorAll('[name="service"], [name="service_rate"], [name="crew_rate"], [name="no_of_items"]');
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
</style>
<div id="toast-container"></div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Only run if editing an existing transaction
    if (!window.location.pathname.match(/\/leads\/(\d+)\/edit/)) return;
    const transactionId = window.location.pathname.match(/\/leads\/(\d+)\/edit/)[1];
    // Fields inside the form
    const form = document.getElementById('leadForm');
    const fields = ['firstname', 'lastname', 'email', 'phone', 'lead_source', 'lead_type', 'assigned_agent'];
    if (form) {
        fields.forEach(function (field) {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                const isSelect = input.tagName.toLowerCase() === 'select';
                const eventName = isSelect ? 'change' : 'blur';
                input.addEventListener(eventName, function () {
                    const value = input.value;
                    fetch(`/leads/${transactionId}/update-field`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({ field, value })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) showToast('Updated information');
                    });
                });
            }
        });
    }
    // Fields outside the form
    const extraFields = ['sales_name', 'sales_email', 'sales_location'];
    extraFields.forEach(function(field) {
        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
            input.addEventListener('blur', function () {
                const value = input.value;
                fetch(`/leads/${transactionId}/update-field`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ field, value })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) showToast('Updated information');
                });
            });
        }
    });

    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'custom-toast';
        toast.innerHTML = `
            <span class="toast-icon">✔️</span>
            <span>${message}</span>
            <button class="toast-close" aria-label="Close">&times;</button>
        `;
        document.getElementById('toast-container').appendChild(toast);
        // Animate out and remove after 2s or on close
        const removeToast = () => {
            toast.style.animation = 'toast-out 0.3s forwards';
            setTimeout(() => toast.remove(), 300);
        };
        setTimeout(removeToast, 2000);
        toast.querySelector('.toast-close').onclick = removeToast;
    }
});
</script>

@endsection
