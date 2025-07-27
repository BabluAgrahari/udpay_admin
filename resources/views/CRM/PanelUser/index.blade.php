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
<<<<<<< HEAD
            <!-- Search and Filter Section -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" id="search" placeholder="Search by name or email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="role-filter">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="vendor" {{ request('role') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="status-filter">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary" onclick="applyFilters()">
                        <i class="bx bx-search"></i> Search
                    </button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                        <i class="bx bx-refresh"></i> Clear
=======
           
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
>>>>>>> 9cae8d43cbd99df28bc9af661b0d7feb4165cf42
                    </button>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
<<<<<<< HEAD
                <table class="table table-hover">
=======
                <table id="panelUsersTable" class="table table-hover table-sm w-100 text-nowrap">
>>>>>>> 9cae8d43cbd99df28bc9af661b0d7feb4165cf42
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
<<<<<<< HEAD
                    <tbody>
                        @if ($users->isNotEmpty())
                            @foreach ($users as $key => $user)
                                <tr>
                                    <td scope="row">{{ ++$key }}</td>
                                    <td>
                                        @if($user->profile_pic)
                                            <img src="{{ $user->profile_pic }}" alt="Profile" class="rounded-circle" width="40" height="40">
                                        @else
                                            <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Default Profile" class="rounded-circle" width="40" height="40">
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-label-{{ $user->role == 'admin' ? 'danger' : 'warning' }}">
                                            {{ ucwords($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->address)
                                            {{ $user->address }}, {{ $user->city }}, {{ $user->state }} {{ $user->zip_code }}
                                        @else
                                            <span class="text-muted">No address</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" 
                                                   onchange="updateStatus('{{ $user->_id }}', this.checked)"
                                                   {{ $user->isactive ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>{{ $user->dFormat($user->created) }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="bx bx-cog"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ url('crm/panel-users/' . $user->_id) }}">
                                                        <i class="bx bx-show"></i> View
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ url('crm/panel-users/' . $user->_id . '/edit') }}">
                                                        <i class="bx bx-edit"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ url('crm/panel-users/' . $user->_id . '/change-password') }}">
                                                        <i class="bx bx-key"></i> Change Password
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="deleteUser('{{ $user->_id }}')">
                                                        <i class="bx bx-trash"></i> Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="text-center">
                                    <i class="fa fa-exclamation-circle" style="font-size: 24px; color: #999;"></i>
                                    <p>No panel users found</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            {{ $users->appends($_GET)->links() }}
=======
                    <tbody></tbody>
                </table>
            </div>
            {{-- DataTable handles pagination --}}
>>>>>>> 9cae8d43cbd99df28bc9af661b0d7feb4165cf42
        </div>
    </div>
</div>

@push('script')
<script>
<<<<<<< HEAD
    $(document).ready(function() {
        console.log('Document ready');
        
        // Simple dropdown test
        $('.dropdown-toggle').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Dropdown clicked');
            
            var $dropdown = $(this).closest('.dropdown');
            var $menu = $dropdown.find('.dropdown-menu');
            
            // Close other dropdowns
            $('.dropdown-menu').not($menu).removeClass('show');
            $('.dropdown-toggle').not(this).removeClass('show');
            
            // Toggle current dropdown
            $menu.toggleClass('show');
            $(this).toggleClass('show');
            
            console.log('Menu toggled:', $menu.hasClass('show'));
        });
        
        // Close dropdowns when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('.dropdown-menu').removeClass('show');
                $('.dropdown-toggle').removeClass('show');
            }
        });
        
        // Test Bootstrap availability
        if (typeof bootstrap !== 'undefined') {
            console.log('Bootstrap available:', bootstrap);
        } else {
            console.log('Bootstrap not available');
        }
    });

    function applyFilters() {
        const search = document.getElementById('search').value;
        const role = document.getElementById('role-filter').value;
        const status = document.getElementById('status-filter').value;
        
        let url = '{{ url("crm/panel-users") }}?';
        const params = new URLSearchParams();
        
        if (search) params.append('search', search);
        if (role) params.append('role', role);
        if (status) params.append('status', status);
        
        window.location.href = url + params.toString();
    }

    function clearFilters() {
        window.location.href = '{{ url("crm/panel-users") }}';
    }

    function updateStatus(userId, status) {
        if (confirm('Are you sure you want to ' + (status ? 'activate' : 'deactivate') + ' this user?')) {
            $.ajax({
                url: "{{ url('crm/panel-users/update-status') }}",
                type: 'POST',
                data: {
                    id: userId,
                    status: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status) {
                        alertMsg(response.status, response.msg, 3000);
                    } else {
                        alertMsg(false, response.msg, 3000);
                        // Revert the checkbox
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function() {
                    alertMsg(false, 'Something went wrong!', 3000);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            });
        } else {
            // Revert the checkbox if user cancels
            setTimeout(() => {
                location.reload();
            }, 100);
        }
    }

    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this panel user? This action cannot be undone.')) {
            $.ajax({
                url: "{{ url('crm/panel-users') }}/" + userId,
=======
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
>>>>>>> 9cae8d43cbd99df28bc9af661b0d7feb4165cf42
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status) {
                        alertMsg(response.status, response.msg, 3000);
<<<<<<< HEAD
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
=======
                        table.ajax.reload();
>>>>>>> 9cae8d43cbd99df28bc9af661b0d7feb4165cf42
                    } else {
                        alertMsg(false, response.msg, 3000);
                    }
                },
                error: function() {
                    alertMsg(false, 'Something went wrong!', 3000);
                }
            });
        }
<<<<<<< HEAD
    }
=======
    });
});
>>>>>>> 9cae8d43cbd99df28bc9af661b0d7feb4165cf42
</script>
@endpush
@endsection 