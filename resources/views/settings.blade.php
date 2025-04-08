@extends('includes.app')

@section('title', 'Dashboard')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Local Inventory</h4>                             
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div><!--end row-->
    <div class="row">                        
        <div class="col-md-12 col-lg-3">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">Category</h4>                      
                        </div><!--end col-->
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <form id="categoryForm" onsubmit="return false;">
                        @csrf
                        <div class="mb-1">
                            <input type="text" class="form-control" id="categoryName" name="name" placeholder="Enter Category">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">Create Category</button>
                    </form>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 table-centered">
                            <thead class="table-light">
                            <tr>
                                <th>Category</th>
                                <th class="text-center" style="width: 40%;">Action</th>
                            </tr>
                            </thead>
                            <tbody id="categoryTableBody">
                                <tr>
                                    <td colspan="2" class="text-center">No records available</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col--> 
        <div class="col-md-12 col-lg-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Inventory Settings</h4>
                    <div class="d-flex align-items-center">
                        <div class="input-group me-2" style="width: 250px;">
                            <input type="text" class="form-control form-control-sm" id="searchInventory" placeholder="Search items...">
                            <button class="btn btn-sm btn-outline-secondary" type="button" id="clearSearch">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" id="addInventoryItemBtn">New Item</button>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table datatable table-bordered mb-0 table-centered" id="inventoryTable">
                            <tbody id="inventoryTableBody">
                                <tr>
                                    <td colspan="4" class="text-center">No records available</td>
                                </tr>
                            </tbody>
                        </table>
                    </div> 
                </div><!--end card-body--> 
            </div><!--end card-->                             
        </div> <!--end col-->           
    </div><!--end row-->                                         
</div>

<!-- Add/Edit Inventory Item Modal -->
<div class="modal fade" id="inventoryItemModal" tabindex="-1" aria-labelledby="inventoryItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inventoryItemModalLabel">Add Inventory Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="inventoryItemForm">
                    @csrf
                    <input type="hidden" id="inventoryItemId">
                    <div class="mb-3">
                        <label for="item" class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="item" name="item" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cubic_ft" class="form-label">Cubic Ft.</label>
                        <input type="number" step="0.01" class="form-control" id="cubic_ft" name="cubic_ft" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveInventoryItemBtn">Save</button>
            </div>
        </div>
    </div>
</div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    // Load initial data
    loadCategories();
    loadInventoryItems();
    
    // Search functionality
    $('#searchInventory').on('keyup', function() {
        const searchText = $(this).val().toLowerCase();
        searchInventoryItems(searchText);
    });
    
    // Clear search
    $('#clearSearch').on('click', function() {
        $('#searchInventory').val('');
        searchInventoryItems('');
    });
    
    // Function to search inventory items
    function searchInventoryItems(searchText) {
        if (searchText === '') {
            // If search is empty, show all items
            $('.category-section').show();
            $('.inventory-item-row').show();
            return;
        }
        
        // Hide all category sections first
        $('.category-section').hide();
        
        // Search through each item
        $('.inventory-item-row').each(function() {
            const itemName = $(this).find('td:first').text().toLowerCase();
            const categoryName = $(this).find('td:eq(1)').text().toLowerCase();
            const cubicFt = $(this).find('td:eq(2)').text().toLowerCase();
            
            if (itemName.includes(searchText) || 
                categoryName.includes(searchText) || 
                cubicFt.includes(searchText)) {
                // Show the item
                $(this).show();
                // Show its parent category section
                $(this).closest('.category-section').show();
            } else {
                // Hide the item
                $(this).hide();
            }
        });
        
        // Hide empty category sections
        $('.category-section').each(function() {
            const visibleItems = $(this).find('.inventory-item-row:visible').length;
            if (visibleItems === 0) {
                $(this).hide();
            }
        });
    }
    
    // Category Form Submit
    $('#categoryForm').on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const name = $('#categoryName').val();
        
        if (!name) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please enter a category name'
            });
            return false;
        }

        // Show loading state
        Swal.fire({
            title: 'Saving...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '/categories',
            method: 'POST',
            data: {
                name: name,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Check if there's a "No records available" message and remove it
                if ($('#categoryTableBody tr td[colspan="2"]').length > 0) {
                    $('#categoryTableBody').empty();
                }
                
                // Add new row to table
                const newRow = `
                    <tr>
                        <td>${response.data.name}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-info edit-category-btn" data-id="${response.data.id}" data-name="${response.data.name}"><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-sm btn-danger delete-category-btn" data-id="${response.data.id}"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                `;
                $('#categoryTableBody').append(newRow);
                
                // Clear form
                $('#categoryName').val('');
                
                // Update category dropdown in the inventory item modal
                const newOption = `<option value="${response.data.id}">${response.data.name}</option>`;
                $('#category_id').append(newOption);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function(xhr) {
                let errorMessage = 'Something went wrong!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage
                });
            }
        });
        
        return false;
    });
    
    // Edit Category
    $(document).on('click', '.edit-category-btn', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        Swal.fire({
            title: 'Edit Category',
            input: 'text',
            inputValue: name,
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'You need to write something!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Updating...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '/categories/' + id,
                    method: 'PUT',
                    data: {
                        name: result.value,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Reload categories
                        loadCategories();
                        // Reload inventory items to update the category names in the table
                        loadInventoryItems();
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Something went wrong!';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage
                        });
                    }
                });
            }
        });
    });
    
    // Delete Category
    $(document).on('click', '.delete-category-btn', function() {
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Deleting...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '/categories/' + id,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Reload categories
                        loadCategories();
                        // Reload inventory items to update the table
                        loadInventoryItems();
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Something went wrong!';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage
                        });
                    }
                });
            }
        });
    });
    
    // Add Inventory Item Button
    $('#addInventoryItemBtn').on('click', function() {
        $('#inventoryItemModalLabel').text('Add Inventory Item');
        $('#inventoryItemId').val('');
        $('#inventoryItemForm')[0].reset();
        $('#inventoryItemModal').modal('show');
    });
    
    // Save Inventory Item
    $('#saveInventoryItemBtn').on('click', function() {
        const id = $('#inventoryItemId').val();
        const item = $('#item').val();
        const category_id = $('#category_id').val();
        const cubic_ft = $('#cubic_ft').val();
        
        if (!item || !category_id || !cubic_ft) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please fill all fields'
            });
            return;
        }
        
        // Show loading state
        Swal.fire({
            title: id ? 'Updating...' : 'Saving...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        const url = id ? '/inventory-items/' + id : '/inventory-items';
        const method = id ? 'PUT' : 'POST';
        
        $.ajax({
            url: url,
            method: method,
            data: {
                item: item,
                category_id: category_id,
                cubic_ft: cubic_ft,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#inventoryItemModal').modal('hide');
                
                // Reload inventory items
                loadInventoryItems();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function(xhr) {
                let errorMessage = 'Something went wrong!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage
                });
            }
        });
    });
    
    // Edit Inventory Item
    $(document).on('click', '.edit-inventory-btn', function() {
        const id = $(this).data('id');
        const item = $(this).data('item');
        const category_id = $(this).data('category-id');
        const cubic_ft = $(this).data('cubic-ft');
        
        $('#inventoryItemModalLabel').text('Edit Inventory Item');
        $('#inventoryItemId').val(id);
        $('#item').val(item);
        $('#category_id').val(category_id);
        $('#cubic_ft').val(cubic_ft);
        
        $('#inventoryItemModal').modal('show');
    });
    
    // Delete Inventory Item
    $(document).on('click', '.delete-inventory-btn', function() {
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Deleting...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '/inventory-items/' + id,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Reload inventory items
                        loadInventoryItems();
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Something went wrong!';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage
                        });
                    }
                });
            }
        });
    });
    
    // Load Categories
    function loadCategories() {
        $.ajax({
            url: '/categories',
            method: 'GET',
            success: function(response) {
                // Update category table
                if (Array.isArray(response) && response.length > 0) {
                    let html = '';
                    response.forEach(function(category) {
                        html += `
                            <tr>
                                <td>${category.name}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-info edit-category-btn" data-id="${category.id}" data-name="${category.name}"><i class="fas fa-edit"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger delete-category-btn" data-id="${category.id}"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#categoryTableBody').html(html);
                    
                    // Update category dropdown
                    let dropdownHtml = '<option value="">Select Category</option>';
                    response.forEach(function(category) {
                        dropdownHtml += `<option value="${category.id}">${category.name}</option>`;
                    });
                    $('#category_id').html(dropdownHtml);
                } else {
                    $('#categoryTableBody').html('<tr><td colspan="2" class="text-center">No records available</td></tr>');
                    $('#category_id').html('<option value="">Select Category</option>');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load categories'
                });
            }
        });
    }
    
    // Load Inventory Items
    function loadInventoryItems() {
        $.ajax({
            url: '/inventory-items',
            method: 'GET',
            success: function(response) {
                // Update inventory table
                if (response.items.length > 0) {
                    let html = '';
                    
                    // Group items by category
                    const groupedItems = response.groupedItems;
                    
                    // If there are items, display them grouped by category
                    if (Object.keys(groupedItems).length > 0) {
                        let categoryIndex = 0;
                        for (const categoryName in groupedItems) {
                            categoryIndex++;
                            // Add category header with collapse functionality
                            html += `
                                <tr class="table-light category-section">
                                    <td colspan="4">
                                        <a class="d-flex align-items-center text-dark text-decoration-none" 
                                           data-bs-toggle="collapse" 
                                           href="#category${categoryIndex}" 
                                           role="button" 
                                           aria-expanded="true" 
                                           aria-controls="category${categoryIndex}">
                                            <i class="fas fa-chevron-down me-2"></i>
                                            <strong>${categoryName}</strong>
                                            <span class="badge bg-primary ms-2">${groupedItems[categoryName].length} items</span>
                                        </a>
                                    </td>
                                </tr>
                            `;
                            
                            // Add collapsible content for items in this category
                            html += `
                                <tr class="category-section">
                                    <td colspan="4" class="p-0">
                                        <div class="collapse show" id="category${categoryIndex}">
                                            <table class="table table-bordered mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Item</th>
                                                        <th>Category</th>
                                                        <th>Cubic Ft.</th>
                                                        <th style="width:120px;">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                            `;
                            
                            // Add items in this category
                            groupedItems[categoryName].forEach(function(item) {
                                html += `
                                    <tr class="inventory-item-row">
                                        <td>${item.item}</td>
                                        <td>${item.category.name}</td>
                                        <td>${item.cubic_ft}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-info edit-inventory-btn" 
                                                data-id="${item.id}" 
                                                data-item="${item.item}" 
                                                data-category-id="${item.category_id}" 
                                                data-cubic-ft="${item.cubic_ft}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-inventory-btn" data-id="${item.id}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            });
                            
                            // Close the collapsible content
                            html += `
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }
                    } else {
                        // If no grouping, just show all items
                        response.items.forEach(function(item) {
                            html += `
                                <tr>
                                    <td>${item.item}</td>
                                    <td>${item.category.name}</td>
                                    <td>${item.cubic_ft}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-info edit-inventory-btn" 
                                            data-id="${item.id}" 
                                            data-item="${item.item}" 
                                            data-category-id="${item.category_id}" 
                                            data-cubic-ft="${item.cubic_ft}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-inventory-btn" data-id="${item.id}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    
                    $('#inventoryTableBody').html(html);
                    
                    // Add click handler for collapse icons
                    $('.collapse').on('show.bs.collapse', function() {
                        $(this).prev().find('.fa-chevron-down').addClass('fa-rotate-180');
                    }).on('hide.bs.collapse', function() {
                        $(this).prev().find('.fa-chevron-down').removeClass('fa-rotate-180');
                    });
                } else {
                    $('#inventoryTableBody').html('<tr><td colspan="4" class="text-center">No records available</td></tr>');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load inventory items'
                });
            }
        });
    }
});
</script>