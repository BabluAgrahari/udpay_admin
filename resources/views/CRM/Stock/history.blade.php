@extends('CRM.Layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row card-header">
            <div class="col-md-10">
                <h5>Stock History</h5>
            </div>
            <div class="col-md-2 text-right">
                <a href="{{ url('crm/stock-history/create') }}" class="btn btn-primary btn-sm">
                    <i class='bx bx-plus'></i> Add Inventory
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <form action="{{ url('crm/stock-history') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Search</label>
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search by product name, SKU or remarks">
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Product</label>
                            <select name="product_id" class="form-select">
                                <option value="">All Products</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->_id }}" 
                                            {{ request('product_id') == $product->_id ? 'selected' : '' }}>
                                        {{ $product->product_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="up" {{ request('type') === 'up' ? 'selected' : '' }}>Stock Up</option>
                                <option value="down" {{ request('type') === 'down' ? 'selected' : '' }}>Stock Down</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Date Range</label>
                            <input type="text" 
                                   name="date_range" 
                                   class="form-control" 
                                   value="{{ request('date_range') }}" 
                                   placeholder="MM/DD/YYYY - MM/DD/YYYY">
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class='bx bx-search'></i> Filter
                                </button>
                                <a href="{{ url('crm/stock-history') }}" class="btn btn-secondary">
                                    <i class='bx bx-reset'></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Stock History Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Closing Stock</th>
                            <th>User</th>
                            <th>Order</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockHistory as $stock)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $stock->dFormat($stock->created) }}</strong><br>
                                    <small class="text-muted">{{ date('H:i:s', $stock->created) }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $stock->product->product_name ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">SKU: {{ $stock->product->sku ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                @if($stock->productVariant)
                                    <span class="badge bg-info">
                                        {{ is_array($stock->productVariant->attributes) ? json_encode($stock->productVariant->attributes) : $stock->productVariant->attributes }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($stock->type === 'up')
                                    <span class="badge bg-success">
                                        <i class='bx bx-up-arrow-alt'></i> Up
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class='bx bx-down-arrow-alt'></i> Down
                                    </span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ number_format($stock->stock, 2) }}</strong>
                            </td>
                            <td>
                                {{ $stock->unit->unit ?? 'N/A' }}
                            </td>
                            <td>
                                <strong>{{ number_format($stock->closing_stock, 2) }}</strong>
                            </td>
                            <td>
                                {{ $stock->user->name ?? 'N/A' }}
                            </td>
                            <td>
                                @if($stock->order)
                                    <a href="javascript:void(0);" class="text-primary">
                                        {{ $stock->order->order_number ?? 'N/A' }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($stock->remarks)
                                    <span title="{{ $stock->remarks }}">
                                        {{ Str::limit($stock->remarks, 30) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">No stock history found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $stockHistory->links() }}
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
    
    // Auto-submit form when select values change
    $('select[name="product_id"], select[name="type"]').change(function() {
        $(this).closest('form').submit();
    });
});
</script>
@endpush 