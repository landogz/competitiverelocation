@extends('includes.app')

@section('title', 'Dashboard')

@section('content')

<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<style>
    table.dataTable td.dt-control:before {
    content: "";
}
table.dataTable tr.dt-hasChild td.dt-control:before {
    content: "";
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Long Distance Leads</h4>                               
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div><!--end row-->
    <div class="row">                        
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Long Distance List Leads</h4>
                    <button type="button" class="btn btn-primary btn-sm">Create New Lead</button>
                </div>
                <div class="card-body pt-0 table-responsive">
                    <table id="example" class="table table-bordered mb-0 table-centered">
                        <thead>
                            <tr>
                                <th style="width: 50px;"></th>
                                <th>Customer</th>
                                <th>Load #</th>
                                <th>Lead Type</th>
                                <th>Origin</th>
                                <th>Destination</th>
                                <th>Moving Date</th>
                                <th>Total Miles</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr data-extra='{"email": "john.doe@example.com", "phone": "+123456789"}'>
                                <td class="dt-control"></td>
                                <td>John Doe</td>
                                <td>123456</td>
                                <td>Local</td>
                                <td>New Jersey</td>
                                <td>New Jersey</td>
                                <td>Sept. 5, 2020</td>
                                <td>129.25</td>
                                <td><span class="badge bg-success">New</span></td>
                            </tr>
                            <tr data-extra='{"email": "jane.smith@example.com", "phone": "+987654321"}'>
                                <td class="dt-control"></td>
                                <td>John Doe</td>
                                <td>123456</td>
                                <td>Local</td>
                                <td>New Jersey</td>
                                <td>New Jersey</td>
                                <td>Sept. 5, 2020</td>
                                <td>129.25</td>
                                <td><span class="badge bg-success">New</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col-->           
    </div><!--end row-->                                         
</div>
<script>
    $(document).ready(function() {
        var table = $('#example').DataTable({
            responsive: true,
            columnDefs: [
                { orderable: false, className: 'dt-control', targets: 0 } // Expand/collapse button
            ],
            order: [[1, 'asc']]
        });
    
        // Function to create a detailed info table
        function format(details) {
            return `
                <table class="table table-bordered table-sm">
                    <tr>
                        <th>Email</th>
                        <td>${details.email}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>${details.phone}</td>
                    </tr>
                </table>
            `;
        }
    
        // Add event listener for opening and closing details
        $('#example tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
    
            if (row.child.isShown()) {
                row.child.hide();
                $(this).html('<button class="btn btn-sm btn-primary">+</button>'); // Show +
            } else {
                // Parse the JSON data from data-extra attribute
                var details = JSON.parse(tr.attr('data-extra'));
                row.child(format(details)).show();
                $(this).html('<button class="btn btn-sm btn-danger">-</button>'); // Show -
            }
        });
    
        // Initialize the expand column with +
        $('#example tbody tr td:first-child').html('<button class="btn btn-sm btn-primary">+</button>');
    });
    </script>

@endsection