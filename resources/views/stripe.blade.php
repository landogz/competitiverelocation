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
                            <h4 class="card-title">Stripe Keys</h4>                      
                        </div><!--end col-->
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <form>
                        <div class="mb-3">
                            <label for="publickey" class="form-label">Public Key</label>
                            <input type="text" class="form-control" id="publickey" placeholder="Enter Public Key">
                        </div>
                        <div class="mb-3">
                            <label for="secrectkey" class="form-label">Secret Key</label>
                            <input type="text" class="form-control" id="secrectkey" aria-describedby="emailHelp" placeholder="Enter Secrect Key">
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col-->   
        <div class="col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">Stripe Logs
                            </h4>                      
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