@extends('includes.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Lead Source</h4>                              
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div><!--end row-->
    <div class="row">                        
        <div class="col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">Lead Source Title</h4>     
                        </div><!--end col-->
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <form>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="leadsource" placeholder="Lead Source here...">
                        </div>
                        <button type="submit" class="btn btn-primary">Create Lead Source</button>
                    </form>
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col--> 
        <div class="col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">Manage Lead Source
                            </h4>                      
                        </div><!--end col-->                                        
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 table-centered">
                            <thead class="table-light">
                            <tr>
                                <th>Lead Source Title</th>
                                <th class="text-center" style="width:150px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Aaron@example.com</td>
                                <td class="text-center">                                                
                                    <button type="button" class="btn btn-info"><i class="fas fa-edit"></i></button>
                                    <button type="button" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>       
                                </td>
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