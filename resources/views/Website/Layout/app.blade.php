<html lang="zxx">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Nutraoshadhi Web Html </title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="icon" type="image/png" sizes="32x32" href="favicon.ico"> -->
  <!-- css -->
  <link rel="stylesheet" href="{{asset('front_assets')}}/css/plugins/slick.css">
  <link rel="stylesheet" href="{{asset('front_assets')}}/fonts/all.css">
  <link rel="stylesheet" href="{{asset('front_assets')}}/fonts/all.min.css">
  <link rel="stylesheet" href="{{asset('front_assets')}}/fonts/fontawesome.css">
  <link rel="stylesheet" href="{{asset('front_assets')}}/fonts/fontawesome.min.css">
  <link rel="stylesheet" href="{{asset('front_assets')}}/css/plugins/bootstrap.css">
  <link rel="stylesheet" href="{{asset('front_assets')}}/css/plugins/bootstrap.min.css">
  <link rel="stylesheet" href="{{asset('front_assets')}}/css/responsive.css">
  <link rel="stylesheet" href="{{asset('front_assets')}}/css/style.css">
<!--  -->
</head>
<body>
<!-- preloader -->
<main class="loader-wrap">
    <div class="">
       	<div class="loader">
			<span>N</span>
			<span>U</span>
			<span>T</span>
			<span>R</span>
			<span>A</span>
			<span>O</span>
			<span>S</span>
			<span>H</span>
			<span>A</span>
			<span>D</span>
			<span>H</span>
			<span>I</span>
		</div>
        <!--<h1 class=" mt-3">Nutraoshadhi</h1>-->
     </div>
</main>
<!-- preloader end -->
<header class="header can-sticky sticky">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="navigaation-warpper">
                    <div class="logo">
                        <a href="index.html"><img src="{{asset('front_assets')}}/images/logo.png" class="image-fit" alt="logo" /> </a>
                    </div>
                    <nav class="navigation">
                        <ul class="main-menu">
                            <li class="menu-item"><a href="index.html" class="">Home</a></li>
                            <li class="menu-item"><a href="product-list.html">Products</a></li>
                            <li class="menu-item"><a href="download.html">Downloads</a></li>
                            <li class="menu-item"><a href="about-us.html">About Us</a></li>
                            <li class="menu-item"><a href="contact-us.html">Contact Us</a></li>
                        </ul>
                        <div class="header-right">
                            <a href="#" data-popup="login1" class="openPopup thm-btn">Login/Sign</a>
                            <div class="profile-main">
                                <a href="#" class="profile-icon">Abcd <i class="fa-solid fa-chevron-down"></i></a>
                                <div class="profile-dropdown">
                                    <ul class="user-list-header">
                                        <li><a href="dashboard.html"><i class="fa-solid fa-user"></i> My Account</a></li>
                                        <!-- <li><a href="#"><i class="fa-solid fa-box"></i> Order History</a></li>
                                        <li><a href="#"><i class="fa-solid fa-book-bookmark"></i> Address Book</a></li> -->
                                        <li><a href="wishlist.html"><i class="fa-solid fa-heart"></i> My Wishlist</a></li>
                                        <li><a href="#"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="header-profile">
                                <div class="position-relative">
                                    <button id="searchToggle" class="profile-icon"><i class="fa-solid fa-magnifying-glass"></i></button>
                                    <input type="text" id="searchInput" class="form-control" placeholder="Search..." />
                                </div>

                                <a href="cart.html" class="profile-icon"><i class="fa-solid fa-cart-shopping"></i></a>
                            </div>
                        </div>
                    </nav>
                    
                    <!-- <a href="#" class="thm-btn"><i class="fa-solid fa-circle-right"></i> <span>Appointment</span></a> -->
                    <div class="hamburger">
                        <div class="hamburger-btn"><span></span> <span></span> <span></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>


@yield('content')


<!-- footer start -->
<footer class="footer section-padding pt-5 pb-0">
    <div class="container">
       <div class="ft-left">
           <a href="index.html" class="ft-logo">
               <img src="{{asset('front_assets')}}/images/logo.png" alt="img" class="ft-logo">
           </a>
           <ul class="ft-social-icon">
               <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
               <li><a href="#"><i class="fa-solid fa-paper-plane"></i></a></li>
               <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
               <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
           </ul>
       </div>
       <ul class="ft-bottom-list">
           <li><a href="#">About Us</a></li>
           <li><a href="#">FAQ</a></li>
           <li><a href="#">Contact</a></li>
           <li><a href="#">Grievance Cell</a></li>
           <li><a href="#">Terms & Conditions</a></li>
           <li><a href="#">Privacy Policy</a></li>
           <li><a href="#">Return Policy</a></li>
           <li><a href="#">Track Order</a></li>
           <li><a href="#">Compliance Documents</a></li>
       </ul>
     </div>
   </footer>
   <!-- -- footer end -->
   <!-- ----copyright start------- -->
   <div id="back-top" class="back-top"><a href="#top"><i class="fa-solid fa-chevron-up"></i></a> </div>
   <!-- ----copyright end------- -->
    <!-- Popup Modal -->
   <div id="login1" class="popup-overlay">
     <div class="popup-content">
       <button class="close-btn">&times;</button>
       <div class="bg-popup">
           <div class="row h-100 align-items-center">
               <div class="col-6 h-100">
                   <div class="back-image">
                       <div class="login-image">
                           <img src="{{asset('front_assets')}}/images/logo.png" alt="img" class="login-logo" />
                       </div>
                   </div>
               </div>
               <div class="col-6">
                   <div class="login-box">
                       <p class="skip-text">Skip</p>
                       <h2 class="login-title">Login/Signup</h2>
                       <form>
                           <div class="form-group">
                               <label>Enter your phone number</label>
                               <input type="text" class="form-control" value="+91" placeholder="+91" />
                           </div>
                           <button data-popup="otp" class="openPopup thm-btn w-100">Continue</button>
                       </form>
                   </div>
               </div>
           </div>
       </div>
     </div>
   </div>
   <div id="otp" class="popup-overlay">
     <div class="popup-content">
       <button class="close-btn">&times;</button>
       <div class="bg-popup">
           <div class="row h-100 align-items-center">
               <div class="col-6 h-100">
                   <div class="back-image">
                       <div class="login-image">
                           <img src="{{asset('front_assets')}}/images/logo.png" alt="img" class="login-logo" />
                       </div>
                   </div>
               </div>
               <div class="col-6">
                   <div class="login-box">
                       <p class="skip-text">Skip</p>
                        <h4 class="mb-2">Enter code</h4>
                        <p class="mb-0">We have sent an OTP verification code to</p>
                       <h6>+91-9874563210</h6>
                       <form>
                           <div class="">
                               <div class="otp-inputs">
                                   <input type="text" maxlength="1" class="otp-box" />
                                   <input type="text" maxlength="1" class="otp-box" />
                                   <input type="text" maxlength="1" class="otp-box" />
                                   <input type="text" maxlength="1" class="otp-box" />
                               </div>
                           </div>
                           <p>Didn’t get the OTP? Resend SMS in <strong class="text-black">00:20</strong></p>
                           <button class="openPopup thm-btn w-100">Continue</button>
                       </form>
                   </div>
               </div>
           </div>
       </div>
     </div>
   </div>
   <!-- js -->
   <script src="{{asset('front_assets')}}/js/plugins/jquery.min.js"></script>
   <script src="{{asset('front_assets')}}/js/plugins/slick.min.js"></script>
   <script src="{{asset('front_assets')}}/js/plugins/bootstrap.js"></script>
   <script src="{{asset('front_assets')}}/js/plugins/bootstrap.min.js"></script>
   <script src="{{asset('front_assets')}}/js/custom.js"></script>
   
   <script>
     // Open any popup
     document.querySelectorAll(".openPopup").forEach(button => {
       button.addEventListener("click", (e) => {
         // Prevent default form submission if inside a form
         e.preventDefault();
   
         const popupId = button.getAttribute("data-popup");
         const popup = document.getElementById(popupId);
         if (popup) popup.style.display = "flex";
       });
     });
   
     // Close any popup
     document.querySelectorAll(".popup-overlay").forEach(popup => {
       popup.querySelector(".close-btn").addEventListener("click", () => {
         popup.style.display = "none";
       });
   
       // Close when clicking outside the popup content
       popup.addEventListener("click", (e) => {
         if (e.target === popup) {
           popup.style.display = "none";
         }
       });
     });
   </script>
   
   </body>
   </html>








