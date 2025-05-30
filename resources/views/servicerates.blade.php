@extends('includes.app')

@section('title', 'Service Rates')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Service Rates</h4>
                <div>
                    <button id="editRates" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Edit Rates
                    </button>
                    <button id="saveRates" class="btn btn-success d-none">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                    <button id="cancelEdit" class="btn btn-secondary d-none">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                </div>
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
                                                @foreach($serviceRates->where('service_type', 'delivery') as $rate)
                                                <tr data-id="{{ $rate->id }}">
                                                    <td>
                                                        <span class="rate-display">{{ $rate->value_range }}</span>
                                                        <input type="text" class="form-control rate-edit d-none" 
                                                            name="value_range" value="{{ $rate->value_range }}">
                                                    </td>
                                                    <td>
                                                        <span class="rate-display">
                                                            <span class="badge bg-{{ $rate->badge_color }}">${{ number_format($rate->rate, 2) }}</span>
                                                            {{ $rate->description }}
                                                        </span>
                                                        <div class="input-group rate-edit d-none">
                                                            <span class="input-group-text">$</span>
                                                            <input type="number" class="form-control" name="rate" 
                                                                value="{{ $rate->rate }}" step="0.01" min="0">
                                                            <input type="text" class="form-control" name="description" 
                                                                value="{{ $rate->description }}">
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Mileage Rate Card -->
                        @if($serviceRates->where('service_type', 'delivery_mileage')->first())
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0"><i class="fas fa-road me-2"></i>Delivery Mileage Rate</h5>
                                </div>
                                <div class="card-body">
                                    @php $mileageRate = $serviceRates->where('service_type', 'delivery_mileage')->first(); @endphp
                                    <div class="d-flex align-items-center" data-id="{{ $mileageRate->id }}">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle bg-secondary bg-opacity-10 text-center">
                                                <i class="fas fa-road fa-2x text-secondary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-1">
                                                <span class="rate-display">${{ number_format($mileageRate->rate, 2) }}/mile</span>
                                                <div class="input-group rate-edit d-none">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" class="form-control" name="rate" 
                                                        value="{{ $mileageRate->rate }}" step="0.01" min="0">
                                                </div>
                                            </h4>
                                            <p class="text-muted mb-0">
                                                <span class="rate-display">{{ $mileageRate->description }}</span>
                                                <input type="text" class="form-control rate-edit d-none" 
                                                    name="description" value="{{ $mileageRate->description }}">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Furniture Removal Card -->
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-couch me-2"></i>Furniture Removal</h5>
                                </div>
                                <div class="card-body">
                                    @foreach($serviceRates->where('service_type', 'furniture_removal') as $rate)
                                    <div class="d-flex align-items-center mb-3" data-id="{{ $rate->id }}">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle bg-success bg-opacity-10 text-center">
                                                <i class="fas fa-dollar-sign fa-2x text-success"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-1">
                                                <span class="rate-display">${{ number_format($rate->rate, 2) }}</span>
                                                <div class="input-group rate-edit d-none">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" class="form-control" name="rate" 
                                                        value="{{ $rate->rate }}" step="0.01" min="0">
                                                </div>
                                            </h4>
                                            <p class="text-muted mb-0">
                                                <span class="rate-display">{{ $rate->description }}</span>
                                                <input type="text" class="form-control rate-edit d-none" 
                                                    name="description" value="{{ $rate->description }}">
                                            </p>
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                    <div class="alert alert-info">
                                        @foreach($serviceRates->where('service_type', 'furniture_removal_additional') as $rate)
                                        <div data-id="{{ $rate->id }}">
                                            <i class="fas fa-info-circle me-2"></i> 
                                            <span class="rate-display">{{ $rate->description }}: <strong>${{ number_format($rate->rate, 2) }}</strong></span>
                                            <div class="input-group mt-2 rate-edit d-none">
                                                <input type="text" class="form-control" name="description" 
                                                    value="{{ $rate->description }}">
                                                <span class="input-group-text">$</span>
                                                <input type="number" class="form-control" name="rate" 
                                                    value="{{ $rate->rate }}" step="0.01" min="0">
                                            </div>
                                        </div>
                                        @endforeach
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
                                    @if($serviceRates->where('service_type', 'moving')->isNotEmpty())
                                    <div class="alert alert-warning mb-3">
                                        <i class="fas fa-clock me-2"></i> 
                                        <span class="rate-display">{{ $serviceRates->where('service_type', 'moving')->first()->description }}</span>
                                        <input type="text" class="form-control rate-edit d-none mt-2" 
                                            name="description" data-id="{{ $serviceRates->where('service_type', 'moving')->first()->id }}"
                                            value="{{ $serviceRates->where('service_type', 'moving')->first()->description }}">
                                    </div>
                                    @endif
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Crew Size</th>
                                                    <th>Rate</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($serviceRates->where('service_type', 'moving') as $rate)
                                                <tr data-id="{{ $rate->id }}">
                                                    <td>
                                                        <span class="rate-display">{{ $rate->category }}</span>
                                                        <input type="text" class="form-control rate-edit d-none" 
                                                            name="category" value="{{ $rate->category }}">
                                                    </td>
                                                    <td>
                                                        <span class="rate-display">
                                                            <span class="badge bg-{{ $rate->badge_color }}">${{ number_format($rate->rate, 2) }}</span>
                                                        </span>
                                                        <div class="input-group rate-edit d-none">
                                                            <span class="input-group-text">$</span>
                                                            <input type="number" class="form-control" name="rate" 
                                                                value="{{ $rate->rate }}" step="0.01" min="0">
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Other Services Cards -->
                        @foreach($serviceRates->whereIn('service_type', ['cleaning', 'rearranging', 'mattress_removal', 'hoisting', 'exterminator', 'college_room_move', 'removal']) as $rate)
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-{{ $rate->badge_color }} @if($rate->badge_color != 'warning') text-white @else text-dark @endif">
                                    <h5 class="mb-0"><i class="{{ $rate->icon }} me-2"></i>{{ $rate->title }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center" data-id="{{ $rate->id }}">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle bg-{{ $rate->badge_color }} bg-opacity-10 text-center">
                                                <i class="fas fa-clock fa-2x text-{{ $rate->badge_color }}"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-1">
                                                <span class="rate-display">${{ number_format($rate->rate, 2) }}{{ $rate->unit === 'hourly' ? '/H' : '' }}</span>
                                                <div class="input-group rate-edit d-none">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" class="form-control" name="rate" 
                                                        value="{{ $rate->rate }}" step="0.01" min="0">
                                                </div>
                                            </h4>
                                            <p class="text-muted mb-0">
                                                <span class="rate-display">{{ $rate->description }}</span>
                                                <input type="text" class="form-control rate-edit d-none" 
                                                    name="description" value="{{ $rate->description }}">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
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
        // Edit functionality
        const editBtn = document.getElementById('editRates');
        const saveBtn = document.getElementById('saveRates');
        const cancelBtn = document.getElementById('cancelEdit');
        
        if (editBtn && saveBtn && cancelBtn) {
            editBtn.addEventListener('click', function() {
                editBtn.classList.add('d-none');
                saveBtn.classList.remove('d-none');
                cancelBtn.classList.remove('d-none');
                
                // Show all edit fields
                document.querySelectorAll('.rate-display').forEach(function(el) {
                    el.classList.add('d-none');
                });
                
                document.querySelectorAll('.rate-edit').forEach(function(el) {
                    el.classList.remove('d-none');
                });
            });
            
            cancelBtn.addEventListener('click', function() {
                editBtn.classList.remove('d-none');
                saveBtn.classList.add('d-none');
                cancelBtn.classList.add('d-none');
                
                // Hide all edit fields
                document.querySelectorAll('.rate-display').forEach(function(el) {
                    el.classList.remove('d-none');
                });
                
                document.querySelectorAll('.rate-edit').forEach(function(el) {
                    el.classList.add('d-none');
                });
            });
            
            saveBtn.addEventListener('click', function() {
                // Show loading state
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
                saveBtn.disabled = true;
                
                const formData = {};
                
                // Collect all the data from input fields
                document.querySelectorAll('[data-id]').forEach(function(row) {
                    const id = row.dataset.id;
                    const data = {};
                    
                    row.querySelectorAll('input[name]').forEach(function(input) {
                        data[input.name] = input.value;
                    });
                    
                    formData[id] = data;
                });
                
                // Send the data to the server
                fetch('{{ route('servicerates.update-batch') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        // Show error message
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || 'Something went wrong',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                })
                .finally(() => {
                    // Reset button state
                    saveBtn.innerHTML = '<i class="fas fa-save me-1"></i> Save Changes';
                    saveBtn.disabled = false;
                });
            });
        }
        
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