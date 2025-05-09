@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Leads Management</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#syncModal">
                            Sync with API
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="leadsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Sales Name</th>
                                <th>Sales Location</th>
                                <th>Service</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Date</th>
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

<!-- Sync Modal -->
<div class="modal fade" id="syncModal" tabindex="-1" role="dialog" aria-labelledby="syncModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="syncModalLabel">Sync with API</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to sync with the API? This will update existing leads and add new ones.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="syncButton">Sync</button>
            </div>
        </div>
    </div>
</div>

<!-- Lead Details Modal -->
<div class="modal fade" id="leadDetailsModal" tabindex="-1" role="dialog" aria-labelledby="leadDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leadDetailsModalLabel">Lead Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>Name:</th>
                                <td id="detail-name"></td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td id="detail-phone"></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td id="detail-email"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Sales Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>Sales Name:</th>
                                <td id="detail-sales-name"></td>
                            </tr>
                            <tr>
                                <th>Sales Email:</th>
                                <td id="detail-sales-email"></td>
                            </tr>
                            <tr>
                                <th>Sales Location:</th>
                                <td id="detail-sales-location"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6>Service Details</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>Service:</th>
                                <td id="detail-service"></td>
                            </tr>
                            <tr>
                                <th>Service Rate:</th>
                                <td id="detail-service-rate"></td>
                            </tr>
                            <tr>
                                <th>No. of Items:</th>
                                <td id="detail-items"></td>
                            </tr>
                            <tr>
                                <th>No. of Crew:</th>
                                <td id="detail-crew"></td>
                            </tr>
                            <tr>
                                <th>Crew Rate:</th>
                                <td id="detail-crew-rate"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Location Details</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>Pickup Location:</th>
                                <td id="detail-pickup"></td>
                            </tr>
                            <tr>
                                <th>Delivery Location:</th>
                                <td id="detail-delivery"></td>
                            </tr>
                            <tr>
                                <th>Miles:</th>
                                <td id="detail-miles"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Financial Details</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>Subtotal:</th>
                                <td id="detail-subtotal"></td>
                            </tr>
                            <tr>
                                <th>Software Fee:</th>
                                <td id="detail-software-fee"></td>
                            </tr>
                            <tr>
                                <th>Truck Fee:</th>
                                <td id="detail-truck-fee"></td>
                            </tr>
                            <tr>
                                <th>Downpayment:</th>
                                <td id="detail-downpayment"></td>
                            </tr>
                            <tr>
                                <th>Grand Total:</th>
                                <td id="detail-grand-total"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Notes</h6>
                        <div id="detail-notes" class="p-3 bg-light"></div>
                    </div>
                </div>
                @if($lead->uploaded_image)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Uploaded Image</h6>
                        <img src="{{ $lead->uploaded_image }}" class="img-fluid" alt="Uploaded Image">
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#leadsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('call-center.datatable') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'phone', name: 'phone'},
            {data: 'email', name: 'email'},
            {data: 'sales_name', name: 'sales_name'},
            {data: 'sales_location', name: 'sales_location'},
            {data: 'service', name: 'service'},
            {data: 'no_of_items', name: 'no_of_items'},
            {data: 'grand_total', name: 'grand_total'},
            {data: 'date', name: 'date'},
            {data: 'status', name: 'status'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        order: [[0, 'desc']]
    });

    // Sync button click handler
    $('#syncButton').click(function() {
        $.ajax({
            url: "{{ route('call-center.sync') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    table.ajax.reload();
                } else {
                    toastr.error(response.message);
                }
                $('#syncModal').modal('hide');
            },
            error: function() {
                toastr.error('An error occurred while syncing with the API');
                $('#syncModal').modal('hide');
            }
        });
    });

    // View details click handler
    $(document).on('click', '.view-details', function() {
        var leadId = $(this).data('id');
        $.get("{{ url('call-center') }}/" + leadId, function(data) {
            $('#detail-name').text(data.name);
            $('#detail-phone').text(data.phone);
            $('#detail-email').text(data.email);
            $('#detail-sales-name').text(data.sales_name);
            $('#detail-sales-email').text(data.sales_email);
            $('#detail-sales-location').text(data.sales_location);
            $('#detail-service').text(data.service);
            $('#detail-service-rate').text('$' + data.service_rate);
            $('#detail-items').text(data.no_of_items);
            $('#detail-crew').text(data.no_of_crew);
            $('#detail-crew-rate').text('$' + data.crew_rate);
            $('#detail-pickup').text(data.pickup_location);
            $('#detail-delivery').text(data.delivery_location);
            $('#detail-miles').text(data.miles);
            $('#detail-subtotal').text('$' + data.subtotal);
            $('#detail-software-fee').text('$' + data.software_fee);
            $('#detail-truck-fee').text('$' + data.truck_fee);
            $('#detail-downpayment').text('$' + data.downpayment);
            $('#detail-grand-total').text('$' + data.grand_total);
            $('#detail-notes').text(data.notes || 'No notes available');
            $('#leadDetailsModal').modal('show');
        });
    });
});
</script>
@endpush 