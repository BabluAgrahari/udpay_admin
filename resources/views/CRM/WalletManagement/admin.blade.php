@extends('CRM.Layout.layout')

@section('title', 'Transfer Money')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Transfer Money</h5>
                </div>
                <div class="card-body">
                    <div id="message"></div>

                    <form id="save" action="{{ url('crm/wallet/transfer') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Select Admin <span class="text-danger">*</span></label>
                                    <select class="form-select" id="user_id" name="user_id" required>
                                        <option value="">Select Admin</option>
                                        @foreach($admins as $admin)
                                            <option value="{{ $admin->_id }}">
                                                {{ $admin->first_name }} {{ $admin->last_name }} ({{ $admin->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="error text-danger" id="user_id_error"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="available_amount" class="form-label">Available Amount</label>
                                    <input type="text" class="form-control" id="available_amount" readonly value="₹ 0.00">
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
                                    <label for="type" class="form-label">Transaction Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="credit">Credit</option>
                                        <option value="debit">Debit</option>
                                    </select>
                                    <div class="error text-danger" id="type_error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
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
                                    Submit Transaction
                                </button>
                                <a href="{{ url('crm/wallet/history') }}" class="btn btn-secondary">View History</a>
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
    // Get user wallet balance when admin is selected
    $('#user_id').change(function() {
        var userId = $(this).val();
        if (userId) {
            $.ajax({
                url: '{{ url("crm/wallet/get-admin-wallet") }}',
                type: 'GET',
                data: { user_id: userId },
                success: function(response) {
                    if (response.status) {
                        $('#available_amount').val('₹ ' + parseFloat(response.record.available_amount).toFixed(2));
                    } else {
                        $('#available_amount').val('₹ 0.00');
                        alertMsg(false, response.msg, 3000);
                    }
                },
                error: function(xhr) {
                    $('#available_amount').val('₹ 0.00');
                    if (xhr.status === 422) {
                        alertMsg(false, xhr.responseJSON.validation, 3000);
                    } else {
                        alertMsg(false, 'Failed to get wallet balance', 3000);
                    }
                }
            });
        } else {
            $('#available_amount').val('₹ 0.00');
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
                $('#saveBtn').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Saving...`).attr('disabled', true);
            },
            success: function (res) {
                $('#saveBtn').html('Submit Transaction').removeAttr('disabled');
                alertMsg(res.status, res.msg, 3000);

                if (res.status) {
                    $('form#save').trigger('reset');
                    $('#available_amount').val('₹ 0.00');
                    setTimeout(() => {
                        window.location.href = "{{ url('crm/wallet/history') }}";
                    }, 1000);
                }
            },
            error: function (xhr) {
                $('#saveBtn').html('Submit Transaction').removeAttr('disabled');
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.validation;
                    $.each(errors, (field, messages) => {
                        $(`#${field}_error`).html(messages[0]);
                    });
                }else if(xhr.status===400){
                    alertMsg(false, xhr.responseJSON.msg, 3000);
                }else {
                    alertMsg(false, xhr.responseJSON.msg||'An error occurred while processing your request.', 3000);
                }
            }
        });
    });
});
</script>
@endpush 