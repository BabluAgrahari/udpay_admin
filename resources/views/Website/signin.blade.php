@extends('Website.Layout.app')
@section('content')
    <!-- Signin Section -->
    <section class="signin-section section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="signin-form-wrapper">
                        <div class="text-center mb-4">
                            <h2 class="section-title">Welcome Back</h2>
                            <p class="text-muted">Sign in to your account</p>
                        </div>

                        <div id="messageError"></div>

                        <!-- Login Form -->
                        <form id="login-form" method="POST" action="{{ url('signin') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="login_id" class="form-label">User ID or Email<span class="text-danger">
                                        *</span></label>
                                <input type="text" class="form-control" id="login_id" name="login_id"
                                    placeholder="Enter your User ID or Email" value="" required>
                                <span class="text-danger error" id="login_id_error"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password<span class="text-danger"> *</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Enter your password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fa fa-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                                <span class="text-danger error" id="password_error"></span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">
                                        Remember me
                                    </label>
                                </div>
                                <a href="{{ url('forgot-password') }}" class="theme-link">
                                    Forgot Password?
                                </a>
                            </div>

                            <button type="submit" class="btn theme-btn w-100 mb-3">
                                <span class="btn-text">Sign In</span>
                                <span class="btn-loading" style="display: none;">
                                    <i class="fa fa-spinner fa-spin"></i> Signing In...
                                </span>
                            </button>

                            <div class="text-center">
                                <p class="mb-0">Don't have an account?
                                    <a href="{{ url('signup') }}" class="theme-link">Sign up here</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .signin-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .signin-form-wrapper {
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
        }

        .theme-link:hover {
            color: #004d2c !important;
            text-decoration: underline;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-outline-secondary {
            border: 2px solid #e9ecef;
            border-left: none;
            border-radius: 0 8px 8px 0;
            color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            border-color: #667eea;
            color: #667eea;
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .error {
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }

        /* Loading animation */
        .btn-loading {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

       
    </style>

    @push('script')
        <script>
            $(function() {
                // Toggle password visibility
                $('#togglePassword').on('click', function() {
                    const passwordField = $('#password');
                    const eyeIcon = $('#eyeIcon');

                    if (passwordField.attr('type') === 'password') {
                        passwordField.attr('type', 'text');
                        eyeIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        passwordField.attr('type', 'password');
                        eyeIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                });

                // Clear errors on input
                $('input').on('input', function() {
                    const fieldName = $(this).attr('name');
                    $('#' + fieldName + '_error').html('');
                });

                // Login form submission with AJAX
                $('#login-form').on('submit', function(e) {
                    e.preventDefault();

                    $('.error').html('');

                   
                    const loginId = $('#login_id').val().trim();
                    const password = $('#password').val();

                    if (!loginId) {
                        $('#login_id_error').html('User ID or Email is required');
                        return;
                    }

                    if (!password || password.length < 1) {
                        $('#password_error').html('Password is required');
                        return;
                    }

                    const submitBtn = $(this).find('button[type="submit"]');
                    const btnText = submitBtn.find('.btn-text');
                    const btnLoading = submitBtn.find('.btn-loading');

                    btnText.hide();
                    btnLoading.show();
                    submitBtn.prop('disabled', true);

                    
                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        success: function(data) {
                            if (data.status) {
                                alertMsg(true, data.msg || 'Login successful!');
                                setTimeout(function() {
                                    window.location.href = data.redirect ||
                                        '{{ url('/') }}';
                                }, 1000);
                            } else {
                                alertMsg(false, data.msg || 'Login failed. Please try again.');
                            }
                        },
                        error: function(xhr) {
                            if (xhr.responseJSON?.errors) {
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    $('#' + key + '_error').html(value[0]);
                                });
                            } else if (xhr.responseJSON?.msg) {
                                alertMsg(false, xhr.responseJSON.msg);
                            } else {
                                alertMsg(false, 'Something went wrong. Please try again.');
                            }
                        },
                        complete: function() {
                            btnText.show();
                            btnLoading.hide();
                            submitBtn.prop('disabled', false);
                        }
                    });
                });

               
                
            });
        </script>
    @endpush
@endsection
