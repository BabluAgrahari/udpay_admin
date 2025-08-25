@extends('Website.Layout.app')
@section('content')


    <section class="section-padding">
        <div class="container">
            <?php //echo"<pre>"; print_r($order->toArray()); die;
            ?>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center">
                        <h4>Thank you for your order!</h4>
                        <p class="text-black">Your order has been successfully placed and is being processed. You will
                            receive an email confirmation shortly with the details of your purchase.</p>
                    </div>
                    <div class="seccess-content">
                        <div class="seccess-item border-none">
                            <h6>Order Summary</h6>
                        </div>
                        <div class="seccess-item">
                            <div class="seccess-text">
                                <h6 class="mb-1">Order Number</h6>
                                <p class="small mb-0">#{{ $order->order_id }}</p>
                            </div>
                            <div class="seccess-text">
                                <h6 class="mb-1">Order Date</h6>
                                <p class="small mb-0">{{ date('Y-m-d', strtotime($order->created_at)) }}</p>
                            </div>
                        </div>
                        <div class="seccess-item">
                            <div class="seccess-text">
                                <h6 class="mb-1">Total Amount</h6>
                                @if ((!empty(Auth::user()) && Auth::user()->can('isDistributor')) || Auth::user()->can('isCustomer'))
                                    <p class="small mb-0">₹ {{ $order->total_net_amt ?? '' }}</p>
                                @else
                                    <p class="small mb-0">₹ {{ $order->total_net_amount ?? '' }}</p>
                                @endif
                            </div>

                            <div class="seccess-text">
                                <h6 class="mb-1">Shipping Address</h6>
                                <p class="small mb-0">{{ $order->shipping_address->user_add_1 ?? '' }},
                                    {{ $order->shipping_address->user_zip_code ?? '' }}</p>
                            </div>
                        </div>

                        <div class="seccess-item">
                            @if ((!empty(Auth::user()) && Auth::user()->can('isDistributor')) || Auth::user()->can('isCustomer'))
                                <div class="seccess-text">
                                    <h6 class="mb-1">Total SV</h6>
                                    <p class="small mb-0">{{ $order->sv ?? '' }}</p>
                                </div>
                                <div class="seccess-text">
                                </div>
                            @endif
                        </div>
                        <div class="seccess-item border-none">
                            <h6>Items Purchased</h6>
                        </div>
                        @if ($order->orderToProducts->isNotEmpty())
                            @foreach ($order->orderToProducts as $item)
                                <div class="purchase-bottom-item">
                                    <img src="{{ getImageWithFallback($item->product->product_image) }}"
                                        class="purchase-item-image">
                                    <div>
                                        <h6 class="mb-0">{{ $item->product->product_name ?? '' }}</h6>
                                        <p class="small mb-0">Quantity: {{ $item->quantity ?? '' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="purchase-bottom-item">
                                <p>No items found</p>
                            </div>
                        @endif
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ url('order-detail/' . $order->id) }}" class="thm-btn">View Order Details</a>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>


@endsection
