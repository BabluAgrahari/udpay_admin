<!-- banner-section start -->
@extends('Website.Layout.app')
@section('content')
<style>
    .product-details-image {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        background: #f8f9fa;
    }
    
    .product-details-image img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        transition: opacity 0.3s ease;
        cursor: zoom-in;
    }
    
    .product-details-image img.error {
        opacity: 0.7;
        filter: grayscale(20%);
    }

    /* Main Image Container */
    .main-image-container {
        margin-bottom: 15px;
    }

    .main-image-container .zoom-gallery {
        width: 100%;
        height: 400px;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        background: #f8f9fa;
    }

    .main-image-container .main-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
        cursor: zoom-in;
    }

    .main-image-container .zoom-gallery:hover .main-image {
        transform: scale(1.05);
    }

    /* Thumbnail Gallery */
    .thumbnail-gallery {
        margin-top: 15px;
    }

    .thumb-container {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-start;
    }

    .thumb-item {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        position: relative;
    }

    .thumb-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .thumb-item.active {
        border-color: #007bff;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }

    .thumb-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: opacity 0.3s ease;
    }

    .thumb-item:hover img {
        opacity: 0.8;
    }

    .thumb-item.active img {
        opacity: 1;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .main-image-container .zoom-gallery {
            height: 300px;
        }

        .thumb-container {
            gap: 8px;
            justify-content: center;
        }

        .thumb-item {
            width: 70px;
            height: 70px;
        }
    }

    @media (max-width: 576px) {
        .main-image-container .zoom-gallery {
            height: 250px;
        }

        .thumb-container {
            gap: 6px;
        }

        .thumb-item {
            width: 60px;
            height: 60px;
        }

        .zoom-overlay img {
            max-width: 95%;
            max-height: 80%;
        }

        .zoom-nav {
            padding: 10px 8px;
            font-size: 18px;
        }

        .zoom-close {
            width: 35px;
            height: 35px;
            font-size: 25px;
        }
    }
    
    .thumb-slider .swiper-slide img {
        width: 100%;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
        cursor: pointer;
        transition: opacity 0.3s ease;
    }
    
    .thumb-slider .swiper-slide img:hover {
        opacity: 0.8;
    }
    
    .thumb-slider .swiper-slide img.error {
        opacity: 0.7;
        filter: grayscale(20%);
    }
    
    .swiper-slide-thumb-active img {
        border: 2px solid #007bff;
    }
    
    /* Loading state */
    .image-loading {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }

    /* Zoom Gallery Styles */
    .zoom-gallery {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        background: #f8f9fa;
    }

    .zoom-gallery img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        transition: transform 0.3s ease;
        cursor: zoom-in;
    }

    .zoom-gallery:hover img {
        transform: scale(1.05);
    }

    .zoom-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        display: none;
        justify-content: center;
        align-items: center;
        cursor: zoom-out;
        transition: opacity 0.3s ease;
    }

    .zoom-overlay img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        transition: opacity 0.3s ease;
    }

    .zoom-close {
        position: absolute;
        top: 20px;
        right: 20px;
        color: white;
        font-size: 30px;
        cursor: pointer;
        z-index: 10000;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease;
    }

    .zoom-close:hover {
        background: rgba(0, 0, 0, 0.8);
    }

    .zoom-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        padding: 15px 10px;
        cursor: pointer;
        font-size: 20px;
        border-radius: 4px;
        transition: background 0.3s ease;
    }

    .zoom-nav:hover {
        background: rgba(0, 0, 0, 0.8);
    }

    .zoom-prev {
        left: 20px;
    }

    .zoom-next {
        right: 20px;
    }

    .zoom-counter {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        color: white;
        background: rgba(0, 0, 0, 0.5);
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
    }

    /* Enhanced thumbnail styles */
    .thumb-slider {
        margin-top: 15px;
    }

    .thumb-slider .swiper-slide {
        opacity: 0.6;
        transition: opacity 0.3s ease;
    }

    .thumb-slider .swiper-slide-thumb-active {
        opacity: 1;
    }

    .thumb-slider .swiper-slide img {
        border: 2px solid transparent;
        transition: border-color 0.3s ease;
    }

    .thumb-slider .swiper-slide-thumb-active img {
        border-color: #007bff;
    }

    /* Image zoom indicator */
    .zoom-indicator {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .zoom-gallery:hover .zoom-indicator {
        opacity: 1;
    }

    /* Loading spinner for zoom images */
    .zoom-loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 40px;
        height: 40px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-top: 3px solid #fff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        z-index: 10001;
    }

    @keyframes spin {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }

    /* Enhanced zoom gallery hover effects */
    .zoom-gallery {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        background: #f8f9fa;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .zoom-gallery:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .zoom-gallery img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        transition: transform 0.3s ease;
        cursor: zoom-in;
    }

    .zoom-gallery:hover img {
        transform: scale(1.05);
    }


</style>
<section class="section-padding pt-5" >
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="product-slider-wrapper">
                    <!-- Single Main Image Display -->
                    <div class="main-image-container">
                        <div class="zoom-gallery" id="mainImageContainer">
                            @if($product->product_image)
                                <img src="{{ getImageWithFallback($product->product_image) }}" class="main-image zoom-image" alt="{{ $product->product_name }}" data-index="0" />
                            @elseif($product->images->count() > 0)
                                <img src="{{ getImageWithFallback($product->images->first()->image) }}" class="main-image zoom-image" alt="{{ $product->product_name }}" data-index="0" />
                            @else
                                <img src="{{ asset('front_assets/images/no-image.svg') }}" class="main-image" alt="No image available" />
                            @endif
                            <div class="zoom-indicator">Click to zoom</div>
                        </div>
                    </div>
                    
                    <!-- Thumbnail Gallery -->
                    <div class="thumbnail-gallery">
                        <div class="thumb-container">
                            @if($product->product_image)
                                <div class="thumb-item active" data-src="{{ getImageWithFallback($product->product_image) }}" data-index="0">
                                    <img src="{{ getImageWithFallback($product->product_image) }}" class="thumb" alt="{{ $product->product_name }}" />
                                </div>
                            @endif
                            @foreach($product->images as $index => $image)
                                <div class="thumb-item {{ !$product->product_image && $index == 0 ? 'active' : '' }}" data-src="{{ getImageWithFallback($image->image) }}" data-index="{{ $product->product_image ? $index + 1 : $index }}">
                                    <img src="{{ getImageWithFallback($image->image) }}" class="thumb" alt="{{ $product->product_name }}" />
                                </div>
                            @endforeach
                            @if(!$product->product_image && $product->images->count() == 0)
                                <div class="thumb-item active" data-src="{{ asset('front_assets/images/no-image.svg') }}" data-index="0">
                                    <img src="{{ asset('front_assets/images/no-image.svg') }}" class="thumb" alt="No image available" />
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Zoom Overlay -->
                <div class="zoom-overlay" id="zoomOverlay">
                    <div class="zoom-close" id="zoomClose">&times;</div>
                    <button class="zoom-nav zoom-prev" id="zoomPrev">‹</button>
                    <button class="zoom-nav zoom-next" id="zoomNext">›</button>
                    <div class="zoom-counter" id="zoomCounter">1 / 1</div>
                    <div class="zoom-loading" id="zoomLoading"></div>
                    <img id="zoomImage" src="" alt="Zoomed Image" />
                </div>
                <div class="text-center py-3">
                    <img src="{{ asset('front_assets') }}/images/product-details/ayurveda.png" class="" alt="img" />
                    <h5 class="color-one">Formulated by Ayurvedic Experts</h5>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="product-details-right">
                    <div class="product-details">
                        <span class="color-one border-bottom">{{ $product->category->name ?? 'Category' }}</span>
                        <h1 class="title">{{ $product->product_name }}</h1>                        
                        <div class="rating ">
                            <div>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div> 4<span>(39 Reviews)</span>
                        </div>
                        <div class="box-color">
                            <p class="color-one mb-0">{{ $product->product_short_description ?? 'Product description not available.' }}</p>
                        </div>
                        <div class="price-box">
                            <span class="current-price">Price: ₹{{ number_format($product->product_sale_price, 2) }}</span>
                            <span class="old-price">Price: ₹{{ number_format($product->mrp, 2) }}</span>
                        </div>
                        <div class="price-box mt-0">
                            @php
                                $discount = 0;
                                if($product->mrp > 0) {
                                    $discount = round((($product->mrp - $product->product_sale_price) / $product->mrp) * 100);
                                }
                            @endphp
                            @if($discount > 0)
                                <span class="discount color-two">{{ $discount }}% OFF</span>
                            @endif
                            <span class="tax-info color-two">Inclusive of all taxes</span>
                        </div>
                        <div class="qty-cart">
                            <div class="quantity">
                                <button class="qty-btn cart-decrement">-</button>
                                <input type="text" value="1" class="quantity-input" data-product-id="{{ $product->id }}">
                                <button class="qty-btn border-raduis cart-increment">+</button>
                            </div>
                            <div class="options">
                                @foreach($product->variants as $variant)
                                    <button class="option {{ $variant->id == $product->variant_id ? 'active' : '' }}">{{ $variant->variant_name }}</button>
                                @endforeach
                            </div>
                        </div>
                        <div class="details-add-btn">
                            <a href="javascript:void(0)" data-id="{{ $product->id }}" class="thm-btn cart-btn">Add to cart</a>

                            @if(Auth::check() && Gate::allows('isCustomer'))
                            <a href="{{ url('buy/'.$product->slug_url) }}" class="thm-btn buy-now">Quick Buy</a>
                            @else
                            <a href="#" data-popup="login1" class="openPopup thm-btn buy-now">Quick Buy</a>
                            @endif

                            @if(Auth::check() && Gate::allows('isCustomer'))
                            <a href="javascript:void(0)" class="thm-btn bg-light w-100 add-to-wishlist" data-id="{{ $product->id }}">Add to wishlist</a>
                            @else
                            <a href="#" data-popup="login1" class="openPopup thm-btn bg-light w-100 ">Add to wishlist</a>
                            @endif
                        </div>
                        <div class="box-color">
                            <p class="color-one mb-2">Available Offers (2 offers)</p>
                            <p class="box-list">Flat Rs 200 cashback for New user & Flat Rs 75 cashback</p>
                            <p class="box-list">Flat Rs 200 cashback on payment with Paytm</p>
                        </div>
                        <div class="box-color">
                            <p class="color-one mb-2">Delivery & Services</p>
                            <div class="delivery-type">
                                <input type="text" placeholder="Enter Pin code" class="form-control">
                                <button class="check-btn">Check</button>
                            </div>
                        </div>
                        <div class="box-color">
                            <p class="mb-0 text-black">Product Details</p>
                            <ul class="delivery-service-list">
                                @if($product_details)
                                    {!! nl2br(e($product_details->details)) !!}
                              
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- banner-section end -->
<section class="section-padding pt-0">
  <div class="container">
    <div class="section-heading">
        <div class="w-100 text-left">
             <h2 class="mb-2">frequently bought together</h2>
            <p>Explore the best combo offers that people has bought frequently</p>
        </div>
    </div>
    <div class="row">
        <?php $totalPrice = 0; ?>
        @foreach($frequentlyBoughtTogether as $product)
        <div class="col-lg-3 col-md-6">
            <div class="product-card mx-0">
                <div class="product-image">
                    <a href="{{ url('product/'.$product->slug_url) }}"><img src="{{ getImageWithFallback($product->product_image) }}" alt="img" class=""></a>
                     <p class="product-review"><i class="fa fa-star"></i> 3.25 (12 Reviews)</p>
                </div>
                <div class="product-perra">
                    <div class="prduct-perra-top">
                        <a href="{{ url('product/'.$product->slug_url) }}"><h3>{{ $product->product_name }}</h3></a>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="price-mian">
                            <span class="price-throuth">₹{{ number_format($product->product_sale_price, 2) }}</span><span class="price-bg">₹{{ number_format($product->mrp, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
        <?php $totalPrice += $product->product_sale_price; ?>
        @endforeach
        <div class="col-lg-3 col-md-6 d-flex align-items-center justify-content-center text-center">
            <div class="product-card-detail-price">
                <p class="mb-2">Total price: <span class="text-black">₹{{ number_format($totalPrice, 2) }}</span> </p>
                <a href="javascript:void(0)" class="thm-btn w-100 cart-btn" data-id="{{ $frequentlyBoughtTogether->pluck('id')->implode(',') }}">Add all 3 to Cart</a>
            </div>
        </div>
    </div>
  </div>
</section>

<!-- frequently -->
 <div class="marquee">
  <div class="marquee-content">
    <span>Organic Ingredients</span>
    <div class="marquee-image">
        <img src="{{ asset('front_assets') }}/images/icons/ayurveda.png" alt="img">
    </div>
    <span>All Natural</span>
    <div class="marquee-image">
        <img src="{{ asset('front_assets') }}/images/icons/ayurveda.png" alt="img">
    </div>
    <span>Step towards Healthy Life</span>
    <div class="marquee-image">
        <img src="{{ asset('front_assets') }}/images/icons/ayurveda.png" alt="img">
    </div>
    <span>100% Ayurveda</span>
    <div class="marquee-image">
        <img src="{{ asset('front_assets') }}/images/icons/ayurveda.png" alt="img">
    </div>
  </div>
</div>
<!-- marquee -->
<section class="section-padding py-3" style="background: #FFF8E6;">
  <div class="container">
    <x-video-player :videos="$reels" title="Our Happy Customers" />
  </div>
</section>
<!-- Our Happy Customers -->
<section class="section-padding py-4 mt-5" style="background: #FFF4EE;">
  <div class="container">
    <div class="">
        <div class="w-100 text-center">
             <h2 class="mb-2">Benefits of {{ $product->product_name }} </h2>
            <p>Benefits that helps you stay healthy and live longer</p>
        </div>
    </div>
    <div class="row">
        @if($product_details)
            {!! nl2br(e($product_details->result)) !!}
        @endif
    </div>
  </div>
</section>
<!-- Our Happy Customers -->
<section class="section-padding py-4">
  <div class="container">
    <div class="">
        <div class="w-100 text-center">
             <h2 class="mb-2">Key Ingredients</h2>
            <p>Natural ingredients that will take care of your health</p>
        </div>
    </div>
    <div class="row">
        @if(!empty($product_details->key_ings))
            {!! nl2br(e($product_details->key_ings)) !!}
        @endif
    </div>
  </div>
</section>
<!-- Our Happy Customers -->
<section class="section-padding py-4" style="background: #FFF8E6;">
  <div class="container">
    <div class="section-heading">
        <div class="w-100 text-left">
             <h2>People also bought</h2>
            <p class="mb-0">Explore our best rated products</p>
        </div>
       <a href="#" class="veiw-btn">View More >></a>    
    </div>
    <div class="row product-slider">
        @foreach($similarProducts as $product)
        <div class="col-lg-3 col-md-6">
            <div class="product-card">
                <div class="product-image">
                    <a href="{{ url('detail/'.$product->slug_url) }}"><img src="{{ getImageWithFallback($product->product_image) }}" alt="img" class=""></a>
                     <p class="product-review"><i class="fa fa-star"></i> {{ $product->reviews->avg('rating') }} ({{ $product->reviews->count() }} Reviews)</p>
                    @if(Auth::check() && Gate::allows('isCustomer'))
                    <span class="prod-wish"><i class="fa-regular fa-heart add-to-wishlist" data-id="{{ $product->id }}"></i></span>
                    @else
                    <span class="prod-wish"><i class="fa-regular fa-heart add-to-wishlist" data-popup="login1" class="openPopup"></i></span>
                    @endif
                </div>
                <div class="product-perra">
                    <div class="prduct-perra-top">
                        <a href="{{ url('detail/'.$product->slug_url) }}"><h3>{{ $product->product_name }}</h3></a>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="price-mian">
                            <span class="price-throuth">₹{{ number_format($product->product_sale_price, 2) }}</span><span class="price-bg">₹{{ number_format($product->mrp, 2) }}</span>
                        </div>
                        <a href="javascript:void(0)" class="thm-btn cart-btn" data-id="{{ $product->id }}">+</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
  </div>
</section>
<!-- People also bought -->
<section class="section-padding py-4">
  <div class="container">
    <div class="">
        <div class="w-100 text-center">
             <h2 class="mb-2">Customers Love</h2>
            <p>Just have a look at how happy our customers are</p>
        </div>
    </div>
    <div class="client-slider">
        @foreach($product->reviews as $review)
        <div class="testimonial-card">
           <div class="testi-header">
             <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Leo" class="testimonial-avatar">
             <div class="testi-content">
                <h4>{{ $review->user->first_name }} {{ $review->user->last_name }}</h4>
                <div class="position-main"> 
                    <p class="position">{{ $review->user->role }}</p>
                    <div class="rating">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <i class="fa fa-star"></i>
                            @else
                                <i class="fa fa-star-o"></i>
                            @endif
                        @endfor
                    </div>
                </div>
             </div>
           </div>
            <p>{{ $review->review }}</p>
            <p>{{ date('M d, Y', strtotime($review->created_at)) }}</p>
        </div>
        @endforeach
       
    </div>
  </div>
</section>
<!-- testimonial end -->
<div class="py-5" style="background: #C9FFE9;">
<div class="container">
    <div>
        <h1 class="mb-0 color-one">Suitable for people with T2DM looking for a natural solution to manage blood sugar levels </h1>
    </div>
</div>
</div>
 <!--  -->
<section class="section-padding">
    <div class="container">
         <div class="w-100 text-center">
             <h2 class="mb-2">How to use?</h2>
            <p>You can achieve your health by simply following the steps</p>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div>
                    <img src="{{ asset('front_assets') }}/images/product-details/bg1.png" alt="img" class="image-fit">
                </div>
            </div>
            <div class="col-lg-6">
                @if(!empty($product_details->uses))
                    {!! nl2br(e($product_details->uses)) !!}
                @endif
            </div>
        </div>
    </div>
</section>
<!-- how to use -->
<section class="section-padding pb-0" style="background: #FFF4EE;">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="choose-left">
                    <h1>Why Choose UniPay?</h1>
                    <p>Save Time and Earn Money!</p>
                    <div>
                        <img src="{{ asset('front_assets') }}/images/bg2.png" alt="img">
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="choose-right">
                    <div class="feature-box p-3 d-flex align-items-start mb-3">
                        <div class="icon me-3">
                            <img src="{{ asset('front_assets') }}/images/icons/1.png" alt="img">
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold color-one">Low Cost</h6>
                            <p class="mb-0">Always get cheapest price with the best in the industry</p>
                        </div>
                        </div>

                        <div class="feature-box p-3 d-flex align-items-start mb-3">
                        <div class="icon me-3">
                            <img src="{{ asset('front_assets') }}/images/icons/2.png" alt="img">
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold color-one">Fast</h6>
                            <p class="mb-0">Get your recharge to family and friends in minutes</p>
                        </div>
                        </div>

                        <div class="feature-box p-3 d-flex align-items-start mb-3">
                        <div class="icon me-3">
                            <img src="{{ asset('front_assets') }}/images/icons/5.png" alt="img">
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold color-one">Trust Pay</h6>
                            <p class="mb-0">100% Payment Protection. Easy Return Policy</p>
                        </div>
                        </div>

                        <div class="feature-box p-3 d-flex align-items-start mb-3">
                        <div class="icon me-3">
                            <img src="{{ asset('front_assets') }}/images/icons/6.png" alt="img">
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold color-one">100% Secure Payments</h6>
                            <p class="mb-0">Moving your card details to a much more secured place</p>
                        </div>
                        </div>

                        <div class="feature-box p-3 d-flex align-items-start mb-3">
                        <div class="icon me-3">
                            <img src="{{ asset('front_assets') }}/images/icons/7.png" alt="img">
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold color-one">24×7 Support</h6>
                            <p class="mb-0">We’re here to help. Have a query and need help? <a href="#">Click here</a></p>
                        </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>
 <!--why choose uniPay?  -->

<section class="section-padding">
  <div class="container">
    <div class="text-center">
        <h2 class="mb-0">Refer & Earn</h2><p>Refer your friends and earn up to 100 Unicash</p>
    </div>
    <div class="row">
      <div class="col-lg-4 col-md-6">
        <div class="step-card p-4 bg-light-green position-relative">
          <div class="icon-circle mb-3">
            <img src="{{ asset('front_assets') }}/images/icons/8.png" alt="img">
          </div>
          <div class="step-number bg-success text-white">1</div>
          <h5 class="fw-bold mb-1">You Refer Friends</h5>
          <p class="mb-0">Share your referral link with friends. Both get 100 Unicash</p>
        </div>
      </div>
      <!-- Card 2 -->
      <div class="col-lg-4 col-md-6">
        <div class="step-card p-4 bg-light-blue position-relative">
          <div class="icon-circle mb-3">
            <img src="{{ asset('front_assets') }}/images/icons/9.png" alt="img">
          </div>
          <div class="step-number bg-primary text-white">2</div>
          <h5 class="fw-bold mb-1">You Refer Friends</h5>
          <p class="mb-0">Your friends register with using your referral link</p>
        </div>
      </div>
      <!-- Card 3 -->
      <div class="col-lg-4 col-md-6">
        <div class="step-card p-4 bg-light-yellow position-relative">
          <div class="icon-circle mb-3">
            <img src="{{ asset('front_assets') }}/images/icons/10.png" alt="img">
          </div>
          <div class="step-number bg-warning text-white">3</div>
          <h5 class="fw-bold mb-1">You can earn</h5>
          <p class="mb-0">You get Unicash. You can use these Unicash to take recharge</p>
        </div>
      </div>
    </div>
    <div class="text-center mt-4">
      <a href="#" class="thm-btn refer-btn">Get your earnings started</a>
    </div>
  </div>
</section>
 <!-- download section -->
 <section class="section-padding py-4" style="background: #C9FFE9;">
    <div class="container">
        <div class="download-bg">
            <div class="download-image">
                <img src="{{ asset('front_assets') }}/images/download-bg.png" alt="img">
            </div>
            <div class="download-content">
                <h2>Download our app And get 100 uNI points free!</h2>
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ asset('front_assets') }}/images/download-play.png" alt="img">
                    <img src="{{ asset('front_assets') }}/images/app-store.png" alt="img">
                </div>
            </div>
        </div>
      </div>
 </section>



@endsection


@push('scripts')
<script src="{{ asset('front_assets') }}/js/zoomProductGallery.js"></script>

  <script>
  $(document).ready(function() {
    // Cart functionality
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

    

    });
  </script>
 @endpush