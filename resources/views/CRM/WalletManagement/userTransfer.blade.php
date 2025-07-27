@extends('CRM.Layout.layout')

@section('title', 'User Transfer')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0 me-4">User Transfer</h5>

                        </div>
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-2">Admin Balance:</span>
                            <span class="badge bg-label-primary fs-6">₹
                                {{ number_format(wallet()->available_amount ?? 0, 2) }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="message"></div>

                        <form id="save" action="{{ url('crm/wallet/user-transfer') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="user_id" class="form-label">User ID <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="user_id" name="user_id"
                                            placeholder="Enter User ID" required>
                                        <div class="error text-danger" id="user_id_error"></div>
                                        <div id="user_name_display" class="mt-2" style="display: none;">
                                            <small class="text-muted">
                                                <strong>User:</strong> <span id="user_name_text"></span>
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Amount <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="amount" name="amount"
                                            step="0.01" min="0.01" placeholder="Enter amount" required>
                                        <div class="error text-danger" id="amount_error"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="remarks" class="form-label">Remarks (Optional)</label>
                                        <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Enter remarks"></textarea>
                                        <div class="error text-danger" id="remarks_error"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary" id="saveBtn">
                                        Transfer to User
                                    </button>
                                    <a href="{{ url('crm/wallet/history') }}" class="btn btn-secondary">View History</a>
                                    <a href="{{ url('crm/wallet/user-to-user-transfer') }}" class="btn btn-secondary">
                                        <i class="bx bx-group me-1"></i>User to User Transfer
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        $(document).ready(function() {
            // Get user details when user_id is entered
            $('#user_id').on('blur', function() {
                var userId = $(this).val().trim();
                if (userId) {
                    getUserDetails(userId);
                } else {
                    $('#user_name_display').hide();
                }
            });

            // Form submission with AJAX
            $('form#save').submit(function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const url = $(this).attr('action');

                $('.error').html('');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#saveBtn').html(
                            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Transferring...`
                            ).attr('disabled', true);
                    },
                    success: function(res) {
                        $('#saveBtn').html('Transfer to User').removeAttr('disabled');
                        alertMsg(res.status, res.msg, 3000);

                        if (res.status) {
                            $('form#save').trigger('reset');
                            $('#user_name_display').hide();
                            setTimeout(() => {
                                window.location.href =
                                "{{ url('crm/wallet/history') }}";
                            }, 1000);
                        }
                    },
                    error: function(xhr) {
                        $('#saveBtn').html('Transfer to User').removeAttr('disabled');

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.validation;
                            $.each(errors, (field, messages) => {
                                $(`#${field}_error`).html(messages[0]);
                            });
                        } else if (xhr.status === 400) {
                            alertMsg(false, xhr.responseJSON.msg, 3000);
                        } else {
                            alertMsg(false, xhr.responseJSON.msg ||
                                'An error occurred while processing your request.', 3000);
                        }
                    }
                });
            });

            // Function to get user details
            function getUserDetails(userId) {
                $.ajax({
                    url: '{{ url('crm/wallet/get-user-details') }}',
                    type: 'GET',
                    data: {
                        user_id: userId
                    },
                    success: function(response) {
                        if (response.status) {
                            var userName = response.record.first_name + ' ' + response.record
                                .last_name + ' (' + response.record.email + ')';
                            $('#user_name_text').text(userName);
                            $('#user_name_display').show();
                        } else {
                            $('#user_name_text').text('User not found');
                            $('#user_name_display').show();
                            alertMsg(false, response.msg, 3000);
                        }
                    },
                    error: function(xhr) {
                        $('#user_name_text').text('User not found');
                        $('#user_name_display').show();
                        if (xhr.status === 422) {
                            alertMsg(false, xhr.responseJSON.validation, 3000);
                        } else {
                            alertMsg(false, 'Failed to get user details', 3000);
                        }
                    }
                });
            }

            // Ensure sidebar menu is open for wallet management
            if (!$('#layout-menu .menu-item.open').length) {
                $('#layout-menu .menu-item:has(.menu-link[href*="wallet"])').addClass('open');
            }
            
            // Add click handlers for internal navigation to ensure menu stays open
            $('a[href*="wallet"]').on('click', function() {
                // Ensure wallet menu stays open
                $('#layout-menu .menu-item:has(.menu-link[href*="wallet"])').addClass('open');
            });
        });
    </script>
@endpush
