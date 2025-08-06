@extends('CRM.Layout.layout')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row card-header pb-0">
            <div class="col-md-10">
                <h5>Coupons</h5>
            </div>
            <div class="col-md-2 text-right">
                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#couponModal" onclick="openCreateModal()">
                    <i class='bx bx-plus'></i>&nbsp;Add New
                </button>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ url('crm/coupons') }}" method="GET" class="mb-3">
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
                        <a href="{{ url('crm/coupons') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                    </div>
                </div>
            </form>

            <table id="couponsTable" class="table table-hover w-100 text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Discount Type</th>
                        <th>Discount Value</th>
                        <th>Usage</th>
                        <th>Valid From</th>
                        <th>Valid To</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Coupon Modal -->
<div class="modal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="couponModalLabel">Add New Coupon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="message"></div>
                <form id="couponForm" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="code" class="form-control" placeholder="Enter coupon code" required>
                            <span class="text-danger error" id="code_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter coupon name" required>
                            <span class="text-danger error" id="name_error"></span>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" placeholder="Enter description"></textarea>
                            <span class="text-danger error" id="description_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                            <select name="discount_type" id="discount_type" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="percentage">Percentage</option>
                                <option value="fixed">Fixed Amount</option>
                            </select>
                            <span class="text-danger error" id="discount_type_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                            <input type="number" name="discount_value" id="discount_value" class="form-control" placeholder="Enter discount value" step="0.01" min="0" required>
                            <span class="text-danger error" id="discount_value_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Minimum Amount</label>
                            <input type="number" name="minimum_amount" id="minimum_amount" class="form-control" placeholder="Enter minimum amount" step="0.01" min="0">
                            <span class="text-danger error" id="minimum_amount_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Maximum Discount</label>
                            <input type="number" name="maximum_discount" id="maximum_discount" class="form-control" placeholder="Enter maximum discount" step="0.01" min="0">
                            <span class="text-danger error" id="maximum_discount_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Usage Limit</label>
                            <input type="number" name="usage_limit" id="usage_limit" class="form-control" placeholder="Enter usage limit" min="1">
                            <span class="text-danger error" id="usage_limit_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <span class="text-danger error" id="status_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Valid From</label>
                            <input type="datetime-local" name="valid_from" id="valid_from" class="form-control">
                            <span class="text-danger error" id="valid_from_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Valid To</label>
                            <input type="datetime-local" name="valid_to" id="valid_to" class="form-control">
                            <span class="text-danger error" id="valid_to_error"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveBtn" onclick="saveCoupon()">Save</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    var baseUrl = '{{ url('/') }}';
    var table;

    $(document).ready(function() {
        table = $('#couponsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ url('crm/coupons/datatable-list') }}',
                type: 'GET',
                data: function(d) {
                    d.status = $('select[name="status"]').val();
                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'code', name: 'code'},
                {data: 'name', name: 'name'},
                {data: 'discount_type', name: 'discount_type'},
                {data: 'discount_value', name: 'discount_value'},
                {data: 'usage_limit', name: 'usage_limit'},
                {data: 'valid_from', name: 'valid_from'},
                {data: 'valid_to', name: 'valid_to'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {data: 'id', name: 'actions', orderable: false, searchable: false,
                    render: function(data) {
                        return `<button class="btn btn-sm btn-icon btn-outline-primary edit-btn" data-id="${data}"><i class='bx bx-edit'></i></button>
                                <button class="btn btn-sm btn-icon btn-outline-danger delete-btn" data-id="${data}"><i class='bx bx-trash'></i></button>`;
                    }
                }
            ],
            order: [[0, 'desc']]
        });

        // Filter form submission
        $('form[action="{{ url('crm/coupons') }}"]').on('submit', function(e) {
            e.preventDefault();
            table.draw();
        });

        // Reset filter
        $('form[action="{{ url('crm/coupons') }}"] .btn-secondary').on('click', function(e) {
            e.preventDefault();
            $('form[action="{{ url('crm/coupons') }}"]')[0].reset();
            table.draw();
        });

        // Status toggle
        $('#couponsTable').on('change', '.status-switch', function() {
            var id = $(this).data('id');
            var status = $(this).prop('checked') ? 1 : 0;

            $.ajax({
                url: '{{ url("crm/coupons/update-status") }}',
                type: 'POST',
                data: {
                    id: id,
                    status: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        table.draw();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Something went wrong!');
                }
            });
        });

        // Delete coupon
        $('#couponsTable').on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            
            if (confirm('Are you sure you want to delete this coupon?')) {
                $.ajax({
                    url: `/crm/coupons/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            table.draw();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong!');
                    }
                });
            }
        });

        // Edit coupon
        $('#couponsTable').on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            var editBtn = $(this);
            openEditModal(id, editBtn);
        });

        // Discount type change
        $('#discount_type').on('change', function() {
            var type = $(this).val();
            var maxValue = type === 'percentage' ? 100 : '';
            $('#discount_value').attr('max', maxValue);
            if (type === 'percentage') {
                $('#discount_value').attr('placeholder', 'Enter percentage (0-100)');
            } else {
                $('#discount_value').attr('placeholder', 'Enter amount');
            }
        });
    });

    function openCreateModal() {
        $('#couponModalLabel').text('Add New Coupon');
        $('#couponForm')[0].reset();
        $('#couponForm').attr('action', '{{ url('crm/coupons') }}');
        $('#couponForm').attr('method', 'POST');
        $('.error').text('');
        $('#message').html('');
    }

    function openEditModal(id, editBtn = null) {
        $('#couponModalLabel').text('Edit Coupon');
        $('#couponForm')[0].reset();
        $('#couponForm').attr('action', `{{ url('crm/coupons') }}/${id}`);
        $('#couponForm').attr('method', 'POST');
        // Remove any existing _method input
        $('#couponForm input[name="_method"]').remove();
        $('#couponForm').append('<input type="hidden" name="_method" value="PUT">');
        $('.error').text('');
        $('#message').html('');

        // Load coupon data
        $.ajax({
            url: `{{ url('crm/coupons') }}/${id}/edit`,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                if (editBtn) {
                    editBtn.html(
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`
                    ).attr('disabled', true);
                }
            },  
            success: function(response) {
                if (response.status) {
                 
                        editBtn.html(
                            `<i class='bx bx-edit'></i>`
                        ).removeAttr('disabled');
                
                    var coupon = response.record;
                    $('#code').val(coupon.code);
                    $('#name').val(coupon.name);
                    $('#description').val(coupon.description);
                    $('#discount_type').val(coupon.discount_type);
                    $('#discount_value').val(coupon.discount_value);
                    $('#minimum_amount').val(coupon.minimum_amount);
                    $('#maximum_discount').val(coupon.maximum_discount);
                    $('#usage_limit').val(coupon.usage_limit);
                    $('#status').val(coupon.status).trigger('change');
                    
                    // Fix datetime format for datetime-local inputs
                    if (coupon.valid_from) {
                        var validFrom = new Date(coupon.valid_from);
                        var validFromFormatted = validFrom.getFullYear() + '-' + 
                            String(validFrom.getMonth() + 1).padStart(2, '0') + '-' + 
                            String(validFrom.getDate()).padStart(2, '0') + 'T' + 
                            String(validFrom.getHours()).padStart(2, '0') + ':' + 
                            String(validFrom.getMinutes()).padStart(2, '0');
                        $('#valid_from').val(validFromFormatted);
                    }
                    if (coupon.valid_to) {
                        var validTo = new Date(coupon.valid_to);
                        var validToFormatted = validTo.getFullYear() + '-' + 
                            String(validTo.getMonth() + 1).padStart(2, '0') + '-' + 
                            String(validTo.getDate()).padStart(2, '0') + 'T' + 
                            String(validTo.getHours()).padStart(2, '0') + ':' + 
                            String(validTo.getMinutes()).padStart(2, '0');
                        $('#valid_to').val(validToFormatted);
                    }
                    
                    $('#couponModal').modal('show');
                } else{
                        editBtn.html(
                            `<i class='bx bx-edit'></i>`
                        ).removeAttr('disabled');
                    
                    alertMsg(false, response.msg,5000);
                }
            },
            error: function() {
                alertMsg(false, 'Something went wrong!',5000);
            }
        });
    }

    function saveCoupon() {
        $('.error').text('');
        $('#message').html('');

        var formData = new FormData($('#couponForm')[0]);

        $.ajax({
            url: $('#couponForm').attr('action'),
            type: $('#couponForm').attr('method'),
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#saveBtn').html(
                            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Saving...`
                        ).attr('disabled', true);
            },
            dataType: 'json',
            success: function(response) {
                $('#saveBtn').html('<i class="bx bx-save"></i> Save').removeAttr('disabled');
                if (response.status) {
                    alertMsg(true,response.msg,3000);
                    $('#couponModal').modal('hide');
                    table.draw();
                } else {
                    alertMsg(false,response.msg,3000);
                }
            },
            error: function(xhr) {
                $('#saveBtn').html('<i class="bx bx-save"></i> Save').removeAttr('disabled');
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.validation;
                    $.each(errors, function(key, value) {
                        $('#' + key + '_error').text(value[0]);
                    });
                } else {
                    alertMsg(false, 'Something went wrong!',5000);
                }
            },
            complete: function() {
                $('#saveBtn').html('<i class="bx bx-save"></i> Save').removeAttr('disabled');
            }
        });
    }
</script>
@endpush 