@extends('Website.Layout.app')

@section('content')

    <style>
        .errorMessage {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }
        .address-card.selected::before {
            top: 22px !important;
            right: 4px !important;
        }
    </style>
    <section class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Left: Cart Items -->
                    <div class="cart-items p-3">
                        <h6 class="mb-2">Your order updates & invoice will be sent to</h6>
                        <div class="d-flex gap-2 align-items-center">
                            <p class="mb-0 text-black"><i class="fa-regular fa-envelope"></i> {{ Auth::user()->email ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="cart-items">
                        <div class="edit-form-open">
                            <div class="address-header-title">
                                <h5 class="mb-0">Payment</h5>
                                <button class="add-address editBtn"
                                    {{ $addresses->isNotEmpty() ? 'style="display:none;"' : '' }}>+ Add New Address</button>
                            </div>
                            <!-- Add/Edit Address Form -->
                            <div class="edit-form-box mb-3" id="addAddressForm"
                                style="display:{{ $addresses->isNotEmpty() ? 'none' : 'block !important' }};">
                                <h4 class="mb-4 form-title">Add New Address</h4>
                                <form id="addressForm" method="POST" action="{{ url('address') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label>Name <span class="color-red">*</span></label>
                                            <input type="text" id="user_add_name" name="user_add_name"
                                                class="form-control" placeholder="Enter Name" required />
                                            <span class="errorMessage" id="user_add_name_error"></span>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label>Mobile No. <span class="color-red">*</span></label>
                                            <input type="text" id="user_add_mobile" name="user_add_mobile"
                                                class="form-control" placeholder="Enter Phone" required />
                                            <span class="errorMessage" id="user_add_mobile_error"></span>
                                        </div>
                                        <div class="col-sm-12 form-group">
                                            <label>Address (Area & Street) <span class="color-red">*</span></label>
                                            <textarea id="user_add_1" name="user_add_1" class="form-control mb-2" placeholder="Enter Addresses" required></textarea>
                                            <span class="errorMessage" id="user_add_1_error"></span>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label>Landmark</label>
                                            <input type="text" id="land_mark" name="land_mark" class="form-control"
                                                placeholder="Enter Landmark" />
                                            <span class="errorMessage" id="land_mark_error"></span>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label>Pincode <span class="color-red">*</span></label>
                                            <input type="text" id="user_zip_code" name="user_zip_code"
                                                class="form-control" placeholder="Enter Pincode" required />
                                            <span class="errorMessage" id="user_zip_code_error"></span>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label>State <span class="color-red">*</span></label>
                                            <input type="text" id="user_state" name="user_state" class="form-control"
                                                placeholder="Enter State" required />
                                            <span class="errorMessage" id="user_state_error"></span>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label>City <span class="color-red">*</span></label>
                                            <input type="text" id="user_city" name="user_city" class="form-control"
                                                placeholder="Enter City" required />
                                            <span class="errorMessage" id="user_city_error"></span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between">
                                        <div>
                                            <p class="mb-2">Save As</p>
                                            <div class="weight-flower mt-0">
                                                <div class="options">
                                                    <label><input type="radio" name="address_type" value="Home" checked>
                                                        Home</label>
                                                    <label><input type="radio" name="address_type" value="Office">
                                                        Office</label>
                                                    <label><input type="radio" name="address_type" value="Others">
                                                        Others</label>
                                                </div>
                                            </div>
                                            <span class="errorMessage" id="address_type_error"></span>
                                        </div>
                                        <button type="submit" class="thm-btn submit-btn">Save Address</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Saved Addresses -->
                        <div class="saved-addresses edit-form-open p-0">
                            <div class="address-list">
                                @if ($addresses->isNotEmpty())
                                    @foreach ($addresses as $address)
                                        <div class="address-card checkout-address {{ $addresses->count() == 1 ? 'selected' : '' }}"
                                            data-address="{{ json_encode([
                                                'id' => $address->id,
                                                'name' => $address->user_add_name,
                                                'mobile' => $address->user_add_mobile,
                                                'address' => $address->user_add_1,
                                                'landmark' => $address->land_mark,
                                                'pincode' => $address->user_zip_code,
                                                'state' => $address->user_state,
                                                'city' => $address->user_city,
                                                'type' => $address->address_type,
                                            ]) }}">
                                            <div class="address-header">
                                                <h6 class="mb-2">{{ $address->user_add_name }}</h6>
                                                <div class="address-actions gap-0 flex-row">
                                                    <button class="btn btn-sm btn-outline-primary edit-address"
                                                        style="margin-right: 15px;" data-address-id="{{ $address->id }}"
                                                        title="Edit Address">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    @if ($addresses->count() > 1)
                                                        <button class="btn btn-sm btn-outline-primary remove-address"
                                                            style="margin-right: 0px;"
                                                            data-address-id="{{ $address->id }}" title="Remove Address">
                                                            <i class="fas fa-trash" style="color: red;"></i>
                                                        </button>
                                                    @endif
                                                    <div class="default-checkbox-wrapper">
                                                        <label class="default-checkbox-label">
                                                            {{-- <input type="checkbox" class="set-default-checkbox" data-address-id="{{ $address->id }}" {{ $address->is_default ? 'checked' : '' }}>
                                            <span class="default-text">Default</span> --}}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <p>{{ $address->user_add_1 }}</p>
                                            <p>{{ $address->user_city }}, {{ $address->user_state }},
                                                {{ $address->user_zip_code }}</p>
                                            <p>{{ $address->user_add_mobile }}</p>
                                            <div class="d-flex gap-2 justify-content-between align-items-center">
                                                <form method="POST" action="{{ url('checkout') }}">
                                                    @csrf
                                                    <input type="hidden" name="address_id" value="{{ $address->id }}">
                                                    @if ($addresses->count() > 1)
                                                        <button type="button" class="thm-btn btn-small">Select
                                                            Address</button>
                                                    @endif
                                                </form>
                                                <label class="select-add">
                                                    {{ ucfirst($address->address_for ?? '') }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p>No saved addresses. Please add one.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4">
                    <div class="cart-summary">
                        <div class="summary-box">
                            <span><strong class="text-black">Order Summary</strong> ({{ $total_items }} Items)</span>
                            <p>Total MRP <span>₹{{ $total_mrp }}</span></p>
                            @if ($total_saving > 0)
                                <p>Total Discounts <span class="discount">−₹{{ $total_saving }}</span></p>
                            @endif
                            @if (session('applied_coupon') && Auth::user()->can('isGuest'))
                                <p>Coupon Discount <span
                                        class="discount">-₹{{ session('applied_coupon.discount_amount') }}</span></p>
                            @endif
                            @if (Auth::check() && (Auth::user()->role == 'customer' || Auth::user()->role == 'distributor'))
                                <p>Total SV <span style="color: #F1624B;">{{ $total_sv }}</span></p>
                            @endif
                            <p id="shipping_charge" class="d-none">Shipping Charge <span></span></p>
                            <hr />
                            <input type="hidden" id="shipping_change" value="0">
                            <input type="hidden" id="net_amount_shipping" value="{{ $net_amount }}">
                            <input type="hidden" id="net_amount" value="{{ $net_amount }}">
                            <p class="total text-black">Payable Amount <span
                                    id="totalNetPayableAmountText">₹{{ $net_amount }}</span></p>
                        </div>
                    </div>
                    <?php
                    $wallet_balance = walletBalance(Auth::user()->user_id);
                    ?>
                    <form method="post" id="saveOrder" action="{{ url('checkout') }}">
                        @csrf
                        <div class="cart-items mt-2 p-3">
                            <h6 class="mb-2">Payment Method</h6>
                            <div class="d-flex gap-3 align-items-center">
                                @if ($wallet_balance > 0)
                                    <input type="radio" name="payment_mode" class="payment_mode paymentMode"
                                        value="wallet" id="wallet">
                                    <label class="mb-0" for="wallet">Wallet</label>
                                @endif
                                <input type="radio" name="payment_mode" class="payment_mode paymentMode"
                                    value="online" id="online" {{ $wallet_balance <= 0 ? 'checked' : '' }}>
                                <label class="mb-0" for="online">Online Payment</label>
                            </div>
                        </div>

                        @if ($wallet_balance > 0)
                            <div class="cart-items mt-2 p-3 d-none" id="wallet_balance">
                                <h6 class="mb-2">Wallet Balance</h6>
                                <input type="hidden" id="wallet_balance_amount" value="{{ $wallet_balance }}">
                                <p class="mb-0">Available Balance <span>₹{{ number_format($wallet_balance, 2) }}</span>
                                </p>
                            </div>
                        @endif
                        <div class="cart-items mt-2 p-3 {{ $wallet_balance > 0 ? 'd-none' : '' }}" id="online_payment">
                            <h6 class="mb-2">Payment Method</h6>

                            <input type="hidden" name="address_id" id="address_id"
                                value="{{ $addresses->count() == 1 ? $addresses->first()->id : '' }}">

                            <!-- <select name="payment_gateway" class="form-control mb-2">
                                        <option value="cashfree">Cashfree</option>
                                        <option value="razorpay">Razorpay</option>
                                    </select> -->
                            <div class="d-flex payment-option gap-4 align-items-center flex-wrap">
                                @if (in_array('cashfree', $payment_gateway))
                                    <div class="d-flex align-items-center gap-3">
                                        <input type="radio" name="payment_gateway" class="payment_mode"
                                            value="cashfree" id="cashfree" checked>
                                        <label class="mb-0" for="cashfree">
                                            <img src="../front_assets/images/payment/1.png" alt="img"
                                                class="payment-image"> Cashfree</label>
                                    </div>
                                @endif
                                @if (in_array('razorpay', $payment_gateway))
                                    <div class="d-flex align-items-center gap-3">
                                        <input type="radio" name="payment_gateway" class="payment_mode"
                                            value="razorpay" id="razorpay">
                                        <label class="mb-0" for="razorpay">
                                            <img src="../front_assets/images/payment/2.png" alt="img"
                                                class="payment-image"> Razorpay</label>
                                    </div>
                                @endif
                                @if (in_array('phonepe', $payment_gateway))
                                    <div class="d-flex align-items-center gap-3">
                                        <input type="radio" name="payment_gateway" class="payment_mode" value="debit"
                                            id="debit">
                                        <label class="mb-0" for="debit">
                                            <img src="../front_assets/images/payment/3.png" alt="img"
                                                class="payment-image"> PhonePe</label>
                                    </div>
                                @endif
                                @if (in_array('payu', $payment_gateway))
                                    <div class="d-flex align-items-center gap-3">
                                        <input type="radio" name="payment_gateway" class="payment_mode" value="upi"
                                            id="payu">
                                        <label class="mb-0" for="payu">
                                            <img src="../front_assets/images/payment/4.png" alt="img"
                                                class="payment-image"> PayU</label>
                                    </div>
                                @endif

                            </div>
                        </div>

                        @if (!empty(Auth::user()) && (Auth::user()->can('isCustomer') || Auth::user()->can('isDistributor')))
                            <div class="cart-items mt-2 p-3">
                                <div class="d-flex gap-3 align-items-center">
                                    <input type="radio" name="delivery_mode" id="pickup" class="payment_mode"
                                        value="self_pickup">
                                    <label class="mb-0" for="pickup">Self Pickup</label>
                                    <input type="radio" name="delivery_mode" id="courier" class="payment_mode"
                                        value="courier">
                                    <label class="mb-0" for="courier">Courier</label>
                                </div>
                            </div>
                        @endif
                        <button type="submit" class="thm-btn" id="proceed_to_pay">Proceed to Pay</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <style>
        .address-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fff;
            position: relative;
        }

        .address-card:hover {
            border-color: #006038;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.1);
        }

        .address-card.selected {
            border-color: #006038;
            background-color: #f8f9ff;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.2);
        }

        .address-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .address-header h6 {
            margin: 0;
            font-weight: 600;
            color: #333;
        }

        .address-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .edit-address {
            background-color: #F1624B;
            border-color: #F1624B;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            transition: all 0.3s ease;
        }

        .edit-address:hover {
            background-color: #F1624B;
            border-color: #F1624B;
            transform: scale(1.1);
        }

        .edit-address i {
            font-size: 12px;
        }

        .default-checkbox-wrapper {
            display: flex;
            align-items: center;
        }

        .default-checkbox-label {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            font-size: 12px;
            color: #666;
            margin: 0;
        }

        .set-default-checkbox {
            width: 16px;
            height: 16px;
            margin: 0;
            cursor: pointer;
        }

        .default-text {
            font-weight: 500;
        }

        .set-default-checkbox:checked+.default-text {
            color: #28a745;
            font-weight: 600;
        }

        .address-card p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }

        .address-card .d-flex {
            margin-top: 10px;
        }

        .btn-small {
            padding: 5px 12px;
            font-size: 12px;
        }

        .edit-form-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 0 10px;
        }


        .options {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .options label {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            font-weight: normal;
        }

        .options input[type="radio"] {
            margin: 0;
        }

        @media (max-width: 768px) {
            .address-card .d-flex {
                flex-direction: column;
                gap: 10px;
            }

            .options {
                flex-direction: column;
                gap: 10px;
            }

            .edit-form-box {
                padding: 15px;
            }

            .address-actions {
                flex-direction: column;
                gap: 5px;
            }
        }

        .processing {
            opacity: 0.6;
            pointer-events: none;
        }

        #addAddressForm {
            transition: all 0.3s ease;
        }

        .address-card.selected::before {
            content: "✓";
            position: absolute;
            top: 10px;
            right: 10px;
            background: #007bff;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
    </style>

    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
    @push('cashfree')
    @endpush
    @push('scripts')
        <script>
            const cashfree = Cashfree({
                mode: "sandbox",
            });

            function initiatePayment(paymentSessionId) {
                let checkoutOptions = {
                    paymentSessionId: paymentSessionId,
                    redirectTarget: "_self",
                };
                cashfree.checkout(checkoutOptions);
            }
        </script>
        <script>
            $(document).ready(function() {
                $('.paymentMode').on('click', function() {
                    // var net_amount = $('#net_amount').val();
                    var net_amount = $('#net_amount_shipping').val();
                    var shipping_change = $('#shipping_change').val();
                    if ($(this).val() == 'wallet') {
                        net_amount = parseFloat(net_amount) + parseFloat(shipping_change);
                        $('#wallet_balance').removeClass('d-none');
                        $('#online_payment').addClass('d-none');

                        var wallet_balance = $('#wallet_balance_amount').val();
                        // console.log(net_amount, wallet_balance);
                        if (parseFloat(net_amount) > parseFloat(wallet_balance)) {
                            $('#online_payment').removeClass('d-none').addClass('d-block');
                            $('#proceed_to_pay').text('Proceed to Pay (₹' + (parseFloat(net_amount) -
                                parseFloat(wallet_balance)).toFixed(2) + ')');

                            // $('#totalNetPayableAmountText').html('₹' + (parseFloat(net_amount) - parseFloat(
                            //     wallet_balance)).toFixed(2));
                        } else {
                            $('#proceed_to_pay').text('Proceed to Pay');
                        }
                    } else {
                        var totalNetAmount = $('#net_amount_shipping').val();

                        $('#wallet_balance').addClass('d-none');
                        $('#online_payment').removeClass('d-none');
                        $('#proceed_to_pay').text('Proceed to  Pay (₹' + (parseFloat(totalNetAmount) + parseFloat(
                            shipping_change)).toFixed(2) + ')');
                        $('#totalNetPayableAmountText').html('₹' + (parseFloat(totalNetAmount) + parseFloat(
                            shipping_change)).toFixed(2));
                    }
                });

                $(document).on('click', '#delivery_mode', function() {
                    var val = $(this).val();

                    var totalNetAmount = $('#net_amount_shipping').val();
                    var paymentMode = $('.paymentMode:checked').val();
                    var wallet_balance = $('#wallet_balance_amount').val();

                    var viewamount = totalNetAmount;
                    if (paymentMode == 'wallet' && parseFloat(wallet_balance) < parseFloat(totalNetAmount)) {
                        viewamount = parseFloat(totalNetAmount) - parseFloat(wallet_balance);
                    }
                    // console.log('paymentMode', paymentMode);
                    // console.log('wallet_balance', wallet_balance);
                    // console.log('totalNetAmount', totalNetAmount);
                    // console.log('viewamount', viewamount);

                    $('#shipping_change').val(0);
                    if (val == 'courier') {
                        $('#shipping_charge').addClass('d-none').html('');

                        if (parseFloat(totalNetAmount) < 649) {
                            $('#shipping_charge').removeClass('d-none').html(
                                'Shipping Charge <span>₹100</span>');
                            $('#totalNetPayableAmountText').html('₹' + (parseFloat(totalNetAmount) + 100).toFixed(2));
                            $('#net_amount').val(parseFloat(viewamount) + 100);
                            $('#shipping_change').val(100);
                            $('#proceed_to_pay').text('Proceed to Pay (₹' + (parseFloat(viewamount) + 100).toFixed(2) + ')');
                        }
                    } else {
                        $('#shipping_charge').addClass('d-none').html('');
                        $('#totalNetPayableAmountText').html('₹' + (parseFloat(totalNetAmount).toFixed(2)));
                        $('#net_amount').val(viewamount);
                        $('#proceed_to_pay').text('Proceed to Pay (₹' + (parseFloat(viewamount).toFixed(2)) + ')');
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {

                $('form#saveOrder').on('submit', function(e) {
                    e.preventDefault();
                    const $form = $(this);
                    const $submitBtn = $form.find('.submit-btn');
                    const originalText = $submitBtn.text();

                    $submitBtn.prop('disabled', true).text('Processing...');
                    $('.error-message').remove();
                    $('.form-control').removeClass('is-invalid');

                    $.ajax({
                        url: $form.attr('action'),
                        method: 'post',
                        data: $form.serialize(),
                        beforeSend: function() {
                            showCartLoading();
                        },
                        dataType: 'json',
                        success: function(response) {
                            hideCartLoading();
                            if (response.status) {
                                if (response.record.online) {
                                    if (response.record.payment_gateway == 'cashfree') {
                                        initiatePayment(response.record.payment_session_id);
                                    }
                                } else if (!response.record.online && response?.record
                                    ?.redirect_url) {
                                    window.location.href = response.record.redirect_url;
                                } else {
                                    showSnackbar(response.msg, 'error');
                                }
                            } else {
                                showSnackbar(response.msg, 'error');
                            }
                            // showSnackbar(response.msg, response.status ? 'success' : 'error');
                            // if (response.status) {
                            //     setTimeout(function() {
                            //         // location.reload();
                            //     }, 1000);
                            // }
                        },
                        error: function(xhr) {
                            hideCartLoading();
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.validation;
                                showSnackbar(errors, 'error');
                            } else {
                                showSnackbar(xhr.responseJSON.msg, 'error');
                            }
                        }
                    });
                });
            })
        </script>
        <script>
            $(document).ready(function() {

                // Global variables
                let isEditMode = false;
                let currentAddressId = null;
                // Toggle address form visibility
                $('.add-address').on('click', function() {
                    const $form = $('#addAddressForm');
                    const $btn = $(this);
                    if ($form.is(':visible')) {
                        $form.hide();
                        $btn.text('+ Add New Address');
                    } else {
                        $form.show();
                        $btn.text('Cancel');
                    }
                });

                // Edit address functionality
                $(document).on('click', '.edit-address', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const addressId = $(this).data('address-id');
                    const $addressCard = $(this).closest('.address-card');
                    const addressData = JSON.parse($addressCard.attr('data-address'));

                    $('#user_add_name').val(addressData.name);
                    $('#user_add_mobile').val(addressData.mobile);
                    $('#user_add_1').val(addressData.address);
                    $('#land_mark').val(addressData.landmark || '');
                    $('#user_zip_code').val(addressData.pincode);
                    $('#user_state').val(addressData.state);
                    $('#user_city').val(addressData.city);
                    $(`input[name="address_type"][value="${addressData.type}"]`).prop('checked', true);

                    isEditMode = true;
                    currentAddressId = addressId;
                    $('#addressForm').attr('action', `{{ url('address') }}/${addressId}`);

                    if ($('#addressForm input[name="_method"]').length === 0) {
                        $('#addressForm').append('<input type="hidden" name="_method" value="PUT">');
                    } else {
                        $('#addressForm input[name="_method"]').val('PUT');
                    }
                    $('.form-title').text('Edit Address');
                    $('.submit-btn').text('Update Address');
                    $('#addAddressForm').show();
                    $('.add-address').text('Cancel');
                });

                $(document).on('change', '.set-default-checkbox', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const addressId = $(this).data('address-id');
                    const $checkbox = $(this);
                    const isChecked = $checkbox.is(':checked');

                    if (isChecked) {
                        $.ajax({
                            url: `{{ url('address') }}/${addressId}/set-default`,
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.status) {
                                    $('.set-default-checkbox').not($checkbox).prop('checked',
                                        false);
                                } else {
                                    $checkbox.prop('checked', false);
                                }
                                showSnackbar(response.msg, response.status ? 'success' : 'error');
                            },
                            error: function(xhr) {
                                $checkbox.prop('checked', false);
                                showSnackbar(xhr.responseJSON.msg, 'error');
                            }
                        });
                    }
                });


                $(document).on('click', '.remove-address', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const addressId = $(this).data('address-id');

                    if (confirm('Are you sure you want to remove this address?')) {
                        $.ajax({
                            url: `{{ url('address') }}/${addressId}/remove`,
                            method: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                showSnackbar(response.msg, response.status ? 'success' : 'error');
                                location.reload();
                            },
                            error: function(xhr) {
                                showSnackbar(xhr.responseJSON.msg, 'error');
                            }
                        });
                    }
                });

                $('#addressForm').on('submit', function(e) {
                    e.preventDefault();

                    const $form = $(this);
                    const $submitBtn = $form.find('.submit-btn');
                    const originalText = $submitBtn.text();

                    $submitBtn.prop('disabled', true).text('Processing...');
                    $('.errorMessage').html('');
                    $('.form-control').removeClass('is-invalid');

                    $.ajax({
                        url: $form.attr('action'),
                        method: $form.attr('method'),
                        data: $form.serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            showCartLoading();
                        },
                        dataType: 'json',
                        success: function(response) {
                            hideCartLoading();
                            showSnackbar(response.msg, response.status ? 'success' : 'error');
                            if (response.status) {
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            }
                        },
                        error: function(xhr) {
                            hideCartLoading();
                            $submitBtn.prop('disabled', false).text(originalText);

                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.validation;
                                $.each(errors, function(field, message) {
                                    $(`#${field}_error`).html(message[0]);
                                });
                            } else {
                                showSnackbar(xhr.responseJSON.msg, 'error');
                            }
                        }
                    });
                });


                $('#user_add_mobile').on('input', function() {
                    let value = $(this).val().replace(/\D/g, '');
                    if (value.length > 10) {
                        value = value.substring(0, 10);
                    }
                    $(this).val(value);

                    //mobiel no not start with 0
                    if (value.length !== 10 || !/^\d+$/.test(value)) {
                        $(this).addClass('is-invalid');
                        $('#user_add_mobile_error').html('Please enter a valid 10-digit mobile number');
                        return false;
                    } else if (value.length == 10 && value.startsWith('0')) {
                        $(this).addClass('is-invalid');
                        $('#user_add_mobile_error').html('Mobile number should not start with 0');
                        return false;
                    } else {
                        $(this).removeClass('is-invalid');
                        $('#user_add_mobile_error').html('');
                        return true;
                    }
                });

                $('#user_zip_code').on('input', function() {
                    let value = $(this).val().replace(/\D/g, '');
                    if (value.length > 6) {
                        value = value.substring(0, 6);
                    }
                    $(this).val(value);
                    if (value.length !== 6 || !/^\d+$/.test(value)) {
                        $(this).addClass('is-invalid');
                        $('#user_zip_code_error').html('Please enter a valid 6-digit pincode');
                        return false;
                    } else if (value.length == 6 && value.startsWith('0')) {
                        $(this).addClass('is-invalid');
                        $('#user_zip_code_error').html('Pincode should not start with 0');
                        return false;
                    } else {
                        $(this).removeClass('is-invalid');
                        $('#user_zip_code_error').html('');
                        getPincode();
                        return true;
                    }
                });

                $('#user_add_1').on('input', function() {
                    let value = $(this).val().trim();
                    if (value.length < 10) {
                        $(this).addClass('is-invalid');
                        $('#user_add_1_error').html('Please enter 10 characters minimum');
                        return false;
                    } else {
                        $(this).removeClass('is-invalid');
                        $('#user_add_1_error').html('');
                        return true;
                    }
                });

                $('#user_add_name').on('input', function() {
                    let value = $(this).val().trim();
                    if (value.length < 2) {
                        $(this).addClass('is-invalid');
                        $('#user_add_name_error').html('Please enter 2 characters minimum');
                        return false;
                    } else {
                        $(this).removeClass('is-invalid');
                        $('#user_add_name_error').html('');
                        return true;
                    }
                });




                $(document).on('click', '.address-card', function() {
                    $('.address-card').removeClass('selected');
                    $(this).addClass('selected');
                    const addressId = $(this).find('input[name="address_id"]').val();
                    $('#address_id').val(addressId);

                });

                // $('.form-control').on('blur', function() {
                //     const $field = $(this);
                //     const value = $field.val().trim();

                //     if ($field.prop('required') && !value) {
                //         $field.addClass('is-invalid');
                //         if (!$field.next('.error-message').length) {
                //             $field.after('<div class="error-message">This field is required</div>');
                //         }
                //     } else {
                //         $field.removeClass('is-invalid');
                //         $field.next('.error-message').remove();
                //     }
                // });

                function getPincode() {
                    var pincode = $('#user_zip_code').val();
                    $.ajax({
                        url: `{{ url('pincode') }}/${pincode}`,
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                if (response.record.office_name) {
                                    $('#land_mark').val(response.record.office_name);
                                }
                                $('#user_state').val(response.record.state);
                                $('#user_city').val(response.record.district);
                            } else {
                                showSnackbar('Pincode not found', 'error');
                            }
                        }
                    });
                }

                $('.form-control').on('input', function() {
                    $(this).removeClass('is-invalid');
                    $(this).next('.error-message').remove();
                });
            });
        </script>
    @endpush
@endsection
