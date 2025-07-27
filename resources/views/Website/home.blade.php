@extends('Website.Layout.app')
@section('content')

    <style>
        .productSectionBg {
            background-color: #FFF8E6;
        }
    </style>

    <!-- banner-section start -->
    <section class="section-padding py-4">
        <div class="container">
            <div class="banner-slider">
                <div class="banner-bg" style="background-image: url({{ asset('front_assets') }}/images/banner/banner1.png);">
                </div>
                <div class="banner-bg" style="background-image: url({{ asset('front_assets') }}/images/banner/banner1.png);">
                </div>
                <div class="banner-bg" style="background-image: url({{ asset('front_assets') }}/images/banner/banner1.png);">
                </div>
            </div>
        </div>
    </section>
    <!-- banner-section end -->

    <section class="section-padding py-5">
        <div class="container">
            <div class="product-slider">
                @foreach ($categories as $category)
                    <div class="category-card">
                        <img src="{{ $category->img }}" alt="{{ $category->name }}">
                        <a href="{{url('product/')}}/{{$category->slug}}" class="category-name">{{ ucwords($category->name) }}</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Category end -->
    @if (!empty($featured_products))
        <section class="section-padding pt-0">
            <div class="container">
                <div class="section-heading">
                    <div class="w-100 text-left">
                        <h2>Featured Products</h2>
                        <p class="mb-0">Explore our best rated herbal products</p>
                    </div>
                    <a href="#" class="veiw-btn">View More >></a>
                </div>
                <div class="row product-slider">
                    @foreach ($featured_products as $product)
                        <div class="col-lg-3 col-md-6">
                            <div class="product-card">
                                <div class="product-image">
                                    <a href="product-details.html"><img src="{{ isValidImageUrl($product->product_image) ? $product->product_image : asset('front_assets') . '/images/no_image.jpeg' }}" alt="img"
                                            class=""></a>
                                    <p class="product-review"><i class="fa fa-star"></i> 3.25 (12 Reviews)</p>
                                    <!-- <span class="discount-prod">10% OFF</span>
                                     <span class="prod-wish"><i class="fa-regular fa-heart"></i></span> -->
                                </div>
                                <div class="product-perra">
                                    <div class="prduct-perra-top">
                                        <a href="product-details.html">
                                            <h3>{{ $product->product_name }}</h3>
                                        </a>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div class="price-mian">
                                            <span class="price-throuth">₹{{ $product->mrp }}</span><span
                                                class="price-bg">₹{{ $product->product_sale_price }}</span>
                                        </div>
                                        <a href="javascript:void(0)" class="thm-btn cart-btn">+</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!-- product items end -->


    @if (!empty($products))
        <?php $key = 0; ?>
        @foreach ($products as $category => $productSection)
            <section class="section-padding {{ (int) $key % 2 == 0 ? 'productSectionBg' : '' }}">
                <div class="container">
                    <div class="section-heading">
                        <div class="w-100 text-left">
                            <h2>{{ ucwords(str_replace('_',' ',$category)) }}</h2>
                            <p class="mb-0">Explore our best rated herbal products</p>
                        </div>
                        <a href="{{url('product')}}" class="veiw-btn">View More >></a>
                    </div>
                    <div class="row product-slider">
                        @foreach ($productSection as $product)
                            <div class="col-lg-3 col-md-6"> 
                                <div class="product-card">
                                    <div class="product-image">
                                        <a href="product-details.html"><img src="{{ isValidImageUrl($product->product_image) ? $product->product_image : asset('front_assets') . '/images/no_image.jpeg' }}"
                                                alt="img" class=""></a>
                                        <p class="product-review"><i class="fa fa-star"></i> 3.25 (12 Reviews)</p>
                                        <!-- <span class="discount-prod">10% OFF</span>
                            <span class="prod-wish"><i class="fa-regular fa-heart"></i></span> -->
                                    </div>
                                    <div class="product-perra">
                                        <div class="prduct-perra-top">
                                            <a href="product-details.html">
                                                <h3>{{ $product->product_name }}</h3>
                                            </a>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="price-mian">
                                                <span class="price-throuth">₹{{ $product->mrp }}</span><span
                                                    class="price-bg">₹{{ $product->product_sale_price }}</span>
                                            </div>
                                            <a href="cart.html" class="thm-btn">+</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            @if ((int) $key == 0)
                <!-- Best in Personal care end -->
                <section class="section-padding">
                    <div class="container">
                        <div class="bg-section">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="">
                                        <h1 class="text-white">"Your <strong>Wellbeing</strong> Comes <strong>First</strong>
                                            — Every
                                            Time."</h1>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="">
                                        <img src="{{ asset('front_assets') }}/images/bg1.png" alt="img">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- brands end -->
            @endif

            @if ((int) $key == 2)
                <section class="section-padding pb-0">
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
                                            <p class="mb-0">We’re here to help. Have a query and need help? <a
                                                    href="#">Click
                                                    here</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
            <?php $key++; ?>
        @endforeach
    @endif


    <section class="section-padding">
        <div class="container">
            <div class="text-center">
                <h2 class="mb-0">Refer & Earn</h2>
                <p>Refer your friends and earn up to 100 Unicash</p>
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
