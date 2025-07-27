@extends('CRM.Layout.layout')

@section('title', 'User to User Transfer')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 me-4">User to User Transfer</h5>
                       
                    </div>
                </div>
                <div class="card-body">
                    <div id="message"></div>

                    <form id="save" action="{{ url('crm/wallet/user-to-user-transfer') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sender_user_id" class="form-label">Sender User ID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" 
                                           id="sender_user_id" name="sender_user_id" 
                                           placeholder="Enter Sender User ID" required>
                                    <div class="error text-danger" id="sender_user_id_error"></div>
                                    <div id="sender_user_display" class="mt-2" style="display: none;">
                                        <small class="text-muted">
                                            <strong>Sender:</strong> <span id="sender_user_name"></span><br>
                                            <strong>Available Amount:</strong> <span id="sender_available_amount" class="text-success fw-bold"></span>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="receiver_user_id" class="form-label">Receiver User ID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" 
                                           id="receiver_user_id" name="receiver_user_id" 
                                           placeholder="Enter Receiver User ID" required>
                                    <div class="error text-danger" id="receiver_user_id_error"></div>
                                    <div id="receiver_user_display" class="mt-2" style="display: none;">
                                        <small class="text-muted">
                                            <strong>Receiver:</strong> <span id="receiver_user_name"></span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" 
                                           id="amount" name="amount" step="0.01" min="0.01" 
                                           placeholder="Enter amount" required>
                                    <div class="error text-danger" id="amount_error"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="remarks" class="form-label">Remarks (Optional)</label>
                                    <textarea class="form-control" 
                                              id="remarks" name="remarks" rows="3" 
                                              placeholder="Enter remarks"></textarea>
                                    <div class="error text-danger" id="remarks_error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="saveBtn">
                                    Transfer Amount
                                </button>
                                <a href="{{ url('crm/wallet/history') }}" class="btn btn-secondary">View History</a>
                                <a href="{{ url('crm/wallet/user-transfer') }}" class="btn btn-secondary">
                                    <i class="bx bx-user-plus me-1"></i>User Transfer
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
    // Get sender user details when sender_user_id is entered
    $('#sender_user_id').on('blur', function() {
        var userId = $(this).val().trim();
        if (userId) {
            getUserDetails(userId, 'sender');
        } else {
            $('#sender_user_display').hide();
        }
    });

    // Get receiver user details when receiver_user_id is entered
    $('#receiver_user_id').on('blur', function() {
        var userId = $(this).val().trim();
        if (userId) {
            getUserDetails(userId, 'receiver');
        } else {
            $('#receiver_user_display').hide();
        }
    });

    // Form submission with AJAX
    $('form#save').submit(function (e) {
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
            beforeSend: function () {
                $('#saveBtn').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Transferring...`).attr('disabled', true);
            },
            success: function (res) {
                $('#saveBtn').html('Transfer Amount').removeAttr('disabled');
                alertMsg(res.status, res.msg, 3000);

                if (res.status) {
                    $('form#save').trigger('reset');
                    $('#sender_user_display').hide();
                    $('#receiver_user_display').hide();
                    setTimeout(() => {
                        window.location.href = "{{ url('crm/wallet/history') }}";
                    }, 1000);
                }
            },
            error: function (xhr) {
                $('#saveBtn').html('Transfer Amount').removeAttr('disabled');
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.validation;
                    $.each(errors, (field, messages) => {
                        $(`#${field}_error`).html(messages[0]);
                    });
                } else if(xhr.status === 400) {
                    alertMsg(false, xhr.responseJSON.msg, 3000);
                } else {
                    alertMsg(false, xhr.responseJSON.msg || 'An error occurred while processing your request.', 3000);
                }
            }
        });
    });

    // Function to get user details
    function getUserDetails(userId, type) {
        $.ajax({
            url: '{{ url("crm/wallet/get-user-details") }}',
            type: 'GET',
            data: { user_id: userId },
            success: function(response) {
                if (response.status) {
                    var userName = response.record.first_name + ' ' + response.record.last_name + ' (' + response.record.email + ')';
                    
                    if (type === 'sender') {
                        $('#sender_user_name').text(userName);
                        $('#sender_user_display').show();
                        
                        // Get sender's wallet balance
                        getWalletBalance(userId, 'sender');
                    } else if (type === 'receiver') {
                        $('#receiver_user_name').text(userName);
                        $('#receiver_user_display').show();
                    }
                } else {
                    if (type === 'sender') {
                        $('#sender_user_name').text('User not found');
                        $('#sender_user_display').show();
                    } else if (type === 'receiver') {
                        $('#receiver_user_name').text('User not found');
                        $('#receiver_user_display').show();
                    }
                    alertMsg(false, response.msg, 3000);
                }
            },
            error: function(xhr) {
                if (type === 'sender') {
                    $('#sender_user_name').text('User not found');
                    $('#sender_user_display').show();
                } else if (type === 'receiver') {
                    $('#receiver_user_name').text('User not found');
                    $('#receiver_user_display').show();
                }
                if (xhr.status === 422) {
                    alertMsg(false, xhr.responseJSON.validation, 3000);
                } else {
                    alertMsg(false, 'Failed to get user details', 3000);
                }
            }
        });
    }

    // Function to get wallet balance
    function getWalletBalance(userId, type) {
        $.ajax({
            url: '{{ url("crm/wallet/get-user-wallet") }}',
            type: 'GET',
            data: { user_id: userId },
            success: function(response) {
                if (response.status) {
                    var amount = parseFloat(response.record.available_amount).toFixed(2);
                    $('#sender_available_amount').text('₹ ' + amount);
                } else {
                    $('#sender_available_amount').text('₹ 0.00');
                }
            },
            error: function(xhr) {
                $('#sender_available_amount').text('₹ 0.00');
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