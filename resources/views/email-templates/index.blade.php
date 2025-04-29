@extends('includes.app')

@section('title', 'Email Templates')

<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<style>
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
        margin-bottom: 24px;
    }

    .card-header {
        border-bottom: 1px solid #e5e7eb;
        padding: 1.5rem;
        background: transparent;
    }

    .card-header h4 {
        margin-bottom: 0;
        color: #1f2937;
    }

    .card-body {
        padding: 1.5rem;
    }

    .btn {
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: #3b82f6;
        border-color: #3b82f6;
    }

    .btn-primary:hover {
        background: #2563eb;
        border-color: #2563eb;
    }

    .btn-info {
        background: #0ea5e9;
        border-color: #0ea5e9;
        color: white;
    }

    .btn-info:hover {
        background: #0284c7;
        border-color: #0284c7;
        color: white;
    }

    .btn-danger {
        background: #ef4444;
        border-color: #ef4444;
    }

    .btn-danger:hover {
        background: #dc2626;
        border-color: #dc2626;
    }

    .btn-sm {
        padding: 0.4rem 0.8rem;
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        font-weight: 600;
        color: #1f2937;
        border-bottom-width: 1px;
        padding: 1rem;
    }

    .table td {
        vertical-align: middle;
        color: #4b5563;
        padding: 1rem;
    }

    .page-title-box {
        margin-bottom: 2rem;
    }

    .breadcrumb {
        margin-bottom: 0;
    }

    .breadcrumb-item a {
        color: #3b82f6;
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: #6b7280;
    }

    .ck.ck-editor__main > .ck-editor__editable {
        height: 200px !important;
        min-height: 200px !important;
        max-height: 200px !important;
        border-radius: 0 0 8px 8px !important;
    }

    .ck.ck-editor {
        width: 100%;
        border-radius: 8px !important;
    }

    .ck-rounded-corners .ck.ck-editor__main > .ck-editor__editable,
    .ck.ck-editor__main > .ck-editor__editable.ck-rounded-corners {
        border-radius: 0 0 8px 8px !important;
    }

    .ck.ck-toolbar {
        border-radius: 8px 8px 0 0 !important;
        background: #f9fafb !important;
        border-color: #e5e7eb !important;
    }

    .ck-content {
        font-size: 14px;
        line-height: 1.6;
        border-color: #e5e7eb !important;
    }

    .placeholder-list {
        max-height: 300px;
        overflow-y: auto;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }

    .placeholder-list::-webkit-scrollbar {
        width: 6px;
    }

    .placeholder-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .placeholder-list::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }

    .placeholder-list::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .list-group-item {
        border-color: #e5e7eb;
        transition: all 0.2s ease;
    }

    .list-group-item:hover {
        background-color: #f3f4f6;
    }

    .modal-content {
        border: none;
        border-radius: 15px;
    }

    .modal-header {
        border-bottom: 1px solid #e5e7eb;
        padding: 1.5rem;
        background: transparent;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #e5e7eb;
        padding: 1.5rem;
    }

    .form-label {
        color: #374151;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        padding: 0.6rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .dataTables_wrapper .dataTables_length select {
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        padding: 0.4rem 2rem 0.4rem 1rem;
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        padding: 0.6rem 1rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 8px;
        padding: 0.5rem 1rem;
        margin: 0 2px;
        border: none !important;
        background: none !important;
        color: #6b7280 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #3b82f6 !important;
        border-color: #3b82f6 !important;
        color: white !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
        background: none !important;
        border: none !important;
        color: #1f2937 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        color: #9ca3af !important;
        cursor: not-allowed;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        color: #9ca3af !important;
        background: none !important;
        border: none !important;
    }

    .btn-group {
        display: inline-flex;
        gap: 0.25rem;
    }

    .btn-group .btn {
        padding: 0.4rem 0.8rem;
    }
</style>

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <h4 class="page-title">Email Templates</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Email Templates</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Manage Email Templates</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
                        <i class="fas fa-plus me-2"></i>Create Template
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="templatesTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Subject</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($templates as $template)
                                <tr id="{{ $template->id }}" data-id="{{ $template->id }}">
                                    <td data-name="{{ $template->name }}">{{ $template->name }}</td>
                                    <td data-subject="{{ $template->subject }}">{{ $template->subject }}</td>
                                    <td data-description="{{ $template->description }}">{{ $template->description }}</td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-info edit-template" 
                                                    data-id="{{ $template->id }}"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editTemplateModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-template" 
                                                    data-id="{{ $template->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
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
    </div>
</div>

<!-- Create Template Modal -->
<div class="modal fade" id="createTemplateModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Email Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('email-templates.store') }}" method="POST" id="createTemplateForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Template Name</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Subject</label>
                                        <input type="text" class="form-control" name="subject" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <input type="text" class="form-control" name="description">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <textarea id="editor" name="content"></textarea>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Available Placeholders</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group placeholder-list">
                                        @foreach($placeholders['Transaction'] as $field => $placeholder)
                                        <button type="button" class="list-group-item list-group-item-action placeholder-item" data-placeholder="{{ $placeholder }}">
                                            {{ $field }}
                                        </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Template</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Template Modal -->
<div class="modal fade" id="editTemplateModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Email Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editTemplateForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Template Name</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Subject</label>
                                        <input type="text" class="form-control" name="subject" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <input type="text" class="form-control" name="description">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <textarea id="editor-edit" name="content"></textarea>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Available Placeholders</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group placeholder-list">
                                        @foreach($placeholders['Transaction'] as $field => $placeholder)
                                        <button type="button" class="list-group-item list-group-item-action placeholder-item" data-placeholder="{{ $placeholder }}">
                                            {{ $field }}
                                        </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Template</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

<!-- Include CKEditor and SweetAlert2 from CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let editor, editEditor;

    // Initialize DataTable
    var table = $('#templatesTable').DataTable({
        processing: true,
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        order: [[0, 'asc']],
        search: {
            smart: true,
            regex: true,
            caseInsensitive: true
        },
        language: {
            search: "",
            searchPlaceholder: "Search templates..."
        },
        columns: [
            { data: 'name' },
            { data: 'subject' },
            { data: 'description' },
            { 
                data: null,
                orderable: false,
                className: 'text-end',
                defaultContent: '',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return `<div class="btn-group">
                            <button type="button" class="btn btn-sm btn-info edit-template" 
                                    data-id="${row.id}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editTemplateModal">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger delete-template" 
                                    data-id="${row.id}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>`;
                    }
                    return '';
                }
            }
        ],
        rowId: 'id'
    });

    // Initialize CKEditor for create form
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: [
                'heading',
                '|',
                'bold', 'italic', 'strikethrough', 'underline',
                '|',
                'bulletedList', 'numberedList',
                '|',
                'alignment',
                '|',
                'link', 'blockQuote', 'insertTable', 'sourceEditing', 'htmlEmbed',
                '|',
                'undo', 'redo'
            ],
            htmlSupport: {
                allow: [
                    {
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }
                ]
            }
        })
        .then(newEditor => {
            editor = newEditor;
            
            // Handle placeholder insertion for create form
            document.querySelectorAll('#createTemplateModal .placeholder-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const placeholder = this.dataset.placeholder;
                    editor.model.change(writer => {
                        const insertPosition = editor.model.document.selection.getFirstPosition();
                        editor.model.insertContent(writer.createText(placeholder), insertPosition);
                    });
                });
            });
        })
        .catch(error => {
            console.error(error);
        });

    // Initialize CKEditor for edit form
    ClassicEditor
        .create(document.querySelector('#editor-edit'), {
            toolbar: [
                'heading',
                '|',
                'bold', 'italic', 'strikethrough', 'underline',
                '|',
                'bulletedList', 'numberedList',
                '|',
                'alignment',
                '|',
                'link', 'blockQuote', 'insertTable', 'sourceEditing', 'htmlEmbed',
                '|',
                'undo', 'redo'
            ],
            htmlSupport: {
                allow: [
                    {
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }
                ]
            }
        })
        .then(newEditor => {
            editEditor = newEditor;
            
            // Handle placeholder insertion for edit form
            document.querySelectorAll('#editTemplateModal .placeholder-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const placeholder = this.dataset.placeholder;
                    editEditor.model.change(writer => {
                        const insertPosition = editEditor.model.document.selection.getFirstPosition();
                        editEditor.model.insertContent(writer.createText(placeholder), insertPosition);
                    });
                });
            });
        })
        .catch(error => {
            console.error(error);
        });

    // Function to update table row
    function updateTableRow(data) {
        var row = table.row('#' + data.id);
        if (row.length) {
            row.data({
                id: data.id,
                name: data.name,
                subject: data.subject,
                description: data.description || ''
            }).draw();
            attachEventListeners();
        }
    }

    // Function to add new row to table
    function addTableRow(data) {
        var newRow = table.row.add({
            id: data.id,
            name: data.name,
            subject: data.subject,
            description: data.description || ''
        }).draw();
        attachEventListeners();
    }

    // Function to remove row from table
    function removeTableRow(id) {
        table.row(`tr[data-id="${id}"]`).remove().draw();
    }

    // Function to attach event listeners to row buttons
    function attachEventListeners() {
        // Remove existing event listeners first
        $('.edit-template').off('click');
        $('.delete-template').off('click');
        
        // Attach new event listeners
        $('.edit-template').on('click', handleEdit);
        $('.delete-template').on('click', handleDelete);
    }

    // Handle create form submission
    document.getElementById('createTemplateForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.set('content', editor.getData());

        // Show loading state
        Swal.fire({
            title: 'Saving...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add new row to table
                addTableRow(data.template);
                
                // Close modal and reset form
                const modal = bootstrap.Modal.getInstance(document.getElementById('createTemplateModal'));
                modal.hide();
                this.reset();
                editor.setData('');

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Template created successfully',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                throw new Error(data.message || 'Failed to create template');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.message || 'Something went wrong'
            });
        });
    });

    // Handle edit form submission
    document.getElementById('editTemplateForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.set('content', editEditor.getData());
        formData.append('_method', 'PUT');

        // Show loading state
        Swal.fire({
            title: 'Updating...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update table row
                updateTableRow(data.template);
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editTemplateModal'));
                modal.hide();

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Template updated successfully',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                throw new Error(data.message || 'Failed to update template');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.message || 'Something went wrong'
            });
        });
    });

    // Handle edit button click
    function handleEdit() {
        const templateId = this.dataset.id;
        const form = document.getElementById('editTemplateForm');
        form.action = `/email-templates/${templateId}`;
        
        // Show loading state
        Swal.fire({
            title: 'Loading...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Fetch template data and populate form
        fetch(`/email-templates/${templateId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                form.querySelector('[name="name"]').value = data.name || '';
                form.querySelector('[name="subject"]').value = data.subject || '';
                form.querySelector('[name="description"]').value = data.description || '';
                editEditor.setData(data.content || '');
                Swal.close();
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to load template data',
                    icon: 'error'
                });
            });
    }

    // Handle delete button click
    function handleDelete() {
        const templateId = this.dataset.id;
        
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

                fetch(`/email-templates/${templateId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove row from table
                        removeTableRow(templateId);
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Template has been deleted.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        throw new Error(data.message || 'Failed to delete template');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: error.message || 'Failed to delete template'
                    });
                });
            }
        });
    }

    // Attach event listeners to existing rows
    attachEventListeners();

    // Handle modal hidden events
    document.getElementById('createTemplateModal').addEventListener('hidden.bs.modal', function () {
        editor.setData('');
        this.querySelector('form').reset();
    });

    document.getElementById('editTemplateModal').addEventListener('hidden.bs.modal', function () {
        editEditor.setData('');
        this.querySelector('form').reset();
    });
});
</script>