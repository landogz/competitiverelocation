@extends('includes.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Best Agents</h4>                               
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div><!--end row-->
    <div class="row">                        
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">                                 
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <div class="row justify-content-center">
                        <div class="col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-body bg-soft-blue text-center rounded-top">
                                    <i class="icofont-bird-wings d-inline-block mt-2 mb-3 display-4 text-blue"></i>                                    
                                </div><!--end card-body--> 
                                <div class="card-body mt-n52">
                                    <div class="text-center">
                                        <div class="py-2 px-3 shadow-sm d-inline-block rounded-pill card-bg">
                                            <h1 class="d-inline-block fw-bold mb-0">$39.00</h1>
                                            <small class="font-12 text-muted">/month</small>
                                        </div>   
                                        <h6 class="pt-3 pb-2 m-0 fs-18 fw-medium">Basic plan</h6>
                                        <hr class="hr-dashed">   
                                                                             
                                        <a href="#" class="btn btn-dark py-2 px-3 mt-2"><span>Get Started</span></a>
                                    </div><!--end pricing Table-->              
                                </div><!--end card-body--> 
                            </div><!--end card--> 
                        </div> <!--end col-->
                        <div class="col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-body bg-soft-pink text-center rounded-top">
                                    <i class="icofont-woman-bird d-inline-block mt-2 mb-3 display-4 text-pink"></i>                                    
                                </div><!--end card-body--> 
                                <div class="card-body mt-n52">
                                    <div class="text-center">
                                        <div class="py-2 px-3 shadow-sm d-inline-block rounded-pill card-bg">
                                            <h1 class="d-inline-block fw-bold mb-0">$49.00</h1>
                                            <small class="font-12 text-muted">/month</small>
                                        </div>   
                                        <h6 class="pt-3 pb-2 m-0 fs-18 fw-medium">Premium Plan</h6>
                                        <hr class="hr-dashed">   
                                                                             
                                        <a href="#" class="btn btn-dark py-2 px-3 mt-2"><span>Get Started</span></a>
                                    </div><!--end pricing Table-->              
                                </div><!--end card-body--> 
                            </div><!--end card--> 
                        </div> <!--end col--> 
                        <div class="col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-body bg-soft-success text-center rounded-top">
                                    <i class="icofont-elk d-inline-block mt-2 mb-3 display-4 text-success"></i>                                    
                                </div><!--end card-body--> 
                                <div class="card-body mt-n52">
                                    <div class="text-center">
                                        <div class="py-2 px-3 shadow-sm d-inline-block rounded-pill card-bg">
                                            <h1 class="d-inline-block fw-bold mb-0">$69.00</h1>
                                            <small class="font-12 text-muted">/month</small>
                                        </div>   
                                        <h6 class="pt-3 pb-2 m-0 fs-18 fw-medium">Plus Plan</h6>
                                        <hr class="hr-dashed">   
                                                                             
                                        <a href="#" class="btn btn-dark py-2 px-3 mt-2"><span>Get Started</span></a>
                                    </div><!--end pricing Table-->              
                                </div><!--end card-body--> 
                            </div><!--end card--> 
                        </div> <!--end col--> 
                        <div class="col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-body bg-soft-warning text-center rounded-top">
                                    <i class="icofont-fire-burn d-inline-block mt-2 mb-3 display-4 text-warning"></i>                                    
                                </div><!--end card-body--> 
                                <div class="card-body mt-n52">
                                    <div class="text-center">
                                        <div class="py-2 px-3 shadow-sm d-inline-block rounded-pill card-bg">
                                            <h1 class="d-inline-block fw-bold mb-0">$199.00</h1>
                                            <small class="font-12 text-muted">/month</small>
                                        </div>   
                                        <h6 class="pt-3 pb-2 m-0 fs-18 fw-medium">Master Plan</h6>
                                        <hr class="hr-dashed">   
                                                                             
                                        <a href="#" class="btn btn-dark py-2 px-3 mt-2"><span>Get Started</span></a>
                                    </div><!--end pricing Table-->              
                                </div><!--end card-body--> 
                            </div><!--end card--> 
                        </div> <!--end col--> 
                                                                              
                    </div><!--end row-->  
                    
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
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col-->          
    </div><!--end row-->                                         
</div>

@endsection