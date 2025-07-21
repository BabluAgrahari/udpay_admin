@extends('CRM.Layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row card-header pb-3">
            <div class="col-md-8">
                <h5>Orders</h5>
            </div>
            <div class="col-md-4 text-end">
                <button type="button" id="insertTestData" class="btn btn-warning btn-sm">
                    <i class="bx bx-data"></i> Insert Test Data (5L)
                </button>
            </div>
        </div>

        <div class="card-body">
            <form id="orderFilterForm" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" id="search" class="form-control form-control-sm" placeholder="Search orders...">
                    </div>
                    <div class="col-md-3">
                        <select name="payment_status" id="payment-status-filter" class="form-select form-select-sm">
                            <option value="">All Payment Status</option>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="failed">Failed</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="order_status" id="order-status-filter" class="form-select form-select-sm">
                            <option value="">All Order Status</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        <button type="button" id="resetFilter" class="btn btn-secondary btn-sm">Reset</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table id="ordersTable" class="table table-hover table-sm w-100 text-nowrap">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Order Number</th>
                            <th>Customer</th>
                            <th>Order Date</th>
                            <th>Amount</th>
                            <th>Payment Info</th>
                            <th>Order Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    var table = $('#ordersTable').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        pagingType: 'simple_numbers',
        ajax: {
            url: '{{ url('crm/orders/datatable-list') }}',
            data: function (d) {
                d.search = $('#search').val();
                d.payment_status = $('#payment-status-filter').val();
                d.order_status = $('#order-status-filter').val();
            }
        },
        columns: [
            { data: 'index', name: 'index', orderable: false, searchable: false },
            { data: 'order_number', name: 'order_number' },
            { data: 'customer', name: 'customer', orderable: false, searchable: false },
            { data: 'order_date', name: 'order_date' },
            { data: 'amount', name: 'amount', orderable: false, searchable: false },
            { data: 'payment_info', name: 'payment_info', orderable: false, searchable: false },
            { data: 'order_status', name: 'order_status', orderable: false, searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[3, 'desc']], // Order by date descending
        lengthMenu: [10, 25, 50, 100, 500],
        pageLength: 10,
        scrollX: false,
    });

    // Filter form submit
    $('#orderFilterForm').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });

    // Reset filter
    $('#resetFilter').on('click', function() {
        $('#orderFilterForm')[0].reset();
        table.ajax.reload();
    });

    // Insert Test Data handler
    $('#insertTestData').on('click', function() {
        if (confirm('This will insert 5 lakh (500,000) test orders. This may take several minutes. Are you sure you want to continue?')) {
            const button = $(this);
            button.html('<span class="spinner-border spinner-border-sm me-2"></span>Inserting...').prop('disabled', true);
            
            $.ajax({
                url: '{{ url("crm/orders/insert-test-data") }}',
                method: 'GET',
                success: function(response) {
                    alertMsg(response.status, response.msg, 5000);
                    if (response.status) {
                        table.ajax.reload();
                    }
                },
                error: function(xhr) {
                    alertMsg(false, xhr.responseJSON?.msg || 'An error occurred while inserting test data.', 5000);
                },
                complete: function() {
                    button.html('<i class="bx bx-data"></i> Insert Test Data (5L)').prop('disabled', false);
                }
            });
        }
    });

    // Push to Courier handler
    $('#ordersTable').on('click', '.push-to-courier', function(e) {
        e.preventDefault();
        const orderId = $(this).data('id');
        const button = $(this);
        
        if (confirm('Are you sure you want to push this order to courier?')) {
            button.html('<span class="spinner-border spinner-border-sm me-2"></span>Pushing...').prop('disabled', true);
            
            $.ajax({
                url: '{{ url("crm/orders/push-to-courier") }}/' + orderId,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alertMsg(response.status, response.msg, 3000);
                    if (response.status) {
                        table.ajax.reload();
                    }
                },
                error: function(xhr) {
                    alertMsg(false, xhr.responseJSON?.msg || 'An error occurred while pushing to courier.', 3000);
                },
                complete: function() {
                    button.html('<i class="bx bx-send me-2"></i>Push to Courier').prop('disabled', false);
                }
            });
        }
    });
});
</script>
@endpush
