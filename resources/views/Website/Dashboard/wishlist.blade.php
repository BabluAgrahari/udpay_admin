@extends('Website.Layout.app')
@section('content')
<section class="section-padding py-5 pt-3">
    <div class="container">
      <div class="row">
          <div class="col-lg-12">
              <div class="dashboard">
                  <!-- Sidebar -->
                  <div class="sidebar">
                      <a href="{{ url('my-account') }}" class="tab-btn {{ request()->is('my-account') ? 'active' : '' }}" ><i class="fa-solid fa-user"></i> My Account</a>
                      <a href="{{ url('order-history') }}" class="tab-btn {{ request()->is('order-history') ? 'active' : '' }}" ><i class="fa-solid fa-box"></i> Order History</a>
                      <a href="{{ url('address-book') }}" class="tab-btn {{ request()->is('address-book') ? 'active' : '' }}" ><i class="fa-solid fa-book"></i> Address Book</a>
                      <a href="{{ url('wishlist') }}" class="tab-btn {{ request()->is('wishlist') ? 'active' : '' }}" ><i class="fa-solid fa-heart"></i> My Wishlist</a>
                      <a href="{{ url('logout') }}" class="tab-btn logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                  </div>
                  <!-- Content -->
                  <div class="tab-content">
                      <div class="tab-panel active" id="wishlist">
                          <h3 class="tab-title">My Wishlist</h3>
                          <div class="tab-content-body">
                              <div class="row">
                                @if($wishlist->count() > 0)
                                    @foreach($wishlist as $item)
                                  <div class="col-lg-4 col-md-6 col-sm-6">
                                      <div class="product-card mx-0">
                                          <div class="product-image">
                                              <a href="{{ url('product/'.$item->product->slug_url) }}"><img src="{{ getImageWithFallback($item->product->product_image) }}" alt="img" class=""></a>
                                              <p class="product-review"><i class="fa fa-star"></i> 3.25 (12 Reviews)</p>
                                          </div>
                                          <div class="product-perra">
                                              <div class="prduct-perra-top">
                                                  <a href="{{ url('product/'.$item->product->slug_url) }}"><h3>{{ $item->product->product_name }}</h3></a>
                                              </div>
                                              <h5 class="price-bg">₹{{ $item->product->product_sale_price }}</h5>
                                              <div class="wishlist-bottom">
                                                  <a href="javascript:void(0)" class="thm-btn bg-light remove-wishlist" data-id="{{ $item->id }}">Remove</a>
                                                  <a href="javascript:void(0)" class="thm-btn add-to-cart cart-btn" data-id="{{ $item->product_id }}">Add to Cart</a>
                                              </div>
                                          </div>
                                      </div>    
                                  </div>
                                  @endforeach
                                
                                  @else
                                  <div class="col-lg-12">
                                    <div class="product-card mx-0">
                                        <h3>No Wishlist Found</h3>
                                    </div>
                                  </div>
                                  @endif
                                  
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        $('.remove-wishlist').click(function(){
            var id = $(this).data('id');
           var url = "{{ url('wishlist/remove') }}/"+id;
           $.ajax({
            url: url,
            type: 'GET',
            success: function(response){
                showSnackbar(response.msg, 'success');
            
            if(response.status){
              location.reload();
            }
            },
        error: function(xhr){
                    showSnackbar(xhr.responseJSON.msg, 'error');
            }
        });
    });
    });
</script>

@endpush