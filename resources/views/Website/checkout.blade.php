
@extends('Website.Layout.app')
@section('content')
<section class="section-padding">
  <div class="container">
    <div class="row">
        <div class="col-lg-8">
            <!-- Left: Cart Items -->
            <div class="cart-items p-3">
                <h6 class="mb-2">Your order updates & invoice will be sent to</h6>
                <div class="d-flex gap-2 align-items-center">
                    <p class="mb-0 text-black"><i class="fa-regular fa-envelope"></i> {{ Auth::user()->email ?? '' }}</p>
                </div>
            </div>
            <div class="cart-items">
                <div class="edit-form-open">
                    <div class="address-header-title">
                        <h5 class="mb-0">Payment</h5>
                        <button class="add-address editBtn" onclick="document.getElementById('addAddressForm').style.display='block'">+ Add New Address</button>
                    </div>
                    <!-- Add Address Form -->
                    <div class="edit-form-box mb-3" id="addAddressForm" style="display:none;">
                        <h4 class="mb-4">Add Address</h4>
                        <form method="POST" action="{{ url('checkout') }}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label>Name <span class="color-red">*</span></label>
                                    <input type="text" name="user_add_name" class="form-control" placeholder="Enter Name" value="" />
                                   
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Mobile No. <span class="color-red">*</span></label>
                                    <input type="text" name="user_add_mobile" class="form-control" placeholder="Enter Phone" value="" />
                                    @error('user_add_mobile')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-sm-12 form-group">
                                    <label>Address (Area & Street) <span class="color-red">*</span></label>
                                    <textarea name="user_add_1" class="form-control" placeholder="Enter Addresses"></textarea>
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Landmark</label>
                                    <input type="text" name="land_mark" class="form-control" placeholder="Enter Landmark" value="" />
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Pincode <span class="color-red">*</span></label>
                                    <input type="text" name="user_zip_code" class="form-control" placeholder="Enter Pincode" value="" />
                                   
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>State <span class="color-red">*</span></label>
                                    <input type="text" name="user_state" class="form-control" placeholder="Enter State" value="" />
                                   
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>City <span class="color-red">*</span></label>
                                    <input type="text" name="user_city" class="form-control" placeholder="Enter City" value="" />
                                   
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between">
                                <div>
                                    <p class="mb-2">Save As</p>
                                    <div class="weight-flower mt-0">
                                        <div class="options">
                                            <label><input type="radio" name="address_type" value="Home" checked> Home</label>
                                            <label><input type="radio" name="address_type" value="Office"> Office</label>
                                            <label><input type="radio" name="address_type" value="Others"> Others</label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="thm-btn">Save this address</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Saved Addresses -->
                <div class="saved-addresses edit-form-open p-0">
                    <div class="address-list">
                        @if($addresses->isNotEmpty())
                        @foreach($addresses as $address)
                        <div class="address-card checkout-address {{ old('address_id') == $address->id ? 'selected' : '' }}">
                            <div class="address-header">
                                <h6 class="mb-2">{{ $address->user_add_name }}</h6>
                            </div>
                            <p>{{ $address->user_add_1 }}</p>
                            <p>{{ $address->user_city }}, {{ $address->user_state }}, {{ $address->user_zip_code }}</p>
                            <p>{{ $address->user_add_mobile }}</p>
                            <div class="d-flex gap-2 justify-content-between align-items-center">
                                <form method="POST" action="{{ url('checkout') }}">
                                    @csrf
                                    <input type="hidden" name="address_id" value="{{ $address->id }}">
                                    <button type="submit" class="thm-btn btn-small">Deliver Here</button>
                                </form>
                                <label class="select-add"><input type="radio" name="address_id" value="{{ $address->id }}" {{ old('address_id') == $address->id ? 'checked' : '' }}> {{ $address->address_type }}</label>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <p>No saved addresses. Please add one.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="cart-items p-3">
                <h6 class="mb-2">Payment Method</h6>
                <form method="POST" action="{{ url('checkout') }}">
                    @csrf
                    <input type="hidden" name="address_id" value="{{ old('address_id') }}">
                    <select name="payment_method" class="form-control mb-2">
                        <option value="cod">Cash on Delivery</option>
                        <option value="online">Online Payment</option>
                    </select>
                    @error('payment_method')<span class="text-danger">{{ $message }}</span>@enderror
                    <button type="submit" class="thm-btn">Proceed to Pay</button>
                </form>
            </div>
        </div>
        <div class="col-lg-4">
            <!-- Right: Summary -->
            <div class="cart-summary">
                <div class="summary-box">
                    <span><strong class="text-black">Order Summary</strong> ({{ $total_items }} Items)</span>
                    <p>Total MRP <span>₹{{ $total_mrp }}</span></p>
                    <p>Total Discounts <span class="discount">−₹{{ $total_saving }}</span></p>
                    <p>Coupon Discount <span class="discount">-₹0</span></p>
                    <p>Convenience Fee <span>₹0</span></p>
                    <hr />
                    <p class="total text-black">Payable Amount <span>₹{{ $subtotal }}</span></p>
                </div>
            </div>
        </div>
    </div>
  </div>
</section>
@endsection