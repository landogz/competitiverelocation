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
                        <label class="mb-1">Home Phone</label>
                        <input class="form-control form-control-sm" type="text" value="" id="example-text-input">                               
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Mobile</label>
                        <input class="form-control form-control-sm" type="text" value="" id="example-text-input">                               
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="mb-1">Work Phone</label>
                        <input class="form-control form-control-sm" type="text" value="" id="example-text-input">                               
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
                        <div class="tab-pane p-3 active show" id="home-1" role="tabpanel">
                            <p class="text-muted mb-0">
                                Raw denim you probably haven't heard of them jean shorts Austin.
                            </p>
                        </div>
                        <div class="tab-pane p-3" id="profile-1" role="tabpanel">
                            <p class="text-muted mb-0">
                                Food truck fixie locavore, accusamus mcsweeney's
                                single-origin coffee squid. 
                            </p>
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
                        <div class="tab-pane p-3 active show" id="sentemails" role="tabpanel">                            
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
                        <div class="tab-pane p-3" id="payments" role="tabpanel">
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


@endsection
