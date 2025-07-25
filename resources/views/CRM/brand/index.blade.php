@extends('CRM.Layout.layout')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row card-header pb-0">
            <div class="col-md-10">
                <h5>Brands</h5>
            </div>
            <div class="col-md-2 text-right">
                <a href="{{ url('crm/brands/create') }}" class="btn btn-outline-primary btn-sm">
                    <i class='bx bx-plus'></i>&nbsp;Add New
                </a>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ url('crm/brands') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Select Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
                        <a href="{{ url('crm/brands') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive mt-3">
                <table id="brandsTable" class="table table-hover w-100 text-nowrap">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Description</th>
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
    var table = $('#brandsTable').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        pagingType: 'simple_numbers',
        ajax: {
            url: '{{ url('crm/brands/datatable-list') }}',
            data: function (d) {
                d.search = $('input[name="search"]').val();
                d.status = $('select[name="status"]').val();
            }
        },
        columns: [
            { data: 'icon', name: 'icon', orderable: false, searchable: false, render: function(data) {
                return data ? `<img src="${data}" style="max-width:50px;">` : '<span class="text-muted">No image</span>';
            }},
            { data: 'name', name: 'name' },
           

            
            { data: 'description', name: 'description', render: function(data) {
                return data ? data.substring(0, 50) : '';
            }},
            { data: 'status', name: 'status', render: function(data, type, row) {
                return `<div class="form-check form-switch"><input type="checkbox" class="form-check-input status-switch" data-id="${row._id}" ${data ? 'checked' : ''}></div>`;
            }},
            { data: '_id', name: 'actions', orderable: false, searchable: false, render: function(data, type, row) {
                return `<a href="${baseUrl}/crm/brands/${data}/edit" class="btn btn-sm btn-icon btn-outline-primary"><i class='bx bx-edit'></i></a> 
                <button type="button" class="btn btn-sm btn-icon btn-outline-danger delete-brand" data-id="${data}"><i class='bx bx-trash'></i></button>`;
            }}
        ],
        order: [[1, 'asc']],
        lengthMenu: [10, 25, 50, 100, 500],
        pageLength: 10,
        scrollX: false,
    });

    // Filter form
    $('form[action="{{ url('crm/brands') }}"]').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });
    $('form[action="{{ url('crm/brands') }}"] .btn-secondary').on('click', function(e) {
        e.preventDefault();
        $('form[action="{{ url('crm/brands') }}"]')[0].reset();
        table.ajax.reload();
    });

    // Status switch handler
    $('#brandsTable').on('change', '.status-switch', function() {
        const id = $(this).data('id');
        const status = $(this).prop('checked');
        const $toggle = $(this);
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
    $('#brandsTable').on('click', '.delete-btn', function() {
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
                        table.ajax.reload();
                    }
                },
                error: function(xhr) {
                    alertMsg(false, xhr.responseJSON.msg || 'An error occurred while deleting the brand.', 3000);
                }
            });
        }
    });
});
var baseUrl = '{{ url('') }}';
</script>
@endpush 