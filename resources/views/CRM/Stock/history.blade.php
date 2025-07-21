@extends('CRM.Layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row card-header pb-3">
            <div class="col-md-10">
                <h5>Stock History</h5>
            </div>
            <div class="col-md-2 text-right">
                <a href="{{ url('crm/stock-history/create') }}" class="btn btn-outline-primary btn-sm">
                    <i class='bx bx-plus'></i> Add Inventory
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <form id="stockHistoryFilterForm" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" id="search" class="form-control form-control-sm" placeholder="Search by product name, SKU or remarks">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Product</label>
                            <select name="product_id" id="product_id" class="form-select form-select-sm">
                                <option value="">All Products</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->_id }}">{{ $product->product_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Type</label>
                            <select name="type" id="type" class="form-select form-select-sm">
                                <option value="">All Types</option>
                                <option value="up">Stock Up</option>
                                <option value="down">Stock Down</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Date Range</label>
                            <input type="text" name="date_range" id="date_range" class="form-control form-control-sm" placeholder="MM/DD/YYYY - MM/DD/YYYY">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
                                <button type="button" id="resetFilter" class="btn btn-outline-secondary btn-sm">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Stock History Table -->
            <div class="table-responsive">
                <table id="stockHistoryTable" class="table table-hover table-sm w-100 text-nowrap">
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
                    <tbody></tbody>
                </table>
            </div>
            {{-- DataTable handles pagination --}}

        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    var table = $('#stockHistoryTable').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        pagingType: 'simple_numbers',
        ajax: {
            url: '{{ url('crm/stock-history/datatable-list') }}',
            data: function (d) {
                d.search = $('#search').val();
                d.product_id = $('#product_id').val();
                d.type = $('#type').val();
                d.date_range = $('#date_range').val();
            }
        },
        columns: [
            { data: 'date_time', name: 'date_time' },
            { data: 'product', name: 'product' },
            { data: 'variant', name: 'variant' },
            { data: 'type', name: 'type' },
            { data: 'quantity', name: 'quantity' },
            { data: 'unit', name: 'unit' },
            { data: 'closing_stock', name: 'closing_stock' },
            { data: 'user', name: 'user' },
            { data: 'order', name: 'order' },
            { data: 'remarks', name: 'remarks' }
        ],
        order: [[0, 'desc']],
        lengthMenu: [10, 25, 50, 100, 500],
        pageLength: 10,
        scrollX: false,
    });

    // Filter form submit
    $('#stockHistoryFilterForm').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });
    // Reset filter
    $('#resetFilter').on('click', function() {
        $('#stockHistoryFilterForm')[0].reset();
        table.ajax.reload();
    });

    // Optionally, initialize date range picker if you have one
    // $('input[name="date_range"]').daterangepicker();
});
</script>
@endpush 