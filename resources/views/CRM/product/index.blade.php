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
            <form id="productFilterForm" class="mb-3">
                <div class="row">
                    <div class="col-md-2">
                        <input type="text" name="search" id="search" class="form-control form-control-sm" 
                               placeholder="Search products..." maxlength="255">
                    </div>
                    <div class="col-md-2">
                        <select name="category_id" id="category-filter" class="form-select form-select-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="brand_id" id="brand-filter" class="form-select form-select-sm">
                            <option value="">All Brands</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" id="status-filter" class="form-select form-select-sm">
                            <option value="">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        <button type="button" id="resetFilter" class="btn btn-secondary btn-sm">Reset</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table id="productsTable" class="table table-hover table-sm w-100 text-nowrap">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Unit</th>
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
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    // Clear error messages function
    function clearErrors() {
        $('.error').text('');
        $('.is-invalid').removeClass('is-invalid');
    }

    var table = $('#productsTable').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        pagingType: 'simple_numbers',
        ajax: {
            url: '{{ url('crm/products/datatable-list') }}',
            data: function (d) {
                d.search = $('#search').val();
                d.category_id = $('#category-filter').val();
                d.brand_id = $('#brand-filter').val();
                d.status = $('#status-filter').val();
            },
            error: function(xhr, error, thrown) {
                console.error('DataTable error:', error);
                alertMsg(false, 'Error loading data. Please try again.', 3000);
            }
        },
        columns: [
            { 
                data: 'image', 
                name: 'image', 
                orderable: false, 
                searchable: false, 
                render: function(data) {
                    if (data) {
                        return `<img src="${data}" style="max-width:50px; max-height:50px; object-fit:cover;" class="rounded" alt="Product Image">`;
                    }
                    return '<span class="text-muted">No image</span>';
                }
            },
            { 
                data: 'product_name', 
                name: 'product_name',
                render: function(data) {
                    return `<span title="${data}">${data.length > 30 ? data.substring(0, 30) + '...' : data}</span>`;
                }
            },
            { 
                data: 'sku_code', 
                name: 'sku_code',
                render: function(data) {
                    return `<span title="${data}">${data.length > 15 ? data.substring(0, 15) + '...' : data}</span>`;
                }
            },
            { 
                data: 'category', 
                name: 'category',
                render: function(data) {
                    return `<span title="${data}">${data.length > 20 ? data.substring(0, 20) + '...' : data}</span>`;
                }
            },
            { 
                data: 'brand', 
                name: 'brand',
                render: function(data) {
                    return `<span title="${data}">${data.length > 15 ? data.substring(0, 15) + '...' : data}</span>`;
                }
            },
            { 
                data: 'unit', 
                name: 'unit',
                render: function(data) {
                    return `<span title="${data}">${data}</span>`;
                }
            },
            { 
                data: 'mrp', 
                name: 'mrp',
                className: 'text-end'
            },
            { 
                data: 'product_sale_price', 
                name: 'product_sale_price',
                className: 'text-end'
            },
            { 
                data: 'product_stock', 
                name: 'product_stock',
                className: 'text-center',
                render: function(data) {
                    const stockClass = data > 10 ? 'text-success' : data > 0 ? 'text-warning' : 'text-danger';
                    return `<span class="${stockClass} fw-bold">${data}</span>`;
                }
            },
            { 
                data: 'status', 
                name: 'status', 
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
            { 
                data: 'id', 
                name: 'actions', 
                orderable: false, 
                searchable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    return `
                        <a href="{{ url('crm/products') }}/${data}/details" class="btn btn-sm btn-icon btn-outline-info" 
                           data-bs-toggle="tooltip" data-bs-placement="top" title="Details">
                            <i class='bx bx-detail'></i>
                        </a>
                        <a href="{{ url('crm/products') }}/${data}/reels" class="btn btn-sm btn-icon btn-outline-success" 
                           data-bs-toggle="tooltip" data-bs-placement="top" title="Manage Reels">
                            <i class='bx bx-video'></i>
                        </a>
                        <a href="{{ url('crm/products') }}/${data}/edit" class="btn btn-sm btn-icon btn-outline-primary" 
                           data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                            <i class='bx bx-edit'></i>
                        </a> 
                        <button type="button" class="btn btn-sm btn-icon btn-outline-danger delete-btn" 
                                data-id="${data}" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                            <i class='bx bx-trash'></i>
                        </button>`;
                }
            }
        ],
        order: [[1, 'asc']],
        lengthMenu: [10, 25, 50, 100],
        pageLength: 10,
        scrollX: false,
        language: {
            processing: '<div class="spinner-border spinner-border-sm" role="status"></div> Loading...',
            emptyTable: "No products found",
            info: "Showing _START_ to _END_ of _TOTAL_ products",
            infoEmpty: "Showing 0 to 0 of 0 products",
            infoFiltered: "(filtered from _MAX_ total products)"
        }
    });

    // Filter form submit
    $('#productFilterForm').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });

    // Reset filter
    $('#resetFilter').on('click', function() {
        $('#productFilterForm')[0].reset();
        table.ajax.reload();
    });

    // Status switch handler
    $('#productsTable').on('change', '.status-switch', function() {
        const id = $(this).data('id');
        const status = $(this).prop('checked');
        const $switch = $(this);
        
        // Disable switch during request
        $switch.prop('disabled', true);
        
        $.ajax({
            url: '{{ url("crm/products/update-status") }}',
            method: 'POST',
            data: {
                id: id,
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status) {
                    alertMsg(response.status, response.msg, 3000);
                } else {
                    // Revert switch if failed
                    $switch.prop('checked', !status);
                    alertMsg(false, response.msg || 'Failed to update status', 3000);
                }
            },
            error: function(xhr) {
                // Revert switch if failed
                $switch.prop('checked', !status);
                alertMsg(false, xhr.responseJSON?.msg || 'An error occurred while updating status.', 3000);
            },
            complete: function() {
                $switch.prop('disabled', false);
            }
        });
    });

    // Delete handler
    $('#productsTable').on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        const $btn = $(this);
        
        if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
            $btn.prop('disabled', true);
            
            $.ajax({
                url: `{{ url('crm/products') }}/${id}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status) {
                        alertMsg(response.status, response.msg, 3000);
                        table.ajax.reload();
                    } else {
                        alertMsg(false, response.msg || 'Failed to delete product', 3000);
                    }
                },
                error: function(xhr) {
                    alertMsg(false, xhr.responseJSON?.msg || 'An error occurred while deleting the product.', 3000);
                },
                complete: function() {
                    $btn.prop('disabled', false);
                }
            });
        }
    });

    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endpush 