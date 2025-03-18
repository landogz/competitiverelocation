@extends('includes.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Call Center</h4>                             
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div><!--end row-->
    <div class="row">               
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Possible Leads</h4>
                    <button type="button" class="btn btn-primary btn-sm">New Leads</button>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
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
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col-->           
    </div><!--end row-->                                         
</div>


@endsection