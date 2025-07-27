@extends('CRM.Layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
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
                    </div>
                </div>
            </form>

            <div class="table-responsive">
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
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    // Initialize date range picker if you have one
    // $('input[name="date_range"]').daterangepicker();
});
</script>
@endpush 