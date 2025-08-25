@extends('Website.Layout.app')

@section('content')
    <section class="section-padding py-5 pt-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="dashboard">
                        <!-- Sidebar -->
                        <div class="sidebar">
                            <a href="{{ url('distributor/wallet/add-money') }}" class="tab-btn active"><i
                                    class="fa-solid fa-plus"></i>Add Money</a>
                            <a href="{{ url('distributor/wallet/money-transfer') }}" class="tab-btn"><i
                                    class="fa-solid fa-exchange-alt"></i> Money Transfer</a>
                            <a href="{{ url('distributor/wallet/my-payout') }}" class="tab-btn"><i
                                    class="fa-solid fa-box"></i>My Payout</a>
                            <a href="{{ url('distributor/wallet/redeem-transaction') }}" class="tab-btn"><i
                                    class="fa-solid fa-heart"></i> Redeem Transaction</a>
                            <a href="{{ url('distributor/wallet/transaction-history') }}" class="tab-btn"><i
                                    class="fa-solid fa-book"></i> Transaction History</a>

                            {{-- <a href="{{ url('distributor/wallet/my-earning') }}" class="tab-btn"><i
                                    class="fa-solid fa-heart"></i> My Earning</a> --}}

                            <a href="{{ url('distributor/wallet/logout') }}" class="tab-btn logout"><i
                                    class="fa-solid fa-right-from-bracket"></i> Logout</a>
                        </div>
                        <!-- Content -->
                        <div class="tab-content">
                            <div class="tab-panel active edit-form-open" id="send-money">
                                <h3 class="tab-title account-top">Add Money </h3>
                                <div class="tab-content-body">
                                    <div id="message"></div>
                                    <form id="addMoneyForm">
                                        @csrf
                                        <div class="row mb-3">
                                            <div class="col-sm-6 form-group">
                                                <label>Name <span class="color-red">*</span></label>
                                                <input type="text" name="name" class="form-control"
                                                    placeholder="Enter your name" autocomplete="off" disabled
                                                    value="{{ Auth::user()->name }}">
                                                <span class="error text-danger" id="name-error"></span>
                                            </div>
                                            <div class="col-sm-6 form-group">
                                                <label>Email <span class="color-red">*</span></label>
                                                <input type="email" name="email" class="form-control"
                                                    placeholder="Enter your email" autocomplete="off" disabled
                                                    value="{{ Auth::user()->email }}">
                                                <span class="error  text-danger" id="email-error"></span>
                                            </div>
                                            <div class="col-sm-6 form-group">
                                                <label>Mobile <span class="color-red">*</span></label>
                                                <input type="text" name="mobile" class="form-control"
                                                    placeholder="Enter your mobile number" autocomplete="off" disabled
                                                    value="{{ Auth::user()->mobile }}">
                                                <span class="error  text-danger" id="mobile-error"></span>
                                            </div>
                                            <div class="col-sm-6 form-group">
                                                <label>Amount <span class="color-red">*</span></label>
                                                <input type="number" name="amount" class="form-control"
                                                    placeholder="Enter amount (min: ₹50)" min="50" step="0.01">
                                                <span class="error  text-danger" id="amount-error"></span>
                                            </div>
                                        </div>
                                        <button type="submit" class="thm-btn" id="submitBtn">Proceed</button>
                                        <button type="button" class="thm-btn bg-light mx-3" id="cancelBtn">Cancel</button>
                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </section>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Transaction Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <strong>Please review the transaction details before confirming:</strong>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <strong>Name:</strong>
                        </div>
                        <div class="col-6" id="preview-name"></div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-6" id="preview-email"></div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <strong>Mobile:</strong>
                        </div>
                        <div class="col-6" id="preview-mobile"></div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <strong>Amount to Add:</strong>
                        </div>
                        <div class="col-6" id="preview-amount"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="thm-btn bg-light mx-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="thm-btn" id="confirmBtn">Confirm Transaction</button>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
@push('scripts')
    <script>
        const cashfree = Cashfree({
            mode: "production",
        });

        function initiatePayment(paymentSessionId) {
            let checkoutOptions = {
                paymentSessionId: paymentSessionId,
                redirectTarget: "_self",
            };
            cashfree.checkout(checkoutOptions);
        }

        $(document).ready(function() {
            // Clear form and errors
            function clearForm() {
                $('#addMoneyForm')[0].reset();
                $('#message').text('');
                $('.error').text('');
            }

            // Handle form submission for preview
            $('#addMoneyForm').on('submit', function(e) {
                e.preventDefault();

                $('#message').text('');
                $('.error').text('');
                var formData = $(this).serialize();
                $('#submitBtn').prop('disabled', true).text('Processing...');

                $.ajax({
                    url: '{{ url('distributor/wallet/preview-add-money') }}',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            $('#preview-name').text(response.record.name);
                            $('#preview-email').text(response.record.email);
                            $('#preview-mobile').text(response.record.mobile);
                            $('#preview-amount').text('₹' + response.record.amount);

                            $('#previewModal').modal('show');
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
                            sweetalert(false, response.msg || 'Something went wrong');
                        }
                    },
                    complete: function() {
                        $('#submitBtn').prop('disabled', false).text('Proceed');
                    }
                });
            });

            // Handle confirm button click
            $('#confirmBtn').click(function() {
                var formData = $('#addMoneyForm').serialize();
                $('#confirmBtn').prop('disabled', true).text('Processing...');

                $.ajax({
                    url: '{{ url('distributor/wallet/add-money-save') }}',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            // Hide preview modal
                            $('#previewModal').modal('hide');

                            if (response.record.payment_gateway == 'cashfree') {
                                initiatePayment(response.record.payment_session_id);
                            }
                            // Show success message
                            // sweetalert(true, response.msg, 0);
                            // Clear form and refresh page after user clicks OK
                            // clearForm();
                            // location.reload();
                        } else {
                            sweetalert(false, response.msg);
                        }
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        sweetalert(false, response.msg || 'Transaction failed');
                    },
                    complete: function() {
                        $('#confirmBtn').prop('disabled', false).text('Confirm Transaction');
                    }
                });
            });
        });
    </script>
@endpush
