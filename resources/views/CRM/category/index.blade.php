@extends('CRM.Layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row card-header">
            <div class="col-md-10">
                <h5>Categories</h5>
            </div>
            <div class="col-md-2 text-right">
                <a href="{{ url('crm/categories/create') }}" class="btn btn-outline-primary btn-sm">
                    <i class='bx bx-plus'></i>&nbsp;Add New
                </a>
            </div>
        </div>

        <div class="card-body">
            <div id="message"></div>
            
            <!-- Filter Section -->
            <form action="{{ url('crm/categories') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Search</label>
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search by name or description">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Parent Category</label>
                            <select name="parent_id" class="form-select">
                                <option value="">All</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->_id }}" 
                                            {{ request('parent_id') == $parent->_id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class='bx bx-search'></i> Filter
                                </button>
                                <a href="{{ url('crm/categories') }}" class="btn btn-secondary">
                                    <i class='bx bx-reset'></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Table Section -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Short</th>
                            <th>Parent</th>
                            <th>Status</th>
                            <th>Labels</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>
                                    @if($category->icon)
                                        <img src="{{ $category->icon }}" alt="Category Icon" class="img-thumbnail" style="max-height: 50px;">
                                    @else
                                        <span class="text-muted">No icon</span>
                                    @endif
                                </td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="text" 
                                               class="form-control short-code" 
                                               value="{{ $category->short }}" 
                                               data-id="{{ $category->_id }}"
                                               placeholder="Enter short code">
                                        <button class="btn btn-outline-primary update-short" 
                                                type="button"
                                                data-id="{{ $category->_id }}">
                                            <i class='bx bx-save'></i>
                                        </button>
                                    </div>
                                </td>
                                <td>{{ $category->parent ? $category->parent->name : 'None' }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" 
                                               class="form-check-input status-toggle" 
                                               id="status_{{ $category->_id }}" 
                                               data-id="{{ $category->_id }}"
                                               {{ $category->status ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status_{{ $category->_id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    @if($category->labels)
                                        @foreach($category->labels as $label)
                                            <span class="badge bg-label-info">{{ $label }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ url('crm/categories/'.$category->_id.'/edit') }}" 
                                       class="btn btn-sm btn-icon btn-outline-primary">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-icon btn-outline-danger delete-category" 
                                            data-id="{{ $category->_id }}">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    // Status toggle functionality
    $('.status-toggle').change(function() {
        const categoryId = $(this).data('id');
        const status = $(this).prop('checked') ? 1 : 0;
        const $toggle = $(this);
        
        $.ajax({
            url: "{{ url('crm/categories/update-status') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id: categoryId,
                status: status
            },
            beforeSend: function() {
                $toggle.prop('disabled', true);
            },
            success: function(res) {
                $toggle.prop('disabled', false);
                alertMsg(res.status, res.msg, 3000);
                if (!res.status) {
                    $toggle.prop('checked', !status);
                }
            },
            error: function(xhr) {
                $toggle.prop('disabled', false);
                $toggle.prop('checked', !status);
                if (xhr.status === 422) {
                    alertMsg(false, xhr.responseJSON.validation, 3000);
                } else if (xhr.status === 400) {
                    alertMsg(false, xhr.responseJSON.msg, 3000);
                } else {
                    alertMsg(false, xhr.responseJSON.msg || 'An error occurred while processing your request.', 3000);
                }
            }
        });
    });

    // Short code update functionality
    $('.update-short').click(function() {
        const categoryId = $(this).data('id');
        const $input = $(this).closest('.input-group').find('.short-code');
        const shortCode = $input.val();
        const $btn = $(this);
        
        $.ajax({
            url: "{{ url('crm/categories/update-short') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id: categoryId,
                short: shortCode
            },
            beforeSend: function() {
                $btn.prop('disabled', true);
            },
            success: function(res) {
                $btn.prop('disabled', false);
                alertMsg(res.status, res.msg, 3000);
                if (!res.status) {
                    $input.val($input.data('original-value'));
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false);
                if (xhr.status === 422) {
                    alertMsg(false, xhr.responseJSON.validation, 3000);
                } else if (xhr.status === 400) {
                    alertMsg(false, xhr.responseJSON.msg, 3000);
                } else {
                    alertMsg(false, xhr.responseJSON.msg || 'An error occurred while processing your request.', 3000);
                }
            }
        });
    });

    // Store original short code value
    $('.short-code').each(function() {
        $(this).data('original-value', $(this).val());
    });

    // Delete functionality
    $('.delete-category').click(function() {
        const categoryId = $(this).data('id');
        const $row = $(this).closest('tr');
        
        if (confirm('Are you sure you want to delete this category?')) {
            $.ajax({
                url: "{{ url('crm/categories') }}/" + categoryId,
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    $row.find('button').prop('disabled', true);
                },
                success: function(res) {
                    alertMsg(res.status, res.msg, 3000);
                    if (res.status) {
                        $row.fadeOut(400, function() {
                            $(this).remove();
                            if ($('tbody tr').length === 0) {
                                $('tbody').html('<tr><td colspan="7" class="text-center">No categories found.</td></tr>');
                            }
                        });
                    }
                },
                error: function(xhr) {
                    $row.find('button').prop('disabled', false);
                    if (xhr.status === 422) {
                        alertMsg(false, xhr.responseJSON.validation, 3000);
                    } else if (xhr.status === 400) {
                        alertMsg(false, xhr.responseJSON.msg, 3000);
                    } else {
                        alertMsg(false, xhr.responseJSON.msg || 'An error occurred while processing your request.', 3000);
                    }
                }
            });
        }
    });
});
</script>
@endpush