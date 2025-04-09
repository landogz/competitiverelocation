@extends('includes.app')

@section('title', 'Service Rates')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Service Rates</h4>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div><!--end row-->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Our Service Rates</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Delivery Service Card -->
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Delivery Service</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Value Range</th>
                                                    <th>Rate</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Less than $750</td>
                                                    <td><span class="badge bg-primary">$79.99</span> light assembly</td>
                                                </tr>
                                                <tr>
                                                    <td>$750 - Less than $1500</td>
                                                    <td><span class="badge bg-primary">$159.99</span> light assembly</td>
                                                </tr>
                                                <tr>
                                                    <td>$1500 - Less than $2000</td>
                                                    <td><span class="badge bg-primary">$179.99</span> light assembly</td>
                                                </tr>
                                                <tr>
                                                    <td>$2000 - Less than $2750</td>
                                                    <td><span class="badge bg-primary">$194.99</span> light assembly</td>
                                                </tr>
                                                <tr>
                                                    <td>$2750 - Less than $4000</td>
                                                    <td><span class="badge bg-primary">$224.99</span> light assembly</td>
                                                </tr>
                                                <tr>
                                                    <td>$4000 - Less than $6000</td>
                                                    <td><span class="badge bg-primary">$284.99</span> light assembly</td>
                                                </tr>
                                                <tr>
                                                    <td>$6000 - Less than $8000</td>
                                                    <td><span class="badge bg-primary">$314.99</span> light assembly</td>
                                                </tr>
                                                <tr>
                                                    <td>$8000 - Less than $10000</td>
                                                    <td><span class="badge bg-primary">$344.99</span> light assembly</td>
                                                </tr>
                                                <tr>
                                                    <td>$10000 and more</td>
                                                    <td><span class="badge bg-primary">$384.99</span> light assembly</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Furniture Removal Card -->
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-couch me-2"></i>Furniture Removal</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle bg-success bg-opacity-10 text-center">
                                                <i class="fas fa-dollar-sign fa-2x text-success"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-1">$175.00</h4>
                                            <p class="text-muted mb-0">Base rate for furniture removal</p>
                                        </div>
                                    </div>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i> Any additional items: <strong>$75.00</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Moving Service Card -->
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="fas fa-people-carry me-2"></i>Moving Service</h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-warning mb-3">
                                        <i class="fas fa-clock me-2"></i> 3 hours minimum (2 hours labor, 1 hour travel)
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Crew Size</th>
                                                    <th>Rate</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>2 men</td>
                                                    <td><span class="badge bg-info">$270.00</span></td>
                                                </tr>
                                                <tr>
                                                    <td>3 men</td>
                                                    <td><span class="badge bg-info">$465.00</span></td>
                                                </tr>
                                                <tr>
                                                    <td>4 men</td>
                                                    <td><span class="badge bg-info">$465.00</span></td>
                                                </tr>
                                                <tr>
                                                    <td>5 men</td>
                                                    <td><span class="badge bg-info">$465.00</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Other Services Card -->
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0"><i class="fas fa-broom me-2"></i>Cleaning Service</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle bg-warning bg-opacity-10 text-center">
                                                <i class="fas fa-clock fa-2x text-warning"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-1">$135/H</h4>
                                            <p class="text-muted mb-0">Professional cleaning service</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rearranging Card -->
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Rearranging</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle bg-danger bg-opacity-10 text-center">
                                                <i class="fas fa-clock fa-2x text-danger"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-1">$150/H</h4>
                                            <p class="text-muted mb-0">Professional rearranging service</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mattress Removal Card -->
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0"><i class="fas fa-bed me-2"></i>Mattress Removal</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle bg-secondary bg-opacity-10 text-center">
                                                <i class="fas fa-clock fa-2x text-secondary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-1">$125/H</h4>
                                            <p class="text-muted mb-0">Professional mattress removal service</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hoisting Card -->
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-dark text-white">
                                    <h5 class="mb-0"><i class="fas fa-crane me-2"></i>Hoisting</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle bg-dark bg-opacity-10 text-center">
                                                <i class="fas fa-clock fa-2x text-dark"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-1">$350/H</h4>
                                            <p class="text-muted mb-0">Professional hoisting service</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Exterminator Card -->
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-purple text-white">
                                    <h5 class="mb-0"><i class="fas fa-bug me-2"></i>Exterminator Service</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle bg-purple bg-opacity-10 text-center">
                                                <i class="fas fa-clock fa-2x text-purple"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-1">$650/H</h4>
                                            <p class="text-muted mb-0">Exterminator, washing and replacing moving blankets</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted mb-0"><small><i class="fas fa-info-circle me-1"></i> All rates are subject to change. Contact us for current pricing.</small></p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <button class="btn btn-primary btn-sm" id="contactUs">
                                <i class="fas fa-envelope me-1"></i> Contact Us
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-purple {
        background-color: #6f42c1;
    }
    .text-purple {
        color: #6f42c1;
    }
    .avatar-sm {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .badge {
        font-size: 0.9rem;
        padding: 0.5em 0.8em;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('searchRates');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const cards = document.querySelectorAll('.card');
                
                cards.forEach(card => {
                    const cardText = card.textContent.toLowerCase();
                    if (cardText.includes(searchTerm)) {
                        card.closest('.col-lg-6').style.display = '';
                    } else {
                        card.closest('.col-lg-6').style.display = 'none';
                    }
                });
            });
        }
        
        // Print functionality
        const printBtn = document.getElementById('printRates');
        if (printBtn) {
            printBtn.addEventListener('click', function() {
                window.print();
            });
        }
        
        // Download functionality
        const downloadBtn = document.getElementById('downloadRates');
        if (downloadBtn) {
            downloadBtn.addEventListener('click', function() {
                alert('Download functionality will be implemented here');
            });
        }
        
        // Contact Us functionality
        const contactBtn = document.getElementById('contactUs');
        if (contactBtn) {
            contactBtn.addEventListener('click', function() {
                window.location.href = '/contact';
            });
        }
    });
</script>

@endsection