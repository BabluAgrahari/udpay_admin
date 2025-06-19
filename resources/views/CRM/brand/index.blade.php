@extends('CRM.Layout.layout')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row card-header">
            <div class="col-md-10">
                <h5>Brands</h5>
            </div>
            <div class="col-md-2 text-right">
                <a href="{{ url('crm/brands/create') }}" class="btn btn-primary btn-sm">
                    <i class='bx bx-plus'></i>&nbsp;Add New
                </a>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ url('crm/brands') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">Select Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ url('crm/brands') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Slug URL</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brands as $brand)
                        <tr>
                            <td>
                                @if($brand->icon)
                                    <img src="{{ asset($brand->icon) }}" alt="{{ $brand->name }}" style="max-width: 50px;">
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" alt="No Image" style="max-width: 50px;">
                                @endif
                            </td>
                            <td>{{ $brand->name }}</td>
                            <td>{{ $brand->slug_url }}</td>
                            <td>{{ Str::limit($brand->description, 50) }}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input status-switch" 
                                        id="status{{ $brand->_id }}" 
                                        data-id="{{ $brand->_id }}"
                                        {{ $brand->status ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status{{ $brand->_id }}"></label>
                                </div>
                            </td>
                            <td>
                                <a href="{{ url('crm/brands/'.$brand->_id.'/edit') }}" class="btn btn-info btn-sm">
                                    <i class='bx bx-edit'></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $brand->_id }}">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No brands found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $brands->links() }}
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
            url: '{{ url("crm/brands/update-status") }}',
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
        
        if (confirm('Are you sure you want to delete this brand?')) {
            $.ajax({
                url: `/crm/brands/${id}`,
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
                    alertMsg(false, xhr.responseJSON.msg || 'An error occurred while deleting the brand.', 3000);
                }
            });
        }
    });
});
</script>
@endpush 