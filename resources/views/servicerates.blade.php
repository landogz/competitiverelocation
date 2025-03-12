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
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-auto">                      
                            <h4 class="card-title">Service Rates</h4>                      
                        </div><!--end col-->
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 table-centered">
                            <thead class="table-light">
                            <tr>
                                <th>Services</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td rowspan="9">DELIVERY SERVICE</td>   
                                <td>Less than $750 - $79.99 light assembly</td>                                
                            </tr>
                            <tr>
                                <td>$750 - Less than $1500 - $159.99 light assembly</td>                                
                            </tr>
                            <tr>
                                <td>$1500 - Less than $2000 - $179.99 light assembly</td>                                
                            </tr>
                            <tr>
                                <td>$2000- Less than $2750 - $194.99 light assembly</td>                                
                            </tr>
                            <tr>
                                <td>$2750 - Less than $4000 - $224.99 light assembly</td>                                
                            </tr>
                            <tr>
                                <td>$4000 - Less than $6000 - $284.99 light assembly</td>                                
                            </tr>
                            <tr>
                                <td>$6000- Less than $8000 - $314.99 light assembly</td>                                
                            </tr>
                            <tr>
                                <td>$8000-Less than $10000 - $344.99 light assembly</td>                                
                            </tr>
                            <tr>
                                <td>$10000 and more - $384.99 light assembly</td>                                
                            </tr>
                            <tr>
                                <td>FURNITURE REMOVAL SERVICE</td>   
                                <td>$175.00 ANY ADDITIONAL ITEMS IS $75.00</td>                                
                            </tr>
                            <tr>
                                <td rowspan="4">MOVING SERVICE -  3 hours minimum (2 hours labor, 1 hour travel)</td>   
                                <td>2 mens - $270.00</td>                                
                            </tr>
                            <tr>  
                                <td>3 mens - $465.00</td>                                
                            </tr>
                            <tr>
                                <td>4 mens - $465.00</td>                                
                            </tr>
                            <tr> 
                                <td>5 mens - $465.00</td>                                
                            </tr>
                            <tr>
                                <td>CLEANING SERVICE</td>   
                                <td>$135/H</td>                                
                            </tr>
                            <tr>
                                <td>RE ARRANGING</td>   
                                <td>$150/H</td>                                
                            </tr>
                            <tr>
                                <td>MATTRESS REMOVAL</td>   
                                <td>$125/H</td>                                
                            </tr>
                            <tr>
                                <td>HOISTING</td>   
                                <td>$350/H</td>                                
                            </tr>
                            <tr>
                                <td>Exterminator,Washing and Replacing <br>Moving Blankets</td>   
                                <td>$650/H</td>                                
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