@extends('CRM.Layout.layout')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row card-header">
            <div class="col-md-10">
                <h5>Products</h5>
            </div>
            <div class="col-md-2 text-right">
                <a href="{{ url('crm/products/create') }}" class="btn btn-primary btn-sm">
                    <i class='bx bx-plus'></i>&nbsp;Add New
                </a>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ url('crm/products') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="category_id" class="form-select">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->_id }}" {{ request('category_id') == $category->_id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Select Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ url('crm/products') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
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
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->images && count($product->images) > 0)
                                    <img src="{{ asset($product->images[0]) }}" alt="{{ $product->product_name }}" style="max-width: 50px;">
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" alt="No Image" style="max-width: 50px;">
                                @endif
                            </td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                            <td>{{ number_format($product->mrp, 2) }}</td>
                            <td>{{ number_format($product->sale_price, 2) }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input status-switch" 
                                        id="status{{ $product->_id }}" 
                                        data-id="{{ $product->_id }}"
                                        {{ $product->status ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status{{ $product->_id }}"></label>
                                </div>
                            </td>
                            <td>
                                <a href="{{ url('crm/products/'.$product->_id.'/edit') }}" class="btn btn-sm btn-icon btn-outline-primary">
                                    <i class='bx bx-edit'></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-icon btn-outline-danger delete-btn" data-id="{{ $product->_id }}">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No products found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    // Status switch handler
    $('.status-switch').change(function() {
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
    $('.delete-btn').click(function() {
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
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
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