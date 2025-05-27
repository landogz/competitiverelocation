@extends('includes.app')

@section('title', 'Lead Logs')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Lead Logs</h4>
                <div>
                    <a href="{{ route('callcenter.show', $lead) }}" class="btn btn-secondary">Back to Lead</a>
                </div>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div><!--end row-->
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Interaction Logs for {{ $lead->name }}</h4>
                    <button type="button" class="btn btn-primary btn-sm add-log-btn" data-lead-id="{{ $lead->id }}">
                        Add Log
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Content</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('m/d/Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $log->type === 'call' ? 'primary' : ($log->type === 'email' ? 'info' : ($log->type === 'meeting' ? 'success' : 'secondary')) }}">
                                            {{ ucfirst($log->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $log->content }}</td>
                                    <td>{{ $log->user_id ?? 'System' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No logs found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Log Modal -->
<div class="modal fade" id="addLogModal" tabindex="-1" aria-labelledby="addLogModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLogModalLabel">Add Log Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addLogForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="type" class="form-label">Log Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="call">Call</option>
                            <option value="email">Email</option>
                            <option value="note">Note</option>
                            <option value="meeting">Meeting</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Log</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Handle Add Log button click
        $('.add-log-btn').on('click', function() {
            var leadId = $(this).data('lead-id');
            $('#addLogForm').attr('action', '/callcenter/' + leadId + '/logs');
            $('#addLogModal').modal('show');
        });
    });
</script>
@endpush 