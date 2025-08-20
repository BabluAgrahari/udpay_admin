
<!-- banner-section start -->
@extends('Website.Layout.app')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            <h1 class="display-6">Your Cart is Empty</h1>
            <p class="lead">Looks like you haven't added anything to your cart yet.</p>
            <img src="empty-cart.png" alt="Empty Cart" class="empty-cart-image mt-4"></p>
            <a href="{{url('product')}}" class="thm-btn">Continue Shopping</a>
        </div>
    </div>
</div>
@endsection