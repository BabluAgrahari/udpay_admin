@extends('Website.Layout.app')
@section('content')
<section class="section-padding">
    <section class="section-padding pt-4">
        <div class="container">
          <div class="row">
              <div class="col-lg-8">
                  <div>
                      <div class="track-left">
                          <h3 class="tab-title">Track Order</h3>
                          <div class="tab-content-body">
                            @foreach($order->orderToProducts as $orderToProduct)
                              <div class="order-card">
                                  <div class="product-img">
                                      <a href="{{ url('detail/'.$orderToProduct->product->slug) }}"><img src="{{ isValidImageUrl($orderToProduct->product->product_image) ? $orderToProduct->product->product_image : asset('front_assets') . '/images/no_image.jpeg' }}" alt="Whey Protein" /></a>
                                  </div>
                                  <div class="order-details">
                                      <div class="product-top">
                                          <a href="{{ url('detail/'.$orderToProduct->product->slug) }}" class="product-title">{{ $orderToProduct->product->product_name }}</a>
                                      </div>
                                      <div class="price">
                                          ₹{{ $orderToProduct->price }} <br> <span class="old-price">₹{{ $orderToProduct->product->mrp }}</span> <span class="discount">{{ $orderToProduct->product->product_discount }}% OFF</span>
                                      </div> 
                                      <div class="order-actions">
                                          <div class="delivery-status"> Expected delivery in 3-4 days</div>
                                      </div>
                                  </div>
                              </div>
                              @endforeach
                              
                          </div>
                      </div>
                       <div class="order-summary-box">
                              <div class="summary-row">
                                  <span>Subtotal . {{ count($order->orderToProducts) }} items</span>
                                  <span>₹{{ $order->total_net_amount }}</span>
                              </div>
                              <div class="summary-row">
                                  <span>Shipping</span>
                                  <span>FREE</span>
                              </div>
                              <div class="summary-row total">
                                  <span>Total</span>
                                  <span>₹{{ $order->total_net_amount }}</span>
                              </div>
                          </div>
                  </div>
              </div>
              <div class="col-lg-4">
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
              </div>
          </div>
        </div>
      </section>
</section>
@endsection