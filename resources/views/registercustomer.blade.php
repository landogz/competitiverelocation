@extends('includes.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Customer Registration</h4>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Mifty</a>
                        </li><!--end nav-item-->
                        <li class="breadcrumb-item"><a href="#">Pages</a>
                        </li><!--end nav-item-->
                        <li class="breadcrumb-item active">Starter</li>
                    </ol>
                </div>                                
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div><!--end row-->
    <div class="row">                        
        <div class="col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">Card title &amp; Dropdown</h4>                      
                        </div><!--end col-->
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col--> 
        <div class="col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">Card Title</h4>                      
                        </div><!--end col-->                                        
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col-->           
    </div><!--end row-->                                         
</div>

@endsection