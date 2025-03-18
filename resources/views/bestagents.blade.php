@extends('includes.app')

@section('title', 'Dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="container mt-4">
    
 <div class="row">
        <div class="col-sm-12">
            <h2 class="mb-4 text-center">🏆 Best Agents Performance</h2>
        </div><!--end col-->
    </div><!--end row-->
    <!-- Performance Summary Cards -->
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Sales</h5>
                    <p class="fs-3 fw-bold text-primary">1,245</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Revenue</h5>
                    <p class="fs-3 fw-bold text-success">$154,320</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Top Agent</h5>
                    <p class="fs-3 fw-bold text-warning">John Doe</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Agents</h5>
                    <p class="fs-3 fw-bold text-danger">25</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Performance Graph -->
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="card-title text-center">📊 Agent Performance Over Time</h5>
            <div id="salesChart"></div>
        </div>
    </div>

    <!-- Best Agents Table -->
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="card-title text-center">🏅 Top Performing Agents</h5>
            <div class="table-responsive">
                <table class="table table-hover mt-3 table-bordered table-centered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Total Sales</th>
                            <th>Revenue</th>
                            <th>Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>320</td>
                            <td>$45,000</td>
                            <td>⭐⭐⭐⭐⭐</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jane Smith</td>
                            <td>290</td>
                            <td>$39,800</td>
                            <td>⭐⭐⭐⭐</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Mark Johnson</td>
                            <td>275</td>
                            <td>$37,500</td>
                            <td>⭐⭐⭐⭐</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    // ApexCharts - Agent Performance Graph
    var options = {
        chart: {
            type: "line",
            height: 350
        },
        series: [{
            name: "Sales",
            data: [30, 50, 60, 90, 120, 160, 200]
        }],
        xaxis: {
            categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"]
        }
    };

    var chart = new ApexCharts(document.querySelector("#salesChart"), options);
    chart.render();
</script>

@endsection