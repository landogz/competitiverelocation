<div class="btn-group">
    <button type="button" class="btn btn-sm btn-info view-details" data-id="{{ $lead->id }}" title="View Details">
        <i class="fas fa-eye"></i>
    </button>
    <button type="button" class="btn btn-sm btn-primary edit-lead" data-id="{{ $lead->id }}" title="Edit Lead">
        <i class="fas fa-edit"></i>
    </button>
    <button type="button" class="btn btn-sm btn-success send-quote" data-id="{{ $lead->id }}" title="Send Quote">
        <i class="fas fa-file-invoice-dollar"></i>
    </button>
    <button type="button" class="btn btn-sm btn-danger delete-lead" data-id="{{ $lead->id }}" title="Delete Lead">
        <i class="fas fa-trash"></i>
    </button>
</div> 