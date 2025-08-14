@extends('Website.Layout.app')

@section('content')

<section class="section-padding pt-4">
    <div class="container">
      <div class="section-heading justify-content-between text-left">
          <div class="text-left">
              <h4 class="mb-0">My Wishlist</h4>
          </div>
      </div>
      <div class="row">
        @foreach($wishlist as $item)
          <div class="col-lg-3 col-md-6">
              <div class="product-card mx-0">
                  <div class="product-image">
                      <a href="{{ url('product/'.$item->product->slug_url) }}"><img src="{{ getImageWithFallback($item->product->product_image) }}" alt="img" class=""></a>
                       <p class="product-review"><i class="fa fa-star"></i> {{ $item->product->reviews->avg('rating') }} ({{ $item->product->reviews->count() }} Reviews)</p>
                  </div>
                  <div class="product-perra">
                      <div class="prduct-perra-top">
                          <a href="{{ url('product/'.$item->product->slug_url) }}"><h3>{{ $item->product->product_name }}</h3></a>
                      </div>
                      <h5 class="price-bg">
                        @canany(['isDistributor', 'isCustomer'])
                            ₹{{ number_format($item->product->product_sale_price, 2) }}
                        @else
                            ₹{{ number_format($item->product->guest_price, 2) }}
                        @endcanany
                        @canany(['isDistributor', 'isCustomer'])
                            <span class="price-bg"><small>SV: {{ $item->product->sv }}</small></span>
                        @endcanany
                      </h5>
                      <div class="wishlist-bottom">
                          <a href="javascript:void(0)" class="thm-btn bg-light remove-wishlist" data-id="{{ $item->id }}">Remove</a>
                          <a href="javascript:void(0)" class="thm-btn add-to-cart cart-btn" data-id="{{ $item->product_id }}">Add to Cart</a>
                      </div>
                  </div>
              </div>
          </div>
          @endforeach
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