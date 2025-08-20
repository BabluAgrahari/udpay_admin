<div id="login1" class="popup-overlay">
    <div class="popup-content">
        <button class="close-btn">&times;</button>
        <div class="bg-popup">
            <div class="row login-main align-items-center">
                <div class="col-md-6 h-100">
                    <div class="back-image">
                        <div class="login-image">
                            <img src="{{ asset('front_assets') }}/images/logo.png" alt="img" class="login-logo" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="login-box">
                        {{-- <p class="skip-text">Skip</p> --}}
                        <h2 class="login-title">Login/Signup</h2>
                        <form id="sendOtpForm" method="post">
                            <div class="form-group">
                                <label>Enter your phone number</label>
                                <input type="text" class="form-control" id="mobileInput" value=""
                                    placeholder="Enter 10-digit mobile number" maxlength="10" />
                                <div id="mobileError" class="text-danger mt-1" style="display: none;"></div>
                            </div>
                            <button id="sendOtpBtn" type="submit" class="openPopup thm-btn w-100">Continue</button>
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
            <div class="row login-main align-items-center">
                <div class="col-md-6 h-100">
                    <div class="back-image">
                        <div class="login-image">
                            <img src="{{ asset('front_assets') }}/images/logo.png" alt="img" class="login-logo" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="login-box">
                        {{-- <p class="skip-text">Skip</p> --}}
                        <h4 class="mb-2">Enter code</h4>
                        <p class="mb-0">We have sent an OTP verification code to</p>
                        <h6 id="otpMobileDisplay">+91-</h6>
                        <form id="verifyOtpForm">
                            <div class="">
                                <div class="otp-inputs">
                                    <input type="text" maxlength="1" class="otp-box" data-index="0" />
                                    <input type="text" maxlength="1" class="otp-box" data-index="1" />
                                    <input type="text" maxlength="1" class="otp-box" data-index="2" />
                                    <input type="text" maxlength="1" class="otp-box" data-index="3" />
                                    <input type="text" maxlength="1" class="otp-box" data-index="4" />
                                    <input type="text" maxlength="1" class="otp-box" data-index="5" />
                                </div>
                                <div id="otpError" class="text-danger mt-1" style="display: none;"></div>
                            </div>
                            <input type="hidden" id="verifyMobileInput" value="" />
                            <p>Didn't get the OTP? <a href="#" id="resendOtpLink">Resend SMS</a> in <strong class="text-black" id="resendTimer">01:00</strong></p>
                            <button id="verifyOtpBtn" class="openPopup thm-btn w-100">Continue</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

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

        $('#sendOtpBtn').on('click', function(e) {
            e.preventDefault();
            sendOtp();
        });

        $('#verifyOtpBtn').on('click', function(e) {
            e.preventDefault();
            verifyOtp();
        });


        $('#resendOtpLink').on('click', function(e) {
            e.preventDefault();
            if ($(this).hasClass('disabled')) return;
            sendOtp(true);
        });


        $('#logoutLink').on('click', function(e) {
            e.preventDefault();
            logout();
        });

        $(document).on('click', '.popup-overlay', function(e) {
            if (e.target === this) {
                $(this).hide();
            }
        });


        $(document).on('click', '.close-btn', function() {
            $(this).closest('.popup-overlay').hide();
        });
    });

    function sendOtp(isResend = false) {
        const mobile = $('#mobileInput').val();

        if (!mobile || mobile.length !== 10) {
            showError('mobileError', 'Please enter a valid 10-digit mobile number');
            return;
        }

        hideError('mobileError');

        $.ajax({
            url: base_url1 + '/send-otp',
            type: 'POST',
            data: {
                mobile: mobile,
                '_token': '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $('#sendOtpBtn').html('<div class="spinner-border spinner-border-sm text-white me-2" role="status"><span class="sr-only">Loading...</span></div>Loading...');
                $('#sendOtpBtn').prop('disabled', true);
            },
            success: function(response) {
                if (response.status) {
                    if (!isResend) {
                        $('#otpMobileDisplay').text('+91-' + mobile);
                        $('#verifyMobileInput').val(mobile);
                        $('#login1').hide();
                        $('#otp').show();
                        startResendTimer();
                    } else {
                        showSnackbar('OTP resent successfully', 'success');
                        startResendTimer();
                    }
                } else {
                    showSnackbar(response.msg, 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showSnackbar(response?.msg || 'Something went wrong', 'error');
            },
            complete: function() {
                $('#sendOtpBtn').html('Continue').prop('disabled', false);
            }
        });
    }

    function verifyOtp() {
        const mobile = $('#verifyMobileInput').val();
        const otp = $('.otp-box').map(function() {
            return $(this).val();
        }).get().join('');

        if (otp.length !== 6) {
            showError('otpError', 'Please enter the complete 6-digit OTP');
            return;
        }

        hideError('otpError');

        $.ajax({
            url: base_url1 + '/verify-otp',
            type: 'POST',
            data: {
                mobile: mobile,
                otp: otp,
                '_token': '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $('#verifyOtpBtn').html('<div class="spinner-border spinner-border-sm text-white me-2" role="status"><span class="sr-only">Loading...</span></div>Loading...');
                $('#verifyOtpBtn').prop('disabled', true);
            },
            success: function(response) {
                if (response.status) {
                    showSnackbar(response.msg, 'success');
                    $('#otp').hide();
                    checkAuthStatus();
                    $('.otp-box').val('');
                    location.reload();
                } else {
                    showSnackbar(response.msg, 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showSnackbar(response?.msg || 'Something went wrong', 'error');
            },
            complete: function() {
                $('#verifyOtpBtn').html('Continue').prop('disabled', false);
                $('#verifyOtpBtn').prop('disabled', false);
            }
        });
    }



    function logout() {
        $.ajax({
            url: base_url1 + '/logout',
            type: 'POST',
            data: {
                '_token': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status) {
                    showSnackbar(response.msg, 'success');
                    checkAuthStatus();
                } else {
                    showSnackbar(response.msg, 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showSnackbar(response?.msg || 'Something went wrong', 'error');
            }
        });
    }

    function startResendTimer() {
        let timeLeft = 60;
        const timerElement = $('#resendTimer');
        const resendLink = $('#resendOtpLink');

        resendLink.addClass('disabled');

        const timer = setInterval(function() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;

            timerElement.text(
                (minutes < 10 ? '0' : '') + minutes + ':' +
                (seconds < 10 ? '0' : '') + seconds
            );

            timeLeft--;

            if (timeLeft < 0) {
                clearInterval(timer);
                timerElement.text('00:00');
                resendLink.removeClass('disabled');
            }
        }, 1000);
    }

    var base_url1 = '{{ url('/') }}';

    function showSnackbar(message, type = 'info') {
        // Use the main snackbar if available, otherwise fallback to alert
        const snackbar = document.getElementById('snackbar');
        if (snackbar) {
            const messageElement = document.getElementById('snackbar-message');
            if (messageElement) {
                messageElement.textContent = message;
                snackbar.className = `snackbar ${type}`;
                snackbar.classList.add('show');

                setTimeout(() => {
                    closeSnackbar();
                }, 3000);
                return;
            }
        }
        // Fallback to alert
        alert(message);
    }

    function checkAuthStatus() {
        $.ajax({
            url: base_url1 + '/check-auth',
            type: 'GET',
            success: function(response) {
                if (response.status && response.logged_in) {
                    console.log('User logged in:', response.user);
                } else {
                    console.log('User not logged in');
                }
            },
            error: function(xhr) {
                console.log('Error checking auth status');
            }
        });
    }


    $(document).on('input', '.otp-box', function() {
        const value = $(this).val();
        const index = parseInt($(this).data('index'));

        if (value.length === 1) {

            if (index < 5) {
                $('.otp-box[data-index="' + (index + 1) + '"]').focus();
            }
        } else if (value.length === 0) {

            if (index > 0) {
                $('.otp-box[data-index="' + (index - 1) + '"]').focus();
            }
        }
    });

    function showError(elementId, message) {
        $('#' + elementId).text(message).show();
    }

    function hideError(elementId) {
        $('#' + elementId).hide();
    }


    $(document).ready(function() {
        $('<style>')
            .prop('type', 'text/css')
            .html(`
                #resendOtpLink.disabled {
                    color: #999 !important;
                    text-decoration: none !important;
                    cursor: not-allowed !important;
                    pointer-events: none !important;
                }
                #resendOtpLink:not(.disabled) {
                    color: #007bff !important;
                    text-decoration: underline !important;
                    cursor: pointer !important;
                }
                button:disabled {
                    opacity: 0.7;
                    cursor: not-allowed;
                }
            `)
            .appendTo('head');
    });
</script>
