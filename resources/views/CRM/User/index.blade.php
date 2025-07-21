@extends('CRM.Layout.layout')
@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row card-header pb-3">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="mb-0">User List</h5>
                    </div>
                    <div class="col-md-8 ml-auto">
                        <ul class="nav nav-tabs justify-content-end" id="userStatusTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="active-users-tab" data-status="1" type="button" role="tab">Active Users</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="inactive-users-tab" data-status="0" type="button" role="tab">Inactive Users</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form id="userFilterForm" class="mb-3">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name, email, or mobile">
                        </div>
                        <!-- Remove status dropdown -->
                        <div class="col-md-2 mb-2">
                            <select name="kyc_status" class="form-select form-select-sm">
                                <option value="">All KYC</option>
                                <option value="with_kyc">With KYC</option>
                                <option value="without_kyc">Without KYC</option>
                                <option value="verified">KYC Verified</option>
                                <option value="pending">KYC Pending</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <select name="gender" class="form-select form-select-sm">
                                <option value="">All Genders</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
                            <button type="button" id="resetFilter" class="btn btn-outline-secondary btn-sm">Reset</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive text-nowrap">
                    <table id="usersTable" class="table table-hover table-sm w-100 text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th>Name</th>
                                <th>Email/Mobile</th>
                                <th>Role</th>
                                <th>KYC Status</th>
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

    @push('modal')
        <div class="modal fade" id="salarySetupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title" id="modalToggleLabel">Loan Details</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>

                    <div class="modal-body" id="tbody">

                    </div>

                    <div class="modal-footer">

                    </div>

                </div>

            </div>

        </div>
    @endpush

    @push('script')
        <style>
            .dropdown-menu {
                position: absolute;
                top: 100%;
                left: 0;
                z-index: 1000;
                display: none;
                float: left;
                min-width: 10rem;
                padding: 0.5rem 0;
                margin: 0.125rem 0 0;
                font-size: 0.875rem;
                color: #212529;
                text-align: left;
                list-style: none;
                background-color: #fff;
                background-clip: padding-box;
                border: 1px solid rgba(0, 0, 0, 0.15);
                border-radius: 0.375rem;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
            }

            .dropdown-menu.show {
                display: block;
            }

            .dropdown-item {
                display: block;
                width: 100%;
                padding: 0.25rem 1rem;
                clear: both;
                font-weight: 400;
                color: #212529;
                text-align: inherit;
                text-decoration: none;
                white-space: nowrap;
                background-color: transparent;
                border: 0;
            }

            .dropdown-item:hover {
                color: #1e2125;
                background-color: #e9ecef;
            }

            .dropdown-divider {
                height: 0;
                margin: 0.5rem 0;
                overflow: hidden;
                border-top: 1px solid #e9ecef;
            }

            .dropdown {
                position: relative;
            }
        </style>

        <script>
            $(document).ready(function() {
                var baseUrl = '{{ url('') }}';
                // Default status filter to active
                var defaultStatus = '1';
                var table = $('#usersTable').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    pagingType: 'simple_numbers',
                    ajax: {
                        url: '{{ url('crm/user/datatable-list') }}',
                        data: function (d) {
                            d.search = $('input[name="search"]').val();
                            d.status = defaultStatus;
                            d.kyc_status = $('select[name="kyc_status"]').val();
                            d.gender = $('select[name="gender"]').val();
                        }
                    },
                    columns: [
                        { data: 'index', name: 'index', orderable: false, searchable: false },
                        { data: 'name', name: 'name' },
                        { data: 'email_mobile', name: 'email_mobile' },
                        { data: 'role', name: 'role' },
                        { data: 'kyc_status', name: 'kyc_status' },
                        { data: 'address', name: 'address' },
                        { data: 'status', name: 'status' },
                        { data: 'created_at', name: 'created_at' },
                        { data: '_id', name: 'actions', orderable: false, searchable: false, render: function(data, type, row) {
                            return `<div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenu${data}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-cog"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu${data}">
                                    <li><a class="dropdown-item" href="${baseUrl}/crm/user/${data}/edit"><i class="bx bx-edit"></i> Edit User</a></li>
                                    <li><a class="dropdown-item" href="${baseUrl}/crm/user/${data}/kyc"><i class="bx bx-id-card"></i> Manage KYC</a></li>
                                    <li><a class="dropdown-item" href="${baseUrl}/crm/user/${data}"><i class="bx bx-show"></i> View Details</a></li>
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
                    initComplete: function() {
                        // Set default tab to active
                        $('#active-users-tab').addClass('active');
                        $('#inactive-users-tab').removeClass('active');
                    }
                });

                // Tab click handler
                $('#userStatusTabs').on('click', '.nav-link', function() {
                    $('#userStatusTabs .nav-link').removeClass('active');
                    $(this).addClass('active');
                    defaultStatus = $(this).data('status');
                    table.ajax.reload();
                });

                // Filter form submit
                $('#userFilterForm').on('submit', function(e) {
                    e.preventDefault();
                    table.ajax.reload();
                });
                // Reset filter
                $('#resetFilter').on('click', function() {
                    $('#userFilterForm')[0].reset();
                    // Reset to active users tab
                    $('#userStatusTabs .nav-link').removeClass('active');
                    $('#active-users-tab').addClass('active');
                    defaultStatus = '1';
                    table.ajax.reload();
                });

                // Delete handler
                $('#usersTable').on('click', '.delete-user', function() {
                    const id = $(this).data('id');
                    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                        $.ajax({
                            url: `${baseUrl}/crm/user/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.status) {
                                    alertMsg(true, response.msg, 3000);
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