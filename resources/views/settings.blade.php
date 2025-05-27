@extends('includes.app')

@section('title', 'Local Inventory')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <h4 class="page-title">Local Inventory</h4>                             
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">                        
        <!-- Categories Section -->
        <div class="col-md-12 col-lg-3">
            <div class="card shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h4 class="card-title mb-0">Categories</h4>
                </div>
                <div class="card-body">
                    <form id="categoryForm" onsubmit="return false;">
                        @csrf
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" id="categoryName" name="name" placeholder="Enter Category">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>Category</th>
                                    <th class="text-end">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="categoryTableBody">
                                <tr>
                                    <td colspan="2" class="text-center">No records available</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Section -->
        <div class="col-md-12 col-lg-9">
            <div class="card shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Inventory Items</h4>
                        <div class="d-flex gap-3">
                            <div class="search-wrapper">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="searchInventory" placeholder="Search items...">
                                    <button class="btn btn-outline-secondary border-start-0" type="button" id="clearSearch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary d-flex align-items-center" id="addInventoryItemBtn">
                                <i class="fas fa-plus me-2"></i> New Item
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="inventoryTable">
                            <tbody id="inventoryTableBody">
                                <!-- Categories will be dynamically inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Inventory Item Modal -->
<div class="modal fade" id="inventoryItemModal" tabindex="-1" aria-labelledby="inventoryItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="inventoryItemModalLabel">
                    <i class="fas fa-box me-2"></i>Add Inventory Item
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="inventoryItemForm">
                    @csrf
                    <input type="hidden" id="inventoryItemId">
                    
                    <!-- Item Name -->
                    <div class="mb-4">
                        <label for="item" class="form-label fw-semibold">
                            <i class="fas fa-tag me-1 text-primary"></i>Item Name
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-box text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0" id="item" name="item" placeholder="Enter item name" required>
                        </div>
                    </div>

                    <!-- Category Selection -->
                    <div class="mb-4">
                        <label for="category_id" class="form-label fw-semibold">
                            <i class="fas fa-folder me-1 text-primary"></i>Category
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-folder-open text-muted"></i>
                            </span>
                            <select class="form-select border-start-0 ps-0" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                            </select>
                        </div>
                    </div>

                    <!-- Cubic Feet -->
                    <div class="mb-4">
                        <label for="cubic_ft" class="form-label fw-semibold">
                            <i class="fas fa-cube me-1 text-primary"></i>Cubic Feet
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-ruler-combined text-muted"></i>
                            </span>
                            <input type="number" step="0.01" class="form-control border-start-0 ps-0" id="cubic_ft" name="cubic_ft" placeholder="Enter cubic feet" required>
                            <span class="input-group-text bg-light border-start-0">ft³</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="saveInventoryItemBtn">
                    <i class="fas fa-save me-1"></i>Save Item
                </button>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-color: #3b82f6;
    --primary-hover: #2563eb;
    --secondary-color: #6b7280;
    --light-bg: #f8fafc;
    --border-color: #e5e7eb;
    --text-dark: #1f2937;
    --text-muted: #6b7280;
    --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
    --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --transition: all 0.3s ease;
}

body {
    background-color: #f9fafb;
    color: var(--text-dark);
}

.card {
    border: none;
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    overflow: hidden;
}

.card:hover {
    box-shadow: var(--shadow-md);
}

.card-header {
    border-bottom: 1px solid var(--border-color);
    padding: 1.5rem;
    background-color: white;
}

.card-body {
    padding: 1.5rem;
}

.form-control, .form-select {
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-color);
    padding: 0.6rem 1rem;
    transition: var(--transition);
    font-size: 0.95rem;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.btn {
    border-radius: var(--radius-sm);
    padding: 0.6rem 1.2rem;
    font-weight: 500;
    transition: var(--transition);
}

.btn-primary {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary:hover {
    background: var(--primary-hover);
    border-color: var(--primary-hover);
    transform: translateY(-1px);
}

.btn-info {
    background-color: #0ea5e9;
    border-color: #0ea5e9;
    color: white;
}

.btn-info:hover {
    background-color: #0284c7;
    border-color: #0284c7;
    color: white;
}

.btn-danger {
    background-color: #ef4444;
    border-color: #ef4444;
}

.btn-danger:hover {
    background-color: #dc2626;
    border-color: #dc2626;
}

.table {
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    color: var(--text-dark);
    border-bottom-width: 1px;
    background-color: var(--light-bg);
}

.table td {
    vertical-align: middle;
    color: var(--text-muted);
    padding: 1rem;
}

.btn-group {
    display: inline-flex;
    gap: 0.25rem;
}

.btn-group .btn {
    padding: 0.4rem 0.8rem;
}

.badge {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-weight: 500;
}

.input-group .form-control {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.input-group .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.modal-content {
    border: none;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
}

.modal-header {
    border-bottom: 1px solid var(--border-color);
    padding: 1.5rem;
}

.modal-footer {
    border-top: 1px solid var(--border-color);
    padding: 1.5rem;
}

.page-title-box {
    margin-bottom: 2rem;
}

.breadcrumb {
    margin-bottom: 0;
}

.breadcrumb-item a {
    color: var(--primary-color);
    text-decoration: none;
}

.breadcrumb-item.active {
    color: var(--text-muted);
}

.search-wrapper {
    position: relative;
    width: 300px;
}

.search-wrapper .input-group {
    border-radius: var(--radius-sm);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.search-wrapper .input-group:focus-within {
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.15);
}

.search-wrapper .input-group-text {
    border: 1px solid var(--border-color);
    border-right: none;
    padding: 0.6rem 1rem;
}

.search-wrapper .form-control {
    border: 1px solid var(--border-color);
    padding: 0.6rem 1rem;
    font-size: 0.95rem;
}

.search-wrapper .btn-outline-secondary {
    border: 1px solid var(--border-color);
    color: var(--text-muted);
    padding: 0.6rem 1rem;
}

.search-wrapper .btn-outline-secondary:hover {
    background-color: #f3f4f6;
    color: #374151;
}

#addInventoryItemBtn {
    padding: 0.6rem 1.2rem;
    font-weight: 500;
    border-radius: var(--radius-sm);
    box-shadow: 0 2px 10px rgba(59, 130, 246, 0.2);
    transition: var(--transition);
}

#addInventoryItemBtn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

#addInventoryItemBtn i {
    font-size: 0.9rem;
}

.category-header {
    background-color: var(--light-bg);
    border-left: 4px solid var(--primary-color);
    cursor: pointer;
    transition: var(--transition);
}

.category-header:hover {
    background-color: #f1f5f9;
}

.category-header .toggle-category {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
}

.category-header .toggle-category i {
    transition: transform 0.3s ease;
}

.category-header .category-title {
    font-weight: 600;
    color: var(--text-dark);
}

.category-header .category-count {
    background-color: var(--primary-color);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
}

.category-items-wrapper {
    background-color: white;
}

.category-items {
    transition: var(--transition);
}

.category-items:hover {
    background-color: #f9fafb;
}

/* Responsive adjustments */
@media (max-width: 991.98px) {
    .search-wrapper {
        width: 100%;
    }
    
    .card-header .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .card-header .d-flex > div:last-child {
        width: 100%;
    }
}

/* Modal Custom Styling */
.modal-content {
    border-radius: 12px;
    overflow: hidden;
}

.modal-header {
    border-bottom: none;
    padding: 1.25rem;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    border-top: none;
    padding: 1.25rem;
}

/* Form Input Styling */
.form-label {
    color: #495057;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border-radius: 8px;
    padding: 0.6rem 1rem;
    font-size: 0.95rem;
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.15);
}

.input-group-text {
    border-radius: 8px;
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    color: #6c757d;
}

.input-group .form-control:not(:first-child) {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.input-group .form-control:not(:last-child) {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

/* Button Styling */
.btn {
    border-radius: 8px;
    padding: 0.6rem 1.2rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-primary {
    background: #3b82f6;
    border-color: #3b82f6;
}

.btn-primary:hover {
    background: #2563eb;
    border-color: #2563eb;
    transform: translateY(-1px);
}

.btn-light {
    background: #f8f9fa;
    border-color: #e9ecef;
    color: #495057;
}

.btn-light:hover {
    background: #e9ecef;
    border-color: #dee2e6;
    color: #212529;
}

/* Required Field Indicator */
.text-danger {
    color: #dc3545;
    font-size: 0.75rem;
    margin-left: 0.25rem;
}

/* Icon Styling */
.fas {
    font-size: 0.875rem;
}

/* Responsive Adjustments */
@media (max-width: 576px) {
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .modal-body {
        padding: 1rem;
    }
    
    .modal-footer {
        padding: 1rem;
    }
}
</style>

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
                        <td class="text-end">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-info edit-category-btn" data-id="${response.data.id}" data-name="${response.data.name}"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-sm btn-danger delete-category-btn" data-id="${response.data.id}"><i class="fas fa-trash-alt"></i></button>
                            </div>
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
            filteredItems = [...allItems];
        } else {
            // Filter items based on search text
            filteredItems = allItems.filter(item => 
                item.item.toLowerCase().includes(searchText) || 
                item.category.name.toLowerCase().includes(searchText) || 
                item.cubic_ft.toString().includes(searchText)
            );
        }
        
        // Render the table
        renderInventoryTable();
        
        // If there's a search text, expand categories with matching items
        if (searchText !== '') {
            // Get unique categories that have matching items
            const matchingCategories = new Set(filteredItems.map(item => item.category.name));
            
            // Expand matching categories
            matchingCategories.forEach(category => {
                $(`.category-content[data-category="${category}"]`).show();
                $(`.category-header[data-category="${category}"] .toggle-category i`)
                    .removeClass('fa-chevron-right')
                    .addClass('fa-chevron-down');
            });
        }
    }
    
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
                                <td class="text-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-info edit-category-btn" data-id="${category.id}" data-name="${category.name}"><i class="fas fa-edit"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger delete-category-btn" data-id="${category.id}"><i class="fas fa-trash-alt"></i></button>
                                    </div>
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
                // Store all items
                allItems = response.items;
                filteredItems = [...allItems];
                
                // Render the table
                renderInventoryTable();
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
    
    // Function to render inventory table
    function renderInventoryTable() {
        // Group items by category
        const groupedItems = {};
        filteredItems.forEach(item => {
            if (!groupedItems[item.category.name]) {
                groupedItems[item.category.name] = {
                    items: []
                };
            }
            groupedItems[item.category.name].items.push(item);
        });
        
        // Render the table
        let html = '';
        
        if (Object.keys(groupedItems).length > 0) {
            Object.entries(groupedItems).forEach(([categoryName, categoryData], categoryIndex) => {
                // Add category header with collapse functionality
                html += `
                    <tr class="category-header" data-category="${categoryName}">
                        <td colspan="4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-link text-dark p-0 me-2 toggle-category" data-category="${categoryName}">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                    <span class="category-title">${categoryName}</span>
                                    <span class="category-count ms-2">${categoryData.items.length} items</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
                
                // Add collapsible content for items
                html += `
                    <tr class="category-content" data-category="${categoryName}">
                        <td colspan="4" class="p-0">
                            <div class="category-items-wrapper">
                                <table class="table table-hover table-bordered mb-0">
                            <thead class="table-light">
                              <tr>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Cubic Ft.</th>
                                            <th class="text-end">Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                `;
                
                // Add items for this category
                categoryData.items.forEach(item => {
                    html += `
                        <tr class="category-items">
                            <td>${item.item}</td>
                            <td>${item.category.name}</td>
                            <td>${item.cubic_ft}</td>
                            <td class="text-end">
                                <div class="btn-group">
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
                                </div>
                                    </td>
                                </tr>                                                                         
                    `;
                });
                
                // Close the items table
                html += `
                            </tbody>
                          </table>
                    </div> 
                        </td>
                    </tr>
                `;
            });
        } else {
            html = '<tr><td colspan="4" class="text-center">No records available</td></tr>';
        }
        
        $('#inventoryTableBody').html(html);
        
        // Initialize category states
        $('.category-content').hide();
        $('.category-header .toggle-category i').addClass('fa-chevron-right').removeClass('fa-chevron-down');
        
        // Add click handlers for category toggles
        $('.toggle-category').off('click').on('click', function(e) {
            e.preventDefault();
            const category = $(this).data('category');
            const icon = $(this).find('i');
            const content = $(`.category-content[data-category="${category}"]`);
            
            content.slideToggle(300);
            icon.toggleClass('fa-chevron-right fa-chevron-down');
        });
    }
});
</script>
@endsection