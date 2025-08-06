@extends('Website.Layout.app')
@section('content')
    <!-- Signup Section -->
    <section class="signup-section section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="signup-form-wrapper">
                        <div class="text-center mb-4">
                            <h2 class="section-title">Create Account</h2>
                            <p class="text-muted">Join us and start your journey</p>
                        </div>

                        <!-- Step 1: Phone Number Form -->
                        <div id="phone-step" class="signup-step active">
                            <form id="phone-form" method="POST" action="{{ url('distributor/signup/verify-phone') }}">
                                @csrf

                                <div class="form-group mb-3">
                                    <label for="referral_id" class="form-label">Referral ID<span class="text-danger">
                                            *</span></label>
                                    <input type="text" class="form-control" id="referral_id" name="referral_id"
                                        placeholder="Enter referral ID" value="{{ old('referral_id') }}" required>
                                    <span class="text-danger error" id="referral_id_error"></span>
                                    <small class="form-text text-muted">Enter your referral ID to continue</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="mobile" class="form-label">Mobile Number *</label>

                                    <input type="tel" class="form-control" id="mobile" name="mobile"
                                        placeholder="Enter your mobile number" value="{{ old('mobile') }}" maxlength="10"
                                        pattern="[0-9]{10}" required>
                                    <span class="text-danger error" id="mobile_error"></span>
                                    <small class="form-text text-muted">We'll send you a verification code</small>
                                </div>



                                <button type="submit" class="btn theme-btn w-100">Send Verification Code</button>
                            </form>
                        </div>

                        <!-- Step 2: OTP Verification -->
                        <div id="otp-step" class="signup-step" style="display: none;">
                            <form id="otp-form" method="POST" action="{{ url('distributor/signup/verify-otp') }}">
                                @csrf
                                <input type="hidden" name="mobile" id="otp-mobile">
                                <div class="text-center mb-3">
                                    <h5>Enter Verification Code</h5>
                                    <p class="text-muted">We've sent a 6-digit code to <span id="display-phone"
                                            class="fw-bold"></span></p>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="otp-input-container">
                                        <input type="text" class="otp-input" maxlength="1" data-index="1" required>
                                        <input type="text" class="otp-input" maxlength="1" data-index="2" required>
                                        <input type="text" class="otp-input" maxlength="1" data-index="3" required>
                                        <input type="text" class="otp-input" maxlength="1" data-index="4" required>
                                        <input type="text" class="otp-input" maxlength="1" data-index="5" required>
                                        <input type="text" class="otp-input" maxlength="1" data-index="6" required>
                                    </div>
                                    <input type="hidden" name="otp" id="otp-value">
                                    <span class="text-danger error" id="otp_error"></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <button type="button" class="theme-link p-0 border-0 bg-transparent" id="resend-otp"
                                        disabled>
                                        Resend Code (<span id="resend-timer">30</span>s)
                                    </button>
                                    <button type="button" class="theme-link p-0 border-0 bg-transparent" id="change-phone">
                                        Change Phone Number
                                    </button>
                                </div>
                                <button type="submit" class="btn theme-btn w-100">Verify Code</button>
                            </form>
                        </div>

                        <!-- Step 3: Complete Registration -->
                        <div id="registration-step" class="signup-step" style="display: none;">
                            <div id="messageError"></div>
                            <form id="registration-form" method="POST" action="{{ url('distributor/signup/complete') }}">
                                @csrf
                                <input type="hidden" name="mobile" id="reg-mobile">
                                <input type="hidden" name="otp_verified" value="1">
                                <input type="hidden" name="referral_id" id="reg-referral-id">

                                <div class="form-group mb-3">
                                    <label for="full_name" class="form-label">Full Name<span class="text-danger">
                                            *</span></label>
                                    <input type="text" class="form-control" id="full_name" name="full_name"
                                        placeholder="Enter your full name" value="{{ old('full_name') }}" required>
                                    <span class="text-danger error" id="full_name_error"></span>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email Address<span class="text-danger">
                                            *</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter your email address" value="{{ old('email') }}" required>
                                    <span class="text-danger error" id="email_error"></span>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">Password<span class="text-danger">
                                            *</span></label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Create a strong password" required>
                                    <span class="text-danger error" id="password_error"></span>
                                    <small class="form-text text-muted">Password must be at least 8 characters long</small>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="confirm_password" class="form-label">Confirm Password<span
                                            class="text-danger"> *</span></label>
                                    <input type="password" class="form-control" id="confirm_password"
                                        name="confirm_password" placeholder="Confirm your password" required>
                                    <span class="text-danger error" id="confirm_password_error"></span>
                                </div>

                                <button type="submit" class="btn theme-btn w-100">Create Account</button>
                            </form>
                        </div>

                        <!-- Progress Indicator -->
                        <div class="signup-progress mt-4">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 33%" id="progress-bar"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted step-label step-active" id="step-label-1">Step 1: Phone</small>
                                <small class="text-muted step-label" id="step-label-2">Step 2: Verify</small>
                                <small class="text-muted step-label" id="step-label-3">Step 3: Complete</small>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            {{-- <p class="mb-0">Already have an account? <a href="{{ url('distributor/signin') }}"
                                    class="theme-link">Login here</a></p> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .signup-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .signup-form-wrapper {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: #333;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .otp-input-container {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
        }

        .otp-input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, .25);
        }

        .signup-step {
            transition: all 0.3s ease;
        }

        .signup-step.active {
            display: block;
        }

        .progress {
            height: 8px;
            border-radius: 4px;
        }

        .progress-bar {
            transition: width 0.3s ease;
            background: #006038;
        }

        .signup-progress .text-muted {
            color: #bdbdbd !important;
            font-weight: 500;
        }

        .signup-progress .step-active {
            color: #006038 !important;
            font-weight: 700;
        }

        /* Theme Button Styles */
        .theme-btn {
            background: #006038;
            border: none;
            padding: 12px 24px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
        }

        .theme-btn:hover {
            background: #004d2c;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 96, 56, 0.15);
            color: white;
            text-decoration: none;
        }

        .theme-btn:disabled {
            background: #6c757d;
            transform: none;
            box-shadow: none;
            color: white;
        }

        /* Theme Link Styles */
        .theme-link {
            color: #006038 !important;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .theme-link:hover {
            color: #004d2c !important;
            text-decoration: underline;
        }

        .theme-link:disabled {
            color: #6c757d !important;
            cursor: not-allowed;
            text-decoration: none;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 12px 16px;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, .25);
        }

        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-right: none;
            border-radius: 8px 0 0 8px;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }

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

    @push('script')
        <script>
            $(function() {
                var currentStep = 1;
                var totalSteps = 3;

                // Phone form submission
                $('#phone-form').on('submit', function(e) {
                    e.preventDefault();
                    var phone = $('#mobile').val();
                    var referralId = $('#referral_id').val();

                    // Clear previous errors
                    $('#mobile_error').html('');
                    $('#referral_id_error').html('');

                    // Validate referral ID
                    if (!referralId || referralId.trim() === '') {
                        $('#referral_id_error').html('Referral ID is required');
                        return;
                    }

                    if (phone.length !== 10 || !/^\d+$/.test(phone)) {
                        $('#mobile_error').html('Please enter a valid 10-digit phone number');
                        return;
                    }

                    var submitBtn = $(this).find('button[type="submit"]');
                    var originalText = submitBtn.text();
                    submitBtn.text('Sending...').prop('disabled', true);
                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function(data) {
                            if (data.status) {
                                $('#otp-mobile').val(phone);
                                $('#reg-mobile').val(phone);
                                $('#reg-referral-id').val(referralId);
                                $('#display-phone').text('+91 ' + phone);
                                showStep(2);
                                startResendTimer();
                            } else {
                                $('#mobile_error').html(data.msg ||
                                    'Something went wrong. Please try again.');
                            }
                        },
                        error: function(xhr) {
                            $('#mobile_error').html(xhr.responseJSON?.msg ||
                                'Something went wrong. Please try again.');
                        },
                        complete: function() {
                            submitBtn.text(originalText).prop('disabled', false);
                        }
                    });
                });

                // OTP input handling
                $('.otp-input').on('input', function() {
                    var $this = $(this);
                    var index = $('.otp-input').index(this);
                    if ($this.val().length === 1 && index < 5) {
                        $('.otp-input').eq(index + 1).focus();
                    }
                    updateOtpValue();
                }).on('keydown', function(e) {
                    var index = $('.otp-input').index(this);
                    if (e.key === 'Backspace' && $(this).val() === '' && index > 0) {
                        $('.otp-input').eq(index - 1).focus();
                    }
                });

                function updateOtpValue() {
                    var otpValue = $('.otp-input').map(function() {
                        return $(this).val();
                    }).get().join('');
                    $('#otp-value').val(otpValue);
                }

                // OTP form submission
                $('#otp-form').on('submit', function(e) {
                    e.preventDefault();
                    var otp = $('#otp-value').val();
                    if (otp.length !== 6) {
                        $('#otp_error').html('Please enter the complete 6-digit code');
                        return;
                    }
                    var submitBtn = $(this).find('button[type="submit"]');
                    var originalText = submitBtn.text();
                    submitBtn.text('Verifying...').prop('disabled', true);
                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function(data) {
                            if (data.status) {
                                showStep(3);
                            } else {
                                $('#otp_error').html(data.msg ||
                                    'Invalid OTP. Please try again.');
                                $('.otp-input').val('');
                                updateOtpValue();
                                $('.otp-input').eq(0).focus();
                            }
                        },
                        error: function(xhr) {
                            $('#otp_error').html(xhr.responseJSON?.msg ||
                                'Something went wrong. Please try again.');
                        },
                        complete: function() {
                            submitBtn.text(originalText).prop('disabled', false);
                        }
                    });
                });

                // Registration form submission
                $('#registration-form').on('submit', function(e) {
                    e.preventDefault();
                    var password = $('#password').val();
                    var confirmPassword = $('#confirm_password').val();
                    if (password !== confirmPassword) {
                        $('#confirm_password_error').html('Passwords do not match');
                        return;
                    }
                    if (password.length < 8) {
                        $('#confirm_password_error').html('Password must be at least 8 characters long');
                        return;
                    }
                    var submitBtn = $(this).find('button[type="submit"]');
                    var originalText = submitBtn.text();
                    submitBtn.text('Creating Account...').prop('disabled', true);
                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function(data) {
                            if (data.status) {
                                alertMsg(true, 'Account created successfully! You can now login.');
                                setTimeout(function() {
                                    window.location.href = '{{ url('distributor/signin') }}';
                                }, 1200);
                            } else {
                                alertMsg(false, data.msg ||
                                    'Something went wrong. Please try again.');
                            }
                        },
                        error: function(xhr) {
                            if (xhr.responseJSON?.errors) {
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    $('#' + key + '_error').html(value[0]);
                                });
                            } else {
                                alertMsg(false, xhr.responseJSON?.msg ||
                                    'Something went wrong. Please try again.');
                            }
                        },
                        complete: function() {
                            submitBtn.text(originalText).prop('disabled', false);
                        }
                    });
                });

                // Change phone number
                $('#change-phone').on('click', function() {
                    $('#otp_error').html('');
                    $('#otp-value').val('');
                    $('.otp-input').val('');
                    $('.otp-input').eq(0).focus();
                    showStep(1);
                });

                // Resend OTP
                $('#resend-otp').on('click', function() {
                    if ($(this).prop('disabled')) return;
                    var mobile = $('#otp-mobile').val();
                    var formData = {
                        mobile: mobile,
                        _token: '{{ csrf_token() }}'
                    };
                    $.ajax({
                        url: '{{ url('distributor/signup/resend-otp') }}',
                        method: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(data) {
                            if (data.status) {
                                startResendTimer();
                                alertMsg(true, 'OTP resent successfully!');
                            } else {
                                alertMsg(false, data.msg || 'Failed to resend OTP');
                            }
                        },
                        error: function(xhr) {
                            alertMsg(false, xhr.responseJSON?.msg || 'Failed to resend OTP');
                        }
                    });
                });

                function showStep(step) {
                    $('.signup-step').hide();
                    $('#' + getStepId(step)).fadeIn(200);
                    var progress = (step / totalSteps) * 100;
                    $('#progress-bar').css('width', progress + '%');
                    currentStep = step;
                    // Highlight the current step label
                    $('.step-label').removeClass('step-active');
                    $('#step-label-' + step).addClass('step-active');
                }

                function getStepId(step) {
                    switch (step) {
                        case 1:
                            return 'phone-step';
                        case 2:
                            return 'otp-step';
                        case 3:
                            return 'registration-step';
                        default:
                            return 'phone-step';
                    }
                }

                function startResendTimer() {
                    var resendBtn = $('#resend-otp');
                    var timerSpan = $('#resend-timer');
                    var timeLeft = 30;
                    resendBtn.prop('disabled', true);
                    timerSpan.text(timeLeft);
                    var timer = setInterval(function() {
                        timeLeft--;
                        timerSpan.text(timeLeft);
                        if (timeLeft <= 0) {
                            clearInterval(timer);
                            resendBtn.prop('disabled', false);
                            timerSpan.text('');
                        }
                    }, 1000);
                }

                // Alert function
                function alertMsg(success, message) {
                    const alertClass = success ? 'alert-success' : 'alert-danger';
                    const alertHtml = `
                        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                            <span>${message}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                    $('#messageError').html(alertHtml);

                    // Auto dismiss after 5 seconds
                    setTimeout(function() {
                        $('#messageError .alert').alert('close');
                    }, 5000);
                }
            });
        </script>
    @endpush
@endsection
