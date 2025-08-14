<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nutraoshadhi Web Html </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <link rel="icon" type="image/png" sizes="32x32" href="favicon.ico"> -->
    <!-- css -->
    <link rel="stylesheet" href="{{ asset('front_assets') }}/css/plugins/slick.css">
    <link rel="stylesheet" href="{{ asset('front_assets') }}/fonts/all.css">
    <link rel="stylesheet" href="{{ asset('front_assets') }}/fonts/all.min.css">
    <link rel="stylesheet" href="{{ asset('front_assets') }}/fonts/fontawesome.css">
    <link rel="stylesheet" href="{{ asset('front_assets') }}/fonts/fontawesome.min.css">
    <link rel="stylesheet" href="{{ asset('front_assets') }}/css/plugins/bootstrap.css">
    <link rel="stylesheet" href="{{ asset('front_assets') }}/css/plugins/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('front_assets') }}/css/responsive.css">
    <link rel="stylesheet" href="{{ asset('front_assets') }}/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        /* Snackbar Styles */
        .snackbar {
            visibility: hidden;
            min-width: 300px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 8px;
            padding: 16px;
            position: fixed;
            z-index: 9999;
            left: 50%;
            bottom: 30px;
            transform: translateX(-50%);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: visibility 0.3s, opacity 0.3s;
            opacity: 0;
        }

        .snackbar.show {
            visibility: visible;
            opacity: 1;
        }

        .snackbar.success {
            background-color: #4CAF50;
        }

        .snackbar.error {
            background-color: #f44336;
        }

        .snackbar.warning {
            background-color: #ff9800;
        }

        .snackbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .snackbar-close {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            margin-left: 10px;
            padding: 0;
            line-height: 1;
        }

        .snackbar-close:hover {
            opacity: 0.8;
        }

        /* Loading spinner for cart operations */
        .cart-loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            justify-content: center;
            align-items: center;
        }

        .cart-loading.show {
            display: flex;
        }

        .cart-loading .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #006038;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Button loading state */
        .cart-btn.loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }

        .cart-btn.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 16px;
            height: 16px;
            margin: -8px 0 0 -8px;
            border: 2px solid transparent;
            border-top: 2px solid #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Additional styles for static pages */
        .page-header {
            background: linear-gradient(135deg, #006038 0%, #008a4f 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 3rem;
            border-radius: 0 0 20px 20px;
        }

        .page-header h1 {
            color: white !important;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .page-header p {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, #006038 0%, #008a4f 100%);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 96, 56, 0.4);
        }

        .text-primary {
            color: #006038 !important;
        }

        .bg-primary {
            background-color: #006038 !important;
        }

        .accordion-button:not(.collapsed) {
            background-color: #006038;
            color: white;
        }

        .accordion-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(0, 96, 56, 0.25);
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }

        .timeline-marker {
            position: absolute;
            left: -22px;
            top: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
        }

        .timeline-content {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .timeline-item.completed .timeline-content {
            border-left: 4px solid #28a745;
        }

        .timeline-item.active .timeline-content {
            border-left: 4px solid #006038;
        }

        /* Footer improvements */
        .footer {
            /* background: linear-gradient(135deg, #006038 0%, #008a4f 100%); */
            color: white;
        }

        .ft-bottom-list li a {
            color: rgba(255, 255, 255, 0.8);
            transition: color 0.3s ease;
        }

        .ft-bottom-list li a:hover {
            color: white;
            text-decoration: none;
        }

        .ft-social-icon li a {
            transition: transform 0.3s ease;
        }

        .ft-social-icon li a:hover {
            transform: translateY(-3px);
        }
    </style>
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
                            <a href="{{ url('/') }}"><img src="{{ asset('front_assets') }}/images/logo.png"
                                    class="image-fit" alt="logo" /> </a>
                        </div>
                        <nav class="navigation">
                            <ul class="main-menu">
                                <li class="menu-item"><a href="{{ url('/') }}" class="">Home</a></li>
                                <li class="menu-item"><a href="{{ url('product') }}">Products</a></li>
                                <li class="menu-item"><a href="{{ url('download') }}">Downloads</a></li>
                                <li class="menu-item"><a href="{{ url('about-us') }}">About Us</a></li>
                                <li class="menu-item"><a href="{{ url('contact-us') }}">Contact Us</a></li>
                            </ul>


                           @can('isCustomer')

                           <div class="profile-main">
                            <a href="#" class="profile-icon">Business<i class="fa-solid fa-chevron-down"></i></a>
                            <div class="profile-dropdown">
                                <ul class="user-list-header">

                                    <li><a href="{{ url('distributor/kyc') }}"><i class="fa-solid fa-plus"></i> KYC</a></li>
                                    <li><a href="{{ url('distributor/team-generation') }}"><i class="fa-solid fa-exchange-alt"></i> Team Generation</a></li>
                                    <li><a href="{{ url('distributor/my-direct-referral') }}"><i class="fa-solid fa-exchange-alt"></i> My Direct Referral</a></li>
                                    <li><a href="{{ url('distributor/my-acheivements') }}"><i class="fa-solid fa-exchange-alt"></i> My Acheivements</a></li>
                                </ul>
                            </div>
                        </div>

                            <div class="profile-main">
                                <a href="#" class="profile-icon">Wallet<i class="fa-solid fa-chevron-down"></i></a>
                                <div class="profile-dropdown">
                                    <ul class="user-list-header">

                                        <li><a href="{{ url('distributor/wallet/add-money') }}"><i class="fa-solid fa-plus"></i> Add Money</a></li>
                                        <li><a href="{{ url('distributor/wallet/money-transfer') }}"><i class="fa-solid fa-exchange-alt"></i> Money Transfer</a></li>
                                    </ul>
                                </div>
                            </div>
                            @endcan

                            <div class="header-right">
                                @if (!auth()->check())
                                    <a href="#" data-popup="login1" class="openPopup thm-btn">Login/Sign</a>
                                @endif


                                @if (auth()->check())
                                    <div class="profile-main">
                                        <a href="#" class="profile-icon">{{ auth()->user()->name??'Guest User' }} <i
                                                class="fa-solid fa-chevron-down"></i></a>
                                        <div class="profile-dropdown">
                                            <ul class="user-list-header">
                                                @can('isCustomer')
                                                <li><a href="{{ url('/products') }}"><i class="fa-solid fa-user"></i> Become a Distributor</a></li>
                                                @endcan
                                                <li><a href="{{ url('my-account') }}"><i class="fa-solid fa-user"></i>
                                                        My Account</a></li>
                                                <li><a href="{{ url('order-history') }}"><i
                                                            class="fa-solid fa-box"></i> Order History</a></li>
                                                {{-- <li><a href="#"><i class="fa-solid fa-book-bookmark"></i> Address Book</a></li> --}}
                                                <li><a href="{{ url('wishlist') }}"><i class="fa-solid fa-heart"></i>
                                                        My Wishlist</a></li>
                                                <li><a href="{{ url('logout') }}"><i
                                                            class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                                <div class="header-profile">
                                    <div class="position-relative">
                                        <button id="searchToggle" class="profile-icon"><i
                                                class="fa-solid fa-magnifying-glass"></i></button>
                                        <input type="text" id="searchInput" class="form-control"
                                            placeholder="Search..." />
                                    </div>

                                    <a href="{{ url('cart') }}" class="profile-icon header-cart"><span
                                            class="total-cart-count">{{ cartCount() }}</span><i
                                            class="fa-solid fa-cart-shopping"></i></a>
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
    <style>
        /* Alert styles */
        .alert {
            border-radius: 8px;
            border: none;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
        }
    </style>

    @yield('content')


    <!-- footer start -->
    <footer class="footer section-padding pt-5 pb-0">
        <div class="container">
            <div class="ft-left">
                <a href="{{ url('/') }}" class="ft-logo">
                    <img src="{{ asset('front_assets') }}/images/logo.png" alt="img" class="ft-logo">
                </a>
                <ul class="ft-social-icon">
                    <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                    <li><a href="#"><i class="fa-solid fa-paper-plane"></i></a></li>
                    <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
                    <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                </ul>
            </div>
            <ul class="ft-bottom-list">
                <li><a href="{{ route('about') }}">About Us</a></li>
                <li><a href="{{ route('faq') }}">FAQ</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
                <li><a href="{{ route('grievance.cell') }}">Grievance Cell</a></li>
                <li><a href="{{ route('terms.conditions') }}">Terms & Conditions</a></li>
                <li><a href="{{ route('privacy.policy') }}">Privacy Policy</a></li>
                <li><a href="{{ route('return.policy') }}">Return Policy</a></li>
                <li><a href="{{ route('shipping.policy') }}">Shipping Policy</a></li>
                <li><a href="{{ route('track.order') }}">Track Order</a></li>
                <li><a href="{{ route('compliance.documents') }}">Compliance Documents</a></li>
            </ul>
        </div>
    </footer>
    <!-- -- footer end -->
    <!-- ----copyright start------- -->
    <div id="back-top" class="back-top"><a href="#top"><i class="fa-solid fa-chevron-up"></i></a> </div>
    <!-- ----copyright end------- -->
    <!-- Popup Modal -->


    <!-- Snackbar for cart messages -->
    <div id="snackbar" class="snackbar">
        <div class="snackbar-content">
            <span id="snackbar-message"></span>
            <button class="snackbar-close" onclick="closeSnackbar()">×</button>
        </div>
    </div>

    <!-- Loading overlay for cart operations -->
    <div class="cart-loading">
        <div class="spinner"></div>
    </div>

    <div class="toast-container">
        <div class="toast" id="myToast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto toast-title">Add To Cart</strong>
                <!--   <small>Just now</small> -->
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">

            </div>
        </div>
    </div>

    <!-- js -->
    <script type="text/javascript">
        let base_url = "{{ url('/') }}"
    </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('front_assets') }}/js/plugins/jquery.min.js"></script>
    <script src="{{ asset('front_assets') }}/js/plugins/slick.min.js"></script>
    <script src="{{ asset('front_assets') }}/js/plugins/bootstrap.js"></script>
    <script src="{{ asset('front_assets') }}/js/plugins/bootstrap.min.js"></script>
    <script src="{{ asset('front_assets') }}/js/custom.js"></script>
    <script src="{{ asset('front_assets') }}/js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @include('Website.Layout.login')
    <script>
        //for cart functionality
        $(function() {
            $(document).on("click", ".cart-btn", function() {
                const $btn = $(this);
                const product_id = $btn.data("id");

                if ($btn.hasClass('loading')) {
                    return;
                }
                $btn.addClass('loading');
                showCartLoading();

                $.ajax({
                    type: "POST",
                    url: base_url + "/add-to-cart",
                    data: {
                        product_id: product_id,
                        '_token': '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        $btn.prop('disabled', true);
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status) {
                            showSnackbar(response.msg || 'Product added to cart successfully!',
                                'success');
                            if (response.record && response.record.cartCount) {
                                $(".total-cart-count").text(response.record.cartCount);
                            }
                        } else {
                            showSnackbar(response.msg || 'Failed to add product to cart',
                                'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMsg = 'Something went wrong. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.msg) {
                            errorMsg = xhr.responseJSON.msg;
                        } else if (xhr.status === 401) {
                            errorMsg = 'Please login to add items to cart';
                        } else if (xhr.status === 422) {
                            errorMsg = 'Invalid product data';
                        }
                        showSnackbar(errorMsg, 'error');
                    },
                    complete: function() {
                        hideCartLoading();
                        $btn.removeClass('loading').prop('disabled', false);
                    },
                });
            });
        });

        function showToast() {
            var toast = new bootstrap.Toast(document.getElementById("myToast"));
            toast.show();
            setTimeout(function() {
                toast.hide();
            }, 3000);
        }

        // Snackbar Functions
        function showSnackbar(message, type = 'success', duration = 3000) {
            const snackbar = document.getElementById('snackbar');
            const messageElement = document.getElementById('snackbar-message');

            if (!snackbar || !messageElement) {
                console.error('Snackbar elements not found');
                return;
            }

            messageElement.textContent = message;
            snackbar.className = `snackbar ${type}`;
            snackbar.classList.add('show');

            // Auto-hide after duration
            setTimeout(() => {
                closeSnackbar();
            }, duration);
        }

        function closeSnackbar() {
            const snackbar = document.getElementById('snackbar');
            if (snackbar) {
                snackbar.classList.remove('show');
            }
        }

        function showCartLoading() {
            const loading = document.querySelector('.cart-loading');
            if (loading) {
                loading.classList.add('show');
                // Prevent body scroll when loading is shown
                document.body.style.overflow = 'hidden';
                console.log('Cart loading shown');
            } else {
                console.error('Cart loading element not found');
            }
        }

        function hideCartLoading() {
            const loading = document.querySelector('.cart-loading');
            if (loading) {
                loading.classList.remove('show');
                // Restore body scroll
                document.body.style.overflow = '';
                console.log('Cart loading hidden');
            } else {
                console.error('Cart loading element not found');
            }
        }

        // SweetAlert function
        function sweetalert(status, msg, delay = 3000) {
            Swal.fire({
                icon: status ? 'success' : 'error',
                title: status ? 'Success!' : 'Error!',
                text: msg,
                timer: delay,
                timerProgressBar: true,
                confirmButtonColor: '#3085d6',
                showConfirmButton: delay === 0 ? true : false
            });
        }

        function alertMsg(success, message, delay = 5000, remove = false, id = false) {
            const alertClass = success ? 'alert-success' : 'alert-danger';

            const alertHtml = `
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        <span>${message}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
            let selector = remove ? 'messageRemove' : (id ? id : 'messageError');
            $('#' + selector).html(alertHtml);
            $('#' + selector).fadeIn();
            setTimeout(function() {
                $('#' + selector).alert('close');
            }, delay);
        }
    </script>

    @stack('scripts')
    @stack('script')


    <script>
        function updateCartQuantity(productId, quantity) {
            showCartLoading();

            $.ajax({
                url: '{{ url('update-cart-quantity') }}',
                type: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    hideCartLoading();
                    if (response.status) {
                        $('#price-'+response.record.cart_id).text('₹'+response.record.price);
                        $('#mrp-'+response.record.cart_id).text('₹'+response.record.mrp);   
                        updateCartSummary(response.record.cart_data);
                        showSnackbar(response.msg, 'success');
                    } else {
                        showSnackbar(response.msg, 'error');
                        var input = $('input[data-product-id="' + productId + '"]');
                        var currentQty = parseInt(input.val());
                         
                        if (quantity > currentQty) {
                            input.val(currentQty - 1);
                        } else {
                            input.val(currentQty + 1);
                        }
                    }
                },
                error: function(xhr) {
                    hideCartLoading();
                    var errorMsg = 'Error updating quantity';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                    }
                    showSnackbar(errorMsg, 'error');
                }
            });
        }

        function updateCartSummary(cartData) {
                console.log(cartData);
                $('.cart-header').text('Shopping Cart (' + cartData.total_items + ' Items)');

                $('.summary-box h6').html('<span class="text-black">Order Summary</span> (' + cartData.total_items +
                    ' Items)');
                $('.summary-box p:contains("Total MRP") span').text('₹' + cartData.total_mrp);

                $('.summary-box p:contains("Total Discounts") span').text('-₹ 0');
                if (cartData.total_saving > 0) {
                    $('.summary-box p:contains("Total Discounts") span').text('-₹' + cartData.total_saving);
                }

                if(cartData.total_sv > 0 && cartData.total_sv != null){
                    $('.summary-box p:contains("Total SV") span').text(cartData.total_sv);
                }

                $('.summary-box p:contains("Payable Amount") span').text('₹' + cartData.subtotal);
                $('.proceed-to-pay').text('Proceed to Pay ₹' + cartData.subtotal);

                $('.total-cart-count').text(cartData.total_items);
            }
    </script>

    <script>
        $(document).ready(function() {
            $('.add-to-wishlist').click(function() {
                var product_id = $(this).data('id');
                var url = "{{ url('wishlist/add') }}";
                showCartLoading();
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        product_id: product_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status) {
                            showSnackbar(response.msg, 'success');
                        } else {
                            showSnackbar(response.msg, 'error');
                        }
                    },
                    error: function(xhr) {
                        hideCartLoading();
                        showSnackbar(xhr.responseJSON.msg, 'error');
                    },
                    complete: function() {
                        hideCartLoading();
                    }
                });
            });
        });
    </script>
</body>

</html>
