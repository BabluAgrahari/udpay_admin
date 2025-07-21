@extends('CRM.Layout.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row card-header pb-3">
                <div class="col-md-10">
                    <h5>Pickup Addresses</h5>
                </div>
                <div class="col-md-2 text-right">
                    <a href="javascript:void(0);" class="add btn-sm btn btn-outline-primary"><i
                            class='bx bxs-plus-circle'></i>&nbsp;Add</a>
                </div>
            </div>

            <div class="card-body">
                <form id="pickupAddressFilterForm" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" id="search" class="form-control form-control-sm" placeholder="Search name, email, phone, location...">
                        </div>
                        <div class="col-md-3">
                            <select name="type" id="type-filter" class="form-select form-select-sm">
                                <option value="">All Types</option>
                                <option value="pickup_address">Pickup Address</option>
                                <option value="rto_address">RTO Address</option>
                                <option value="return_address">Return Address</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" id="status-filter" class="form-select form-select-sm">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                            <button type="button" id="resetFilter" class="btn btn-secondary btn-sm">Reset</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="pickupAddressesTable" class="table table-hover table-sm w-100 text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Contact Info</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Address</th>
                                <th>City/State/Pincode</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Actions</th>
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
    <div class="modal fade" id="createPickupAddressModal" tabindex="-1" aria-labelledby="createPickupAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPickupAddressModalLabel">Create Pickup Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="save" method="post" action="{{ url('crm/pickup-addresses') }}" enctype="multipart/form-data">
                    @csrf
                    <div id="put"></div>
                    <div class="modal-body">
                        <div id="message"></div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Enter name" required>
                                    <span class="text-danger error" id="name_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        placeholder="Enter email" required>
                                    <span class="text-danger error" id="email_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" id="phone" class="form-control"
                                        placeholder="Enter phone number" required>
                                    <span class="text-danger error" id="phone_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Type <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-select" required>
                                        <option value="">Select Type</option>
                                        <option value="pickup_address">Pickup Address</option>
                                        <option value="rto_address">RTO Address</option>
                                        <option value="return_address">Return Address</option>
                                    </select>
                                    <span class="text-danger error" id="type_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Location <span class="text-danger">*</span></label>
                                    <input type="text" name="location" id="location" class="form-control"
                                        placeholder="Enter location" required>
                                    <span class="text-danger error" id="location_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Pincode <span class="text-danger">*</span></label>
                                    <input type="number" name="pincode" id="pincode" class="form-control"
                                        placeholder="Enter 6-digit pincode" min="100000" max="999999" required>
                                    <span class="text-danger error" id="pincode_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea name="address" id="address" class="form-control" rows="3"
                                placeholder="Enter complete address" required></textarea>
                            <span class="text-danger error" id="address_error"></span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text" name="city" id="city" class="form-control"
                                        placeholder="Enter city" required>
                                    <span class="text-danger error" id="city_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">State <span class="text-danger">*</span></label>
                                    <input type="text" name="state" id="state" class="form-control"
                                        placeholder="Enter state" required>
                                    <span class="text-danger error" id="state_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="status" name="status" value="1"
                                    checked>
                                <label class="form-check-label" for="status">Active</label>
                            </div>
                            <span class="text-danger error" id="status_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="saveBtn">
                            <i class='bx bx-save'></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            var table = $('#pickupAddressesTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                pagingType: 'simple_numbers',
                ajax: {
                    url: '{{ url('crm/pickup-addresses/datatable-list') }}',
                    data: function (d) {
                        d.search = $('#search').val();
                        d.type = $('#type-filter').val();
                        d.status = $('#status-filter').val();
                    }
                },
                columns: [
                    { data: 'index', name: 'index', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'contact_info', name: 'contact_info', orderable: false, searchable: false },
                    { data: 'type', name: 'type', orderable: false, searchable: false },
                    { data: 'location', name: 'location' },
                    { data: 'address', name: 'address' },
                    { data: 'location_details', name: 'location_details', orderable: false, searchable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'created', name: 'created' },
                    { data: '_id', name: 'actions', orderable: false, searchable: false, render: function(data, type, row) {
                        return `<a href="javascript:void(0);" _id="${data}" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="Edit" class="edit text-primary"><i class='bx bxs-edit'></i></a>`;
                    }}
                ],
                order: [[8, 'desc']],
                lengthMenu: [10, 25, 50, 100, 500],
                pageLength: 10,
                scrollX: true,
            });

            // Filter form submit
            $('#pickupAddressFilterForm').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });
            
            // Reset filter
            $('#resetFilter').on('click', function() {
                $('#pickupAddressFilterForm')[0].reset();
                table.ajax.reload();
            });

            // Add button click
            $('.add').on('click', function() {
                $('#save')[0].reset();
                $('#save').attr('action', '{{ url('crm/pickup-addresses') }}');
                $('#put').html('');
                $('#saveBtn').html('<i class="bx bx-save"></i> Save');
                $('#createPickupAddressModalLabel').text('Create Pickup Address');
                $('#createPickupAddressModal').modal('show');
            });

            // Status switch handler
            $('#pickupAddressesTable').on('change', '.status-switch', function() {
                const id = $(this).data('id');
                const status = $(this).prop('checked');
                $.ajax({
                    url: '{{ url("crm/pickup-addresses/update-status") }}',
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

            // Edit handler
            $('#pickupAddressesTable').on('click', '.edit', function(e) {
                var id = $(this).attr('_id');
                var url = "{{ url('crm/pickup-addresses') }}/" + id + '/edit';
                var selector = $(this);
                $.ajax({
                    url: url,
                    dataType: "JSON",
                    type: "GET",
                    beforeSend: function() {
                        selector.html(
                            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...`
                        ).attr('disabled', true);
                    },
                    success: function(res) {
                        if (res.status) {
                            $('#name').val(res.record.name);
                            $('#email').val(res.record.email);
                            $('#phone').val(res.record.phone);
                            $('#type').val(res.record.type);
                            $('#location').val(res.record.location);
                            $('#address').val(res.record.address);
                            $('#city').val(res.record.city);
                            $('#state').val(res.record.state);
                            $('#pincode').val(res.record.pincode);
                            $('#status').prop('checked', res.record.status);
                            var url_ = "{{ url('crm/pickup-addresses') }}/" + res.record._id;
                            $('#save').attr('action', url_);
                            $('#put').html(
                                `<input type="hidden" id="putField" name="_method" value="PUT">`);
                            $('#saveBtn').html('<i class="bx bx-save"></i> Update');
                            $('#createPickupAddressModalLabel').text('Edit Pickup Address');
                            selector.html('<i class="bx bxs-edit"></i>').removeAttr('disabled');
                            $('#createPickupAddressModal').modal('show');
                        } else {
                            alertMsg(res.status, res.msg, 3000);
                        }
                    }
                })
            });

            // Form submit handler
            $('#save').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                var method = form.attr('method');
                var data = form.serialize();

                $.ajax({
                    url: url,
                    method: method,
                    data: data,
                    beforeSend: function() {
                        $('#saveBtn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...').attr('disabled', true);
                        $('.error').text('');
                    },
                    success: function(response) {
                        if (response.status) {
                            alertMsg(response.status, response.msg, 3000);
                            $('#createPickupAddressModal').modal('hide');
                            table.ajax.reload();
                        } else {
                            alertMsg(response.status, response.msg, 3000);
                        }
                        $('#saveBtn').html('<i class="bx bx-save"></i> Save').removeAttr('disabled');
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.validation;
                            $.each(errors, function(key, value) {
                                $('#' + key + '_error').text(value[0]);
                            });
                        } else {
                            alertMsg(false, xhr.responseJSON.msg || 'An error occurred while saving.', 3000);
                        }
                        $('#saveBtn').html('<i class="bx bx-save"></i> Save').removeAttr('disabled');
                    }
                });
            });
        });
    </script>
@endpush 