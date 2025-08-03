@extends('Website.Layout.app')
@section('content')

<section class="section-padding py-5 pt-3">
    <div class="container">
      <div class="row">
          <div class="col-lg-12">
              <div class="dashboard">
                  <!-- Sidebar -->
                  <div class="sidebar">
                      <a href="{{ url('my-account') }}" class="tab-btn" ><i class="fa-solid fa-user"></i> My Account</a>
                      <a href="{{ url('order-history') }}" class="tab-btn {{ request()->is('order-history') ? 'active' : '' }}" data-tab="orders"><i class="fa-solid fa-box"></i> Order History</a>
                      <a href="{{ url('address-book') }}" class="tab-btn" ><i class="fa-solid fa-book"></i> Address Book</a>
                      <a href="{{ url('wishlist') }}" class="tab-btn" ><i class="fa-solid fa-heart"></i> My Wishlist</a>
                      <a href="{{ url('logout') }}" class="tab-btn logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                  </div>
                  <!-- Content -->
                  <div class="tab-content">
                     
                      <div class="tab-panel active" id="orders">
                          <h3 class="tab-title">Order History</h3>
                          <div class="tab-content-body">
                            @foreach($orders as $order)
                              <div class="order-card">
                                  
                                  <div class="order-details">
                                      <div class="product-top">
                                          <a href="{{ url('order-detail/'.$order->id) }}" class="product-title">Order ID: {{ $order->order_id }}</a>
                                          <div class="text-end">
                                            @if($order->status == 'delivered')
                                              <a href="#" class="reorder"><i class="fa-solid fa-right-left"></i> Reorder</a>
                                            @endif
                                              <a href="{{ url('order-detail/'.$order->id) }}" class="track"><i class="fa-solid fa-location-dot"></i> Track Order</a>
                                          </div>
                                      </div>
                                      <div class="price">
                                        Amount: <span class="color-one">{{ $order->total_amount }}</span>
                                      </div> 
                                      <p class="mb-2">{{ date('d M, Y', strtotime($order->created_at)) }}</p>                              
                                      <div class="order-actions">
                                          <div class="rating open-rating-modal">
                                              <span class="text-black">Rate</span>
                                              <i class="fa-regular fa-star"></i>
                                              <i class="fa-regular fa-star"></i>
                                              <i class="fa-regular fa-star"></i>
                                              <i class="fa-regular fa-star"></i>
                                              <i class="fa-regular fa-star"></i>
                                          </div>
                                          @if($order->status == 'delivered')
                                          <button class="delivered"><i class="fa fa-circle-check"></i> Delivered</button>
                                         @elseif($order->status == 'cancelled')
                                         <button class="cancelled"><i class="fa-solid fa-circle-xmark"></i> Cancelled</button>
                                          @else
                                            <div class="delivery-status"><i class="fa-solid fa-truck-fast"></i> Expected delivery in 3–4 days</div>
                                          @endif
                                      </div>
                                  </div>
                              </div>
                              @endforeach
                              
                          </div>
                      </div>
  
                  </div>
              </div>
          </div>
      </div>
    </div>
  </section>
@endsection