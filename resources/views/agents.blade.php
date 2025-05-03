@extends('includes.app')

@section('title', 'Dashboard')

@section('content')

<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<style>
    table.dataTable td.dt-control:before {
    content: "";
}
table.dataTable tr.dt-hasChild td.dt-control:before {
    content: "";
}
.badge-yes {
    background-color: #28a745;
    color: white;
}
.badge-no {
    background-color: #dc3545;
    color: white;
}

/* Clickable contact info styles */
.contact-link {
    color: var(--primary-color);
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.contact-link:hover {
    color: var(--primary-hover);
    text-decoration: underline;
}

.contact-link i {
    font-size: 0.9rem;
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Agents List</h4>                               
                <button type="button" class="btn btn-primary" id="syncButton">
                    <i class="fas fa-sync-alt"></i> Sync Now
                </button>
            </div>
        </div>
    </div>
    <div class="row">                        
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">List of Agents</h4>                      
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                        <table id="agentsTable" class="table table-bordered mb-0 table-centered">
                        <thead>
                            <tr>
                                <th style="width: 50px;"></th>
                                    <th>Company</th>
                                    <th>Contact</th>
                                    <th>Location</th>
                                    <th>Services</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                            </tr>
                        </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Agent Modal -->
<div class="modal fade" id="editAgentModal" tabindex="-1" aria-labelledby="editAgentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editAgentModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Agent
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editAgentForm">
                    <input type="hidden" id="agent_id" name="agent_id">
                    <div class="row g-4">
                        <!-- Company Information -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-building me-2"></i>Company Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="company_name" name="company_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="company_website" class="form-label">Company Website</label>
                                        <input type="url" class="form-control" id="company_website" name="company_website">
                                    </div>
                                    <div class="mb-3">
                                        <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select" id="is_active" name="is_active" required>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-address-card me-2"></i>Contact Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="contact_name" class="form-label">Contact Name</label>
                                            <input type="text" class="form-control" id="contact_name" name="contact_name">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="contact_title" class="form-label">Contact Title</label>
                                            <input type="text" class="form-control" id="contact_title" name="contact_title">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone_number" class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" id="phone_number" name="phone_number">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-map-marker-alt me-2"></i>Address Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="address" class="form-label">Address</label>
                                            <input type="text" class="form-control" id="address" name="address">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="city" class="form-label">City</label>
                                            <input type="text" class="form-control" id="city" name="city">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="state" class="form-label">State</label>
                                            <input type="text" class="form-control" id="state" name="state">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="zip_code" class="form-label">ZIP Code</label>
                                            <input type="text" class="form-control" id="zip_code" name="zip_code">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Business Details -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-truck me-2"></i>Business Details
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="num_trucks" class="form-label">Number of Trucks</label>
                                            <input type="number" class="form-control" id="num_trucks" name="num_trucks" min="0">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="truck_size" class="form-label">Truck Size</label>
                                            <input type="text" class="form-control" id="truck_size" name="truck_size">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="num_crews" class="form-label">Number of Crews</label>
                                            <input type="number" class="form-control" id="num_crews" name="num_crews" min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sales Information -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-chart-line me-2"></i>Sales Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="corporate_sales" class="form-label">Corporate Sales</label>
                                            <input type="number" class="form-control" id="corporate_sales" name="corporate_sales" min="0">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="consumer_sales" class="form-label">Consumer Sales</label>
                                            <input type="number" class="form-control" id="consumer_sales" name="consumer_sales" min="0">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="local_sales" class="form-label">Local Sales</label>
                                            <input type="number" class="form-control" id="local_sales" name="local_sales" min="0">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="long_distance_sales" class="form-label">Long Distance Sales</label>
                                            <input type="number" class="form-control" id="long_distance_sales" name="long_distance_sales" min="0">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="delivery_service_sales" class="form-label">Delivery Service Sales</label>
                                            <input type="number" class="form-control" id="delivery_service_sales" name="delivery_service_sales" min="0">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_sales" class="form-label">Total Sales</label>
                                            <input type="number" class="form-control" id="total_sales" name="total_sales" min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
                <button type="button" class="btn btn-primary" id="saveAgentChanges">
                    <i class="fas fa-save me-1"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    var table = $('#agentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('agents.data') }}",
            type: "GET",
            data: function(d) {
                // Add any additional parameters you want to send with the request
                return d;
            }
        },
        columns: [
            {
                className: 'dt-control',
                orderable: false,
                data: null,
                defaultContent: '<button class="btn btn-sm btn-primary">+</button>'
            },
            { 
                data: 'company_name',
                name: 'company_name',
                render: function(data, type, row) {
                    if (type === 'display') {
                        // Format the website URL properly
                        let websiteUrl = row.company_website || '';
                        
                        // Remove any localhost prefix
                        websiteUrl = websiteUrl.replace(/^https?:\/\/127\.0\.0\.1:8000\//, '');
                        
                        // Ensure the URL has a proper protocol
                        if (websiteUrl && !websiteUrl.match(/^https?:\/\//)) {
                            websiteUrl = 'https://' + websiteUrl;
                        }
                        
                        // Clean up any double slashes except after protocol
                        websiteUrl = websiteUrl.replace(/([^:])\/\//g, '$1/');
                        
                        return `<strong>${data}</strong><br>
                                <small><i class="fas fa-globe"></i> ${websiteUrl ? `<a href="${websiteUrl}" target="_blank" rel="noopener noreferrer" class="contact-link">${websiteUrl.replace(/^https?:\/\//, '')}</a>` : 'N/A'}</small>`;
                    }
                    return data; // Return raw data for sorting and searching
                }
            },
            { 
                data: 'contact_name',
                name: 'contact_name',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return `${data || ''}<br>
                                <small>${row.contact_title || ''}</small>`;
                    }
                    return data; // Return raw data for sorting and searching
                }
            },
            { 
                data: 'city',
                name: 'city',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return `${data || ''}, ${row.state || ''}<br>
                                <small>${row.zip_code || ''}</small>`;
                    }
                    return data; // Return raw data for sorting and searching
                }
            },
            {
                data: 'services',
                name: 'services',
                render: function(data, type, row) {
                    if (type === 'display') {
                        if (data && typeof data === 'object') {
                            const labelMap = {
                                labor: 'Labor',
                                delivery: 'Delivery',
                                commercial: 'Commercial',
                                local_moving: 'Local Moving',
                                booking_agent: 'Booking Agent',
                                general_freight: 'General Freight'
                            };
                            const enabled = Object.keys(data)
                                .filter(key => data[key])
                                .map(key => `<span class='badge bg-primary me-1'>${labelMap[key] || key}</span>`);
                            
                            // Group spans into pairs
                            const groupedSpans = [];
                            for (let i = 0; i < enabled.length; i += 2) {
                                const pair = enabled.slice(i, i + 2);
                                groupedSpans.push(pair.join(' '));
                            }
                            
                            return groupedSpans.length ? 
                                groupedSpans.join('<br>') : 
                                'No services';
                        }
                        return 'No services';
                    }
                    return '';
                }
            },
            {
                data: 'status',
                name: 'is_active',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return data ? 
                            '<span class="badge badge-yes">Active</span>' : 
                            '<span class="badge badge-no">Inactive</span>';
                    }
                    return data ? 'Active' : 'Inactive'; // For searching
                }
            },
            {
                data: 'id',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `<div class="btn-group">
                            <button class="btn btn-sm btn-primary edit-agent" data-id="${data}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <a href="${row.unique_url}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Open Delivery System URL">
                                <i class="fas fa-external-link-alt"></i> Open URL
                            </a>
                            <button type="button" class="btn btn-sm btn-secondary copy-url" data-url="${row.unique_url}" data-bs-toggle="tooltip" data-bs-placement="top" title="Copy Delivery System URL">
                                <i class="fas fa-copy"></i> Copy URL
                            </button>
                        </div>`;
                }
            }
        ],
        order: [[1, 'asc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        search: {
            smart: true,
            regex: true,
            caseInsensitive: true
        }
    });

    function format(d) {
            return `
            <div class="p-3">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Contact Information</h5>
                        <table class="table table-sm">
                            <tr>
                                <th>Email:</th>
                                <td><a href="mailto:${d.email}" class="contact-link"><i class="fas fa-envelope"></i> ${d.email}</a></td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td><a href="tel:${d.phone_number}" class="contact-link"><i class="fas fa-phone"></i> ${d.phone_number}</a></td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td>${d.address}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Business Details</h5>
                        <table class="table table-sm">
                            <tr>
                                <th>Total Sales:</th>
                                <td>$${Number(d.total_sales).toLocaleString()}</td>
                            </tr>
                            <tr>
                                <th>Corporate Sales:</th>
                                <td>$${Number(d.corporate_sales).toLocaleString()}</td>
                            </tr>
                            <tr>
                                <th>Consumer Sales:</th>
                                <td>$${Number(d.consumer_sales).toLocaleString()}</td>
                            </tr>
                            <tr>
                                <th>Local Sales:</th>
                                <td>$${Number(d.local_sales).toLocaleString()}</td>
                            </tr>
                            <tr>
                                <th>Long Distance Sales:</th>
                                <td>$${Number(d.long_distance_sales).toLocaleString()}</td>
                            </tr>
                            <tr>
                                <th>Delivery Service Sales:</th>
                                <td>$${Number(d.delivery_service_sales).toLocaleString()}</td>
                            </tr>
                            <tr>
                                <th>Trucks:</th>
                                <td>${d.num_trucks} (${d.truck_size || 'N/A'})</td>
                    </tr>
                    <tr>
                                <th>Crews:</th>
                                <td>${d.num_crews}</td>
                    </tr>
                </table>
                    </div>
                </div>
                ${d.truck_image ? `
                <div class="row mt-3">
                    <div class="col-12">
                        <h5>Truck Images</h5>
                        <div class="d-flex gap-2">
                            ${d.truck_image.split(',').map(img => 
                                `<a href="${img.trim()}" data-lightbox="truck-${d.id}" data-title="Truck Image">
                                    <img src="${img.trim()}" alt="Truck" style="max-height: 100px; cursor: pointer;">
                                </a>`
                            ).join('')}
                        </div>
                    </div>
                </div>
                ` : ''}
            </div>
        `;
    }

    $('#agentsTable tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
    
            if (row.child.isShown()) {
                row.child.hide();
            $(this).html('<button class="btn btn-sm btn-primary">+</button>');
        } else {
            row.child(format(row.data())).show();
            $(this).html('<button class="btn btn-sm btn-danger">-</button>');
        }
    });

    // Edit agent button click handler
    $('#agentsTable tbody').on('click', '.edit-agent', function() {
        var id = $(this).data('id');
        var row = table.row($(this).closest('tr')).data();
        
        // Populate the form with agent data
        $('#agent_id').val(id);
        $('#company_name').val(decodeHTMLEntities(row.company_name));
        $('#company_website').val(row.company_website);
        $('#contact_name').val(row.contact_name);
        $('#contact_title').val(row.contact_title);
        $('#email').val(row.email);
        $('#phone_number').val(row.phone_number);
        $('#address').val(row.address);
        $('#city').val(row.city);
        $('#state').val(row.state);
        $('#zip_code').val(row.zip_code);
        $('#num_trucks').val(row.num_trucks);
        $('#truck_size').val(row.truck_size);
        $('#num_crews').val(row.num_crews);
        $('#is_active').val(row.is_active ? '1' : '0');
        $('#corporate_sales').val(row.corporate_sales);
        $('#consumer_sales').val(row.consumer_sales);
        $('#local_sales').val(row.local_sales);
        $('#long_distance_sales').val(row.long_distance_sales);
        $('#delivery_service_sales').val(row.delivery_service_sales);
        $('#total_sales').val(row.total_sales);
        
        // Show the modal
        $('#editAgentModal').modal('show');
    });

    // Function to decode HTML entities
    function decodeHTMLEntities(text) {
        var textarea = document.createElement('textarea');
        textarea.innerHTML = text;
        return textarea.value;
    }

    // Save changes button click handler
    $('#saveAgentChanges').on('click', function() {
        var formData = $('#editAgentForm').serialize();
        
        $.ajax({
            url: "{{ route('agents.update') }}",
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#editAgentModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message
                    });
            } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'Failed to update agent'
                });
            }
        });
    });

    // Perform silent sync on page load
    function silentSync() {
        $.ajax({
            url: "{{ route('agents.syncAgents') }}",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Reload table data silently if new agents were added
                    table.ajax.reload(null, false);
                }
            },
            error: function(xhr) {
                console.log('Silent sync failed:', xhr.responseJSON?.message || 'Unknown error');
            }
        });
    }

    // Manual sync button
    $('#syncButton').on('click', function() {
        var button = $(this);
        button.prop('disabled', true);
        button.html('<i class="fas fa-spinner fa-spin"></i> Syncing...');

        $.ajax({
            url: "{{ route('agents.syncAgents') }}",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'Failed to sync agents data'
                });
            },
            complete: function() {
                button.prop('disabled', false);
                button.html('<i class="fas fa-sync-alt"></i> Sync Now');
            }
        });
    });

    // Copy URL functionality
    $('#agentsTable').on('click', '.copy-url', function() {
        const url = $(this).data('url');
        navigator.clipboard.writeText(url).then(function() {
            // Show success message
            const button = $(this);
            const originalHtml = button.html();
            button.html('<i class="fas fa-check"></i> Copied!');
            button.removeClass('btn-secondary').addClass('btn-success');
            
            // Reset button after 2 seconds
            setTimeout(function() {
                button.html(originalHtml);
                button.removeClass('btn-success').addClass('btn-secondary');
            }, 2000);
        }.bind(this)).catch(function(err) {
            console.error('Failed to copy URL: ', err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to copy URL to clipboard'
            });
        });
    });
    });
    </script>

@endsection