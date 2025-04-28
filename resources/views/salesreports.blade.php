@extends('includes.app')

@section('title', 'Sales Reports')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Sales Reports</h4>
                <div class="page-title-right">
                    <button class="btn btn-primary" id="exportBtn">
                        <i class="fas fa-download me-1"></i> Export Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
            <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Sales</span>
                            <h4 class="mb-3">
                                $<span class="counter-value" data-target="1245">0</span>
                                <span class="text-success ms-2 fs-12">
                                    <i class="fas fa-arrow-up me-1"></i>8%
                                </span>
                            </h4>
                            <div class="text-nowrap">
                                <span class="badge bg-soft-success text-success">+$12.5k</span>
                                <span class="ms-1 text-muted">From last month</span>
                            </div>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="fas fa-chart-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Revenue</span>
                            <h4 class="mb-3">
                                $<span class="counter-value" data-target="154320">0</span>
                                <span class="text-danger ms-2 fs-12">
                                    <i class="fas fa-arrow-down me-1"></i>5%
                                </span>
                            </h4>
                            <div class="text-nowrap">
                                <span class="badge bg-soft-danger text-danger">-$8.2k</span>
                                <span class="ms-1 text-muted">From last month</span>
                            </div>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="fas fa-dollar-sign text-success"></i>
                            </span>
                        </div>
                    </div>
            </div>
        </div>
    </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
            <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">New Customers</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="320">0</span>
                                <span class="text-success ms-2 fs-12">
                                    <i class="fas fa-arrow-up me-1"></i>12%
                                </span>
                            </h4>
                            <div class="text-nowrap">
                                <span class="badge bg-soft-success text-success">+42</span>
                                <span class="ms-1 text-muted">From last month</span>
                            </div>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="fas fa-users text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Average Order Value</span>
                            <h4 class="mb-3">
                                $<span class="counter-value" data-target="482">0</span>
                                <span class="text-success ms-2 fs-12">
                                    <i class="fas fa-arrow-up me-1"></i>3%
                                </span>
                            </h4>
                            <div class="text-nowrap">
                                <span class="badge bg-soft-success text-success">+$14</span>
                                <span class="ms-1 text-muted">From last month</span>
                            </div>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="fas fa-shopping-cart text-info"></i>
                            </span>
        </div>
    </div>
</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Sales Performance</h4>
                </div>
            <div class="card-body">
                    <div id="salesChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Sales by Category</h4>
                </div>
                <div class="card-body">
                    <div id="salesByCategory" style="height: 350px;"></div>
    </div>
</div>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Recent Sales</h4>
                </div>
        <div class="card-body">
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table id="salesTable" class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                                <tr>
                                    <td>#CR-001</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user" class="avatar-xs rounded-circle">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h6 class="mb-0">John Doe</h6>
                                                <small class="text-muted">john@example.com</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Feb 20, 2025</td>
                                    <td>$500.00</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-soft-primary">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#CR-002</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('assets/images/users/avatar-2.jpg') }}" alt="user" class="avatar-xs rounded-circle">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h6 class="mb-0">Jane Smith</h6>
                                                <small class="text-muted">jane@example.com</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Feb 18, 2025</td>
                                    <td>$750.00</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-soft-primary">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#CR-003</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('assets/images/users/avatar-3.jpg') }}" alt="user" class="avatar-xs rounded-circle">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h6 class="mb-0">Michael Brown</h6>
                                                <small class="text-muted">michael@example.com</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Feb 15, 2025</td>
                                    <td>$1,200.00</td>
                                    <td><span class="badge bg-danger">Failed</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-soft-primary">View</button>
                                    </td>
                                </tr>
                </tbody>
            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Counter Animation
    const counters = document.querySelectorAll('.counter-value');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000; // 2 seconds
        const step = target / (duration / 16); // 60fps
        let current = 0;

        const updateCounter = () => {
            current += step;
            if (current < target) {
                counter.textContent = Math.floor(current).toLocaleString();
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target.toLocaleString();
            }
        };

        updateCounter();
    });

    // Sales Chart
    var salesOptions = {
            series: [{
                name: 'Sales',
            data: [31, 40, 28, 51, 42, 109, 100]
        }, {
            name: 'Revenue',
            data: [11, 32, 45, 32, 34, 52, 41]
        }],
        chart: {
            height: 350,
            type: 'area',
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
            xaxis: {
            type: 'datetime',
            categories: ["2025-02-01", "2025-02-02", "2025-02-03", "2025-02-04", "2025-02-05", "2025-02-06", "2025-02-07"]
        },
        tooltip: {
            x: {
                format: 'dd/MM/yy HH:mm'
            },
        },
    };

    var salesChart = new ApexCharts(document.querySelector("#salesChart"), salesOptions);
    salesChart.render();

    // Sales by Category Chart
    var categoryOptions = {
        series: [44, 55, 13, 43],
        chart: {
            type: 'donut',
            height: 350
        },
        labels: ['Local Moving', 'Long Distance', 'Storage', 'Other'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    var categoryChart = new ApexCharts(document.querySelector("#salesByCategory"), categoryOptions);
    categoryChart.render();
});
</script>
@endsection