@extends('CRM.Layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
<<<<<<< HEAD
        <div class="row card-header">
            <div class="col-md-10">
                <h5>Orders</h5>
            </div>
            
        </div>

        <div class="card-body">
            <form action="{{ url('crm/order') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by order number, customer name, email or phone..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="payment_status" class="form-select">
                            <option value="">All Payment Status</option>
                            <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="order_status" class="form-select">
                            <option value="">All Order Status</option>
                            <option value="pending" {{ request('order_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('order_status') === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ request('order_status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ request('order_status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ request('order_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="date_range" class="form-control" placeholder="Date Range (MM/DD/YYYY - MM/DD/YYYY)" value="{{ request('date_range') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ url('crm/order') }}" class="btn btn-secondary">Reset</a>
=======
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
>>>>>>> 9cae8d43cbd99df28bc9af661b0d7feb4165cf42
                    </div>
                </div>
            </form>

            <div class="table-responsive">
<<<<<<< HEAD
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Customer</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                            <th>Payment Status</th>
                            <th>Order Status</th>
                            <th>Payment Method</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                <strong>{{ $order->order_number }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $order->customer_name }}</strong><br>
                                    <small class="text-muted">{{ $order->customer_email }}</small><br>
                                    <small class="text-muted">{{ $order->customer_phone }}</small>
                                </div>
                            </td>
                            <td>{{ $order->dFormat($order->order_date) }}</td>
                            <td>
                                <strong>₹{{ number_format($order->final_amount, 2) }}</strong><br>
                                <small class="text-muted">Tax: ₹{{ number_format($order->tax_amount, 2) }}</small><br>
                                <small class="text-muted">Discount: ₹{{ number_format($order->discount_amount, 2) }}</small>
                            </td>
                            <td>
                                @if($order->payment_status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($order->payment_status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($order->payment_status === 'failed')
                                    <span class="badge bg-danger">Failed</span>
                                @elseif($order->payment_status === 'refunded')
                                    <span class="badge bg-info">Refunded</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($order->order_status === 'delivered')
                                    <span class="badge bg-success">Delivered</span>
                                @elseif($order->order_status === 'shipped')
                                    <span class="badge bg-info">Shipped</span>
                                @elseif($order->order_status === 'processing')
                                    <span class="badge bg-primary">Processing</span>
                                @elseif($order->order_status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($order->order_status === 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($order->order_status) }}</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($order->payment_method) }}</td>
                            <td>
                                <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-outline-primary" title="View">
                                    <i class='bx bx-show'></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-outline-success" title="Edit">
                                    <i class='bx bx-edit'></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-icon btn-outline-danger" title="Delete">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No orders found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $orders->links() }}
            </div>
=======
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
>>>>>>> 9cae8d43cbd99df28bc9af661b0d7feb4165cf42
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
<<<<<<< HEAD
    // Initialize date range picker if you have one
    // $('input[name="date_range"]').daterangepicker();
});
</script>
@endpush 
=======
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
>>>>>>> 9cae8d43cbd99df28bc9af661b0d7feb4165cf42
