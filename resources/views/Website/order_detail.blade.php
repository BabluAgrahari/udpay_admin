@extends('Website.Layout.app')
@section('content')
    <style>
        /* Review Modal Styles */
        .review-modal .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .review-modal .modal-header {
            border-bottom: 1px solid #eee;
            padding: 20px 25px;
        }

        .review-modal .modal-body {
            padding: 25px;
        }

        .review-modal .modal-footer {
            border-top: 1px solid #eee;
            padding: 20px 25px;
        }

        .rating-stars {
            display: flex;
            gap: 5px;
            margin-bottom: 20px;
        }

        .rating-stars .star {
            font-size: 30px;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .rating-stars .star.active {
            color: #ffc107;
        }

        .rating-stars .star:hover,
        .rating-stars .star:hover~.star {
            color: #ffc107;
        }

        .review-form textarea {
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 15px;
            resize: vertical;
            min-height: 120px;
        }

        .review-form textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .review-link {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            font-size: 14px;
        }

        .review-link:hover {
            text-decoration: underline;
        }

        .review-submit-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .review-submit-btn:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .review-submit-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }

        .order-actions {
            margin-top: 10px;
        }

        .review-link {
            display: inline-block;
            margin-top: 5px;
        }
    </style>

    <section class="section-padding">
        <section class="section-padding pt-4">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div>
                            <div style="text-align: right;">Order ID: {{ $order->order_id }}</div>
                            <div class="track-left">
                               
                                <h3 class="tab-title">Track Order</h3>
                                <div class="tab-content-body">
                                    @foreach ($order->orderToProducts as $orderToProduct)
                                        <div class="order-card">
                                            <div class="product-img">
                                                <a href="{{ url('detail/' . $orderToProduct->product->slug) }}"><img
                                                        src="{{ isValidImageUrl($orderToProduct->product->product_image) ? $orderToProduct->product->product_image : asset('front_assets') . '/images/no_image.jpeg' }}"
                                                        alt="Whey Protein" /></a>
                                            </div>
                                            <div class="order-details">
                                                <div class="product-top">
                                                    <a href="{{ url('detail/' . $orderToProduct->product->slug) }}"
                                                        class="product-title">{{ $orderToProduct->product->product_name }}</a>
                                                </div>
                                                <div class="price">
                                                    <?php
                                                    //calculate discount from and mrp
                                                    $discount = $orderToProduct->product->mrp * $orderToProduct->quantity - $orderToProduct->price * $orderToProduct->quantity;
                                                    $discountPercentage = ($discount / ($orderToProduct->product->mrp * $orderToProduct->quantity)) * 100; ?>
                                                    ₹{{ $orderToProduct->price * $orderToProduct->quantity }} <br> <span
                                                        class="old-price">₹{{ $orderToProduct->product->mrp * $orderToProduct->quantity }}</span>
                                                    <span class="discount">{{ number_format($discountPercentage, 2) }}%
                                                        OFF</span>
                                                </div>
                                                <div class="order-actions">
                                                    <div class="delivery-status"> Expected delivery in 6-7 days</div>

                                                    @if (Auth::check() && $order->payment_status !='failed')
                                                        <span class="review-link"
                                                            onclick="openReviewModal({{ $orderToProduct->product->id }}, '{{ $orderToProduct->product->product_name }}')">
                                                            <i class="fa fa-edit"></i> Write a Review
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                            <?php //echo "<pre>"; print_r($order);die; ?>
                            <div class="order-summary-box">
                                <div class="summary-row">
                                    <span>Taxable Amount . {{ count($order->orderToProducts) }} items</span>
                                    @if(!empty(Auth::check()) && Auth::user()->can('isCustomer') || Auth::user()->can('isDistributor'))
                                        <span>₹{{ $order->total_gross??0 }}</span>
                                    @else
                                        <span>₹{{ $order->total_gross??0 }}</span>
                                    @endif
                                </div>
                                <div class="summary-row">
                                    <span>Shipping</span>
                                    <span>₹{{ $order->shipping_charges ?? 0 }}</span>
                                </div>
                                <div class="summary-row">
                                    <span>Discount</span>
                                    @if(!empty(Auth::check()) && Auth::user()->can('isCustomer') || Auth::user()->can('isDistributor'))
                                    <span>₹{{ $order->discount_amt ?? 0 }}</span>
                                @else
                                    <span>₹{{ $order->total_discount ?? 0 }}</span>
                                @endif
                                </div>
                                <div class="summary-row">
                                    <span>GST</span>
                                    @if(!empty(Auth::check()) && Auth::user()->can('isCustomer') || Auth::user()->can('isDistributor'))
                                    <span>₹{{ $order->gst_amt ?? 0 }}</span>
                                @else
                                    <span>₹{{ $order->total_gst ?? 0 }}</span>
                                @endif
                                </div>
                                
                                <div class="summary-row total">
                                    <span>Total</span>
                                    @if(!empty(Auth::check()) && Auth::user()->can('isCustomer') || Auth::user()->can('isDistributor'))
                                    <span>₹{{ $order->total_net_amt ?? 0 }}</span>
                                @else
                                    <span>₹{{ $order->total_net_amount ?? 0 }}</span>
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-lg-4">
                        <div class="track-right">
                            <div class="order-track-steps">
                                <div class="step completed">
                                    <div class="dot"></div>
                                    <div>
                                        <div class="step-title">Ordered</div>
                                        <div class="step-date">Thu, 6th Aug 25</div>
                                    </div>
                                </div>
                                <div class="step completed">
                                    <div class="dot"></div>
                                    <div>
                                        <div class="step-title">Packed</div>
                                        <div class="step-date">Mon, 10th Aug 25</div>
                                    </div>
                                </div>
                                <div class="step completed">
                                    <div class="dot"></div>
                                    <div>
                                        <div class="step-title">Shipped</div>
                                        <div class="step-date">Mon, 10th Aug 25</div>
                                    </div>
                                </div>
                                <div class="step pending">
                                    <div class="dot"></div>
                                    <div>
                                        <div class="step-title">Delivery</div>
                                        <div class="step-date">Expected by Fri, 14th Aug '25</div>
                                        <div class="step-note">Shipment yet to be delivered</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </section>
    </section>

    <!-- Product Review Modal -->
    <div class="modal fade review-modal" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Write a Review</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="reviewForm" class="review-form">
                        <input type="hidden" id="productId" name="product_id">

                        <div class="form-group">
                            <label for="rating">Rating</label>
                            <div class="rating-stars" id="ratingStars">
                                <i class="fa fa-star star" data-rating="1"></i>
                                <i class="fa fa-star star" data-rating="2"></i>
                                <i class="fa fa-star star" data-rating="3"></i>
                                <i class="fa fa-star star" data-rating="4"></i>
                                <i class="fa fa-star star" data-rating="5"></i>
                            </div>
                            <input type="hidden" id="rating" name="rating" value="5">
                            <span class="error" id="rating_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="review">Review</label>
                            <textarea class="form-control" id="review" name="review"
                                placeholder="Share your experience with this product (minimum 10 characters)..." required></textarea>
                            <small class="form-text text-muted">Minimum 10 characters required</small>
                            <span class="error" id="review_error"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="thm-btn" data-dismiss="modal">Cancel</button>
                    <button type="button" class="thm-btn" id="submitReview" onclick="submitReview()">
                        <span class="btn-text">Submit Review</span>
                        <span class="btn-loading" style="display: none;">
                            <i class="fa fa-spinner fa-spin"></i> Submitting...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Review Modal Functions
        function openReviewModal(productId, productName) {
            $('#productId').val(productId);
            $('#rating').val(5);
            $('#review').val('');
            $('#reviewModalLabel').text('Write a Review for ' + productName);

            // Reset stars to 5
            $('.rating-stars .star').removeClass('active');
            $('.rating-stars .star[data-rating="5"]').addClass('active');

            $('#reviewModal').modal('show');
        }

        // Star rating functionality
        $(document).ready(function() {
            $('.rating-stars .star').click(function() {
                var rating = $(this).data('rating');
                $('#rating').val(rating);

                // Update star display
                $('.rating-stars .star').removeClass('active');
                $('.rating-stars .star').each(function() {
                    if ($(this).data('rating') <= rating) {
                        $(this).addClass('active');
                    }
                });
            });
        });

        function submitReview() {
            var productId = $('#productId').val();
            var rating = $('#rating').val();
            var review = $('#review').val().trim();

            // Validation
            if (!review) {
                showSnackbar('Please enter your review', 'error');
                return;
            }

            if (review.length < 10) {
                showSnackbar('Review must be at least 10 characters long', 'error');
                return;
            }

            // Show loading state
            var submitBtn = $('#submitReview');
            var btnText = submitBtn.find('.btn-text');
            var btnLoading = submitBtn.find('.btn-loading');

            submitBtn.prop('disabled', true);
            btnText.hide();
            btnLoading.show();

            // Submit review via AJAX
            $.ajax({
                url: '{{ url('review/store') }}',
                type: 'POST',
                data: {
                    product_id: productId,
                    rating: rating,
                    review: review,
                    '_token': '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        showSnackbar(response.msg, 'success');
                        $('#reviewModal').modal('hide');

                        // Optionally refresh the page or update the review section
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        showSnackbar(response.msg, 'error');
                    }
                },
                error: function(xhr, status, error) {

                    //validation msg and error msg
                    if (xhr.responseJSON.validation) {
                        $.each(xhr.responseJSON.validation, function(key, value) {
                            $('#' + key + '_error').text(value[0]);
                        });
                    } else {
                        showSnackbar(xhr.responseJSON.msg, 'error');
                    }
                },
                complete: function() {
                    // Reset button state
                    submitBtn.prop('disabled', false);
                    btnText.show();
                    btnLoading.hide();
                }
            });
        }
    </script>
@endpush
