@extends('includes.app')

@section('title', 'Email Templates')

<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.ckeditor.com/4.19.1/standard-all/ckeditor.js"></script>

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
        min-height: 150px !important;
        max-height: 500px !important;
        resize: vertical !important;
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
        max-height: 600px;
        overflow-y: auto;
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

    .accordion-button {
        padding: 1rem;
        font-weight: 500;
        color: #1f2937;
        background-color: #f8fafc;
        border: none;
        transition: all 0.2s ease;
    }

    .accordion-button:not(.collapsed) {
        color: #3b82f6;
        background-color: #eff6ff;
        box-shadow: none;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(59, 130, 246, 0.1);
    }

    .accordion-button::after {
        background-size: 1rem;
        transition: all 0.2s ease;
    }

    .accordion-item {
        border: none;
        margin-bottom: 0.5rem;
    }

    .accordion-item:last-child {
        margin-bottom: 0;
    }

    .list-group-item {
        border: none;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
    }

    .list-group-item:hover {
        background-color: #f3f4f6;
    }

    .placeholder-item {
        cursor: pointer;
        border-left: 3px solid transparent;
        transition: all 0.2s ease;
    }

    .placeholder-item:hover {
        border-left-color: #3b82f6;
        background-color: #eff6ff;
    }

    .placeholder-item small {
        font-size: 0.75rem;
        opacity: 0.7;
    }

    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
    }

    .card {
        border: none;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }

    .card-header {
        background: transparent;
        border-bottom: 1px solid #e5e7eb;
        padding: 1rem;
    }

    .card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .fas {
        font-size: 0.875rem;
    }

    .text-primary {
        color: #3b82f6 !important;
    }

    .text-muted {
        color: #6b7280 !important;
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

    /* Make Quill editor adjustable in height */
    .ql-container {
        min-height: 400px;
        max-height: 600px;
        resize: vertical;
        overflow: auto;
    }

    /* Skeleton loader styles */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 37%, #f0f0f0 63%);
        background-size: 400% 100%;
        animation: skeleton-loading 1.2s ease-in-out infinite;
        border-radius: 6px;
        min-height: 38px;
        width: 100%;
        margin-bottom: 1rem;
    }
    .skeleton-textarea {
        min-height: 120px;
    }
    @keyframes skeleton-loading {
        0% { background-position: 100% 50%; }
        100% { background-position: 0 50%; }
    }

    .ql-editor p {
        margin: 0 0 10px 0 !important;
        line-height: 1.4 !important;
    }
    .ql-editor {
        line-height: 1.4 !important;
    }

    /* Quill Editor Background Color */
    .ql-container {
        background-color: #ffffff !important;
    }

    .ql-toolbar {
        background-color: #f8f9fa !important;
        border-top-left-radius: 8px !important;
        border-top-right-radius: 8px !important;
    }

    .ql-editor {
        background-color: #ffffff !important;
        min-height: 200px !important;
        border-bottom-left-radius: 8px !important;
        border-bottom-right-radius: 8px !important;
    }

    .ql-container.ql-snow {
        border: 1px solid #e5e7eb !important;
        border-radius: 8px !important;
    }

    /* CKEditor Table styles */
    .cke_editable table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
        border: 2px solid #bbb;
        table-layout: fixed;
    }
    
    .cke_editable table th {
        padding: 10px;
        background-color: #f2f2f2;
        border: 1px solid #ccc;
        font-weight: bold;
        text-align: left;
    }
    
    .cke_editable table td {
        padding: 10px;
        border: 1px solid #ccc;
        vertical-align: top;
    }
    
    .cke_editable table tr:nth-child(even) {
        background-color: #fafafa;
    }

    /* CKEditor custom styling */
    .cke {
        visibility: hidden;
    }
    
    .cke_top {
        border-bottom: 1px solid #e5e7eb !important;
        background: #f9fafb !important;
    }
    
    .cke_chrome {
        border-color: #e5e7eb !important;
        box-shadow: none !important;
        border-radius: 8px !important;
    }
    
    .cke_bottom {
        border-top: 1px solid #e5e7eb !important;
        background: #f9fafb !important;
    }
    
    .cke_editable {
        padding: 10px;
        min-height: 200px;
        font-size: 14px;
        line-height: 1.6;
    }

    /* Hide CKEditor notifications */
    .cke_notifications_area {
        display: none !important;
    }

    /* Quill Better Table styles */
    .ql-table {
        width: 36px;
        font-size: 14px !important;
        margin-right: 5px;
    }
    
    .ql-table .fas {
        display: inline-block;
        line-height: 18px;
    }
    
    .quill-better-table {
        border-collapse: collapse;
        width: 100%;
    }
    
    .quill-better-table td {
        border: 1px solid #ccc;
        padding: 8px;
        min-width: 25px;
        word-break: break-word;
    }
    
    table.quill-better-table .ql-picker.ql-table-colwidth {
        border: 1px solid #ccc;
        border-radius: 3px;
    }
    
    table.quill-better-table .ql-picker.ql-table-colwidth .ql-picker-label {
        padding: 0 4px;
    }
    
    .ql-better-table .table-operations {
        margin: 8px;
    }
    
    .ql-better-table .table-operations .btn {
        margin-right: 8px;
        margin-bottom: 8px;
    }
    
    .ql-better-table .table-buttons {
        margin-bottom: 8px;
    }
    
    .ql-better-table .table-buttons button {
        margin-right: 8px;
    }
    
    /* Fixed insertion tooltip */
    .qlbt-operation-menu {
        background-color: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        border-radius: 4px;
        padding: 5px 0;
        z-index: 10;
    }
    
    .qlbt-operation-menu .qlbt-operation-item {
        cursor: pointer;
        padding: 5px 15px;
        color: #595959;
        line-height: 28px;
        font-size: 14px;
    }
    
    .qlbt-operation-menu .qlbt-operation-item:hover {
        background-color: #f7f7f7;
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
                    <div class="btn-group">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
                            <i class="fas fa-plus me-2"></i>Create Template
                        </button>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#sendCustomEmailModal">
                            <i class="fas fa-paper-plane me-2"></i>Send Custom Email
                        </button>
                    </div>
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
                                            <button type="button" class="btn btn-sm btn-success test-template" 
                                                    data-id="{{ $template->id }}"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#testTemplateModal">
                                                <i class="fas fa-paper-plane"></i>
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
                        <div class="col-md-12">
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
                                <div class="row">
                                    <div class="col-md-9">
                                        <textarea name="content" id="editor"></textarea>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-header bg-transparent border-0">
                                                <h5 class="card-title mb-0">Available Placeholders</h5>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="placeholder-list">
                                                    <div class="accordion" id="createPlaceholderAccordion">
                                                        <!-- Transaction Fields -->
                                                        <div class="accordion-item border-0">
                                                            <h2 class="accordion-header" id="createTransactionHeading">
                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#createTransactionCollapse">
                                                                    <i class="fas fa-exchange-alt me-2 text-primary"></i>
                                                                    Transaction Fields
                                                                    <span class="badge bg-primary ms-2">{{ count($placeholders['Transaction']) }}</span>
                                                                </button>
                                                            </h2>
                                                            <div id="createTransactionCollapse" class="accordion-collapse collapse" data-bs-parent="#createPlaceholderAccordion">
                                                                <div class="accordion-body p-0">
                                                                    <div class="list-group list-group-flush">
                                                                        @foreach($placeholders['Transaction'] as $field => $placeholder)
                                                                        <button type="button" class="list-group-item list-group-item-action placeholder-item d-flex align-items-center" data-placeholder="{{ $placeholder }}">
                                                                            <i class="fas fa-tag text-muted me-2"></i>
                                                                            <span class="flex-grow-1">{{ $field }}</span>
                                                                            <small class="text-muted">{{ $placeholder }}</small>
                                                                        </button>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Lead Fields -->
                                                        <div class="accordion-item border-0">
                                                            <h2 class="accordion-header" id="createLeadHeading">
                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#createLeadCollapse">
                                                                    <i class="fas fa-user me-2 text-primary"></i>
                                                                    Lead Fields
                                                                    <span class="badge bg-primary ms-2">{{ count($placeholders['Lead']) }}</span>
                                                                </button>
                                                            </h2>
                                                            <div id="createLeadCollapse" class="accordion-collapse collapse" data-bs-parent="#createPlaceholderAccordion">
                                                                <div class="accordion-body p-0">
                                                                    <div class="list-group list-group-flush">
                                                                        @foreach($placeholders['Lead'] as $field => $placeholder)
                                                                        <button type="button" class="list-group-item list-group-item-action placeholder-item d-flex align-items-center" data-placeholder="{{ $placeholder }}">
                                                                            <i class="fas fa-tag text-muted me-2"></i>
                                                                            <span class="flex-grow-1">{{ $field }}</span>
                                                                            <small class="text-muted">{{ $placeholder }}</small>
                                                                        </button>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                    <div id="editModalSkeletons">
                        <div class="skeleton" style="max-width: 200px;"></div>
                        <div class="skeleton" style="max-width: 300px;"></div>
                        <div class="skeleton" style="max-width: 300px;"></div>
                        <div class="skeleton skeleton-textarea"></div>
                    </div>
                    <div id="editModalFields" style="display:none;">
                        <div class="row">
                            <div class="col-md-12">
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
                                    <div class="row">
                                        <div class="col-md-9">
                                            <textarea name="content" id="editor-edit"></textarea>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card">
                                                <div class="card-header bg-transparent border-0">
                                                    <h5 class="card-title mb-0">Available Placeholders</h5>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="placeholder-list">
                                                        <div class="accordion" id="editPlaceholderAccordion">
                                                            <!-- Transaction Fields -->
                                                            <div class="accordion-item border-0">
                                                                <h2 class="accordion-header" id="editTransactionHeading">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#editTransactionCollapse">
                                                                        <i class="fas fa-exchange-alt me-2 text-primary"></i>
                                                                        Transaction Fields
                                                                        <span class="badge bg-primary ms-2">{{ count($placeholders['Transaction']) }}</span>
                                                                    </button>
                                                                </h2>
                                                                <div id="editTransactionCollapse" class="accordion-collapse collapse" data-bs-parent="#editPlaceholderAccordion">
                                                                    <div class="accordion-body p-0">
                                                                        <div class="list-group list-group-flush">
                                                                            @foreach($placeholders['Transaction'] as $field => $placeholder)
                                                                            <button type="button" class="list-group-item list-group-item-action placeholder-item d-flex align-items-center" data-placeholder="{{ $placeholder }}">
                                                                                <i class="fas fa-tag text-muted me-2"></i>
                                                                                <span class="flex-grow-1">{{ $field }}</span>
                                                                                <small class="text-muted">{{ $placeholder }}</small>
                                                                            </button>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Lead Fields -->
                                                            <div class="accordion-item border-0">
                                                                <h2 class="accordion-header" id="editLeadHeading">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#editLeadCollapse">
                                                                        <i class="fas fa-user me-2 text-primary"></i>
                                                                        Lead Fields
                                                                        <span class="badge bg-primary ms-2">{{ count($placeholders['Lead']) }}</span>
                                                                    </button>
                                                                </h2>
                                                                <div id="editLeadCollapse" class="accordion-collapse collapse" data-bs-parent="#editPlaceholderAccordion">
                                                                    <div class="accordion-body p-0">
                                                                        <div class="list-group list-group-flush">
                                                                            @foreach($placeholders['Lead'] as $field => $placeholder)
                                                                            <button type="button" class="list-group-item list-group-item-action placeholder-item d-flex align-items-center" data-placeholder="{{ $placeholder }}">
                                                                                <i class="fas fa-tag text-muted me-2"></i>
                                                                                <span class="flex-grow-1">{{ $field }}</span>
                                                                                <small class="text-muted">{{ $placeholder }}</small>
                                                                            </button>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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

<!-- Test Template Modal -->
<div class="modal fade" id="testTemplateModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="testTemplateForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div id="modalSkeletons">
                        <div class="skeleton" style="max-width: 300px;"></div>
                        <div class="skeleton" style="max-width: 400px;"></div>
                        <div class="skeleton skeleton-textarea"></div>
                    </div>
                    <div id="modalFields" style="display:none;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Recipient Email</label>
                                    <input type="email" class="form-control" name="recipient_email" required 
                                           placeholder="Enter recipient email address">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Subject</label>
                                    <input type="text" class="form-control" name="subject" required 
                                           placeholder="Enter email subject">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Email Content</label>
                                    <div class="card">
                                        <div class="card-body">
                                            <textarea name="content" id="emailEditor"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Send Custom Email Modal -->
<div class="modal fade" id="sendCustomEmailModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Custom Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="sendCustomEmailForm" method="POST" action="{{ route('email-templates.send-custom') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">To</label>
                                <input type="email" class="form-control" name="to" required multiple>
                                <small class="text-muted">For multiple recipients, separate emails with commas</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">CC</label>
                                <div class="cc-container form-control" style="min-height: 38px; padding: 0.375rem 0.75rem;">
                                    <div class="cc-tags d-flex flex-wrap gap-2" style="padding: 2px 0;">
                                        <input type="text" class="cc-input border-0" style="flex: 1; min-width: 100px;" placeholder="Add CC recipients">
                                    </div>
                                </div>
                                <small class="text-muted">Press Enter or comma to add email addresses</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Subject</label>
                                <input type="text" class="form-control" name="subject" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Attachments</label>
                                <input type="file" class="form-control" name="attachments[]" multiple>
                                <small class="text-muted">You can select multiple files</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Email Body</label>
                                <textarea name="body" id="customEmailEditor"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

<!-- Include SweetAlert2 from CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let editor, editEditor, emailEditor, customEmailEditor;

    // Initialize editors with CKEditor
    editor = CKEDITOR.replace('editor', {
        height: 400,
        toolbarGroups: [
            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
            { name: 'forms', groups: [ 'forms' ] },
            '/',
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
            { name: 'links', groups: [ 'links' ] },
            { name: 'insert', groups: [ 'insert' ] },
            '/',
            { name: 'styles', groups: [ 'styles' ] },
            { name: 'colors', groups: [ 'colors' ] },
            { name: 'tools', groups: [ 'tools' ] },
            { name: 'others', groups: [ 'others' ] }
        ],
        extraPlugins: 'font,colorbutton,justify,tableresize,tabletools,lineutils,widget',
        removeButtons: '',
        startupMode: 'wysiwyg',
        notification: {
            duration: 0
        }
    });

    // Edit modal editor
    editEditor = CKEDITOR.replace('editor-edit', {
        height: 400,
        toolbarGroups: [
            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
            { name: 'forms', groups: [ 'forms' ] },
            '/',
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
            { name: 'links', groups: [ 'links' ] },
            { name: 'insert', groups: [ 'insert' ] },
            '/',
            { name: 'styles', groups: [ 'styles' ] },
            { name: 'colors', groups: [ 'colors' ] },
            { name: 'tools', groups: [ 'tools' ] },
            { name: 'others', groups: [ 'others' ] }
        ],
        extraPlugins: 'font,colorbutton,justify,tableresize,tabletools,lineutils,widget',
        removeButtons: '',
        startupMode: 'wysiwyg',
        notification: {
            duration: 0
        }
    });

    // Email editor for test emails
    emailEditor = CKEDITOR.replace('emailEditor', {
        height: 400,
        toolbarGroups: [
            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
            { name: 'forms', groups: [ 'forms' ] },
            '/',
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
            { name: 'links', groups: [ 'links' ] },
            { name: 'insert', groups: [ 'insert' ] },
            '/',
            { name: 'styles', groups: [ 'styles' ] },
            { name: 'colors', groups: [ 'colors' ] },
            { name: 'tools', groups: [ 'tools' ] },
            { name: 'others', groups: [ 'others' ] }
        ],
        extraPlugins: 'font,colorbutton,justify,tableresize,tabletools,lineutils,widget',
        removeButtons: '',
        startupMode: 'wysiwyg',
        notification: {
            duration: 0
        }
    });

    // Custom email editor
    customEmailEditor = CKEDITOR.replace('customEmailEditor', {
        height: 400,
        toolbarGroups: [
            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
            { name: 'forms', groups: [ 'forms' ] },
            '/',
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
            { name: 'links', groups: [ 'links' ] },
            { name: 'insert', groups: [ 'insert' ] },
            '/',
            { name: 'styles', groups: [ 'styles' ] },
            { name: 'colors', groups: [ 'colors' ] },
            { name: 'tools', groups: [ 'tools' ] },
            { name: 'others', groups: [ 'others' ] }
        ],
        extraPlugins: 'font,colorbutton,justify,tableresize,tabletools,lineutils,widget',
        removeButtons: '',
        startupMode: 'wysiwyg',
        notification: {
            duration: 0
        }
    });

    // Function to insert text into CKEditor
    function insertTextIntoCKEditor(editor, text) {
        if (!editor) return;
        
        try {
            // This is the correct way to insert text in CKEditor 4
            var selection = editor.getSelection();
            if (selection) {
                var range = selection.getRanges()[0];
                if (range) {
                    range.insertNode(new CKEDITOR.dom.text(text));
                    range.select();
                }
            }
        } catch (error) {
            console.error('Error inserting text into CKEditor:', error);
            // Fallback methods
            try {
                editor.insertText(text);
            } catch (e) {
                try {
                    editor.insertHtml(text);
                } catch (e2) {
                    console.error('All methods to insert text failed');
                }
            }
        }
    }

    // Handle placeholder clicks for both create and edit modals
    document.querySelectorAll('.placeholder-item').forEach(item => {
        item.addEventListener('click', function() {
            const placeholder = this.dataset.placeholder;
            const activeModal = document.querySelector('.modal.show');
            let editorInstance;

            if (activeModal.id === 'createTemplateModal') {
                editorInstance = editor;
            } else if (activeModal.id === 'editTemplateModal') {
                editorInstance = editEditor;
            }

            if (editorInstance) {
                // Use our helper function
                insertTextIntoCKEditor(editorInstance, placeholder);
            }
        });
    });

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
                            <button type="button" class="btn btn-sm btn-success test-template" 
                                    data-id="${row.id}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#testTemplateModal">
                                <i class="fas fa-paper-plane"></i>
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

    // Function to add new row to table
    function addTableRow(data) {
        table.row.add({
            id: data.id,
            name: data.name,
            subject: data.subject,
            description: data.description || ''
        }).draw();
    }

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
        }
    }

    // Handle create form submission
    document.getElementById('createTemplateForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        // Make sure to get data from CKEditor
        if (editor && typeof editor.getData === 'function') {
            formData.set('content', editor.getData());
        } else {
            console.error('Create editor not initialized properly');
        }

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
                editor.getData();

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
        
        // Make sure to get data from CKEditor
        if (editEditor && typeof editEditor.getData === 'function') {
            formData.set('content', editEditor.getData());
        } else {
            console.error('Edit editor not initialized properly');
        }
        
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

    // When opening edit modal, set CKEditor content
    window.setEditorContent = function(html) {
        // Add a slight delay to ensure the editor is fully initialized
        setTimeout(function() {
            if (editEditor && typeof editEditor.setData === 'function') {
                editEditor.setData(html || '');
            } else {
                console.error('Edit editor not fully initialized');
            }
        }, 100);
    };

    // When opening create modal, clear CKEditor content
    window.clearEditorContent = function() {
        // Add a slight delay to ensure the editor is fully initialized
        setTimeout(function() {
            if (editor && typeof editor.setData === 'function') {
                editor.setData('');
            } else {
                console.error('Create editor not fully initialized');
            }
        }, 100);
    };

    // Replace CKEditor setData calls in modal events
    document.getElementById('createTemplateModal').addEventListener('hidden.bs.modal', function () {
        clearEditorContent();
        this.querySelector('form').reset();
    });

    document.getElementById('editTemplateModal').addEventListener('hidden.bs.modal', function () {
        setEditorContent('');
        this.querySelector('form').reset();
    });

    // Update handleEdit to set Quill content
    function handleEdit() {
        const templateId = this.dataset.id;
        const form = document.getElementById('editTemplateForm');
        form.action = `/email-templates/${templateId}`;
        // Show skeletons, hide fields
        document.getElementById('editModalSkeletons').style.display = '';
        document.getElementById('editModalFields').style.display = 'none';
        fetch(`/email-templates/${templateId}`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                form.querySelector('[name="name"]').value = data.name || '';
                form.querySelector('[name="subject"]').value = data.subject || '';
                form.querySelector('[name="description"]').value = data.description || '';
                
                // Set CKEditor content with a slight delay to ensure editor is ready
                setTimeout(function() {
                    if (editEditor && typeof editEditor.setData === 'function') {
                        editEditor.setData(data.content || '');
                    } else {
                        console.error('Edit editor not initialized properly');
                    }
                }, 100);
                
                // Hide skeletons, show fields
                document.getElementById('editModalSkeletons').style.display = 'none';
                document.getElementById('editModalFields').style.display = '';
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to load template data',
                    icon: 'error'
                });
                // Hide skeletons, show fields anyway (fallback)
                document.getElementById('editModalSkeletons').style.display = 'none';
                document.getElementById('editModalFields').style.display = '';
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
                        table.row(`tr[data-id="${templateId}"]`).remove().draw();
                        
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

    // Handle test template button click
    document.querySelectorAll('.test-template').forEach(button => {
        button.addEventListener('click', function() {
            const templateId = this.dataset.id;
            const form = document.getElementById('testTemplateForm');
            form.action = `/email-templates/${templateId}/test`;
            // Reset form
            form.reset();
            // Show skeletons, hide fields
            document.getElementById('modalSkeletons').style.display = '';
            document.getElementById('modalFields').style.display = 'none';
            // Fetch template content
            fetch(`/email-templates/${templateId}`)
                .then(response => response.json())
                .then(data => {
                    // Set CKEditor content with a slight delay to ensure editor is ready
                    setTimeout(function() {
                        if (emailEditor && typeof emailEditor.setData === 'function') {
                            emailEditor.setData(data.content || '');
                        } else {
                            console.error('Email editor not initialized properly');
                        }
                    }, 100);
                    
                    form.querySelector('[name="subject"]').value = data.subject || '';
                    // Hide skeletons, show fields
                    document.getElementById('modalSkeletons').style.display = 'none';
                    document.getElementById('modalFields').style.display = '';
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Hide skeletons, show fields anyway (fallback)
                    document.getElementById('modalSkeletons').style.display = 'none';
                    document.getElementById('modalFields').style.display = '';
                });
        });
    });

    // Handle test form submission
    document.getElementById('testTemplateForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Create formData from the form
        const formData = new FormData(this);
        
        // Get the email content from CKEditor
        if (emailEditor && typeof emailEditor.getData === 'function') {
            formData.set('content', emailEditor.getData());
        } else {
            console.error('Email editor not initialized properly');
        }
        
        // Show loading state
        Swal.fire({
            title: 'Sending Email...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new TypeError("Oops, we haven't got JSON!");
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('testTemplateModal'));
                modal.hide();

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Email sent successfully',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                throw new Error(data.message || 'Failed to send email');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.message || 'Something went wrong. Please try again later.'
            });
        });
    });

    // CC Email Tags Functionality
    const ccContainer = document.querySelector('.cc-container');
    const ccTags = document.querySelector('.cc-tags');
    const ccInput = document.querySelector('.cc-input');

    function createTag(email) {
        const tag = document.createElement('span');
        tag.className = 'badge bg-primary d-flex align-items-center';
        tag.innerHTML = `
            ${email}
            <button type="button" class="btn-close btn-close-white ms-2" style="font-size: 0.5rem;"></button>
        `;
        
        const closeBtn = tag.querySelector('.btn-close');
        closeBtn.addEventListener('click', () => {
            tag.remove();
        });
        
        return tag;
    }

    function addTag(email) {
        if (email) {
            const tag = createTag(email);
            ccInput.insertAdjacentElement('beforebegin', tag);
            ccInput.value = '';
            ccInput.focus();
        }
    }

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    ccInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            const email = ccInput.value.trim();
            if (email && validateEmail(email)) {
                addTag(email);
            }
        }
    });

    // Add paste event handler
    ccInput.addEventListener('paste', (e) => {
        e.preventDefault();
        const pastedText = (e.clipboardData || window.clipboardData).getData('text');
        const emails = pastedText.split(/[,\n\s]+/).filter(email => email && validateEmail(email));
        emails.forEach(email => addTag(email));
    });

    // Add blur event handler
    ccInput.addEventListener('blur', () => {
        const email = ccInput.value.trim();
        if (email && validateEmail(email)) {
            addTag(email);
        }
    });

    // Update form submission to collect CC emails
    document.getElementById('sendCustomEmailForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Collect all form data
        const formData = new FormData(this);
        
        // Get CC emails
        const ccEmails = Array.from(ccTags.querySelectorAll('.badge'))
            .map(tag => tag.textContent.trim());
        
        // Add CC emails to form data
        formData.set('cc', ccEmails.join(','));
        
        // Add email body from CKEditor
        if (customEmailEditor && typeof customEmailEditor.getData === 'function') {
            formData.set('body', customEmailEditor.getData());
        } else {
            console.error('Custom email editor not initialized properly');
        }
        
        // Validate required fields
        const to = formData.get('to');
        const subject = formData.get('subject');
        const body = formData.get('body');
        
        if (!to || !subject || !body) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please fill in all required fields (To, Subject, and Body)'
            });
            return;
        }
        
        // Log form data for debugging
        console.log('Form data:', {
            to: to,
            cc: formData.get('cc'),
            subject: subject,
            hasBody: !!body,
            hasAttachments: formData.getAll('attachments[]').length > 0,
            csrfToken: document.querySelector('meta[name="csrf-token"]').content
        });
        
        // Show loading state
        Swal.fire({
            title: 'Sending Email...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                return response.json().then(data => {
                    console.error('Error response:', data);
                    throw new Error(data.message || `HTTP error! status: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Success response:', data);
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('sendCustomEmailModal'));
                modal.hide();

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Email sent successfully',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                throw new Error(data.message || 'Failed to send email');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.message || 'Something went wrong. Please try again later.'
            });
        });
    });

    // Clear CC tags when modal is closed
    document.getElementById('sendCustomEmailModal').addEventListener('hidden.bs.modal', function () {
        ccTags.innerHTML = '<input type="text" class="cc-input border-0" style="flex: 1; min-width: 100px;" placeholder="Add CC recipients">';
        this.querySelector('form').reset();
        customEmailEditor.setData('');
    });

    // Attach event listeners using event delegation (works across all DataTable pages)
    $('#templatesTable').on('click', '.edit-template', handleEdit);
    $('#templatesTable').on('click', '.delete-template', handleDelete);
    $('#templatesTable').on('click', '.test-template', function() {
        const templateId = this.dataset.id;
        const form = document.getElementById('testTemplateForm');
        form.action = `/email-templates/${templateId}/test`;
        // Reset form
        form.reset();
        // Show skeletons, hide fields
        document.getElementById('modalSkeletons').style.display = '';
        document.getElementById('modalFields').style.display = 'none';
        // Fetch template content
        fetch(`/email-templates/${templateId}`)
            .then(response => response.json())
            .then(data => {
                // Set CKEditor content with a slight delay to ensure editor is ready
                setTimeout(function() {
                    if (emailEditor && typeof emailEditor.setData === 'function') {
                        emailEditor.setData(data.content || '');
                    } else {
                        console.error('Email editor not initialized properly');
                    }
                }, 100);
                
                form.querySelector('[name="subject"]').value = data.subject || '';
                // Hide skeletons, show fields
                document.getElementById('modalSkeletons').style.display = 'none';
                document.getElementById('modalFields').style.display = '';
            })
            .catch(error => {
                console.error('Error:', error);
                // Hide skeletons, show fields anyway (fallback)
                document.getElementById('modalSkeletons').style.display = 'none';
                document.getElementById('modalFields').style.display = '';
            });
    });
    
    // For direct DOM elements (legacy approach)
    function attachEventListeners() {
        $('.edit-template').off('click').on('click', handleEdit);
        $('.delete-template').off('click').on('click', handleDelete);
        $('.test-template').off('click'); // Remove any direct handlers to avoid double-triggering
    }
    
    // Initialize for initial page load
    attachEventListeners();
});
</script>