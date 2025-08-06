@extends('Website.Layout.app')

@section('content')
    <section class="section-padding py-5 pt-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="dashboard">
                        <!-- Sidebar -->
                        <div class="sidebar">
                            <a href="{{ url('distributor/wallet/add-money') }}" class="tab-btn"><i
                                    class="fa-solid fa-plus"></i>Add Money</a>
                            <a href="{{ url('distributor/wallet/money-transfer') }}" class="tab-btn active"><i
                                    class="fa-solid fa-exchange-alt"></i> Money Transfer</a>
                            <a href="{{ url('distributor/wallet/my-payout') }}" class="tab-btn"><i
                                    class="fa-solid fa-box"></i>My Payout</a>
                            <a href="{{ url('distributor/wallet/redeem-transaction') }}" class="tab-btn"><i
                                    class="fa-solid fa-heart"></i> Redeem Transaction</a>
                            <a href="{{ url('distributor/wallet/transaction-history') }}" class="tab-btn"><i
                                    class="fa-solid fa-book"></i> Transaction History</a>

                            <a href="{{ url('distributor/wallet/my-earning') }}" class="tab-btn"><i
                                    class="fa-solid fa-heart"></i> My Earning</a>

                            <a href="{{ url('distributor/wallet/logout') }}" class="tab-btn logout"><i
                                    class="fa-solid fa-right-from-bracket"></i> Logout</a>
                        </div>
                        <!-- Content -->
                        <div class="tab-content">
                            <div class="tab-panel active" id="money-transfer">
                                <h3 class="tab-title account-top">Money Transfer</h3>
                                <div class="tab-content-body">
                                    <div id="message"></div>
                                    <form id="moneyTransferForm">
                                        @csrf
                                        <div class="row mb-3">
                                            <div class="col-sm-6 form-group">
                                                <label>User ID <span class="color-red">*</span></label>
                                                <input type="text" name="user_id" id="user_id" class="form-control"
                                                    placeholder="Enter recipient User ID" autocomplete="off">
                                                <span class="error-message text-danger" id="user_id-error"></span>
                                            </div>
                                            <div class="col-sm-6 form-group">
                                                <label>Confirm User ID <span class="color-red">*</span></label>
                                                <input type="text" name="confirm_user_id" id="confirm_user_id"
                                                    class="form-control" placeholder="Confirm recipient User ID"
                                                    autocomplete="off">
                                                <span class="error-message text-danger" id="confirm_user_id-error"></span>
                                            </div>
                                            <div class="col-sm-6 form-group">
                                                <label>Recipient Name</label>
                                                <input type="text" id="recipient_name" class="form-control"
                                                    placeholder="Recipient name will appear here" readonly>
                                                <small class="text-muted">Enter User ID above to see recipient name</small>
                                            </div>
                                            <div class="col-sm-6 form-group">
                                                <label>Amount <span class="color-red">*</span></label>
                                                <input type="number" name="amount" class="form-control"
                                                    placeholder="Enter amount" min="1" step="0.01">
                                                <span class="error-message text-danger" id="amount-error"></span>
                                            </div>
                                            <div class="col-sm-12 form-group">
                                                <label>Reason <span class="color-red">*</span></label>
                                                <textarea name="reason" class="form-control" rows="3" placeholder="Enter reason for transfer"></textarea>
                                                <span class="error-message text-danger" id="reason-error"></span>
                                            </div>
                                        </div>
                                        <button type="submit" class="thm-btn" id="submitBtn">Send Money</button>
                                        <button type="button" class="thm-btn bg-light mx-3" id="resetBtn">Reset</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let recipientName = '';

            // Clear form and errors
            function clearForm() {
                $('#moneyTransferForm')[0].reset();
                $('#recipient_name').val('');
                $('.error-message').text('');
                recipientName = '';
            }

            // Reset button
            $('#resetBtn').click(function() {
                clearForm();
            });

            // Get user name when user_id is entered
            $('#user_id').on('blur', function() {
                const userId = $(this).val().trim();
                if (userId) {
                    $.ajax({
                        url: '{{ url('distributor/money-transfer/get-user-name') }}',
                        type: 'POST',
                        data: {
                            user_id: userId,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                $('#recipient_name').val(response.record.name);
                                recipientName = response.record.name;
                                $('#user_id-error').text('');
                            } else {
                                $('#recipient_name').val('');
                                $('#user_id-error').text('User not found');
                                recipientName = '';
                            }
                        },
                        error: function(xhr) {
                            $('#recipient_name').val('');
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.validation;
                                if (errors.user_id) {
                                    $('#user_id-error').text(errors.user_id[0]);
                                }
                            } else {
                                $('#user_id-error').text('User not found');
                            }
                            recipientName = '';
                        }
                    });
                } else {
                    $('#recipient_name').val('');
                    recipientName = '';
                }
            });

            // Handle form submission
            $('#moneyTransferForm').on('submit', function(e) {
                e.preventDefault();

                // Clear previous errors
                $('.error-message').text('');

                // Validate user IDs match
                const userId = $('#user_id').val().trim();
                const confirmUserId = $('#confirm_user_id').val().trim();

                if (userId !== confirmUserId) {
                    $('#confirm_user_id-error').text('User ID and Confirm User ID do not match');
                    return;
                }

                // Validate recipient name is found
                if (!recipientName) {
                    $('#user_id-error').text('Please enter a valid User ID');
                    return;
                }

                var formData = $(this).serialize();
                $('#submitBtn').prop('disabled', true).text('Processing...');

                $.ajax({
                    url: '{{ url('distributor/money-transfer/transfer') }}',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            sweetalert(true, response.msg, 0);
                            clearForm();
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        } else {
                            sweetalert(false, response.msg);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.validation;
                            $.each(errors, function(field, messages) {
                                $('#' + field + '-error').text(messages[0]);
                            });
                        } else {
                            var response = xhr.responseJSON;
                            sweetalert(false, response.msg || 'Transfer failed');
                        }
                    },
                    complete: function() {
                        $('#submitBtn').prop('disabled', false).text('Send Money');
                    }
                });
            });

            // // Auto-fill confirm user ID when user ID changes
            // $('#user_id').on('input', function() {
            //     $('#confirm_user_id').val($(this).val());
            // });
        });
    </script>
@endpush
