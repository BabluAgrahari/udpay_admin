
<!-- banner-section start -->
@extends('Website.Layout.app')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            <h1 class="display-4">Your Cart is Empty</h1>
            <p class="lead">Looks like you haven't added anything to your cart yet.</p>
            <p><a href="{{url('product')}}" class="btn btn-primary">Continue Shopping</a>
            <img src="empty-cart.png" alt="Empty Cart" class="img-fluid mt-4"></p>
            
        </div>
    </div>
</div>
@endsection