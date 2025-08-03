@extends('Website.Layout.app')
@section('content')
<section class="section-padding py-5 pt-3">
    <div class="container">
      <div class="row">
          <div class="col-lg-12">
              <div class="dashboard">
                  <!-- Sidebar -->
                  <div class="sidebar">
                      <button class="tab-btn active" data-tab="account"><i class="fa-solid fa-user"></i> My Account</button>
                      <button class="tab-btn" data-tab="orders"><i class="fa-solid fa-box"></i> Order History</button>
                      <button class="tab-btn" data-tab="addresses"><i class="fa-solid fa-book"></i> Address Book</button>
                      <button class="tab-btn" data-tab="wishlist"><i class="fa-solid fa-heart"></i> My Wishlist</button>
                      <button class="tab-btn logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
                  </div>
                  <!-- Content -->
                  <div class="tab-content">
                      <div class="tab-panel active edit-form-open" id="account">
                          <h3 class="tab-title account-top">My Account <button class="edit-link editBtn"><i class="fa-solid fa-pen-to-square"></i> Edit</button></h3>
                          <div class="tab-content-body">
                              <div class="">
                                  <div class="d-flex gap-3 ">
                                      <div class="">
                                          <h3 class="color-one mb-0 user-sort">VS</h3>
                                      </div>
                                      <div class="user-detail">
                                          <h5 class="mb-2">Vishal Singh </h5>
                                          <p class="mb-2">vishalsingh887@gmail.com</p>
                                          <p class="mb-2">Male</p>
                                          <p class="mb-2">8009791198 </p>
                                      </div>
                                  </div>
                                  <div class="edit-form-box" style="display: none;">
                                      <form>
                                          <div class="row mb-3">
                                              <div class="col-sm-6 form-group">
                                                  <label>Name <span class="color-red">*</span></label>
                                                  <input type="text" class="form-control" placeholder="Enter name">
                                              </div>
                                              <div class="col-sm-6 form-group">
                                                  <label>Email <span class="color-red">*</span></label>
                                                  <input type="email" class="form-control" placeholder="Enter email">
                                              </div>
                                              <div class="col-sm-6 form-group">
                                                  <label>Gender <span class="color-red">*</span></label>
                                                  <select class="form-control">
                                                      <option>Male</option>
                                                      <option>Female</option>
                                                      <option>Other</option>
                                                  </select>
                                              </div>
                                              <div class="col-sm-6 form-group">
                                                  <label>Phone <span class="color-red">*</span></label>
                                                  <input type="text" class="form-control" placeholder="Enter phone">
                                              </div>
                                          </div>
                                          <button type="button" class="thm-btn">Save Address</button>
                                          <button type="button" class="thm-btn bg-light mx-3" id="cancelBtn">Cancel</button>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="tab-panel" id="orders">
                          <h3 class="tab-title">Order History</h3>
                          <div class="tab-content-body">
                              <div class="order-card">
                                  <div class="product-img">
                                      <a href="#"><img src="assets/images/product/1.jpg" alt="Whey Protein" /></a>
                                  </div>
                                  <div class="order-details">
                                      <div class="product-top">
                                          <a href="#" class="product-title">MuscleBlaze Biozyme Performance Whey Protein Powder, 4.4 lb Chocolate Hazelnut</a>
                                          <div class="text-end">
                                              <a href="#" class="reorder"><i class="fa-solid fa-right-left"></i> Reorder</a>
                                              <a href="order-track.html" class="track"><i class="fa-solid fa-location-dot"></i> Track Order</a>
                                          </div>
                                      </div>
                                      <div class="price">
                                          ₹5,149 <span class="old-price">₹5,499</span> <span class="discount">6% OFF</span>
                                      </div> 
                                      <p class="mb-2">24 Apr, 11:38 am</p>                              
                                      <div class="order-actions">
                                          <div class="rating open-rating-modal">
                                              <span class="text-black">Rate</span>
                                              <i class="fa-regular fa-star"></i>
                                              <i class="fa-regular fa-star"></i>
                                              <i class="fa-regular fa-star"></i>
                                              <i class="fa-regular fa-star"></i>
                                              <i class="fa-regular fa-star"></i>
                                          </div>
                                          <div class="delivery-status"><i class="fa-solid fa-truck-fast"></i> Expected delivery in 3–4 days</div>
                                      </div>
                                  </div>
                              </div>
                              <div class="order-card">
                                  <div class="product-img">
                                      <a href="#"><img src="assets/images/product/2.jpg" alt="Vitals" /></a>
                                  </div>
                                  <div class="order-details">
                                      <div class="product-top">
                                          <a href="#" class="product-title">MuscleBlaze Biozyme Performance Whey Protein Powder, 4.4 lb Chocolate Hazelnut</a>
                                          <a href="#" class="reorder"><i class="fa-solid fa-right-left"></i> Reorder</a>
                                      </div>
                                      <div class="price">
                                          ₹329 <span class="old-price">₹499</span> <span class="discount">34% OFF</span>
                                      </div>
                                      <p class="mb-2">24 Apr, 11:38 am</p>                              
                                      <div class="order-actions">
                                          <div class="rating open-rating-modal">
                                              <span class="text-black">Rate</span>
                                              <i class="fa-regular fa-star"></i>
                                              <i class="fa-regular fa-star"></i>
                                              <i class="fa-regular fa-star"></i>
                                              <i class="fa-regular fa-star"></i>
                                              <i class="fa-regular fa-star"></i>
                                          </div>
                                          <button class="delivered"><i class="fa fa-circle-check"></i> Delivered</button>
                                      </div>
                                  </div>
                              </div>
                              <!-- Cancelled Card -->
                              <div class="order-card">
                                  <div class="product-img">
                                      <a href="#"><img src="assets/images/product/3.jpg" alt="Vitals" /></a>
                                  </div>
                                  <div class="order-details">
                                      <div class="product-top">
                                          <a href="#" class="product-title">MuscleBlaze Biozyme Performance Whey Protein Powder, 4.4 lb Chocolate Hazelnut</a>
                                          <a href="#" class="reorder"><i class="fa-solid fa-right-left"></i> Reorder</a>
                                          
                                      </div>
                                      <div class="price">
                                          ₹329 <span class="old-price">₹499</span> <span class="discount">34% OFF</span>
                                      </div>
                                      <p class="mb-2">24 Apr, 11:38 am</p>                              
                                      <div class="order-actions">
                                          <div class="rating open-rating-modal">
                                              <span class="text-black">Rate</span>
                                              <i class="fa-regular fa-star"></i>
                                              <i class="fa-regular fa-star"></i>
                                              <i class="fa-regular fa-star"></i>
                                              <i class="fa-regular fa-star"></i>
                                              <i class="fa-regular fa-star"></i>
                                          </div>
                                          <button class="cancelled"><i class="fa-solid fa-circle-xmark"></i> Cancelled</button>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
  
                      <div class="tab-panel" id="addresses">
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
                                  <div class="address-card selected">
                                      <div class="address-header">
                                          <h6 class="mb-2">Vishal Singh</h6>
                                          <button class="edit-link editBtn"><i class="fa-solid fa-pen"></i> Edit</button>
                                      </div>
                                      <p>645A/968/5 Jankivihar Jankipuram behind St. Antony Inter College,...</p>
                                      <p>LUCKNOW, UTTAR PRADESH, 226031</p>
                                      <p>+91 8009791198</p>
                                      <button class="select-btn selected-btn"><i class="fa-solid fa-circle-check"></i> Selected Address</button>
                                  </div>
  
                                  <!-- Address Card 2 - Not Selected -->
                                  <div class="address-card">
                                      <div class="address-header">
                                          <h6 class="mb-2">Vishal Singh</h6>
                                          <button class="edit-link editBtn"><i class="fa-solid fa-pen"></i> Edit</button>
                                      </div>
                                      <p>645A/968/5 Jankivihar Jankipuram behind St. Antony Inter College,...</p>
                                      <p>LUCKNOW, UTTAR PRADESH, 226031</p>
                                      <p>+91 8009791198</p>
                                      <button class="select-btn">Select Address</button>
                                  </div>
                              </div>
                              <!-- Edit Box -->
                              <div class="edit-form-box">
                                  <form>
                                      <div class="row mb-3">
                                          <div class="col-sm-6 form-group">
                                              <label>Name</label>
                                              <input type="text" class="form-control" placeholder="Enter Name" />
                                          </div>
                                          
                                          <div class="col-sm-6 form-group">
                                              <label>Phone</label>
                                              <input type="text" class="form-control" placeholder="Enter Phone" />
                                          </div>
                                          <div class="col-sm-12 form-group">
                                              <label>Address</label>
                                              <textarea  type="text" class="form-control" placeholder="Enter Addresses"></textarea>
                                          </div>
                                      </div>
                                      <button type="button" class="thm-btn">Save</button>
                                      <button type="button" class="thm-btn bg-light mx-3" id="cancelBtn">Cancel</button>
                                  </form>
                              </div>
                          </div>
                      </div>
  
                      <div class="tab-panel" id="wishlist">
                          <h3 class="tab-title">wishlist</h3>
                          <div class="tab-content-body">
                              <div class="row">
                                  <div class="col-lg-3 col-md-6">
                                      <div class="product-card mx-0">
                                          <div class="product-image">
                                              <a href="product-details.html"><img src="assets/images/product/1.jpg" alt="img" class=""></a>
                                              <p class="product-review"><i class="fa fa-star"></i> 3.25 (12 Reviews)</p>
                                          </div>
                                          <div class="product-perra">
                                              <div class="prduct-perra-top">
                                                  <a href="product-details.html"><h3>Wellness Beat Beauty Vitamins Capsules | Immunity Booster and Antioxidant | Dietary Supplement for Healthy Skin | Supplement for Glowing Skin and Stronger Hair (60 Capsules)</h3></a>
                                              </div>
                                              <h5 class="price-bg">₹1,199</h5>
                                              <div class="wishlist-bottom">
                                                  <a href="cart.html" class="thm-btn bg-light">Remove</a>
                                                  <a href="cart.html" class="thm-btn">Add to Cart</a>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-lg-3 col-md-6">
                                      <div class="product-card mx-0">
                                          <div class="product-image">
                                              <a href="product-details.html"><img src="assets/images/product/2.jpg" alt="img" class=""></a>
                                              <p class="product-review"><i class="fa fa-star"></i> 3.25 (12 Reviews)</p>
                                          </div>
                                          <div class="product-perra">
                                              <div class="prduct-perra-top">
                                                  <a href="product-details.html"><h3>Wellness Beat Beauty Vitamins Capsules | Immunity Booster and Antioxidant | Dietary Supplement for Healthy Skin | Supplement for Glowing Skin and Stronger Hair (60 Capsules)</h3></a>
                                              </div>
                                              <h5 class="price-bg">₹1,199</h5>
                                              <div class="wishlist-bottom">
                                                  <a href="cart.html" class="thm-btn bg-light">Remove</a>
                                                  <a href="cart.html" class="thm-btn">Add to Cart</a>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-lg-3 col-md-6">
                                      <div class="product-card mx-0">
                                          <div class="product-image">
                                              <a href="product-details.html"><img src="assets/images/product/3.jpg" alt="img" class=""></a>
                                              <p class="product-review"><i class="fa fa-star"></i> 3.25 (12 Reviews)</p>
                                          </div>
                                          <div class="product-perra">
                                              <div class="prduct-perra-top">
                                                  <a href="product-details.html"><h3>Wellness Beat Beauty Vitamins Capsules | Immunity Booster and Antioxidant | Dietary Supplement for Healthy Skin | Supplement for Glowing Skin and Stronger Hair (60 Capsules)</h3></a>
                                              </div>
                                              <h5 class="price-bg">₹1,199</h5>
                                              <div class="wishlist-bottom">
                                                  <a href="cart.html" class="thm-btn bg-light">Remove</a>
                                                  <a href="cart.html" class="thm-btn">Add to Cart</a>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-lg-3 col-md-6">
                                      <div class="product-card mx-0">
                                          <div class="product-image">
                                              <a href="product-details.html"><img src="assets/images/product/4.jpg" alt="img" class=""></a>
                                              <p class="product-review"><i class="fa fa-star"></i> 3.25 (12 Reviews)</p>
                                          </div>
                                          <div class="product-perra">
                                              <div class="prduct-perra-top">
                                                  <a href="product-details.html"><h3>Wellness Beat Beauty Vitamins Capsules | Immunity Booster and Antioxidant | Dietary Supplement for Healthy Skin | Supplement for Glowing Skin and Stronger Hair (60 Capsules)</h3></a>
                                              </div>
                                              <h5 class="price-bg">₹1,199</h5>
                                              <div class="wishlist-bottom">
                                                  <a href="cart.html" class="thm-btn bg-light">Remove</a>
                                                  <a href="cart.html" class="thm-btn">Add to Cart</a>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
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