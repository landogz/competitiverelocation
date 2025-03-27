@extends('includes.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Customer Registration</h4>                               
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
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">First Name</label>
                        <input class="form-control form-control-sm" type="text" value="" id="example-text-input">                               
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Last Name</label>
                        <input class="form-control form-control-sm" type="text" value="" id="example-text-input">                               
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Email</label>
                        <input class="form-control form-control-sm" type="email" value="" id="example-text-input">                               
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Phone Number</label>
                        <input class="form-control form-control-sm" type="text" value="" id="example-text-input" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="By providing a telephone number and submitting the form, you are consenting to be contacted by SMS text message (our message frequency may vary). Message & data rates may apply. Reply STOP to opt-out of further messaging. Reply HELP for more information. See our Privacy Policy.">                               
                    </div>
                    <hr>
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Lead Source</label>
                        <select id="lead_source">
                            <option value="value-1">Value 1</option>
                            <option value="value-2">Value 2</option>
                        </select>                                    
                    </div><!-- end col -->
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Lead Type</label>
                        <select id="lead_type">
                            <option value="value-1">Local</option>
                            <option value="value-2">Long Distance</option>
                        </select>                                    
                    </div><!-- end col -->
                    
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Assigned Agent</label>
                        <select id="assigned">
                            <option value="value-1">Rolan</option>
                            <option value="value-2">Lamar</option>
                        </select>                                    
                    </div><!-- end col -->
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col--> 
        <div class="col-md-12 col-lg-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0"></h4>
                    <button type="button" class="btn btn-success btn-xl">Save Data</button>
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
                            <p class="text-muted mb-0">
                                <div class="card-body pt-0">
                                    <hr>
                                    <div class="row mb-3">
                                        <div id="map" style="width: 100%; height: 250px; margin-top: 20px;"></div>
                                    </div>                                    
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1" class="form-label">Service</label>
                                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Service">
                                            </div>                     
                                        </div><!--end col-->  
                                        <div class="col-lg-3">   
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1" class="form-label">Move Date</label>
                                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Move Date">
                                            </div>                                          
                                        </div><!--end col-->
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1" class="form-label">No. of Moving or Delivery or Removal</label>
                                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="No. of Moving">
                                            </div>                     
                                        </div><!--end col-->  
                                        <div class="col-lg-3">   
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1" class="form-label">Pictures of Removal Items / Receipt</label>
                                                <button type="button" class="btn rounded-pill btn-success btn-xl mb-2">Click here to open images</button>
                                            </div>                                          
                                        </div><!--end col-->
                                    </div> <!--end row-->
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1" class="form-label">Pick-up Address</label>
                                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Pick-up Address">
                                            </div>                     
                                        </div><!--end col-->  
                                        <div class="col-lg-5">   
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1" class="form-label">Drop Off Address</label>
                                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Drop Off Address">
                                            </div>                                          
                                        </div><!--end col-->
                                        <div class="col-lg-2">   
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1" class="form-label">Calculated Miles</label>
                                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Miles">
                                            </div>                                          
                                        </div><!--end col-->
                                    </div> <!--end row-->       
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1" class="form-label">Sales Reps Name</label>
                                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Sales Reps Name">
                                            </div>                     
                                        </div><!--end col-->  
                                        <div class="col-lg-4">   
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1" class="form-label">Sales Reps Email</label>
                                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Sales Reps Email">
                                            </div>                                          
                                        </div><!--end col-->
                                        <div class="col-lg-4">   
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1" class="form-label">Store Location</label>
                                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Store Location">
                                            </div>                                          
                                        </div><!--end col-->
                                    </div> <!--end row-->     
                                         
                                </div>
                            </p>
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
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row align-items-center">
                                                    <div class="col">                      
                                                        <h4 class="card-title">COSTS</h4>                      
                                                    </div><!--end col-->
                                                </div>  <!--end row-->                                  
                                            </div><!--end card-header-->
                                            <div class="card-body pt-0">
                                                <table class="table table-bordered mb-0 table-centered">
                                                    <thead class="table-light">
                                                    <tr>
                                                        <th>Service</th>
                                                        <th>Rate</th>
                                                        <th>No. Crew</th>
                                                        <th>Crew rate</th>
                                                        <th>Purchased Amount</th>
                                                        <th>Delivery Cost</th>
                                                        <th>Service Total</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <th>MOVING SERVICES</th>
                                                        <th>$175/hour</th>
                                                        <th>2</th>
                                                        <th>$350.00</th>
                                                        <th>0</th>
                                                        <th>$0.00</th>
                                                        <th>$350.00</th>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div><!--end card-body--> 
                                        </div>      
                                    </div><!--end col-->    
                                    <div class="col-lg-3">
                                        <div class="card">
                                            <div class="card-body pt-0">
                                                <div class="mb-3 mt-3">
                                                    <label for="exampleInputEmail1" class="form-label">Software Management Fee</label>
                                                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">                   
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label for="exampleInputEmail1" class="form-label">Truck Fee</label>
                                                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">                   
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label for="exampleInputEmail1" class="form-label">Estimated Total</label>
                                                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">                   
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label for="exampleInputEmail1" class="form-label">DownPayment</label>
                                                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">                   
                                                </div>
                                                <div class="mb-3 mt-3">
                                                    <label for="exampleInputEmail1" class="form-label">Remaining Balance</label>
                                                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">                   
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

// Initialize Google Map and Autocomplete
function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 37.7749, lng: -122.4194 }, // Default center (San Francisco)
        zoom: 13,
    });

}



// Load the Google Maps API
window.onload = function () {
    initMap();
};

</script>
@endsection
