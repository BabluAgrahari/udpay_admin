

<!-- banner-section start -->
@extends('Website.Layout.app')
@section('content')
<section class="section-padding">
  <div class="container">
    <div class="row">
        <div class="col-lg-8">
            <!-- Left: Cart Items -->
            <div class="cart-items">
                <h5 class="cart-header ">Shopping Cart ({{cartCount()}} Items) <a href="{{url('product')}}" class="continue">Continue Shopping</a></h5>
                @php
                	$subtotal = 0;
                	$total_mrp = 0;
                	$total_saving = 0;

                @endphp
                @foreach($cartItems as $item)

                @php

                	$item_save = isset($item->product->mrp) && $item->product->mrp > 0 ? ($item->product->mrp - $item->product->product_sale_price) * $item->quantity : 0;
                	$total_saving = $total_saving + $item_save;
                	$subtotal = $subtotal + ($item->product->product_sale_price * $item->quantity);
                	$item_mrp = isset($item->product->mrp) && $item->product->mrp > 0 ? $item->product->mrp * $item->quantity : $item->product->product_sale_price * $item->quantity;
                	$total_mrp = $total_mrp + $item_mrp;

                @endphp


                <div class="cart-item" data-product-id="{{$item->product_id}}">
                    <div class="row">
                        <div class="col-xl-2 col-md-3">
                            <a href="#" class="product-img w-100 h-100">
                                <img src="{{ isValidImageUrl($item->product->product_image) ? $item->product->product_image : asset('front_assets') . '/images/product/5.jpg' }}" alt="Product" />
                            </a>
                        </div>
                        <div class="col-xl-10 col-md-9">
                            <div class="item-details">
                                <div class="d-flex justify-content-between align-items-baseline gap-3">
                                    <a href="{{url('detail')}}/{{$item->product->slug_url}}" class="product-title">{{ $item->product->product_name ?? '' }}</a>
                                    <button class="delete delete-item" type="button" data-id="{{$item->product_id}}"><i class="fa-solid fa-trash-can"></i></button>
                                </div>
                                <div class="price">

                                    ₹{{$item->product->product_sale_price }}
                                     @if( isset($item->product->mrp) && $item->product->mrp > $item->product->product_sale_price )
                                    <span class="old-price">₹{{$item->product->mrp}}</span>
                                    <!--  <span class="discount">6% OFF</span> -->
                                     @endif
                                </div>
                                <div class="order-actions">
                                    <div class="quantity">
                                        <button class="qty-btn cart-decrement" type="button" data-product-id="{{$item->product_id}}">-</button>
                                        <input type="text" value="{{$item->quantity ?? 1 }}" name="quantity" data-product-id="{{$item->product_id}}" class="quantity-input">
                                        <button class="qty-btn border-radius cart-increment" type="button" data-product-id="{{$item->product_id}}">+</button>
                                    </div>
                                    <div class="delivery-status"><i class="fa-solid fa-truck-fast"></i> Expected delivery in 3–4 days</div>
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
                
                <button class="coupon-btn" id="openBtn">
                    <div>
                        <img src="asset('front_assets')/images/icons/bxs_offer.png" alt="offer"> <span class="px-2">Apply Coupon</span>
                    </div>
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
                <a href="{{url('checkout')}}" class="thm-btn w-100 mb-3">Proceed to Pay ₹{{$subtotal}}</a>
                <button class="btn btn-outline-danger w-100 mb-3" onclick="clearCart()">Clear Cart</button>

                <div class="summary-box">
                    <h6 class="mb-2"><span class="text-black">Order Summary</span> ({{cartCount()}} Items)</h6>
                    <p>Total MRP <span>₹{{$total_mrp}}</span></p>
                    @if($total_saving > 0)
                    <p>Total Discounts <span class="discount">−₹{{$total_saving}}</span></p>
                    @endif

                    <p>Convenience Fee <span>₹0</span></p>
                    <hr />
                    <p class="total text-black">Payable Amount <span>₹{{$subtotal}}</span></p>
                </div>
            </div>
        </div>
    </div>
  </div>
</section>
<!-- offer modul open -->
<div class="offcanvas p-0" id="myOffcanvas">
  <div class="offcanvas-header">
    <h6 class="mb-0" id="offcanvasRightLabel">Apply Coupon</h6>
    <button type="button" class="btn-close" id="closeBtn"></button>
  </div>
  <div class="offcanvas-body p-0">
    <div class="pincode-check p-2">
        <input type="text" placeholder="Enter Pin code">
        <button class="check-btn">Check</button>
    </div>
    <div class="offer-headding">
        <p>Payment Offers</p>
    </div>
    <div class="offer-item">
        <p class="mb-2 text-black">Simpl Flat Rs 200 cashback for New user & Flat Rs 75 cashback for Repeat user Via Simpl pay-in-3</p>
        <p class="mb-2">Flat Rs 200 cashback for New user & Flat Rs 75 cashback for Repeat on Mov Rs 2500 user Via Simpl pay-in-3</p>
        <div class="offer-item-bottom">
            <p class="mb-0" style="color: #00BF5B;">No Code Required</p><p class="mb-0">T&C Apply</p>
        </div>
    </div>
    <div class="offer-item">
        <p class="mb-2 text-black">Simpl Flat Rs 200 cashback for New user & Flat Rs 75 cashback for Repeat user Via Simpl pay-in-3</p>
        <p class="mb-2">Flat Rs 200 cashback for New user & Flat Rs 75 cashback for Repeat on Mov Rs 2500 user Via Simpl pay-in-3</p>
        <div class="offer-item-bottom">
            <p class="mb-0" style="color: #00BF5B;">No Code Required</p><p class="mb-0">T&C Apply</p>
        </div>
    </div>
  </div>
</div>
<div class="backdrop" id="backdrop"></div>
<!--  -->
<!-- Success Modal -->
<div id="couponModal" class="coupon-modal">
  <div class="coupon-modal-content">
    <button class="modal-close" onclick="closeCouponModal()">×</button>
    <div class="offer-seccess-image mb-4">
        <img src="assets/images/offer-seccess.png" alt="img">
    </div>
    <p class="mb-0 text-black">Coupon Applied Successfully!</p>
    
  </div>
</div>
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

    window.clearCart = function() {
        if (confirm('Are you sure you want to clear your entire cart?')) {
            showCartLoading();
            
            $.ajax({
                url: '{{ url("clear-cart") }}',
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

    function updateCartQuantity(productId, quantity) {
        showCartLoading();
        
        $.ajax({
            url: '{{ url("update-cart-quantity") }}',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                hideCartLoading();
                if (response.status) {
                    updateCartSummary(response.record.cart_data);
                    showSnackbar(response.msg, 'success');
                } else {
                    showSnackbar(response.msg, 'error');
                    var input = $('input[data-product-id="' + productId + '"]');
                    var currentQty = parseInt(input.val());
                    if (quantity > currentQty) {
                        input.val(currentQty - 1);
                    } else {
                        input.val(currentQty + 1);
                    }
                }
            },
            error: function(xhr) {
                hideCartLoading();
                var errorMsg = 'Error updating quantity';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                }
                showSnackbar(errorMsg, 'error');
            }
        });
    }

    function removeFromCart(productId) {
        showCartLoading();
        
        $.ajax({
            url: '{{ url("remove-from-cart") }}',
            type: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                hideCartLoading();
                if (response.status) {
                    $('.cart-item[data-product-id="' + productId + '"]').fadeOut(300, function() {
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

    function updateCartSummary(cartData) {
        console.log(cartData);
        $('.cart-header').text('Shopping Cart (' + cartData.total_items + ' Items)');
        
        $('.summary-box h6').html('<span class="text-black">Order Summary</span> (' + cartData.total_items + ' Items)');
        $('.summary-box p:contains("Total MRP") span').text('₹' + cartData.total_mrp);
        
        if (cartData.total_saving > 0) {
            $('.summary-box p:contains("Total Discounts") span').text('-₹' + cartData.total_saving);
        }
        
        $('.summary-box p:contains("Payable Amount") span').text('₹' + cartData.subtotal);
        $('.thm-btn').text('Proceed to Pay ₹' + cartData.subtotal);
        
        $('.total-cart-count').text(cartData.total_items);
    }
});
</script>
@endpush