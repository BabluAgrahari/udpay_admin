@extends('Website.Layout.app')
@section('content')
    <section class="section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center">
                        <h4>Order Failed!</h4>
                        <p class="text-black">Your order payment has been failed. </p>
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
                                <h6 class="mb-1">Payment Status</h6>
                                <p class="small mb-0">{{ $order->payment_status }}</p>
                            </div>
                            <div class="seccess-text">
                                <h6 class="mb-1">Order Date</h6>
                                <p class="small mb-0">{{ date('Y-m-d', strtotime($order->created_at)) }}</p>
                            </div>
                        </div>
                        <div class="seccess-item">
                            <div class="seccess-text">
                                <h6 class="mb-1">Total</h6>
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

                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ url('order-detail/' . $order->id) }}" class="thm-btn">View Order Details</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
