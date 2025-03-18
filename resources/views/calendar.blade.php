@extends('includes.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Calendar</h4>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Mifty</a>
                        </li><!--end nav-item-->
                        <li class="breadcrumb-item"><a href="#">Apps</a>
                        </li><!--end nav-item-->
                        <li class="breadcrumb-item active">Calendar</li>
                    </ol>
                </div>                                
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div><!--end row-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div id='calendar'></div>
                    <div style='clear:both'></div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div> <!-- end col -->
    </div> <!-- end row -->
</div><!-- container -->



@endsection