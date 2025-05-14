@extends('includes.app')

@section('title', 'Best Agents')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Best Performing Agents</h4>
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

    <!-- Stats Cards -->
 <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body" data-stat="totalAgents">
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
                                <span class="ms-1 text-muted">From last period</span>
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
                <div class="card-body" data-stat="totalSales">
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
                                <span class="ms-1 text-muted">From last period</span>
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
                <div class="card-body" data-stat="averageRating">
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
                                <span class="ms-1 text-muted">From last period</span>
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
                <div class="card-body" data-stat="successRate">
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
                                <span class="ms-1 text-muted">From last period</span>
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

    <!-- Top 3 Agents Podium -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: none;">
                <div class="card-header" style="background-color: #fff; border-bottom: 1px solid #f0f0f0; padding: 15px 20px;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-trophy text-warning me-2"></i>
                        <h5 class="card-title mb-0" style="font-weight: 600; font-size: 16px;">Top Performing Agents</h5>
                    </div>
                </div>
                <div class="card-body" style="background-color: #fff; padding: 20px;">
                    <div class="row align-items-end justify-content-center" style="min-height: 200px;" id="agentsPodium">
                        <div class="d-flex justify-content-center align-items-center w-100" style="height: 200px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading podium data...</span>
                            </div>
                            <span class="ms-2">Loading top agents...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card" style="border-radius: 10px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);">
                <div class="card-header" style="background: linear-gradient(to right, rgba(91, 115, 232, 0.05), rgba(0, 136, 255, 0.02)); border-bottom: 1px solid rgba(0, 0, 0, 0.05); border-radius: 10px 10px 0 0; padding: 15px 20px;">
                    <h4 class="card-title mb-0">Agent Performance Trends</h4>
                </div>
                <div class="card-body">
                    <div id="performanceChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Best Agents Table -->
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-radius: 10px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);">
                <div class="card-header" style="background: linear-gradient(to right, rgba(91, 115, 232, 0.05), rgba(0, 136, 255, 0.02)); border-bottom: 1px solid rgba(0, 0, 0, 0.05); border-radius: 10px 10px 0 0; padding: 15px 20px;">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0" style="font-weight: 600; color: #333;">
                            <i class="fas fa-user-tie me-2" style="color: #5b73e8;"></i>Top Performing Agents
                        </h4>
                        <div>
                            <input type="text" id="agentSearch" class="form-control form-control-sm" placeholder="Search agents..." style="width: 200px;">
                        </div>
                    </div>
                </div>
        <div class="card-body">
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table id="agentsTable" class="table table-centered table-hover table-striped mb-0">
                            <thead style="position: sticky; top: 0; z-index: 100;">
                                <tr class="table-light">
                                    <th style="font-weight: 600; padding: 12px 15px;">Rank</th>
                                    <th style="font-weight: 600; padding: 12px 15px;">Agent</th>
                                    <th style="font-weight: 600; padding: 12px 15px;">Total Sales</th>
                                    <th style="font-weight: 600; padding: 12px 15px;">Revenue</th>
                                    <th style="font-weight: 600; padding: 12px 15px;">Rating</th>
                                    <th style="font-weight: 600; padding: 12px 15px;">Success Rate</th>
                                    <th style="font-weight: 600; padding: 12px 15px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="d-flex justify-content-center align-items-center py-4">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <span class="ms-2">Loading agent data...</span>
                                        </div>
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
    // Counter Animation for initial load
    initCounters();
    
    // Initialize search functionality for agents table
    document.getElementById('agentSearch').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#agentsTable tbody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
    
    // Initialize Performance Chart
    var performanceChart;
    const initialOptions = {
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

    // Initialize performance chart
    performanceChart = new ApexCharts(document.querySelector("#performanceChart"), initialOptions);
    performanceChart.render();

    // Handle date range selection
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
            fetchDetailedAgentStats(range);
            fetchAgentStatsData(range);
            fetchAgentPerformanceData(range);
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
        fetchDetailedAgentStats(customRange);
        fetchAgentStatsData(customRange);
        fetchAgentPerformanceData(customRange);
    });
    
    // Helper function to format date for input value (YYYY-MM-DD)
    function formatDateForInput(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    // Function to fetch detailed agent stats
    function fetchDetailedAgentStats(range) {
        // Show loading indicator for table
        document.querySelector('#agentsTable tbody').innerHTML = `
            <tr>
                <td colspan="7" class="text-center">
                    <div class="d-flex justify-content-center align-items-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="ms-2">Loading agent data...</span>
                    </div>
                </td>
            </tr>
        `;
        
        // Properly encode the URL
        const url = new URL(`${window.location.origin}/bestagents/detailed-stats`);
        
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
                    console.log('Received detailed agent stats:', data);
                    
                    // Update agents table with detailed stats
                    updateAgentsTableWithDetailedStats(data.data);
                    
                    // We could also update other UI elements with the detailed data
                    updateTopPerformersPodium(data.data.slice(0, 3));
                } else {
                    console.error('Error in response:', data);
                    document.querySelector('#agentsTable tbody').innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="alert alert-danger">Failed to load agent data</div>
                            </td>
                        </tr>
                    `;
                }
            })
            .catch(error => {
                console.error('Error fetching detailed stats:', error);
                document.querySelector('#agentsTable tbody').innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="alert alert-danger">Error loading data</div>
                        </td>
                    </tr>
                `;
            });
    }
    
    // Function to update agents table with detailed stats
    function updateAgentsTableWithDetailedStats(agents) {
        const tableBody = document.querySelector('#agentsTable tbody');
        if (!tableBody) return;
        
        // Clear existing rows
        tableBody.innerHTML = '';
        
        // Check if we have any agent data
        if (!agents || agents.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            No agent data available for the selected period.
                        </div>
                    </td>
                </tr>
            `;
            return;
        }
        
        // Add new rows for each agent
        agents.forEach((agent, index) => {
            // Determine badge style based on rank
            let badgeStyle = '';
            if (index === 0) {
                badgeStyle = 'style="background-color: #FFD700; color: #000;"';
            } else if (index === 1) {
                badgeStyle = 'style="background-color: #C0C0C0; color: #000;"';
            } else if (index === 2) {
                badgeStyle = 'style="background-color: #CD7F32; color: #fff;"';
            }
            
            // Generate star rating HTML
            const fullStars = Math.floor(agent.rating);
            const hasHalfStar = agent.rating % 1 >= 0.5;
            const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
            
            let starsHtml = '';
            for (let i = 0; i < fullStars; i++) {
                starsHtml += '<i class="fas fa-star text-warning"></i>';
            }
            if (hasHalfStar) {
                starsHtml += '<i class="fas fa-star-half-alt text-warning"></i>';
            }
            for (let i = 0; i < emptyStars; i++) {
                starsHtml += '<i class="far fa-star text-warning"></i>';
            }
            
            // Create row HTML with transaction count
            const row = `
                <tr>
                    <td style="padding: 12px 15px;">
                        <span class="badge ${index < 3 ? '' : 'bg-secondary'}" ${badgeStyle}>${index + 1}</span>
                    </td>
                    <td style="padding: 12px 15px;">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="${agent.avatar || '{{ asset("assets/images/users") }}/avatar-' + (index + 1) + '.jpg'}" alt="user" class="rounded-circle" width="40">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0" style="font-size: 14px;">${agent.name}</h6>
                                <small class="text-muted" style="font-size: 12px;">${agent.position || 'Agent'}</small>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 12px 15px;">${agent.transaction_count}</td>
                    <td style="padding: 12px 15px; font-weight: 500;">$${agent.revenue.toLocaleString()}</td>
                    <td style="padding: 12px 15px;">
                        <div class="d-flex align-items-center">
                            <span class="me-2">${agent.rating.toFixed(1)}</span>
                            <div>
                                ${starsHtml}
                            </div>
                        </div>
                    </td>
                    <td style="padding: 12px 15px;">${agent.success_rate}%</td>
                    <td style="padding: 12px 15px;">
                        <span class="badge bg-soft-${agent.status === 'Active' ? 'success' : 'warning'} text-${agent.status === 'Active' ? 'success' : 'warning'}" style="font-weight: 500; padding: 5px 10px; border-radius: 4px;">${agent.status}</span>
                    </td>
                </tr>
            `;
            
            // Add to table
            tableBody.innerHTML += row;
        });
    }
    
    // Function to display agent details modal
    function showAgentDetailsModal(agentId, agents) {
        // Find the agent in our data
        const agent = agents.find(a => a.id == agentId);
        if (!agent) return;
        
        // Create modal HTML
        const modalHtml = `
            <div class="modal fade" id="agentDetailsModal" tabindex="-1" aria-labelledby="agentDetailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="agentDetailsModalLabel">Agent Profile: ${agent.name}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 text-center mb-4">
                                    <img src="${agent.avatar}" alt="${agent.name}" class="rounded-circle img-thumbnail" width="120">
                                    <h5 class="mt-3">${agent.name}</h5>
                                    <p class="text-muted">${agent.position}</p>
                                    <div class="mt-2">
                                        <span class="badge bg-soft-${agent.status === 'Active' ? 'success' : 'warning'} text-${agent.status === 'Active' ? 'success' : 'warning'}" style="font-size: 12px; padding: 6px 12px;">
                                            ${agent.status}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted mb-3">Contact Information</h6>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 text-muted">Email:</div>
                                        <div class="col-sm-8">${agent.email}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 text-muted">Phone:</div>
                                        <div class="col-sm-8">${agent.phone}</div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <h6 class="text-muted mb-3">Performance Metrics</h6>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 text-muted">Total Revenue:</div>
                                        <div class="col-sm-8">$${agent.revenue.toLocaleString()}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 text-muted">Transactions:</div>
                                        <div class="col-sm-8">${agent.transaction_count}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 text-muted">Avg. Transaction:</div>
                                        <div class="col-sm-8">$${agent.avg_transaction_value.toLocaleString()}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 text-muted">Success Rate:</div>
                                        <div class="col-sm-8">${agent.success_rate}%</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 text-muted">Rating:</div>
                                        <div class="col-sm-8">
                                            <span class="me-2">${agent.rating.toFixed(1)}</span>
                                            <span class="text-warning">
                                                ${getStarRatingHtml(agent.rating)}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            ${agent.service_categories && agent.service_categories.length > 0 ? `
                            <hr>
                            <h6 class="text-muted mb-3">Service Categories</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr class="table-light">
                                            <th>Service</th>
                                            <th>Transactions</th>
                                            <th>Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${agent.service_categories.map(cat => `
                                            <tr>
                                                <td>${cat.service || 'Uncategorized'}</td>
                                                <td>${cat.count}</td>
                                                <td>$${Number(cat.revenue).toLocaleString()}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                            ` : ''}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Add modal to document
        const modalContainer = document.createElement('div');
        modalContainer.innerHTML = modalHtml;
        document.body.appendChild(modalContainer);
        
        // Initialize and show the modal
        const modal = new bootstrap.Modal(document.getElementById('agentDetailsModal'));
        modal.show();
        
        // Add event listener to remove modal from DOM when hidden
        document.getElementById('agentDetailsModal').addEventListener('hidden.bs.modal', function() {
            document.body.removeChild(modalContainer);
        });
    }
    
    // Helper function to generate star rating HTML
    function getStarRatingHtml(rating) {
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
        
        let html = '';
        for (let i = 0; i < fullStars; i++) {
            html += '<i class="fas fa-star"></i>';
        }
        if (hasHalfStar) {
            html += '<i class="fas fa-star-half-alt"></i>';
        }
        for (let i = 0; i < emptyStars; i++) {
            html += '<i class="far fa-star text-warning"></i>';
        }
        
        return html;
    }
    
    // Function to update podium with detailed agent info
    function updateTopPerformersPodium(topAgents) {
        const podiumContainer = document.getElementById('agentsPodium');
        if (!podiumContainer) return;
        
        // Clear any existing content
        podiumContainer.innerHTML = '';
        
        // Check if we have enough agents data
        if (!topAgents || topAgents.length === 0) {
            podiumContainer.innerHTML = `
                <div class="col-12 text-center py-4">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        No agent data available for the selected period.
                    </div>
                </div>
            `;
            return;
        }
        
        // Create the podium elements
        const placementOrder = [2, 1, 3]; // The visual order: 2nd place, 1st place, 3rd place
        
        const podiumData = [
            {
                position: 2,
                label: 'SILVER',
                bgColor: '#f8f8f8',
                badgeColor: '#C0C0C0'
            },
            {
                position: 1,
                label: 'GOLD',
                bgColor: '#ffcf33',
                badgeColor: '#FFD700',
                hasCrown: true
            },
            {
                position: 3,
                label: 'BRONZE',
                bgColor: '#f8f8f8',
                badgeColor: '#CD7F32'
            }
        ];
        
        // Create HTML for each position
        for (let i = 0; i < placementOrder.length; i++) {
            const position = placementOrder[i];
            const config = podiumData[i];
            const agentIndex = position - 1;
            const agent = agentIndex < topAgents.length ? topAgents[agentIndex] : null;
            
            const colElement = document.createElement('div');
            colElement.className = `col-md-4 col-sm-12 text-center order-md-${i+1} ${position === 1 ? 'order-1' : `order-${position}`}`;
            
            // Create the HTML content based on whether we have data for this position
            if (agent) {
                // Format agent data
                const agentName = agent.name || `Agent ${position}`;
                const revenue = agent.revenue ? agent.revenue.toLocaleString() : 0;
                const transactionCount = agent.transaction_count || agent.sales || agent.total_sales || 0;
                
                // Properly handle profile image path
                let avatarUrl;
                if (agent.avatar && agent.avatar.includes('profile-images')) {
                    // Use the provided avatar path directly
                    avatarUrl = agent.avatar;
                } else if (agent.avatar) {
                    // Use whatever avatar was provided
                    avatarUrl = agent.avatar;
                } else {
                    // Fallback to no image
                    avatarUrl = `{{ asset('assets/images/users/noimage.jpg') }}`;
                }
                
                // Create star rating HTML
                let starsHtml = '';
                const rating = agent.rating || 0;
                const fullStars = Math.floor(rating);
                const hasHalfStar = rating % 1 >= 0.5;
                const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
                
                for (let i = 0; i < fullStars; i++) {
                    starsHtml += '<i class="fas fa-star text-warning"></i>';
                }
                if (hasHalfStar) {
                    starsHtml += '<i class="fas fa-star-half-alt text-warning"></i>';
                }
                for (let i = 0; i < emptyStars; i++) {
                    starsHtml += '<i class="far fa-star text-warning"></i>';
                }
                
                colElement.innerHTML = `
                    <div class="agent-card" style="background-color: ${config.bgColor}; border-radius: 8px; height: 100%; position: relative; padding: 15px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        ${position === 1 ? `
                            <div style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%);">
                                <i class="fas fa-crown" style="color: #FFD700; font-size: 24px;"></i>
                            </div>
                        ` : ''}
                        
                        <div class="position-badge" style="position: absolute; top: 10px; right: 10px; background-color: white; color: #333; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            ${position}
                        </div>
                        
                        ${config.label ? `
                            <div style="position: absolute; top: 10px; left: 0; background-color: ${config.badgeColor}; color: ${position === 1 ? '#333' : '#fff'}; padding: 2px 8px; font-size: 10px; font-weight: bold;">
                                ${config.label}
                            </div>
                        ` : ''}
                        
                        <div style="padding-top: ${position === 1 ? '25px' : '15px'}; padding-bottom: 10px;">
                            <div style="width: 70px; height: 70px; margin: 0 auto; border-radius: 50%; background-color: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                <img src="${avatarUrl}" alt="${agentName}" class="rounded-circle" width="60" height="60" style="object-fit: cover;">
                            </div>
                            
                            <h5 style="margin-top: 15px; font-size: 16px; font-weight: 600;">${agentName}</h5>
                            
                            <div style="font-weight: bold; font-size: 18px; margin-top: 5px;">$${revenue}</div>
                            <div style="font-size: 12px; opacity: 0.7;">${transactionCount} transactions</div>
                            
                            ${starsHtml ? `<div style="margin-top: 8px;">${starsHtml}</div>` : ''}
                        </div>
                    </div>
                `;
            } else {
                // No data for this position
                colElement.innerHTML = `
                    <div class="agent-card" style="background-color: ${config.bgColor}; border-radius: 8px; height: 100%; position: relative; padding: 15px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        ${config.label ? `
                            <div style="position: absolute; top: 10px; left: 0; background-color: ${config.badgeColor}; color: ${position === 1 ? '#333' : '#fff'}; padding: 2px 8px; font-size: 10px; font-weight: bold;">
                                ${config.label}
                            </div>
                        ` : ''}
                        
                        <div class="position-badge" style="position: absolute; top: 10px; right: 10px; background-color: white; color: #333; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            ${position}
                        </div>
                        
                        <div style="padding-top: 15px; padding-bottom: 10px;">
                            <div style="width: 70px; height: 70px; margin: 0 auto; border-radius: 50%; background-color: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                <img src="{{ asset('assets/images/users/noimage.jpg') }}" alt="No data" class="rounded-circle" width="60" height="60" style="object-fit: cover; opacity: 0.5;">
                            </div>
                            
                            <h5 style="margin-top: 15px; font-size: 16px; font-weight: 600; color: #999;">No data</h5>
                            
                            <div style="font-weight: bold; font-size: 18px; margin-top: 5px; color: #999;">--</div>
                            <div style="font-size: 12px; opacity: 0.7;">0 transactions</div>
                        </div>
                    </div>
                `;
            }
            
            podiumContainer.appendChild(colElement);
        }
    }
    
    // Function to initialize counter animations
    function initCounters() {
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
    }
    
    // Function to fetch agent stats data
    function fetchAgentStatsData(range) {
        // Add loading indicators to all stat cards
        document.querySelectorAll('.card-h-100 .card-body').forEach(card => {
            card.innerHTML = '<div class="d-flex justify-content-center align-items-center" style="height: 120px;"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        });
        
        // Fetch data for the stats
        const url = new URL(`${window.location.origin}/bestagents/stats-data`);
        
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
                    updateStatsCards(data.stats);
                    // Also update the podium with top 3 agents
                    if (data.topAgents && data.topAgents.length >= 3) {
                        updatePodium(data.topAgents);
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
        // Total Agents
        updateStatCard(
            'totalAgents', 
            stats.totalAgents.value,
            stats.totalAgents.percentChange,
            stats.totalAgents.isPositive,
            stats.totalAgents.difference,
            false // not currency
        );
        
        // Total Sales
        updateStatCard(
            'totalSales', 
            stats.totalSales.value,
            stats.totalSales.percentChange,
            stats.totalSales.isPositive,
            stats.totalSales.difference,
            true // is currency
        );
        
        // Average Rating
        updateStatCard(
            'averageRating', 
            stats.averageRating.value,
            stats.averageRating.percentChange,
            stats.averageRating.isPositive,
            stats.averageRating.difference,
            false // not currency
        );
        
        // Success Rate
        updateStatCard(
            'successRate', 
            stats.successRate.value,
            stats.successRate.percentChange,
            stats.successRate.isPositive,
            stats.successRate.difference,
            false // not currency
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
                        ${isCurrency ? '$' : ''}${id === 'successRate' ? '<span class="counter-value" data-target="' + value + '">0</span>%' : 
                          '<span class="counter-value" data-target="' + value + '">0</span>'}
                        <span class="text-${colorClass} ms-2 fs-12">
                            <i class="fas fa-arrow-${direction} me-1"></i>${formattedPercent}%
                        </span>
                    </h4>
                    <div class="text-nowrap">
                        <span class="badge bg-soft-${colorClass} text-${colorClass}">
                            ${isPositive ? '+' : ''}${isCurrency ? '$' : ''}${formattedDiff}${id === 'totalSales' ? 'k' : (id === 'successRate' ? '%' : '')}
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
    
    // Function to update the podium with top agents
    function updatePodium(topAgents) {
        // Simply call our new universal podium update function
        updateTopPerformersPodium(topAgents);
    }
    
    // Function to get the title for a stat card
    function getStatTitle(id) {
        const titles = {
            totalAgents: 'Total Agents',
            totalSales: 'Total Sales',
            averageRating: 'Average Rating',
            successRate: 'Success Rate'
        };
        return titles[id] || id;
    }
    
    // Function to get the icon class for a stat card
    function getIconClass(id) {
        const icons = {
            totalAgents: 'fas fa-users',
            totalSales: 'fas fa-chart-line',
            averageRating: 'fas fa-star',
            successRate: 'fas fa-check-circle'
        };
        return icons[id] || 'fas fa-chart-bar';
    }
    
    // Function to get the icon color class for a stat card
    function getIconColorClass(id) {
        const colors = {
            totalAgents: 'primary',
            totalSales: 'success',
            averageRating: 'warning',
            successRate: 'info'
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
        
        const target = parseFloat(counterElement.getAttribute('data-target'));
        const duration = 2000; // 2 seconds
        const step = target / (duration / 16); // 60fps
        let current = 0;

        const updateCounter = () => {
            current += step;
            if (current < target) {
                counterElement.textContent = Number.isInteger(target) ? 
                    Math.floor(current).toLocaleString() : 
                    current.toFixed(1);
                requestAnimationFrame(updateCounter);
            } else {
                counterElement.textContent = Number.isInteger(target) ? 
                    target.toLocaleString() : 
                    target.toFixed(1);
            }
        };

        updateCounter();
    }
    
    // Function to fetch agent performance data
    function fetchAgentPerformanceData(range) {
        // Show loading indicator for performance chart
        document.getElementById('performanceChart').innerHTML = '<div class="d-flex justify-content-center align-items-center" style="height: 350px;"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        
        // Properly encode the URL
        const url = new URL(`${window.location.origin}/bestagents/performance-data`);
        
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
                    
                    // Update performance chart
                    updatePerformanceChart(data.performance);
                    
                    // Update agents table
                    if (data.agents && data.agents.length > 0) {
                        updateAgentsTable(data.agents);
                    }
                } else {
                    console.error('Error in response:', data);
                    document.getElementById('performanceChart').innerHTML = '<div class="alert alert-danger">Failed to load chart data</div>';
                }
            })
            .catch(error => {
                console.error('Error fetching performance data:', error);
                document.getElementById('performanceChart').innerHTML = '<div class="alert alert-danger">Error loading data</div>';
            });
    }
    
    // Function to update performance chart
    function updatePerformanceChart(performanceData) {
        // Clean up the chart container
        document.getElementById('performanceChart').innerHTML = '';
        
        // Completely destroy the existing chart if it exists
        if (performanceChart) {
            performanceChart.destroy();
        }
        
        // Create fresh chart options
        const freshOptions = {
            series: performanceData.series,
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
                type: 'category',
                categories: performanceData.dates
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
            },
        };
        
        // Create a completely new chart
        setTimeout(() => {
            performanceChart = new ApexCharts(document.querySelector("#performanceChart"), freshOptions);
            performanceChart.render();
        }, 100);
    }
    
    // Function to update agents table
    function updateAgentsTable(agents) {
        const tableBody = document.querySelector('#agentsTable tbody');
        if (!tableBody) return;
        
        // Clear existing rows
        tableBody.innerHTML = '';
        
        // Add new rows for each agent
        agents.forEach((agent, index) => {
            // Determine badge style based on rank
            let badgeStyle = '';
            if (index === 0) {
                badgeStyle = 'style="background-color: #FFD700; color: #000;"';
            } else if (index === 1) {
                badgeStyle = 'style="background-color: #C0C0C0; color: #000;"';
            } else if (index === 2) {
                badgeStyle = 'style="background-color: #CD7F32; color: #fff;"';
            }
            
            // Generate star rating HTML
            const fullStars = Math.floor(agent.rating);
            const hasHalfStar = agent.rating % 1 >= 0.5;
            const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
            
            let starsHtml = '';
            for (let i = 0; i < fullStars; i++) {
                starsHtml += '<i class="fas fa-star text-warning"></i>';
            }
            if (hasHalfStar) {
                starsHtml += '<i class="fas fa-star-half-alt text-warning"></i>';
            }
            for (let i = 0; i < emptyStars; i++) {
                starsHtml += '<i class="far fa-star text-warning"></i>';
            }
            
            // Create row HTML
            const row = `
                <tr>
                    <td style="padding: 12px 15px;">
                        <span class="badge ${index < 3 ? '' : 'bg-secondary'}" ${badgeStyle}>${index + 1}</span>
                    </td>
                    <td style="padding: 12px 15px;">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="${agent.avatar || '{{ asset("assets/images/users") }}/avatar-' + (index + 1) + '.jpg'}" alt="user" class="rounded-circle" width="40">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0" style="font-size: 14px;">${agent.name}</h6>
                                <small class="text-muted" style="font-size: 12px;">${agent.position || 'Agent'}</small>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 12px 15px;">${agent.sales}</td>
                    <td style="padding: 12px 15px; font-weight: 500;">$${agent.revenue.toLocaleString()}</td>
                    <td style="padding: 12px 15px;">
                        <div class="d-flex align-items-center">
                            <span class="me-2">${agent.rating.toFixed(1)}</span>
                            <div>
                                ${starsHtml}
                            </div>
                        </div>
                    </td>
                    <td style="padding: 12px 15px;">${agent.successRate}%</td>
                    <td style="padding: 12px 15px;">
                        <span class="badge bg-soft-${agent.status === 'Active' ? 'success' : 'warning'} text-${agent.status === 'Active' ? 'success' : 'warning'}" style="font-weight: 500; padding: 5px 10px; border-radius: 4px;">${agent.status}</span>
                    </td>
                </tr>
            `;
            
            // Add to table
            tableBody.innerHTML += row;
        });
    }
    
    // Initialize the page
    const initialRange = document.getElementById('statsRange').value;
    fetchDetailedAgentStats(initialRange);
    fetchAgentStatsData(initialRange);
    fetchAgentPerformanceData(initialRange);
    
    // Export Button
    document.getElementById('exportBtn').addEventListener('click', function() {
        window.location.href = "{{ route('bestagents.export') }}";
    });
});
</script>
@endsection