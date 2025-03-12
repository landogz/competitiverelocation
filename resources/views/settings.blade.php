@extends('includes.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Local Inventory</h4>                             
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div><!--end row-->
    <div class="row">                        
        <div class="col-md-12 col-lg-3">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">Category</h4>                      
                        </div><!--end col-->
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <form class="row align-items-center">
                        <div class="mb-1 ">
                            <input type="text" class="form-control" id="category" placeholder="Enter Category">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">Create Category</button>
                    </form>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 table-centered">
                            <thead class="table-light">
                            <tr>
                                <th>Category</th>
                                <th class="text-center" style="width: 40%;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Boxes</td>   
                                <td>
                                    <button type="button" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></button>
                                    <button type="button" class="btn  btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>    
                                </td>
                            </tr>
                            </tbody>
                        </table><!--end /table-->
                    </div>
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col--> 
        <div class="col-md-12 col-lg-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Inventory Settings</h4>
                    <button type="button" class="btn btn-primary btn-sm">New Item</button>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table datatable table-bordered mb-0 table-centered" id="datatable_1">
                            <thead class="table-light">
                              <tr>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Cubic Ft.</th>
                                <th  style="width:120px;">Actions </th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Air Conditioner</td>
                                    <td>Home</td>
                                    <td>15</td>                                    
                                    <td class="text-center">                                                
                                        <button type="button" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>       
                                    </td>
                                </tr>                                                                         
                            </tbody>
                          </table>
                    </div> 
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col-->           
    </div><!--end row-->                                         
</div>

@endsection