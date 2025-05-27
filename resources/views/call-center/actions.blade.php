<div class="btn-group">
    <button type="button" class="btn btn-sm btn-info view-details position-relative" data-id="{{ $lead->id }}" title="View Logs">
        <i class="fas fa-history"></i>
        @php
            $logCount = $lead->logs()->count();
        @endphp
        @if($logCount > 0)
            <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                {{ $logCount }}
                <span class="visually-hidden">logs</span>
            </span>
        @endif
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