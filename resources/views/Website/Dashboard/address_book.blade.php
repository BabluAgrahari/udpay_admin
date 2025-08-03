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
                     
                      <div class="tab-panel active" id="addresses">
                          <div class="edit-form-open">
                              <div class="address-header-title ">
                                  <h3 class="tab-title">Addresses</h3>
                                  <button class="add-address editBtn">+ Add New Address</button>
                              </div>
                              <div class="tab-content-body address-location-body">
                                  <div class="">
                                      <div class="address-location-icon">
                                          <i class="fa-solid fa-location-dot"></i>
                                      </div>
                                      <p>No Addresses Added</p>
                                     <button class="text-white thm-btn editBtn">Add New Address</button>
  
                                  </div>
                              </div>
                              <div class="edit-form-box m-3">
                                  <form>
                                      <div class="row">
                                          <div class="col-sm-6 form-group">
                                              <label>Name <span class="color-red">*</span></label>
                                              <input type="text" class="form-control" placeholder="Enter Name" />
                                          </div>
                                          
                                          <div class="col-sm-6 form-group">
                                              <label>Mobile No. <span class="color-red">*</span></label>
                                              <input type="text" class="form-control" placeholder="Enter Phone" />
                                          </div>
                                          <div class="col-sm-12 form-group">
                                              <label>Address (Area & Street) <span class="color-red">*</span></label>
                                              <textarea  type="text" class="form-control" placeholder="Enter Addresses"></textarea>
                                          </div>
                                          <div class="col-sm-6 form-group">
                                              <label>Landmark</label>
                                              <input type="text" class="form-control" placeholder="Enter Landmark" />
                                          </div>
                                          <div class="col-sm-6 form-group">
                                              <label>Pincode <span class="color-red">*</span></label>
                                              <input type="text" class="form-control" placeholder="Enter Pincode" />
                                          </div>
                                           <div class="col-sm-6 form-group">
                                              <label>State <span class="color-red">*</span></label>
                                              <select class="form-control">
                                                  <option>Rajasthan</option>
                                                  <option>MP</option>
                                                  <option>Dehli</option>
                                              </select>
                                          </div>
                                          <div class="col-sm-6 form-group">
                                              <label>City <span class="color-red">*</span></label>
                                              <select class="form-control">
                                                  <option>Jaipur</option>
                                                  <option>Kota</option>
                                                  <option>Jodhpur</option>
                                              </select>
                                          </div>
                                      </div>
                                      <div class="d-flex align-items-end justify-content-between">
                                          <div>
                                              <p class="mb-2">Save As</p>
                                              <div class="weight-flower mt-0">
                                                  <div class="options">
                                                      <button type="button" class="option active">Home</button>
                                                      <button type="button" class="option">Office</button>
                                                      <button type="button" class="option">Others</button>
                                                  </div>
                                              </div>
                                          </div>
                                          <button type="button" class="thm-btn">Save address</button>
                                      </div>
                                  </form>
                              </div>
                          </div>
                          
                          <div class="saved-addresses tab-content-body edit-form-open">
                              <div class="address-list">
                                  <!-- Address Card 1 - Selected -->
                                  @foreach($addresses as $address)
                                  <div class="address-card selected">
                                      <div class="address-header">
                                          <h6 class="mb-2">{{ $address->user_add_name }}</h6>
                                          <button class="edit-link editBtn"><i class="fa-solid fa-pen"></i> Edit</button>
                                      </div>
                                      <p>{{ $address->user_add_1 }}, {{ $address->user_add_2 }}, {{ $address->land_mark }}</p>
                                      <p>{{ $address->user_city }}, {{ $address->user_state }}, {{ $address->user_zip_code }}, {{ $address->user_country }}</p>
                                      <p>{{ $address->user_add_mobile }}, {{ $address->alternate_mob }}</p>
                                      <p>{{ $address->address_for??'Home'}}</p>
                                      <button class="select-btn selected-btn"><i class="fa-solid fa-circle-check"></i> Selected Address</button>
                                  </div>
                                  @endforeach
                                 
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