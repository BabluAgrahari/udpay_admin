<!-- banner-section start -->
@extends('Website.Layout.app')
@section('content')
    <style>
        .coupon-item {
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0 !important;
        }

        .coupon-item:hover {
            border-color: #007bff !important;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.1);
        }

        .coupon-item h6 {
            color: #333;
            font-weight: 600;
        }

        .coupon-item .btn-outline-primary {
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 12px;
        }

        .applied-coupon {
            border: 2px solid #28a745;
            background: linear-gradient(135deg, #28a745, #20c997);
        }



        .alert {
            border-radius: 8px;
            border: none;
            padding: 10px 15px;
            margin-top: 10px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
    </style>
    <section class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Left: Cart Items -->
                    <div class="cart-items">
                        <h5 class="cart-header ">Shopping Cart ({{ cartCount() }} Items) <a href="{{ url('product') }}"
                                class="continue">Continue Shopping</a></h5>
                        @php
                            $subtotal = 0;
                            $total_mrp = 0;
                            $total_saving = 0;

                        @endphp
                        @foreach ($cartItems as $item)

                        @canany(['isDistributor', 'isCustomer'])
                            @php $price = $item->product->product_sale_price; @endphp
                        @else
                            @php $price = $item->product->guest_price; @endphp
                        @endcanany
                            @php

                                $item_save =
                                    isset($item->product->mrp) && $item->product->mrp > 0
                                        ? ($item->product->mrp - $price) * $item->quantity
                                        : 0;
                                $total_saving = $total_saving + $item_save;
                                $subtotal = $subtotal + $price * $item->quantity;
                                $item_mrp =
                                    isset($item->product->mrp) && $item->product->mrp > 0
                                        ? $item->product->mrp * $item->quantity
                                        : $price * $item->quantity;
                                $total_mrp = $total_mrp + $item_mrp;

                            @endphp


                            <div class="cart-item" data-product-id="{{ $item->product_id }}">
                                <div class="row">
                                    <div class="col-xl-2 col-md-3">
                                        <a href="#" class="product-img w-100 h-100">
                                            <img src="{{ getImageWithFallback($item->product->product_image) }}"
                                                alt="Product" />
                                        </a>
                                    </div>
                                    <div class="col-xl-10 col-md-9">
                                        <div class="item-details">
                                            <div class="d-flex justify-content-between align-items-baseline gap-3">
                                                <a href="{{ url('detail') }}/{{ $item->product->slug_url }}"
                                                    class="product-title">{{ $item->product->product_name ?? '' }}</a>
                                                <button class="delete delete-item" type="button"
                                                    data-id="{{ $item->product_id }}"><i
                                                        class="fa-solid fa-trash-can"></i></button>
                                            </div>
                                            <div class="price">
                                                @canany(['isCustomer', 'isDistributor'])
                                                    ₹{{ $item->product->product_sale_price }}
                                                @else
                                                    ₹{{ $item->product->guest_price }}
                                                @endcanany
                                                @if (isset($item->product->mrp) && $item->product->mrp > $item->product->product_sale_price)
                                                    <span class="old-price">₹{{ $item->product->mrp }}</span>
                                                    <!--  <span class="discount">6% OFF</span> -->
                                                @endif
                                            </div>
                                            <div class="order-actions">
                                                <div class="quantity">
                                                    <button class="qty-btn cart-decrement" type="button"
                                                        data-product-id="{{ $item->product_id }}">-</button>
                                                    <input type="text" value="{{ $item->quantity ?? 1 }}"
                                                        name="quantity" data-product-id="{{ $item->product_id }}"
                                                        class="quantity-input">
                                                    <button class="qty-btn border-radius cart-increment" type="button"
                                                        data-product-id="{{ $item->product_id }}">+</button>
                                                </div>
                                                <div class="delivery-status"><i class="fa-solid fa-truck-fast"></i> Expected
                                                    delivery in 3–4 days</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
                <div class="col-lg-4">
                    <!-- Right: Summary -->
                    <div class="cart-summary">
                        <div class="pincode-check">
                            <input type="text" placeholder="Enter Pin code">
                            <button class="check-btn">Check</button>
                        </div>

                        <div class="pincode-check mt-3">
                            <input type="text" id="couponCode" placeholder="Enter coupon code" maxlength="50">
                            <button class="check-btn" type="button" onclick="applyCoupon()" id="applyCouponBtn">
                                <span class="btn-text">Apply</span>
                                <span class="btn-loading d-none">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </span>
                            </button>
                        </div>
                        <div id="couponMessage" class="mt-2"></div>

                        @if (session('applied_coupon'))
                            <div class="applied-coupon mt-2 p-2 bg-success text-white rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ session('applied_coupon.code') }}</strong> -
                                        {{ session('applied_coupon.name') }}
                                        <br>
                                        <small>Discount: ₹{{ session('applied_coupon.discount_amount') }}</small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-light" onclick="removeCoupon()">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                        <a href="{{ url('checkout') }}" class="thm-btn w-100 mb-3">Proceed to Pay
                            ₹{{ session('applied_coupon') ? $subtotal - session('applied_coupon.discount_amount') : $subtotal }}</a>
                        <button class="btn btn-outline-danger w-100 mb-3" onclick="clearCart()">Clear Cart</button>

                        <div class="summary-box">
                            <h6 class="mb-2"><span class="text-black">Order Summary</span> ({{ cartCount() }} Items)
                            </h6>
                            <p>Total MRP <span>₹{{ $total_mrp }}</span></p>
                            @if ($total_saving > 0)
                                <p>Total Discounts <span class="discount">−₹{{ $total_saving }}</span></p>
                            @endif
                            @if (session('applied_coupon'))
                                <p>Coupon Discount <span
                                        class="discount">−₹{{ session('applied_coupon.discount_amount') }}</span></p>
                            @endif

                            <p>Convenience Fee <span>₹0</span></p>
                            <hr />
                            <p class="total text-black">Payable Amount
                                <span>₹{{ session('applied_coupon') ? $subtotal - session('applied_coupon.discount_amount') : $subtotal }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--  -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.cart-increment').on('click', function() {
                var productId = $(this).data('product-id');
                var input = $('input[data-product-id="' + productId + '"]');
                var currentQty = parseInt(input.val());
                input.val(currentQty + 1);
                updateCartQuantity(productId, currentQty + 1);
            });

            $('.cart-decrement').on('click', function() {
                var productId = $(this).data('product-id');
                var input = $('input[data-product-id="' + productId + '"]');
                var currentQty = parseInt(input.val());
                if (currentQty > 1) {
                    input.val(currentQty - 1);
                    updateCartQuantity(productId, currentQty - 1);
                }
            });

            $('.quantity-input').on('change', function() {
                var productId = $(this).data('product-id');
                var quantity = parseInt($(this).val());
                if (quantity < 1) {
                    $(this).val(1);
                    quantity = 1;
                }
                updateCartQuantity(productId, quantity);
            });

            $('.delete-item').on('click', function() {
                var productId = $(this).data('id');
                if (confirm('Are you sure you want to remove this item from cart?')) {
                    removeFromCart(productId);
                }
            });

            // Enter key functionality for coupon input
            $('#couponCode').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    applyCoupon();
                }
            });

            // Backup click handler for apply button
            $('#applyCouponBtn').on('click', function(e) {
                e.preventDefault();
                applyCoupon();
            });

            window.clearCart = function() {
                if (confirm('Are you sure you want to clear your entire cart?')) {
                    showCartLoading();

                    $.ajax({
                        url: '{{ url('clear-cart') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            hideCartLoading();
                            if (response.status) {
                                showSnackbar(response.msg, 'success');
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            } else {
                                showSnackbar(response.msg, 'error');
                            }
                        },
                        error: function(xhr) {
                            hideCartLoading();
                            var errorMsg = 'Error clearing cart';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                            }
                            showSnackbar(errorMsg, 'error');
                        }
                    });
                }
            };

    
            function removeFromCart(productId) {
                showCartLoading();

                $.ajax({
                    url: '{{ url('remove-from-cart') }}',
                    type: 'POST',
                    data: {
                        product_id: productId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        hideCartLoading();
                        if (response.status) {
                            $('.cart-item[data-product-id="' + productId + '"]').fadeOut(300,
                        function() {
                                $(this).remove();
                                updateCartSummary(response.record.cart_data);
                                showSnackbar(response.msg, 'success');

                                if (response.cart_data.total_items === 0) {
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1000);
                                }
                            });
                        } else {
                            showSnackbar(response.msg, 'error');
                        }
                    },
                    error: function(xhr) {
                        hideCartLoading();
                        var errorMsg = 'Error removing item';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                        }
                        showSnackbar(errorMsg, 'error');
                    }
                });
            }

         

            // Coupon functionality
            function applyCoupon() {
                var couponCode = $('#couponCode').val().trim();

                if (!couponCode) {
                    showSnackbar('Please enter a coupon code', 'error');
                    return;
                }

                // Show loading state
                $('#applyCouponBtn .btn-text').addClass('d-none');
                $('#applyCouponBtn .btn-loading').removeClass('d-none');
                $('#applyCouponBtn').attr('disabled', true);
                $('#couponMessage').html('');

                $.ajax({
                    url: '{{ url('apply-coupon') }}',
                    type: 'POST',
                    data: {
                        coupon_code: couponCode,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status) {
                            showSnackbar(response.msg, 'success');
                            $('#couponCode').val('');

                            updateCartWithCoupon(response.data);

                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        } else {
                            showSnackbar(response.msg, 'error');
                        }
                    },
                    error: function(xhr) {
                        var errorMsg = 'Something went wrong!';
                        if (xhr.responseJSON && xhr.responseJSON.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        }
                        showSnackbar(errorMsg, 'error');
                    },
                    complete: function() {
                        // Reset button state
                        $('#applyCouponBtn .btn-text').removeClass('d-none');
                        $('#applyCouponBtn .btn-loading').addClass('d-none');
                        $('#applyCouponBtn').removeAttr('disabled');
                    }
                });
            }

            function removeCoupon() {
                if (confirm('Are you sure you want to remove this coupon?')) {
                    $.ajax({
                        url: '{{ url('remove-coupon') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status) {
                                showSnackbar(response.msg, 'success');
                                location.reload();
                            } else {
                                showSnackbar(response.msg, 'error');
                            }
                        },
                        error: function() {
                            showSnackbar('Something went wrong!', 'error');
                        }
                    });
                }
            }

            function updateCartWithCoupon(couponData) {
                // Update the cart summary with coupon discount
                var finalAmount = couponData.final_amount;
                $('.summary-box p:contains("Payable Amount") span').text('₹' + finalAmount);
                $('.thm-btn').text('Proceed to Pay ₹' + finalAmount);

                // Add coupon discount line if not exists
                if ($('.summary-box p:contains("Coupon Discount")').length === 0) {
                    $('.summary-box p:contains("Convenience Fee")').before(
                        '<p>Coupon Discount <span class="discount">−₹' + couponData.discount_amount +
                        '</span></p>'
                    );
                }
            }




        });
    </script>
@endpush
