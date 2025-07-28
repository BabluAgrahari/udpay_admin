@extends('Website.Layout.app')
@section('content')
<!-- banner-section end -->
<section class="section-padding pt-4">
  <div class="container">
    <div class="section-heading justify-content-between text-left">
        <div class="text-left">
            <h6 class="mb-0">Showing Total {{ $products->total() }} Results 
            	@if($category_data)
            	for <strong> “{{$category_data->name}}”</strong>
            	@endif
            	</h6>
        </div>
        <div class="filter-right">
            <p class="color-two filter-text mb-0">FILTER  <i class="fa-solid fa-filter"></i></p>
            <div class="color-two">
                <p class="product-sort mb-0 color-two">Sort By<i class="fa-solid fa-arrow-right-arrow-left"></i></p>
            </div>
        </div>
    </div>
    <div class="row">
    	 @if ($products->isEmpty())
    	 <div class="col"><p>No products found.</p></div>
        
    @else
    	@foreach($products as $product)
        <div class="col-lg-3 col-md-6">
            <div class="product-card">
                <div class="product-image">
                    <a href="{{url('detail')}}/{{$product->slug_url}}"><img src="{{ getImageWithFallback($product->product_image) }}" alt="img" class="" onerror="this.src='{{ asset('front_assets/images/no-image.svg') }}'"></a>
                     <p class="product-review"><i class="fa fa-star"></i> 3.25 (12 Reviews)</p>
                    <!-- <span class="discount-prod">10% OFF</span>
                    <span class="prod-wish"><i class="fa-regular fa-heart"></i></span> -->
                </div>
                <div class="product-perra">
                    <div class="prduct-perra-top">
                        <a href="{{url('detail')}}/{{$product->slug_url}}"><h3>{{ $product->product_name }}</h3></a>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="price-mian">
                            <span class="price-throuth">₹{{ $product->mrp }}</span><span class="price-bg">₹{{ $product->product_sale_price }}</span>
                        </div>
                        <a href="javascript:void(0)" class="thm-btn cart-btn">+</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
     @endif
       
    </div>

    <div class="row">
    	<div class="col">
    		 <div class="d-flex justify-content-center">
            {{ $products->links('vendor.pagination.bootstrap-5') }} <!-- Use Bootstrap 5 pagination view -->
        </div>
    	</div>
    </div>
  </div>
</section>
 <!-- product items end -->

<section class="section-padding pt-0">
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