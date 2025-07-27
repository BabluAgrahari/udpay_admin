@extends('CRM.Layout.layout')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row card-header pb-3">
            <div class="col-md-10">
                <h5>Products</h5>
            </div>
            <div class="col-md-2 text-right">
                <a href="{{ url('crm/products/create') }}" class="btn btn-outline-primary btn-sm">
                    <i class='bx bx-plus'></i>&nbsp;Add New
                </a>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ url('crm/products') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="category_id" class="form-select form-select-sm">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->_id }}" {{ request('category_id') == $category->_id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Select Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
                        <a href="{{ url('crm/products') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
<<<<<<< HEAD
                <table class="table table-hover">
=======
                <table id="productsTable" class="table table-hover table-sm w-100 text-nowrap">
>>>>>>> 9cae8d43cbd99df28bc9af661b0d7feb4165cf42
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>MRP</th>
                            <th>Sale Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="mt-3">
                {{-- DataTable handles pagination --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    var baseUrl = '{{ url('') }}';
    var table = $('#productsTable').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        pagingType: 'simple_numbers',
        ajax: {
            url: '{{ url('crm/products/datatable-list') }}',
            data: function (d) {
                d.search = $('input[name="search"]').val();
                d.category_id = $('select[name="category_id"]').val();
                d.status = $('select[name="status"]').val();
            }
        },
        columns: [
            { data: 'image', name: 'image', orderable: false, searchable: false, render: function(data) {
                return data ? `<img src="${data}" style="max-width:50px;">` : '<span class="text-muted">No image</span>';
            }},
            { data: 'product_name', name: 'product_name' },
            { data: 'sku', name: 'sku' },
            { data: 'category', name: 'category' },
            { data: 'mrp', name: 'mrp' },
            { data: 'sale_price', name: 'sale_price' },
            { data: 'stock', name: 'stock' },
            { data: 'status', name: 'status', render: function(data, type, row) {
                return `<div class="form-check form-switch"><input type="checkbox" class="form-check-input status-switch" data-id="${row._id}" ${data ? 'checked' : ''}></div>`;
            }},
            { data: '_id', name: 'actions', orderable: false, searchable: false, render: function(data, type, row) {
                return `<a href="${baseUrl}/crm/products/${data}/edit" class="btn btn-sm btn-icon btn-outline-primary"><i class='bx bx-edit'></i></a> 
                <button type="button" class="btn btn-sm btn-icon btn-outline-danger delete-btn" data-id="${data}"><i class='bx bx-trash'></i></button>`;
            }}
        ],
        order: [[1, 'asc']],
        lengthMenu: [10, 25, 50, 100, 500],
        pageLength: 10,
        scrollX: false,
    });

    // Filter form
    $('form[action="{{ url('crm/products') }}"]').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });
    $('form[action="{{ url('crm/products') }}"] .btn-secondary').on('click', function(e) {
        e.preventDefault();
        $('form[action="{{ url('crm/products') }}"]')[0].reset();
        table.ajax.reload();
    });

    // Status switch handler
    $('#productsTable').on('change', '.status-switch', function() {
        const id = $(this).data('id');
        const status = $(this).prop('checked');
        $.ajax({
            url: '{{ url("crm/products/update-status") }}',
            method: 'POST',
            data: {
                id: id,
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alertMsg(response.status, response.msg, 3000);
            },
            error: function(xhr) {
                alertMsg(false, xhr.responseJSON.msg || 'An error occurred while updating status.', 3000);
            }
        });
    });

    // Delete handler
    $('#productsTable').on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this product?')) {
            $.ajax({
                url: `/crm/products/${id}`,
                method: 'DELETE',
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
                    alertMsg(false, xhr.responseJSON.msg || 'An error occurred while deleting the product.', 3000);
                }
            });
        }
    });
});
</script>
@endpush 