@extends('includes.app')

@section('title', 'Sales Reports')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Sales Reports</h4>
                <div class="page-title-right d-flex align-items-center">
                    <select id="statsRange" class="form-select form-select-sm me-2" style="width: auto;">
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="last_7_days" selected>Last 7 Days</option>
                        <option value="this_week">This Week</option>
                        <option value="last_week">Last Week</option>
                        <option value="this_month">This Month</option>
                        <option value="last_month">Last Month</option>
                        <option value="last_3_months">Last 3 Months</option>
                        <option value="custom">Custom Range</option>
                    </select>
                    <div id="customDateRange" class="me-2" style="display: none;">
                        <div class="input-group input-group-sm">
                            <input type="date" id="customStartDate" class="form-control form-control-sm" aria-label="Start date">
                            <span class="input-group-text">to</span>
                            <input type="date" id="customEndDate" class="form-control form-control-sm" aria-label="End date">
                            <button class="btn btn-sm btn-primary" id="applyCustomRange">Apply</button>
                        </div>
                    </div>
                    <button class="btn btn-primary" id="exportBtn">
                        <i class="fas fa-download me-1"></i> Export Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Agent View Notice -->
    @if(auth()->user()->privilege === 'agent')
    @php
        $agent = auth()->user()->agent_id ? \App\Models\Agent::find(auth()->user()->agent_id) : null;
        $companyName = $agent ? $agent->company_name : 'your company';
    @endphp
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="fas fa-info-circle me-3 fs-4"></i>
                <div>
                    <strong>Agent View:</strong> You're viewing sales data for <strong>{{ $companyName }}</strong>. 
                    All statistics and charts are filtered to show only your assigned transactions.
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
            <div class="card-body" data-stat="totalSales">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Sales</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="{{ $stats['totalSales']['value'] }}">0</span>
                                <span class="{{ $stats['totalSales']['isPositive'] ? 'text-success' : 'text-danger' }} ms-2 fs-12">
                                    <i class="fas fa-arrow-{{ $stats['totalSales']['isPositive'] ? 'up' : 'down' }} me-1"></i>{{ number_format($stats['totalSales']['percentChange'], 2) }}%
                                </span>
                            </h4>
                            <div class="text-nowrap">
                                <span class="badge {{ $stats['totalSales']['isPositive'] ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}">{{ $stats['totalSales']['isPositive'] ? '+' : '-' }}{{ number_format(abs($stats['totalSales']['difference']), 2) }}</span>
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
                <div class="card-body" data-stat="totalRevenue">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Revenue</span>
                            <h4 class="mb-3">
                                $<span class="counter-value" data-target="{{ $stats['totalRevenue']['value'] }}">0</span>
                                <span class="{{ $stats['totalRevenue']['isPositive'] ? 'text-success' : 'text-danger' }} ms-2 fs-12">
                                    <i class="fas fa-arrow-{{ $stats['totalRevenue']['isPositive'] ? 'up' : 'down' }} me-1"></i>{{ number_format($stats['totalRevenue']['percentChange'], 2) }}%
                                </span>
                            </h4>
                            <div class="text-nowrap">
                                <span class="badge {{ $stats['totalRevenue']['isPositive'] ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}">{{ $stats['totalRevenue']['isPositive'] ? '+' : '' }}${{ number_format($stats['totalRevenue']['difference'], 2) }}k</span>
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
            <div class="card-body" data-stat="newCustomers">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">New Customers</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="{{ $stats['newCustomers']['value'] }}">0</span>
                                <span class="{{ $stats['newCustomers']['isPositive'] ? 'text-success' : 'text-danger' }} ms-2 fs-12">
                                    <i class="fas fa-arrow-{{ $stats['newCustomers']['isPositive'] ? 'up' : 'down' }} me-1"></i>{{ number_format($stats['newCustomers']['percentChange'], 2) }}%
                                </span>
                            </h4>
                            <div class="text-nowrap">
                                <span class="badge {{ $stats['newCustomers']['isPositive'] ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}">{{ $stats['newCustomers']['isPositive'] ? '+' : '' }}{{ number_format($stats['newCustomers']['difference'], 2) }}</span>
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
                <div class="card-body" data-stat="averageOrderValue">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Average Order Value</span>
                            <h4 class="mb-3">
                                $<span class="counter-value" data-target="{{ round($stats['averageOrderValue']['value']) }}">0</span>
                                <span class="{{ $stats['averageOrderValue']['isPositive'] ? 'text-success' : 'text-danger' }} ms-2 fs-12">
                                    <i class="fas fa-arrow-{{ $stats['averageOrderValue']['isPositive'] ? 'up' : 'down' }} me-1"></i>{{ number_format($stats['averageOrderValue']['percentChange'], 2) }}%
                                </span>
                            </h4>
                            <div class="text-nowrap">
                                <span class="badge {{ $stats['averageOrderValue']['isPositive'] ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}">{{ $stats['averageOrderValue']['isPositive'] ? '+' : '' }}${{ number_format($stats['averageOrderValue']['difference'], 2) }}</span>
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
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Sales Performance</h4>
                </div>
            <div class="card-body">
                    <div id="salesChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Agent Assignment Stats -->
    <div class="row">
    <div class="col-xl-6">
            <div class="card" style="height: 410px;">
                <div class="card-header">
                    <h4 class="card-title mb-0">Sales by Category</h4>
                </div>
                <div class="card-body">
                    <div id="salesByCategory" style="height: 330px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card" style="height: 410px;">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        @if(auth()->user()->privilege === 'agent')
                        Your Assignment Rate
                        @else
                        Agent Assignment Status
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    <div id="agentAssignmentChart" style="height: 330px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-radius: 10px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);">
                <div class="card-header" style="background: linear-gradient(to right, rgba(91, 115, 232, 0.05), rgba(0, 136, 255, 0.02)); border-bottom: 1px solid rgba(0, 0, 0, 0.05); border-radius: 10px 10px 0 0; padding: 15px 20px;">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0" style="font-weight: 600; color: #333;">
                            <i class="fas fa-list-alt me-2" style="color: #5b73e8;"></i>Recent Sales
                        </h4>
                        <div>
                            <input type="text" id="salesSearch" class="form-control form-control-sm" placeholder="Search..." style="width: 200px;">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table id="salesTable" class="table table-centered table-hover table-striped mb-0">
                            <thead style="position: sticky; top: 0; z-index: 100;">
                                <tr class="table-light">
                                    <th style="font-weight: 600; padding: 12px 15px;">ID</th>
                                    <th style="font-weight: 600; padding: 12px 15px;">Customer</th>
                                    <th style="font-weight: 600; padding: 12px 15px;">Date</th>
                                    <th style="font-weight: 600; padding: 12px 15px;">Amount</th>
                                    <th style="font-weight: 600; padding: 12px 15px;">Status</th>
                                    <th style="font-weight: 600; padding: 12px 15px;">Agent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($recentSales) > 0)
                                @foreach($recentSales as $sale)
                                <tr>
                                    <td style="padding: 12px 15px;">{{ $sale['id'] }}</td>
                                    <td style="padding: 12px 15px;">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-xs rounded-circle bg-soft-primary text-primary" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                                    <span class="avatar-title">{{ substr($sale['customer']['name'], 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0" style="font-size: 14px;">{{ $sale['customer']['name'] }}</h6>
                                                <small class="text-muted" style="font-size: 12px;">{{ $sale['customer']['email'] }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 12px 15px;">{{ $sale['date'] }}</td>
                                    <td style="padding: 12px 15px; font-weight: 500;">${{ number_format($sale['amount'], 2) }}</td>
                                    <td style="padding: 12px 15px;">
                                        @if($sale['status'] == 'completed')
                                            <span class="badge bg-soft-success text-success" style="font-weight: 500; padding: 5px 10px; border-radius: 4px;">Completed</span>
                                        @elseif($sale['status'] == 'pending')
                                            <span class="badge bg-soft-warning text-warning" style="font-weight: 500; padding: 5px 10px; border-radius: 4px;">Pending</span>
                                        @elseif($sale['status'] == 'failed')
                                            <span class="badge bg-soft-danger text-danger" style="font-weight: 500; padding: 5px 10px; border-radius: 4px;">Failed</span>
                                        @else
                                            <span class="badge bg-soft-secondary text-secondary" style="font-weight: 500; padding: 5px 10px; border-radius: 4px;">{{ ucfirst($sale['status']) }}</span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px 15px;">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                            @if($sale['agent']['company_name'] ?? false)
                                                    <span class="badge bg-soft-info text-info mb-1" style="font-weight: 500; font-size: 14px;">
                                                {{ $sale['agent']['company_name'] }}
                                                    </span><br>
                                                @endif
                                                <small class="text-muted" style="font-size: 12px;">{{ $sale['agent']['name'] }}</small><br>
                                               
                                                @if($sale['agent']['email'])
                                                    <small class="text-muted" style="font-size: 12px;">{{ $sale['agent']['email'] }}</small>
                                                @else
                                                    <small class="text-muted" style="font-size: 12px;">-</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="alert alert-info mb-0">
                                                <i class="fas fa-info-circle me-2"></i>
                                                No sales data available for the selected period.
                                            </div>
                                        </td>
                                    </tr>
                                @endif
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
    // Counter Animation for initial load
    initCounters();
    
    // Initialize search functionality for sales table
    document.getElementById('salesSearch').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#salesTable tbody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
    
    // Initialize chart variables
    var salesChart;
    
    // Get the initial selected range
    const initialRange = document.getElementById('statsRange').value || 'last_7_days';
    
    // Immediately fetch data for all charts using the selected range
    fetchStatsData(initialRange);
    fetchSalesData(initialRange);
    fetchCategoryData(initialRange);
    fetchAgentData(initialRange);
    
    // Handle date range selection for all charts
    document.getElementById('statsRange').addEventListener('change', function() {
        const range = this.value;
        const customDateRangeDiv = document.getElementById('customDateRange');
        
        if (range === 'custom') {
            // Show the custom date range picker
            customDateRangeDiv.style.display = 'block';
            
            // Set default dates if not already set
            if (!document.getElementById('customStartDate').value) {
                const today = new Date();
                const oneWeekAgo = new Date();
                oneWeekAgo.setDate(today.getDate() - 7);
                
                document.getElementById('customEndDate').value = formatDateForInput(today);
                document.getElementById('customStartDate').value = formatDateForInput(oneWeekAgo);
            }
        } else {
            // Hide the custom date range picker
            customDateRangeDiv.style.display = 'none';
            
            // Fetch data with the selected range
            fetchStatsData(range);
            fetchSalesData(range);
            fetchCategoryData(range);
            fetchAgentData(range);
        }
    });
    
    // Handle custom date range apply button
    document.getElementById('applyCustomRange').addEventListener('click', function() {
        const startDate = document.getElementById('customStartDate').value;
        const endDate = document.getElementById('customEndDate').value;
        
        if (!startDate || !endDate) {
            alert('Please select both start and end dates.');
            return;
        }
        
        // Check if end date is after start date
        if (new Date(endDate) < new Date(startDate)) {
            alert('End date must be after start date.');
            return;
        }
        
        // Create a custom range object
        const customRange = {
            type: 'custom',
            startDate: startDate,
            endDate: endDate
        };
        
        // Fetch data with the custom range
        fetchStatsData(customRange);
        fetchSalesData(customRange);
        fetchCategoryData(customRange);
        fetchAgentData(customRange);
    });
    
    // Helper function to format date for input value (YYYY-MM-DD)
    function formatDateForInput(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    // Function to initialize counter animations
    function initCounters() {
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
    }

    // Function to fetch stats data
    function fetchStatsData(range) {
        // Add loading indicators to all stat cards
        document.querySelectorAll('.card-h-100 .card-body').forEach(card => {
            card.innerHTML = '<div class="d-flex justify-content-center align-items-center" style="height: 120px;"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        });
        
        // Fetch data for the stats
        const url = new URL(`${window.location.origin}/salesreports/stats-data`);
        
        // Handle different range types (string or custom object)
        if (typeof range === 'object' && range.type === 'custom') {
            url.searchParams.append('range', 'custom');
            url.searchParams.append('start_date', range.startDate);
            url.searchParams.append('end_date', range.endDate);
        } else {
        url.searchParams.append('range', range);
        }
        
        fetch(url.toString())
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Received stats data:', data);
                    
                    if (!data.hasData) {
                        // Show no data message for all stat cards
                        document.querySelectorAll('.card-h-100 .card-body').forEach(card => {
                            card.innerHTML = `
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No sales data available for the selected period.
                                </div>
                            `;
                        });
                    } else {
                        // Update stats cards with data
                    updateStatsCards(data.stats);
                    }
                } else {
                    console.error('Error in response:', data);
                    showStatsError();
                }
            })
            .catch(error => {
                console.error('Error fetching stats data:', error);
                showStatsError();
            });
    }
    
    // Function to update all stats cards with new data
    function updateStatsCards(stats) {
        // Total Sales
        updateStatCard(
            'totalSales', 
            stats.totalSales.value,
            stats.totalSales.percentChange,
            stats.totalSales.isPositive,
            stats.totalSales.difference,
            false // not currency
        );
        
        // Total Revenue
        updateStatCard(
            'totalRevenue', 
            stats.totalRevenue.value,
            stats.totalRevenue.percentChange,
            stats.totalRevenue.isPositive,
            stats.totalRevenue.difference,
            true // is currency
        );
        
        // New Customers
        updateStatCard(
            'newCustomers', 
            stats.newCustomers.value,
            stats.newCustomers.percentChange,
            stats.newCustomers.isPositive,
            stats.newCustomers.difference,
            false // not currency
        );
        
        // Average Order Value
        updateStatCard(
            'averageOrderValue', 
            Math.round(stats.averageOrderValue.value),
            stats.averageOrderValue.percentChange,
            stats.averageOrderValue.isPositive,
            stats.averageOrderValue.difference,
            true // is currency
        );
    }
    
    // Helper function to update a single stat card
    function updateStatCard(id, value, percentChange, isPositive, difference, isCurrency) {
        // Get the card elements
        const card = document.querySelector(`.card-h-100 [data-stat="${id}"]`);
        if (!card) return;
        
        // Format values
        const formattedPercent = parseFloat(percentChange).toFixed(2);
        const formattedDiff = parseFloat(difference).toFixed(2);
        const direction = isPositive ? 'up' : 'down';
        const colorClass = isPositive ? 'success' : 'danger';
        
        // Create the HTML
        let html = `
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <span class="text-muted mb-3 lh-1 d-block text-truncate">${getStatTitle(id)}</span>
                    <h4 class="mb-3">
                        ${isCurrency ? '$' : ''}<span class="counter-value" data-target="${value}">0</span>
                        <span class="text-${colorClass} ms-2 fs-12">
                            <i class="fas fa-arrow-${direction} me-1"></i>${formattedPercent}%
                        </span>
                    </h4>
                    <div class="text-nowrap">
                        <span class="badge bg-soft-${colorClass} text-${colorClass}">
                            ${isPositive ? '+' : ''}${isCurrency ? '$' : ''}${formattedDiff}${id === 'totalRevenue' ? 'k' : ''}
                        </span>
                        <span class="ms-1 text-muted">From previous period</span>
                    </div>
                </div>
                <div class="avatar-sm flex-shrink-0">
                    <span class="avatar-title bg-soft-${getIconColorClass(id)} rounded-circle fs-3">
                        <i class="${getIconClass(id)} text-${getIconColorClass(id)}"></i>
                    </span>
                </div>
            </div>
        `;
        
        // Update the card
        card.innerHTML = html;
        
        // Initialize the counter
        initCounter(card.querySelector('.counter-value'));
    }
    
    // Function to get the title for a stat card
    function getStatTitle(id) {
        const titles = {
            totalSales: 'Total Sales',
            totalRevenue: 'Total Revenue',
            newCustomers: 'New Customers',
            averageOrderValue: 'Average Order Value'
        };
        return titles[id] || id;
    }
    
    // Function to get the icon class for a stat card
    function getIconClass(id) {
        const icons = {
            totalSales: 'fas fa-chart-line',
            totalRevenue: 'fas fa-dollar-sign',
            newCustomers: 'fas fa-users',
            averageOrderValue: 'fas fa-shopping-cart'
        };
        return icons[id] || 'fas fa-chart-bar';
    }
    
    // Function to get the icon color class for a stat card
    function getIconColorClass(id) {
        const colors = {
            totalSales: 'primary',
            totalRevenue: 'success',
            newCustomers: 'warning',
            averageOrderValue: 'info'
        };
        return colors[id] || 'primary';
    }
    
    // Function to show error in stats cards
    function showStatsError() {
        document.querySelectorAll('.card-h-100 .card-body').forEach(card => {
            card.innerHTML = '<div class="alert alert-danger">Error loading statistics</div>';
        });
    }
    
    // Function to initialize counter animation
    function initCounter(counterElement) {
        if (!counterElement) return;
        
        const target = parseInt(counterElement.getAttribute('data-target'));
        const duration = 2000; // 2 seconds
        const step = target / (duration / 16); // 60fps
        let current = 0;

        const updateCounter = () => {
            current += step;
            if (current < target) {
                counterElement.textContent = Math.floor(current).toLocaleString();
                requestAnimationFrame(updateCounter);
            } else {
                counterElement.textContent = target.toLocaleString();
            }
        };

        updateCounter();
    }
    
    // Function to update performance chart
    function updatePerformanceChart(data) {
        // Clean up the chart container
        document.getElementById('salesChart').innerHTML = '';
        
        // Completely destroy the existing chart if it exists
        if (salesChart) {
            try {
                salesChart.destroy();
            } catch (error) {
                console.error('Error destroying existing chart:', error);
            }
        }
        
        // Create fresh chart options to avoid any shared state issues
        const freshOptions = {
            series: [
                {
                    name: 'Sales',
                    data: data.sales
                },
                {
                    name: 'Revenue',
                    data: data.revenue
                }
            ],
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
                type: 'category', // Use category instead of datetime for non-standard date formats
                categories: data.dates
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
            },
        };
        
        // Create a completely new chart
        try {
            console.log('Creating sales performance chart with options:', freshOptions);
            setTimeout(() => {
                salesChart = new ApexCharts(document.querySelector("#salesChart"), freshOptions);
                salesChart.render();
            }, 100);
        } catch (error) {
            console.error('Error rendering sales performance chart:', error);
            document.getElementById('salesChart').innerHTML = 
                '<div class="alert alert-danger">Error rendering chart: ' + error.message + '</div>';
        }
    }

    // Function to fetch sales data
    function fetchSalesData(range) {
        // Show loading indicator
        document.getElementById('salesChart').innerHTML = '<div class="d-flex justify-content-center align-items-center" style="height: 350px;"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        
        // Properly encode the URL
        const url = new URL(`${window.location.origin}/salesreports/performance-data`);
        
        // Handle different range types (string or custom object)
        if (typeof range === 'object' && range.type === 'custom') {
            url.searchParams.append('range', 'custom');
            url.searchParams.append('start_date', range.startDate);
            url.searchParams.append('end_date', range.endDate);
        } else {
            url.searchParams.append('range', range);
        }
        
        fetch(url.toString())
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Received performance data:', data);
                    
                    // SUPER strict check: ensure there's at least one non-zero sales value
                    // This is because revenue might have values even when there are no completed sales
                    // We explicitly ignore revenue data when determining if there's data to show
                    const hasSalesData = Array.isArray(data.sales) && data.sales.some(value => value > 0);
                    
                    if (!hasSalesData) {
                        // Show no data message when there are no sales, regardless of revenue
                        document.getElementById('salesChart').innerHTML = `
                            <div class="alert alert-info mb-0 d-flex justify-content-center align-items-center" style="height: 350px;">
                                <div>
                                    <i class="fas fa-info-circle me-2"></i>
                                    No sales data available for the selected period.
                                </div>
                            </div>
                        `;
                    } else {
                        // Only update the performance chart when there are actual sales
                        updatePerformanceChart(data);
                    }
                } else {
                    console.error('Error in response:', data);
                    document.getElementById('salesChart').innerHTML = '<div class="alert alert-danger">Failed to load chart data</div>';
                }
            })
            .catch(error => {
                console.error('Error fetching performance data:', error);
                document.getElementById('salesChart').innerHTML = '<div class="alert alert-danger">Error loading data</div>';
            });
    }
    
    // Export Button
    document.getElementById('exportBtn').addEventListener('click', function() {
        window.location.href = "{{ route('salesreports.export') }}";
    });

    // Function to fetch category data
    function fetchCategoryData(range) {
        // Show loading indicator
        document.getElementById('salesByCategory').innerHTML = '<div class="d-flex justify-content-center align-items-center" style="height: 330px;"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        
        // Properly encode the URL
        const url = new URL(`${window.location.origin}/salesreports/category-data`);
        
        // Handle different range types (string or custom object)
        if (typeof range === 'object' && range.type === 'custom') {
            url.searchParams.append('range', 'custom');
            url.searchParams.append('start_date', range.startDate);
            url.searchParams.append('end_date', range.endDate);
        } else {
            url.searchParams.append('range', range);
        }
        
        fetch(url.toString())
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Received category data:', data);
                    
                    // Check if we have any data
                    const totalCategorySales = Object.values(data.categories).reduce((sum, value) => sum + value, 0);
                    
                    if (totalCategorySales <= 0) {
                        // Show no data message
                        document.getElementById('salesByCategory').innerHTML = `
                            <div class="alert alert-info mb-0 d-flex justify-content-center align-items-center" style="height: 330px;">
                                <div>
                                    <i class="fas fa-info-circle me-2"></i>
                                    No sales category data available for the selected period.
                                </div>
                            </div>
                        `;
                    } else {
                        // Update category chart
                        updateCategoryChart(data.categories);
                    }
                } else {
                    console.error('Error in response:', data);
                    document.getElementById('salesByCategory').innerHTML = '<div class="alert alert-danger">Failed to load category data</div>';
                }
            })
            .catch(error => {
                console.error('Error fetching category data:', error);
                document.getElementById('salesByCategory').innerHTML = '<div class="alert alert-danger">Error loading data</div>';
            });
    }
    
    // Function to update category chart
    function updateCategoryChart(categories) {
        // Clean up the chart container
        document.getElementById('salesByCategory').innerHTML = '';
        
        // Create options for the chart
        const options = {
            series: [
                categories['Local Moving'] || 0, 
                categories['Long Distance'] || 0
            ],
            chart: {
                type: 'donut',
                height: 330,
                dropShadow: {
                    enabled: true,
                    top: 0,
                    left: 0,
                    blur: 3,
                    opacity: 0.2
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%'
                    }
                }
            },
            colors: ['#0088ff', '#5b73e8'],
            labels: ['Local Moving', 'Long Distance'],
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '13px',
                markers: {
                    width: 10,
                    height: 10,
                    radius: 5
                },
                itemMargin: {
                    horizontal: 8,
                    vertical: 0
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val.toFixed(1) + "%";
                },
                style: {
                    fontSize: '13px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: '500'
                }
            },
            tooltip: {
                style: {
                    fontSize: '13px',
                    fontFamily: 'Helvetica, Arial, sans-serif'
                },
                y: {
                    formatter: function(value) {
                        return Math.round(value) + " sales";
                    }
                }
            }
        };
        
        // Create and render the chart
        try {
            console.log('Creating category chart with options:', options);
            const categoryChart = new ApexCharts(document.querySelector("#salesByCategory"), options);
            categoryChart.render();
        } catch (error) {
            console.error('Error rendering category chart:', error);
            document.getElementById('salesByCategory').innerHTML = 
                '<div class="alert alert-danger">Error rendering chart: ' + error.message + '</div>';
        }
    }

    // Function to fetch agent assignment data
    function fetchAgentData(range) {
        // Show loading indicator
        document.getElementById('agentAssignmentChart').innerHTML = '<div class="d-flex justify-content-center align-items-center" style="height: 330px;"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        
        // Properly encode the URL
        const url = new URL(`${window.location.origin}/salesreports/agent-data`);
        
        // Handle different range types (string or custom object)
        if (typeof range === 'object' && range.type === 'custom') {
            url.searchParams.append('range', 'custom');
            url.searchParams.append('start_date', range.startDate);
            url.searchParams.append('end_date', range.endDate);
        } else {
            url.searchParams.append('range', range);
        }
        
        fetch(url.toString())
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Received agent assignment data:', data);
                    
                    // Check if we have any data
                    const totalTransactions = data.assigned + data.unassigned;
                    
                    if (totalTransactions <= 0) {
                        // Show no data message
                        document.getElementById('agentAssignmentChart').innerHTML = `
                            <div class="alert alert-info mb-0 d-flex justify-content-center align-items-center" style="height: 330px;">
                                <div>
                                    <i class="fas fa-info-circle me-2"></i>
                                    No agent assignment data available for the selected period.
                                </div>
                            </div>
                        `;
                    } else {
                        // Update agent assignment chart
                        updateAgentChart(data);
                    }
                } else {
                    console.error('Error in response:', data);
                    document.getElementById('agentAssignmentChart').innerHTML = '<div class="alert alert-danger">Failed to load agent data</div>';
                }
            })
            .catch(error => {
                console.error('Error fetching agent data:', error);
                document.getElementById('agentAssignmentChart').innerHTML = '<div class="alert alert-danger">Error loading data</div>';
            });
    }
    
    // Function to update agent assignment chart
    function updateAgentChart(data) {
        // Clean up the chart container
        document.getElementById('agentAssignmentChart').innerHTML = '';
        
        // Define labels based on user privilege
        let label1, label2;
        
        @if(auth()->user()->privilege === 'agent')
        label1 = "Your Jobs";
        label2 = "Other Jobs"; 
        @else
        label1 = "Assigned";
        label2 = "Unassigned";
        @endif
        
        // Create options for the chart
        const options = {
            series: [data.assignedPercentage, data.unassignedPercentage],
            chart: {
                type: 'donut',
                height: 330,
                dropShadow: {
                    enabled: true,
                    top: 0,
                    left: 0,
                    blur: 3,
                    opacity: 0.2
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%'
                    }
                }
            },
            colors: ['#34c38f', '#f46a6a'],
            labels: [label1, label2],
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '13px',
                markers: {
                    width: 10,
                    height: 10,
                    radius: 5
                },
                itemMargin: {
                    horizontal: 8,
                    vertical: 0
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val.toFixed(1) + "%";
                },
                style: {
                    fontSize: '13px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: '500'
                }
            },
            stroke: {
                width: 2,
                colors: ['#fff']
            },
            tooltip: {
                style: {
                    fontSize: '13px',
                    fontFamily: 'Helvetica, Arial, sans-serif'
                },
                y: {
                    formatter: function(value) {
                        return value.toFixed(1) + "%";
                    }
                }
            }
        };
        
        // Create and render the chart
        try {
            console.log('Creating agent chart with options:', options);
            const agentChart = new ApexCharts(document.querySelector("#agentAssignmentChart"), options);
            agentChart.render();
        } catch (error) {
            console.error('Error rendering agent chart:', error);
            document.getElementById('agentAssignmentChart').innerHTML = 
                '<div class="alert alert-danger">Error rendering chart: ' + error.message + '</div>';
        }
    }
});
</script>
@endsection