@extends('Website.Layout.app')
@section('content')
    <!-- Signup Section -->
    <section class="signup-section section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="signup-form-wrapper">
                        <div class="text-center mb-4">
                            <h2 class="section-title">Welcome to Nutra Oshadhi</h2>
                            <p class="text-muted">We're excited to have you on board!</p>
                        </div>

                        <div class="text-center mb-4">
                            <b>UNI ID : {{ $user->alpha_num_uid??'NA' }}</b>
                        </div>

                        <div class="text-center mb-4">
                            Click here to <a href="javascript:void(0)" class="openPopup text-center" data-popup="login1" style="color: #006038; text-decoration: underline;"> login</a>
                        </div>

                        <div class="text-center mt-4">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* .signup-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        } */

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
      
    @endpush
@endsection
