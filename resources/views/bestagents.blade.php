@extends('includes.app')

@section('title', 'Best Agents')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Best Performing Agents</h4>
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
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Agents</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="25">0</span>
                                <span class="text-success ms-2 fs-12">
                                    <i class="fas fa-arrow-up me-1"></i>4%
                                </span>
                            </h4>
                            <div class="text-nowrap">
                                <span class="badge bg-soft-success text-success">+2</span>
                                <span class="ms-1 text-muted">From last month</span>
                            </div>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="fas fa-users text-primary"></i>
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
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Sales</span>
                            <h4 class="mb-3">
                                $<span class="counter-value" data-target="154320">0</span>
                                <span class="text-success ms-2 fs-12">
                                    <i class="fas fa-arrow-up me-1"></i>12%
                                </span>
                            </h4>
                            <div class="text-nowrap">
                                <span class="badge bg-soft-success text-success">+$16.5k</span>
                                <span class="ms-1 text-muted">From last month</span>
                            </div>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="fas fa-chart-line text-success"></i>
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
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Average Rating</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="4.8">0</span>
                                <span class="text-success ms-2 fs-12">
                                    <i class="fas fa-star text-warning"></i>
                                </span>
                            </h4>
                            <div class="text-nowrap">
                                <span class="badge bg-soft-success text-success">+0.2</span>
                                <span class="ms-1 text-muted">From last month</span>
                            </div>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="fas fa-star text-warning"></i>
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
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Success Rate</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="92">0</span>%
                                <span class="text-success ms-2 fs-12">
                                    <i class="fas fa-arrow-up me-1"></i>3%
                                </span>
                            </h4>
                            <div class="text-nowrap">
                                <span class="badge bg-soft-success text-success">+2%</span>
                                <span class="ms-1 text-muted">From last month</span>
                            </div>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="fas fa-check-circle text-info"></i>
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
                    <h4 class="card-title mb-0">Agent Performance Trends</h4>
                </div>
                <div class="card-body">
                    <div id="performanceChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Sales Distribution</h4>
                </div>
                <div class="card-body">
                    <div id="salesDistribution" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Best Agents Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Top Performing Agents</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table id="agentsTable" class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Rank</th>
                                    <th>Agent</th>
                                    <th>Total Sales</th>
                                    <th>Revenue</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">#1</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user" class="avatar-xs rounded-circle">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h6 class="mb-0">John Doe</h6>
                                                <small class="text-muted">Senior Agent</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>320</td>
                                    <td>$45,000</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-soft-primary">View Profile</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">#2</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('assets/images/users/avatar-2.jpg') }}" alt="user" class="avatar-xs rounded-circle">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h6 class="mb-0">Jane Smith</h6>
                                                <small class="text-muted">Senior Agent</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>290</td>
                                    <td>$39,800</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="far fa-star text-warning"></i>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-soft-primary">View Profile</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">#3</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('assets/images/users/avatar-3.jpg') }}" alt="user" class="avatar-xs rounded-circle">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h6 class="mb-0">Mark Johnson</h6>
                                                <small class="text-muted">Senior Agent</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>275</td>
                                    <td>$37,500</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="far fa-star text-warning"></i>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-soft-primary">View Profile</button>
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
        const target = parseFloat(counter.getAttribute('data-target'));
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

    // Performance Chart
    var performanceOptions = {
        series: [{
            name: 'John Doe',
            data: [31, 40, 28, 51, 42, 109, 100]
        }, {
            name: 'Jane Smith',
            data: [11, 32, 45, 32, 34, 52, 41]
        }, {
            name: 'Mark Johnson',
            data: [21, 22, 35, 42, 44, 62, 51]
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

    var performanceChart = new ApexCharts(document.querySelector("#performanceChart"), performanceOptions);
    performanceChart.render();

    // Sales Distribution Chart
    var distributionOptions = {
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

    var distributionChart = new ApexCharts(document.querySelector("#salesDistribution"), distributionOptions);
    distributionChart.render();
});
</script>
@endsection