@extends('CRM.Layout.layout')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header pt-3 pb-3">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="mb-0">Panel Users</h5>
                </div>
                <div class="col-md-8">
                    <div class="d-flex justify-content-end">
                        <a href="{{ url('crm/panel-users/create') }}" class="btn btn-sm btn-outline-primary">
                            <i class='bx bxs-plus-circle'></i>&nbsp;Create Panel User
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
           
            <!-- Search and Filter Section -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" class="form-control form-control-sm" id="search" placeholder="Search by name or email...">
                </div>
                <div class="col-md-2">
                    <select class="form-select form-select-sm" id="role-filter">
                        <option value="">All Roles</option>
                        <option value="admin">Admin</option>
                        <option value="vendor">Vendor</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select form-select-sm" id="status-filter">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-primary btn-sm" id="applyFilters">
                        Search
                    </button>
                
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="clearFilters">
                       Clear
                    </button>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table id="panelUsersTable" class="table table-hover table-sm w-100 text-nowrap">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th>Profile</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            {{-- DataTable handles pagination --}}
        </div>
    </div>
</div>

@push('script')
<script>
$(document).ready(function() {
    var baseUrl = '{{ url('') }}';
    var table = $('#panelUsersTable').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        pagingType: 'simple_numbers',
        ajax: {
            url: '{{ url('crm/panel-users/datatable-list') }}',
            data: function (d) {
                d.search = $('#search').val();
                d.role = $('#role-filter').val();
                d.status = $('#status-filter').val();
            }
        },
        columns: [
            { data: 'index', name: 'index', orderable: false, searchable: false },
            { data: 'profile', name: 'profile', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'role', name: 'role' },
            { data: 'address', name: 'address' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: '_id', name: 'actions', orderable: false, searchable: false, render: function(data, type, row) {
                return `<div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bx bx-cog"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="${baseUrl}/crm/panel-users/${data}"><i class="bx bx-show"></i> View</a></li>
                        <li><a class="dropdown-item" href="${baseUrl}/crm/panel-users/${data}/edit"><i class="bx bx-edit"></i> Edit</a></li>
                        <li><a class="dropdown-item" href="${baseUrl}/crm/panel-users/${data}/change-password"><i class="bx bx-key"></i> Change Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger delete-user" href="javascript:void(0);" data-id="${data}"><i class="bx bx-trash"></i> Delete</a></li>
                    </ul>
                </div>`;
            }}
        ],
        order: [[7, 'desc']],
        lengthMenu: [10, 25, 50, 100, 500],
        pageLength: 10,
        scrollX: false,
    });

    // Filter handlers
    $('#applyFilters').on('click', function() {
        table.ajax.reload();
    });
    $('#clearFilters').on('click', function() {
        $('#search').val('');
        $('#role-filter').val('');
        $('#status-filter').val('');
        table.ajax.reload();
    });

    // Status switch handler
    $('#panelUsersTable').on('change', '.status-switch', function() {
        const id = $(this).data('id');
        const status = $(this).prop('checked');
        $.ajax({
            url: '{{ url("crm/panel-users/update-status") }}',
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
    $('#panelUsersTable').on('click', '.delete-user', function() {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this panel user? This action cannot be undone.')) {
            $.ajax({
                url: `${baseUrl}/crm/panel-users/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status) {
                        alertMsg(response.status, response.msg, 3000);
                        table.ajax.reload();
                    } else {
                        alertMsg(false, response.msg, 3000);
                    }
                },
                error: function() {
                    alertMsg(false, 'Something went wrong!', 3000);
                }
            });
        }
    });
});
</script>
@endpush
@endsection 