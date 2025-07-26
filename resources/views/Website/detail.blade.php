<!-- banner-section start -->
@extends('Website.Layout.app')
@section('content')
<section class="section-padding pt-5" >
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="product-slider-wrapper">
                    <!-- Main Image Slider -->
                    <div class="swiper main-slider">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="product-details-image">
                                    <img src="{{ asset('front_assets') }}/images/product-details/bg1.png" class="main-image" />
                                    <!-- <span class="prod-wish"><i class="fa-regular fa-heart"></i></span> -->
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="product-details-image">
                                    <img src="{{ asset('front_assets') }}/images/product-details/bg1.png" class="main-image" />
                                     <!-- <span class="prod-wish"><i class="fa-regular fa-heart"></i></span> -->
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="product-details-image">
                                    <img src="{{ asset('front_assets') }}/images/product-details/bg1.png" class="main-image" />
                                     <!-- <span class="prod-wish"><i class="fa-regular fa-heart"></i></span> -->
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="product-details-image">
                                    <img src="{{ asset('front_assets') }}/images/product-details/bg1.png" class="main-image" />
                                     <!-- <span class="prod-wish"><i class="fa-regular fa-heart"></i></span> -->
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="product-details-image">
                                    <img src="{{ asset('front_assets') }}/images/product-details/bg1.png" class="main-image" />
                                     <!-- <span class="prod-wish"><i class="fa-regular fa-heart"></i></span> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Thumbnail Slider -->
                    <div class="swiper thumb-slider">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide"><img src="{{ asset('front_assets') }}/images/product-details/sm1.png" class="thumb" /></div>
                            <div class="swiper-slide"><img src="{{ asset('front_assets') }}/images/product-details/sm2.png" class="thumb" /></div>
                            <div class="swiper-slide"><img src="{{ asset('front_assets') }}/images/product-details/sm3.png" class="thumb" /></div>
                            <div class="swiper-slide"><img src="{{ asset('front_assets') }}/images/product-details/sm4.png" class="thumb" /></div>
                            <div class="swiper-slide"><img src="{{ asset('front_assets') }}/images/product-details/sm3.png" class="thumb" /></div>
                        </div>
                    </div>
                </div>
                <div class="text-center py-3">
                    <img src="{{ asset('front_assets') }}/images/product-details/ayurveda.png" class="" alt="img" />
                    <h5 class="color-one">Formulated by Ayurvedic Experts</h5>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="product-details-right">
                    <div class="product-details">
                        <span class="color-one border-bottom">Detox</span>
                        <h1 class="title">Nutraoshadhi d-tox green tea with vitamin-c and lemon flavor 60-tablet</h1>                        
                        <div class="rating ">
                            <div>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div> 4<span>(39 Reviews)</span>
                        </div>
                        <div class="box-color">
                            <p class="color-one mb-0">Reduced Blood Sugar by 30% in 3 Months* as per an ICMR-compliant clinical study on subjects with T2DM. Can consume with Allopathic Medicines also.</p>
                        </div>
                        <div class="price-box">
                            <span class="current-price">Price: ₹5,149</span>
                            <span class="old-price">Price: ₹199</span>
                        </div>
                        <div class="price-box mt-0">
                            <span class="discount color-two">6% OFF</span>
                            <span class="tax-info color-two">Inclusive of all taxes</span>
                        </div>
                        <div class="qty-cart">
                            <div class="quantity">
                                <button class="qty-btn">-</button>
                                <input type="text" value="1">
                                <button class="qty-btn border-raduis">+</button>
                            </div>
                            <div class="options">
                                <button class="option active">100 G</button>
                                <button class="option">200 G</button>
                                <button class="option">50 G</button>
                            </div>
                        </div>
                        <div class="details-add-btn">
                            <a href="cart.html" class="thm-btn add-to-cart">Add to cart</a>
                            <a href="#" class="thm-btn buy-now">Quick Buy</a>
                            <a href="#" class="thm-btn bg-light w-100">Add to wishlist</a>
                        </div>
                        <div class="box-color">
                            <p class="color-one mb-2">Available Offers (2 offers)</p>
                            <p class="box-list">Flat Rs 200 cashback for New user & Flat Rs 75 cashback</p>
                            <p class="box-list">Flat Rs 200 cashback on payment with Paytm</p>
                        </div>
                        <div class="box-color">
                            <p class="color-one mb-2">Delivery & Services</p>
                            <div class="delivery-type">
                                <input type="text" placeholder="Enter Pin code" class="form-control">
                                <button class="check-btn">Check</button>
                            </div>
                        </div>
                        <div class="box-color">
                            <p class="mb-0 text-black">Product Details</p>
                            <ul class="delivery-service-list">
                                <li>Natural Detox Formula: Infused with Green Tea & Grape Seed Extract, these tablets help cleanse your system and support healthy digestion naturally.</li>
                                <li>Rich in Antioxidants: Packed with the goodness of Tulsi, Ginger, Lemon, Black Pepper, and Cinnamon to boost immunity and fight oxidative stress.</li>
                                <li>Convenient Wellness: Comes in a 60-tablet bottle—an easy, no-brew way to enjoy the benefits of detox tea daily.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- banner-section end -->
<section class="section-padding pt-0">
  <div class="container">
    <div class="section-heading">
        <div class="w-100 text-left">
             <h2 class="mb-2">frequently bought together</h2>
            <p>Explore the best combo offers that people has bought frequently</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="product-card mx-0">
                <div class="product-image">
                    <a href="product-details.html"><img src="{{ asset('front_assets') }}/images/product/16.jpg" alt="img" class=""></a>
                     <p class="product-review"><i class="fa fa-star"></i> 3.25 (12 Reviews)</p>
                    <!-- <span class="discount-prod">10% OFF</span>
                    <span class="prod-wish"><i class="fa-regular fa-heart"></i></span> -->
                </div>
                <div class="product-perra">
                    <div class="prduct-perra-top">
                        <a href="product-details.html"><h3>Wellness Beat Beauty Vitamins Capsules | Immunity Booster and Antioxidant | Dietary Supplement for Healthy Skin | Supplement for Glowing Skin and Stronger Hair (60 Capsules)</h3></a>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="price-mian">
                            <span class="price-throuth">₹1,299</span><span class="price-bg">₹1,199</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="product-card mx-0">
                <div class="product-image">
                    <a href="product-details.html"><img src="{{ asset('front_assets') }}/images/product/17.jpg" alt="img" class=""></a>
                     <p class="product-review"><i class="fa fa-star"></i> 3.25 (12 Reviews)</p>
                    <!-- <span class="discount-prod">10% OFF</span>
                    <span class="prod-wish"><i class="fa-regular fa-heart"></i></span> -->
                </div>
                <div class="product-perra">
                    <div class="prduct-perra-top">
                        <a href="product-details.html"><h3>Wax Powder 10 Minutes Herbal Hair Removal Wax Easy to use at home, No chemicals - No Irritation, No Skin rashes for Women and Girls (100g)</h3></a>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="price-mian">
                            <span class="price-throuth">₹1,299</span><span class="price-bg">₹1,199</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="product-card mx-0">
                <div class="product-image">
                    <a href="product-details.html"><img src="{{ asset('front_assets') }}/images/product/12.jpg" alt="img" class=""></a>
                     <p class="product-review"><i class="fa fa-star"></i> 3.25 (12 Reviews)</p>
                    <!-- <span class="discount-prod">10% OFF</span>
                    <span class="prod-wish"><i class="fa-regular fa-heart"></i></span> -->
                </div>
                <div class="product-perra">
                    <div class="prduct-perra-top">
                        <a href="product-details.html"><h3>Turmeric Facial Wax Powder, 5 min Painless Natural Face Hair Removal Waxing Powder, Easy to use at home, No chemicals - No Irritation, No Skin rashes (100g)</h3></a>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="price-mian">
                            <span class="price-throuth">₹1,299</span><span class="price-bg">₹1,199</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 d-flex align-items-center justify-content-center text-center">
            <div class="product-card-detail-price">
                <p class="mb-2">Total price: <span class="text-black">₹2,039</span> </p>
                <a href="cart.html" class="thm-btn w-100">Add all 3 to Cart</a>
            </div>
        </div>
    </div>
  </div>
</section>
<!-- frequently -->
 <div class="marquee">
  <div class="marquee-content">
    <span>Organic Ingredients</span>
    <div class="marquee-image">
        <img src="{{ asset('front_assets') }}/images/icons/ayurveda.png" alt="img">
    </div>
    <span>All Natural</span>
    <div class="marquee-image">
        <img src="{{ asset('front_assets') }}/images/icons/ayurveda.png" alt="img">
    </div>
    <span>Step towards Healthy Life</span>
    <div class="marquee-image">
        <img src="{{ asset('front_assets') }}/images/icons/ayurveda.png" alt="img">
    </div>
    <span>100% Ayurveda</span>
    <div class="marquee-image">
        <img src="{{ asset('front_assets') }}/images/icons/ayurveda.png" alt="img">
    </div>
  </div>
</div>
<!-- marquee -->
<section class="section-padding py-3" style="background: #FFF8E6;">
  <div class="container">
    <div class="">
        <div class="w-100 text-center">
             <h2 class="mb-2">Our Happy Customers</h2>
            <p>Reels of our customers that are happy with our products</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="client-testi-card">
                <div class="client-image">
                    <img src="{{ asset('front_assets') }}/images/client.png" alt="img">
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="client-testi-card">
                <div class="client-image">
                    <img src="{{ asset('front_assets') }}/images/client.png" alt="img">
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="client-testi-card">
                <div class="client-image">
                    <img src="{{ asset('front_assets') }}/images/client.png" alt="img">
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="client-testi-card">
                <div class="client-image">
                    <img src="{{ asset('front_assets') }}/images/client.png" alt="img">
                </div>
            </div>
        </div>
    </div>
  </div>
</section>
<!-- Our Happy Customers -->
<section class="section-padding py-4 mt-5" style="background: #FFF4EE;">
  <div class="container">
    <div class="">
        <div class="w-100 text-center">
             <h2 class="mb-2">Benefits of D-tox Green Tea </h2>
            <p>Benefits that helps you stay healthy and live longer</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="benefits-card">
                <div class="benefits-image">
                    <img src="{{ asset('front_assets') }}/images/testi/1.png" alt="img">
                </div>
                <div class="benefits-content">
                    <h6>Blood Sugar Management</h6>
                    <p>Naturally helps manage blood sugar and HbA1c levels on consuming as advised for at least 3 Months</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="benefits-card">
                <div class="benefits-image">
                    <img src="{{ asset('front_assets') }}/images/testi/2.png" alt="img">
                </div>
                <div class="benefits-content">
                    <h6>Has Herbs to Improve Insulin Release</h6>
                    <p>Vijaysaar helps regenerate insulin-producing cells & Gudmar, Giloy, etc. help improve insulin release in the body</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="benefits-card">
                <div class="benefits-image">
                    <img src="{{ asset('front_assets') }}/images/testi/3.png" alt="img">
                </div>
                <div class="benefits-content">
                    <h6>Prevent Other Related Complications</h6>
                    <p>Naturally controlling Blood Sugar can prevent other health complications like BP and kidney issues</p>
                </div>
            </div>
        </div>
    </div>
  </div>
</section>
<!-- Our Happy Customers -->
<section class="section-padding py-4">
  <div class="container">
    <div class="">
        <div class="w-100 text-center">
             <h2 class="mb-2">Key Ingredients</h2>
            <p>Natural ingredients that will take care of your health</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="key-card">
                <div class="key-image">
                    <img src="{{ asset('front_assets') }}/images/key-ingredients/1.jpg" alt="img">
                </div>
                <div class="key-content">
                    <h6>Bitter Gourd (karela)</h6>
                    <p>Bitter Gourd is beneficial for regulating blood sugar levels. It contains Polypeptide-P which acts like insulin</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="key-card">
                <div class="key-image">
                    <img src="{{ asset('front_assets') }}/images/key-ingredients/2.jpg" alt="img">
                </div>
                <div class="key-content">
                    <h6>Amla</h6>
                    <p>Regulates carbohydrate metabolism and relieve digestive ailments with high fiber content</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="key-card">
                <div class="key-image">
                    <img src="{{ asset('front_assets') }}/images/key-ingredients/3.jpg" alt="img">
                </div>
                <div class="key-content">
                    <h6>Jamun</h6>
                    <p>Lowers the conversion of starch in food to sugar in the bloodstream.</p>
                </div>
            </div>
        </div>
    </div>
  </div>
</section>
<!-- Our Happy Customers -->
<section class="section-padding py-4" style="background: #FFF8E6;">
  <div class="container">
    <div class="section-heading">
        <div class="w-100 text-left">
             <h2>People also bought</h2>
            <p class="mb-0">Explore our best rated products</p>
        </div>
       <a href="#" class="veiw-btn">View More >></a>
    </div>
    <div class="row product-slider">
        <div class="col-lg-3 col-md-6">
            <div class="product-card">
                <div class="product-image">
                    <a href="product-details.html"><img src="{{ asset('front_assets') }}/images/product/9.jpg" alt="img" class=""></a>
                     <p class="product-review"><i class="fa fa-star"></i> 3.25 (12 Reviews)</p>
                    <!-- <span class="discount-prod">10% OFF</span>
                    <span class="prod-wish"><i class="fa-regular fa-heart"></i></span> -->
                </div>
                <div class="product-perra">
                    <div class="prduct-perra-top">
                        <a href="product-details.html"><h3>MuscleBlaze High Protein Oats, 2 kg, Dark Chocolate</h3></a>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="price-mian">
                            <span class="price-throuth">₹1,299</span><span class="price-bg">₹1,199</span>
                        </div>
                        <a href="cart.html" class="thm-btn">+</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="product-card">
                <div class="product-image">
                    <a href="product-details.html"><img src="{{ asset('front_assets') }}/images/product/10.jpg" alt="img" class=""></a>
                     <p class="product-review"><i class="fa fa-star"></i> 3.25 (12 Reviews)</p>
                    <!-- <span class="discount-prod">10% OFF</span>
                    <span class="prod-wish"><i class="fa-regular fa-heart"></i></span> -->
                </div>
                <div class="product-perra">
                    <div class="prduct-perra-top">
                        <a href="product-details.html"><h3>MuscleBlaze High Protein Oats, 2 kg, Dark Chocolate</h3></a>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="price-mian">
                            <span class="price-throuth">₹1,299</span><span class="price-bg">₹1,199</span>
                        </div>
                        <a href="cart.html" class="thm-btn">+</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="product-card">
                <div class="product-image">
                    <a href="product-details.html"><img src="{{ asset('front_assets') }}/images/product/11.jpg" alt="img" class=""></a>
                     <p class="product-review"><i class="fa fa-star"></i> 3.25 (12 Reviews)</p>
                    <!-- <span class="discount-prod">10% OFF</span>
                    <span class="prod-wish"><i class="fa-regular fa-heart"></i></span> -->
                </div>
                <div class="product-perra">
                    <div class="prduct-perra-top">
                        <a href="product-details.html"><h3>MuscleBlaze High Protein Oats, 2 kg, Dark Chocolate</h3></a>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="price-mian">
                            <span class="price-throuth">₹1,299</span><span class="price-bg">₹1,199</span>
                        </div>
                        <a href="cart.html" class="thm-btn">+</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="product-card">
                <div class="product-image">
                    <a href="product-details.html"><img src="{{ asset('front_assets') }}/images/product/12.jpg" alt="img" class=""></a>
                     <p class="product-review"><i class="fa fa-star"></i> 3.25 (12 Reviews)</p>
                    <!-- <span class="discount-prod">10% OFF</span>
                    <span class="prod-wish"><i class="fa-regular fa-heart"></i></span> -->
                </div>
                <div class="product-perra">
                    <div class="prduct-perra-top">
                        <a href="product-details.html"><h3>MuscleBlaze High Protein Oats, 2 kg, Dark Chocolate</h3></a>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="price-mian">
                            <span class="price-throuth">₹1,299</span><span class="price-bg">₹1,199</span>
                        </div>
                        <a href="cart.html" class="thm-btn">+</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="product-card">
                <div class="product-image">
                    <a href="product-details.html"><img src="{{ asset('front_assets') }}/images/product/10.jpg" alt="img" class=""></a>
                     <p class="product-review"><i class="fa fa-star"></i> 3.25 (12 Reviews)</p>
                    <!-- <span class="discount-prod">10% OFF</span>
                    <span class="prod-wish"><i class="fa-regular fa-heart"></i></span> -->
                </div>
                <div class="product-perra">
                    <div class="prduct-perra-top">
                        <a href="product-details.html"><h3>MuscleBlaze High Protein Oats, 2 kg, Dark Chocolate</h3></a>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="price-mian">
                            <span class="price-throuth">₹1,299</span><span class="price-bg">₹1,199</span>
                        </div>
                        <a href="cart.html" class="thm-btn">+</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</section>
<!-- People also bought -->
<section class="section-padding py-4">
  <div class="container">
    <div class="">
        <div class="w-100 text-center">
             <h2 class="mb-2">Customers Love</h2>
            <p>Just have a look at how happy our customers are</p>
        </div>
    </div>
    <div class="client-slider">
        <div class="testimonial-card">
           <div class="testi-header">
             <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Leo" class="testimonial-avatar">
             <div class="testi-content">
                <h4>Leo</h4>
                <div class="position-main">
                    <p class="position">Lead Designer</p>
                    <div class="rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                </div>
             </div>
           </div>
            <h5>It was a very good experience</h5>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cursus nibh mauris, nec turpis orci lectus maecenas. Suspendisse sed magna eget nibh in turpis. Consequat duis diam lacus arcu. Faucibus venenatis felis id augue sit cursus pellentesque enim arcu. Elementum felis magna pretium in tincidunt. Suspendisse sed magna eget nibh in turpis. Consequat duis diam lacus arcu.</p>
        </div>

        <div class="testimonial-card">
           <div class="testi-header">
             <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Leo" class="testimonial-avatar">
             <div class="testi-content">
                <h4>Leo</h4>
                <div class="position-main">
                    <p class="position">Lead Designer</p>
                    <div class="rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                </div>
             </div>
           </div>
            <h5>It was a very good experience</h5>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cursus nibh mauris, nec turpis orci lectus maecenas. Suspendisse sed magna eget nibh in turpis. Consequat duis diam lacus arcu. Faucibus venenatis felis id augue sit cursus pellentesque enim arcu. Elementum felis magna pretium in tincidunt. Suspendisse sed magna eget nibh in turpis. Consequat duis diam lacus arcu.</p>
        </div>
        <div class="testimonial-card">
           <div class="testi-header">
             <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Leo" class="testimonial-avatar">
             <div class="testi-content">
                <h4>Leo</h4>
                <div class="position-main">
                    <p class="position">Lead Designer</p>
                    <div class="rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                </div>
             </div>
           </div>
            <h5>It was a very good experience</h5>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cursus nibh mauris, nec turpis orci lectus maecenas. Suspendisse sed magna eget nibh in turpis. Consequat duis diam lacus arcu. Faucibus venenatis felis id augue sit cursus pellentesque enim arcu. Elementum felis magna pretium in tincidunt. Suspendisse sed magna eget nibh in turpis. Consequat duis diam lacus arcu.</p>
        </div>
        <div class="testimonial-card">
           <div class="testi-header">
             <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Leo" class="testimonial-avatar">
             <div class="testi-content">
                <h4>Leo</h4>
                <div class="position-main">
                    <p class="position">Lead Designer</p>
                    <div class="rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                </div>
             </div>
           </div>
            <h5>It was a very good experience</h5>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cursus nibh mauris, nec turpis orci lectus maecenas. Suspendisse sed magna eget nibh in turpis. Consequat duis diam lacus arcu. Faucibus venenatis felis id augue sit cursus pellentesque enim arcu. Elementum felis magna pretium in tincidunt. Suspendisse sed magna eget nibh in turpis. Consequat duis diam lacus arcu.</p>
        </div>
    </div>
  </div>
</section>
<!-- testimonial end -->
<div class="py-5" style="background: #C9FFE9;">
<div class="container">
    <div>
        <h1 class="mb-0 color-one">Suitable for people with T2DM looking for a natural solution to manage blood sugar levels </h1>
    </div>
</div>
</div>
 <!--  -->
<section class="section-padding">
    <div class="container">
         <div class="w-100 text-center">
             <h2 class="mb-2">How to use?</h2>
            <p>You can achieve your health by simply following the steps</p>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div>
                    <img src="{{ asset('front_assets') }}/images/product-details/bg1.png" alt="img" class="image-fit">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="how-use-list">
                    <div class="how-use-items">
                        <h6>Step 1 : Mix</h6>
                        <p>Shake the bottle & add 30 ml of Dia Free Juice to a glass of water</p>
                    </div>
                    <div class="how-use-items">
                        <h6>Step 2 : Consume</h6>
                        <p>Drink twice daily on an empty stomach in the morning and half an hour before dinner</p>
                    </div>
                    <div class="how-use-items">
                        <h6>Step 3 : Stay Consistent</h6>
                        <p>Consume daily for at least 3 months (Dosage may be varied as prescribed by a healthcare professional/physician.)</p>
                    </div>
                    <div class="how-use-items">
                        <h6>Step 4 : Can consume with Allopathic Medicines</h6>
                        <p>Kapiva Dia Free Juice can be taken with other allopathic medicines as well</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- how to use -->
<section class="section-padding pb-0" style="background: #FFF4EE;">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="choose-left">
                    <h1>Why Choose UniPay?</h1>
                    <p>Save Time and Earn Money!</p>
                    <div>
                        <img src="{{ asset('front_assets') }}/images/bg2.png" alt="img">
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="choose-right">
                    <div class="feature-box p-3 d-flex align-items-start mb-3">
                        <div class="icon me-3">
                            <img src="{{ asset('front_assets') }}/images/icons/1.png" alt="img">
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold color-one">Low Cost</h6>
                            <p class="mb-0">Always get cheapest price with the best in the industry</p>
                        </div>
                        </div>

                        <div class="feature-box p-3 d-flex align-items-start mb-3">
                        <div class="icon me-3">
                            <img src="{{ asset('front_assets') }}/images/icons/2.png" alt="img">
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold color-one">Fast</h6>
                            <p class="mb-0">Get your recharge to family and friends in minutes</p>
                        </div>
                        </div>

                        <div class="feature-box p-3 d-flex align-items-start mb-3">
                        <div class="icon me-3">
                            <img src="{{ asset('front_assets') }}/images/icons/5.png" alt="img">
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold color-one">Trust Pay</h6>
                            <p class="mb-0">100% Payment Protection. Easy Return Policy</p>
                        </div>
                        </div>

                        <div class="feature-box p-3 d-flex align-items-start mb-3">
                        <div class="icon me-3">
                            <img src="{{ asset('front_assets') }}/images/icons/6.png" alt="img">
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold color-one">100% Secure Payments</h6>
                            <p class="mb-0">Moving your card details to a much more secured place</p>
                        </div>
                        </div>

                        <div class="feature-box p-3 d-flex align-items-start mb-3">
                        <div class="icon me-3">
                            <img src="{{ asset('front_assets') }}/images/icons/7.png" alt="img">
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold color-one">24×7 Support</h6>
                            <p class="mb-0">We’re here to help. Have a query and need help? <a href="#">Click here</a></p>
                        </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>
 <!--why choose uniPay?  -->

<section class="section-padding">
  <div class="container">
    <div class="text-center">
        <h2 class="mb-0">Refer & Earn</h2><p>Refer your friends and earn up to 100 Unicash</p>
    </div>
    <div class="row">
      <div class="col-lg-4 col-md-6">
        <div class="step-card p-4 bg-light-green position-relative">
          <div class="icon-circle mb-3">
            <img src="{{ asset('front_assets') }}/images/icons/8.png" alt="img">
          </div>
          <div class="step-number bg-success text-white">1</div>
          <h5 class="fw-bold mb-1">You Refer Friends</h5>
          <p class="mb-0">Share your referral link with friends. Both get 100 Unicash</p>
        </div>
      </div>
      <!-- Card 2 -->
      <div class="col-lg-4 col-md-6">
        <div class="step-card p-4 bg-light-blue position-relative">
          <div class="icon-circle mb-3">
            <img src="{{ asset('front_assets') }}/images/icons/9.png" alt="img">
          </div>
          <div class="step-number bg-primary text-white">2</div>
          <h5 class="fw-bold mb-1">You Refer Friends</h5>
          <p class="mb-0">Your friends register with using your referral link</p>
        </div>
      </div>
      <!-- Card 3 -->
      <div class="col-lg-4 col-md-6">
        <div class="step-card p-4 bg-light-yellow position-relative">
          <div class="icon-circle mb-3">
            <img src="{{ asset('front_assets') }}/images/icons/10.png" alt="img">
          </div>
          <div class="step-number bg-warning text-white">3</div>
          <h5 class="fw-bold mb-1">You can earn</h5>
          <p class="mb-0">You get Unicash. You can use these Unicash to take recharge</p>
        </div>
      </div>
    </div>
    <div class="text-center mt-4">
      <a href="#" class="thm-btn refer-btn">Get your earnings started</a>
    </div>
  </div>
</section>
 <!-- download section -->
 <section class="section-padding py-4" style="background: #C9FFE9;">
    <div class="container">
        <div class="download-bg">
            <div class="download-image">
                <img src="{{ asset('front_assets') }}/images/download-bg.png" alt="img">
            </div>
            <div class="download-content">
                <h2>Download our app And get 100 uNI points free!</h2>
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ asset('front_assets') }}/images/download-play.png" alt="img">
                    <img src="{{ asset('front_assets') }}/images/app-store.png" alt="img">
                </div>
            </div>
        </div>
    </div>
 </section>
 @endsection