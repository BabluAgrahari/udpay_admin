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
                      <form id="sendOtpForm" >
                          <div class="form-group">
                              <label>Enter your phone number</label>
                              <input type="text" class="form-control" id="mobileInput" value="" placeholder="+91" />
                          </div>
                          <button id="sendOtpBtn" class="openPopup thm-btn w-100">Continue</button>
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
                      <form id="verifyOtpForm">
                          <div class="">
                              <div class="otp-inputs">
                                  <input type="text" maxlength="1" class="otp-box" />
                                  <input type="text" maxlength="1" class="otp-box" />
                                  <input type="text" maxlength="1" class="otp-box" />
                                  <input type="text" maxlength="1" class="otp-box" />
                              </div>
                          </div>
                          <p>Didn’t get the OTP? Resend SMS in <strong class="text-black">00:20</strong></p>
                          <button id="verifyOtpBtn" class="openPopup thm-btn w-100">Continue</button>
                      </form>
                  </div>
              </div>
          </div>
      </div>
    </div>
  </div>




@push('scripts')
<script>
$(document).ready(function() {
   
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

     
    
    // Send OTP Form
    $('#sendOtpForm').on('submit', function(e) {
        e.preventDefault();
        sendOtp();
    });
    
    // Verify OTP Form
    $('#verifyOtpForm').on('submit', function(e) {
        e.preventDefault();
        verifyOtp();
    });
    
    // OTP Input handling
    $('.otp-box').on('input', function() {
        const value = $(this).val();
        const index = $(this).data('index');
        
        if (value.length === 1) {
            // Move to next input
            if (index < 3) {
                $('.otp-box[data-index="' + (index + 1) + '"]').focus();
            }
        } else if (value.length === 0) {
            // Move to previous input on backspace
            if (index > 0) {
                $('.otp-box[data-index="' + (index - 1) + '"]').focus();
            }
        }
    });
    
    // Resend OTP
    $('#resendOtpLink').on('click', function(e) {
        e.preventDefault();
        if ($(this).hasClass('disabled')) return;
        sendOtp(true);
    });
    
    // Logout
    $('#logoutLink').on('click', function(e) {
        e.preventDefault();
        logout();
    });
    
    // Close modals when clicking outside
    $(document).on('click', '.popup-overlay', function(e) {
        if (e.target === this) {
            $(this).hide();
        }
    });

    // Close modals with close button
    $(document).on('click', '.close-btn', function() {
        $(this).closest('.popup-overlay').hide();
    });
});

function sendOtp(isResend = false) {
    const mobile = $('#mobileInput').val();
    
    if (!mobile || mobile.length !== 10) {
        $('#mobileError').text('Please enter a valid 10-digit mobile number');
        return;
    }
    
    $('#mobileError').text('');
    $('#sendOtpBtn').html('<span class="btn-loading">Loading...</span>');
    $('#sendOtpBtn').prop('disabled', true);
    
    $.ajax({
        url: base_url + '/send-otp',
        type: 'POST',
        data: {
            mobile: mobile,
            '_token': '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status) {
                if (!isResend) {
                    // Show OTP modal
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
    
    if (otp.length !== 4) {
        $('#otpError').text('Please enter the complete 4-digit OTP');
        return;
    }
    
    $('#otpError').text('');
    $('#verifyOtpBtn .btn-text').hide();
    $('#verifyOtpBtn .btn-loading').show();
    $('#verifyOtpBtn').prop('disabled', true);
    
    $.ajax({
        url: base_url + '/verify-otp',
        type: 'POST',
        data: {
            mobile: mobile,
            otp: otp,
            '_token': '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status) {
                showSnackbar(response.msg, 'success');
                $('#otp').hide();
                checkAuthStatus();
                // Clear OTP inputs
                $('.otp-box').val('');
            } else {
                showSnackbar(response.msg, 'error');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            showSnackbar(response?.msg || 'Something went wrong', 'error');
        },
        complete: function() {
            $('#verifyOtpBtn .btn-text').show();
            $('#verifyOtpBtn .btn-loading').hide();
            $('#verifyOtpBtn').prop('disabled', false);
        }
    });
}



function logout() {
    $.ajax({
        url: base_url + '/logout',
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
    let timeLeft = 120; // 2 minutes
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
            timerElement.text('Resend');
            resendLink.removeClass('disabled');
        }
    }, 1000);
}

</script>
@endpush