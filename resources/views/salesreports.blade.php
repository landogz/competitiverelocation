@extends('includes.app')

@section('title', 'Dashboard')

@section('content')

<div class="container mt-4">
    <h2 class="mb-4 text-center">📊 Sales Report Dashboard</h2>

<!-- Sales Summary Cards -->
<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Total Sales</h5>
                <p class="fs-3 fw-bold text-primary">1,245</p>
                <small class="text-muted">Compared to last month: <span class="text-success">+8%</span></small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Total Revenue</h5>
                <p class="fs-3 fw-bold text-success">$154,320</p>
                <small class="text-muted">Compared to last month: <span class="text-danger">-5%</span></small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">New Customers</h5>
                <p class="fs-3 fw-bold text-warning">320</p>
                <small class="text-muted">Compared to last month: <span class="text-success">+12%</span></small>
            </div>
        </div>
    </div>
</div>

<!-- Additional Sales Insights -->
<div class="row g-3 mt-3">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-success">Total Income</h5>
                <p class="fs-3 fw-bold text-success">850</p>
                <small class="text-muted">High-profit transactions <span class="text-success">+15%</span></small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-danger">Total Expenses</h5>
                <p class="fs-3 fw-bold text-danger">395</p>
                <small class="text-muted">Low-margin or lost sales <span class="text-danger">-7%</span></small>
            </div>
        </div>
    </div>
</div>

    <!-- Sales Chart -->
    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Monthly Sales Performance</h5>
            <div id="salesChart"></div>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Sales Transactions</h5>
            <table id="salesTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>1</td><td>John Doe</td><td>Feb 20, 2025</td><td>$500</td><td><span class="badge bg-success">Completed</span></td></tr>
                    <tr><td>2</td><td>Jane Smith</td><td>Feb 18, 2025</td><td>$750</td><td><span class="badge bg-warning">Pending</span></td></tr>
                    <tr><td>3</td><td>Michael Brown</td><td>Feb 15, 2025</td><td>$1,200</td><td><span class="badge bg-danger">Failed</span></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.0.0/dist/chart.umd.min.js"></script>


<!-- Your JavaScript (Make sure this is placed AFTER Chart.js) -->
<script>
   document.addEventListener("DOMContentLoaded", function () {
    var chartEl = document.querySelector("#salesChart");

    if (chartEl) { // Ensure element exists
        var options = {
            chart: {
                type: 'bar',
                height: 350
            },
            series: [{
                name: 'Sales',
                data: [10, 20, 30, 40, 50]
            }],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May']
            }
        };
        var chart = new ApexCharts(chartEl, options);
        chart.render();
    } else {
        console.error("Chart container not found!");
    }
});
</script>

@endsection