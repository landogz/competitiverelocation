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
        <div class="col-md-12 col-lg-6">
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

                    <div class="text-center py-4">
                        @if(isset($settings) && $settings->is_active)
                            <div class="mb-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 64px;"></i>
                                <h4 class="mt-3">Connected to Stripe</h4>
                                <p class="text-muted">Your Stripe account is successfully connected.</p>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch d-inline-block">
                                    <input type="checkbox" class="form-check-input" id="is_active" 
                                        {{ $settings->is_active ? 'checked' : '' }} disabled>
                                    <label class="form-check-label" for="is_active">Connection Active</label>
                                </div>
                            </div>
                            @if($settings->last_error)
                                <div class="alert alert-warning mt-3">
                                    <small>Last error: {{ $settings->last_error }}</small>
                                </div>
                            @endif
                        @else
                            <div class="mb-4">
                                <i class="fas fa-plug text-muted" style="font-size: 64px;"></i>
                                <h4 class="mt-3">Connect to Stripe</h4>
                                <p class="text-muted mb-4">Click the button below to connect your Stripe account.</p>
                                <a href="{{ route('stripe.connect') }}" class="btn btn-primary btn-lg px-5">
                                    <i class="fab fa-stripe me-2"></i>Connect with Stripe
                                </a>
                            </div>
                        @endif
                    </div>
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col-->   
        <div class="col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">Stripe Logs</h4>                      
                        </div><!--end col-->                                        
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
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