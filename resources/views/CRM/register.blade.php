
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{asset('')}}assets/" data-template="vertical-menu-template-free" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{config('app.name')}} - Register</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{user()->svg_icon ?? ''}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{asset('')}}assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{asset('')}}assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{asset('')}}assets/vendor/css/theme-default.css"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{asset('')}}assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{asset('')}}assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{asset('')}}assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="{{asset('')}}assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{asset('')}}assets/js/config.js"></script>

    <!-- <style>
        .wrapper {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            justify-items: space-between;
        }

        .wrapper input.field {
            width: 30px;
            line-height: 32px;
            font-size: 14px;
            border: none;
            background-color: rgb(241, 241, 241);
            border-radius: 5px;
            text-align: center;
            text-transform: uppercase;
            color: #222;
            /* margin-bottom: 25px; */
        }

        .wrapper input.field:focus {
            outline: none;
        }


        span {
            font-size: 13px;
            color: #001A16;
            padding: 2px;
        }

        /* Loader Overlay */
        .loader-overlay {
            display: none;
            /* Hidden by default */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            /* Semi-transparent white background */
            backdrop-filter: blur(5px);
            /* Blur effect */
            z-index: 1000;
            /* Ensure it's on top */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Loader Animation */
        .loader {
            border: 5px solid #f3f3f3;
            /* Light grey */
            border-top: 5px solid #3498db;
            /* Blue */
            border-radius: 50%;
            width: 50px;
            height: 50px;
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

        /* Ensure the form container is relatively positioned */
        .formcontainer {
            position: relative;
        }

        .profile-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
        }

        .profile-photo img {
            width: 100%;
            object-fit: cover;
            height: 100%;
            border-radius: 50%;
            overflow: hidden;

        }


        .wrapper {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            justify-items: space-between;
        }

        .wrapper input.field {
            width: 35px;
            line-height: 40px;
            font-size: 16px;
            border: none;
            background-color: rgb(241, 241, 241);
            border-radius: 5px;
            text-align: center;
            text-transform: uppercase;
            color: #222;
            /* margin-bottom: 25px; */
        }

        .wrapper input.field:focus {
            outline: none;
        }

        button.resend {
            margin-top: 5px;
            background-color: transparent;
            border: none;
            font-size: 13px;
            font-weight: 400;
            color: #222;
            cursor: pointer;
        }
    </style> -->
</head>

<body>
    <!-- Content -->

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner" style="max-width:700px !important">
                <!-- Register -->
                <div class="card px-sm-8 px-0">
                    <div class="card-body formcontainer">
                        <!-- Logo -->
                        <div id="formLoader" class="d-none loader-overlay">
                            <div class="loader"></div>
                        </div>

                        <div class="app-brand justify-content-center">
                            <a href="index.html" class="app-brand-link gap-2">
                                <span class="text-center">
                                    <img src="{{user()->logo ?? ''}}" style="width:60%;">
                                </span>
                                <!-- <span class="app-brand-text demo text-heading fw-bold">Pay WebDuniya</span> -->
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-1">Welcome to {{config('app.name')}}! 🚀</h4>
                        @include('CRM.message')
                        <div class="row">
                            <div class="col-md-12" id="msgDiv"></div>
                        </div>
                        <!-- <p class="mb-6">Please sign-in to your account and start the adventure</p> -->

                        <form id="formAuthentication" class="mb-6" action="{{url('register')}}" method="post">
                            @csrf

                            <div class="row">

                                <div class="mb-2 col-md-6">
                                    <input type="hidden" name="gst_verified" id="gst_verified">
                                    <label for="email" class="form-label">GST No <span
                                            class="text-danger"><b>*</b></span></label>
                                    <input type="text" class="form-control actionField" id="gst_no" _val="gst"
                                        name="gst_no" placeholder="GST No" value="{{old('gst_no')}}" autofocus />
                                    @error('gst_no')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                    <span id="gst_noMsg" class="custom-text-danger text-danger"></span>
                                    <span id="gst_verifiedMsg" class="custom-text-danger text-danger"></span>
                                </div>


                                <div class="mb-2 col-md-6">
                                    <label for="email" class="form-label">Business Name <span
                                            class="text-danger"><b>*</b></span></label>
                                    <input type="text" class="form-control" id="business_name" name="business_name"
                                        placeholder="Company Name" value="{{old('business_name')}}" autofocus />
                                    @error('business_name')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <!-- <div class="mb-6 col-md-6">
                                    <label for="email" class="form-label">Business Type</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="business_type"
                                        name="business_type"
                                        placeholder="Business Type"
                                        value="{{old('business_type')}}"
                                        autofocus />
                                    @error('business_type')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div> -->

                                <!-- <div class="mb-6 col-md-6">
                                    <label for="email" class="form-label">Business Phone No <span class="text-danger"><b>*</b></span></label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="business_phone_no"
                                        name="business_phone_no"
                                        placeholder="Business Phone No"
                                        value="{{old('business_phone_no')}}"
                                        autofocus />
                                    @error('business_phone_no')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div> -->

                                <!-- <div class="mb-6 col-md-6">
                                    <label for="email" class="form-label">Business Email <span class="text-danger"><b>*</b></span></label>
                                    <input
                                        type="email"
                                        class="form-control"
                                        id="business_email"
                                        name="business_email"
                                        placeholder="Business Email"
                                        value="{{old('business_email')}}"
                                        autofocus />
                                    @error('business_email')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div> -->

                                <!-- <div class="mb-6 col-md-6">
                                    <label for="email" class="form-label">Business Registration No</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="registration_no"
                                        name="registration_no"
                                        placeholder="Registration No"
                                        value="{{old('registration_no')}}"
                                        autofocus />
                                    @error('registration_no')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div> -->


                                <div class="mb-6 col-md-6">
                                    <label for="email" class="form-label">City <span
                                            class="text-danger"><b>*</b></span></label>
                                    <input type="text" class="form-control" id="business_city" name="business_city"
                                        placeholder="City" value="{{old('business_city')}}" autofocus />
                                    @error('business_city')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-6 col-md-6">
                                    <label for="email" class="form-label">State <span
                                            class="text-danger"><b>*</b></span></label>
                                    <input type="text" class="form-control" id="business_state" name="business_state"
                                        placeholder="State" value="{{old('business_state')}}" autofocus />
                                    @error('business_state')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-6 col-md-6">
                                    <label for="email" class="form-label">Pincode <span
                                            class="text-danger"><b>*</b></span></label>
                                    <input type="number" class="form-control" id="business_pincode"
                                        name="business_pincode" placeholder="Pincode"
                                        value="{{old('business_pincode')}}" autofocus />
                                    @error('business_pincode')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-6 col-md-6">
                                    <label for="email" class="form-label">Business Address <span
                                            class="text-danger"><b>*</b></span></label>
                                    <textarea class="form-control" rows="1" id="business_address"
                                        name="business_address">
                                    {{old('business_address')}}
                                    </textarea>
                                    @error('business_address')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                            </div>


                            <div class="mb-1 col-md-12">
                                <h6>Personal Address</h6>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <small class="text-secondary">* Aadhar & Pancard must be required.</small>
                                </div>

                                <div class="col-12 col-sm-6  mb-2">
                                    <input type="hidden" name="aadhar_verified" id="aadhar_verified">
                                    <label for="address-proff ">Aadhar No <span class="text-danger">*</span></label>
                                    <input type="text" name="aadhar_no" id="aadhar_no" _val="aadhar"
                                        class="send_aadhar_otp signupinput mt-1 form-control"
                                        placeholder="Addhar Card Number">
                                    <span id="aadhar_noMsg" class="custom-text-danger text-danger"></span>
                                    <span id="aadhar_verifiedMsg" class="custom-text-danger text-danger"></span>
                                </div>

                                <div class="col-12 col-sm-6 mb-2">
                                    <div class="d-none" id="otpDiv">
                                        <input type="hidden" id="ref_id" name="ref_id">
                                        <label for="otp">OTP <span>*</span></label>
                                        <div class="wrapper">
                                            <input type="text" class="otpVerify field 1" maxlength="1">
                                            <input type="text" class="otpVerify field 2" maxlength="1">
                                            <input type="text" class="otpVerify field 3" maxlength="1">
                                            <input type="text" class="otpVerify field 4" maxlength="1">
                                            <input type="text" class="otpVerify field 5" maxlength="1">
                                            <input type="text" class="otpVerify field 6" maxlength="1">
                                        </div>
                                    </div>
                                    <span id="aadharVerified"></span>
                                    <span id="otpMsg" class="custom-text-danger text-danger"></span>
                                </div>
                            </div>


                            <div class="row">

                                <div class="col-6 col-sm-6 mb-2  ">
                                    <input type="hidden" name="pancard_verified" id="pancard_verified">
                                    <label for="retailer-name">Pancard <span class="text-danger">*</span></label>
                                    <input type="text" name="pancard" id="pancard" _val="pancard"
                                        class="actionField userField signupinput mt-1 form-control"
                                        placeholder="Pancard">
                                    <div id="pancardNamePerson" class="d-none"></div>
                                    <span id="pancardMsg" class="custom-text-danger text-danger"></span>
                                    <span id="pancard_verifiedMsg" class="custom-text-danger text-danger"></span>
                                </div>

                                <div class="mb-2 col-md-6">
                                    <label for="email" class="form-label">Name <span
                                            class="text-danger"><b>*</b></span></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Name"
                                        value="{{old('name')}}" autofocus />
                                    @error('name')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-6 col-md-6">
                                    <label for="email" class="form-label">Email <span
                                            class="text-danger"><b>*</b></span></label>
                                    <input type="email" class="form-control" id="username" name="username"
                                        placeholder="Enter your email" value="{{old('username')}}" autofocus />
                                    @error('username')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-6 col-md-6">
                                    <label for="" class="form-label">Mobile No <span
                                            class="text-danger"><b>*</b></span></label>
                                    <input type="number" class="form-control" id="mobile_no" name="mobile_no"
                                        placeholder="Mobile No" value="{{old('mobile_no')}}" autofocus />
                                    @error('mobile_no')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="col-6 col-sm-6 mb-6">
                                    <label for="gender">Gender <span class="text-danger">*</span></label>
                                    <select name="gender" id="gender"
                                        class="userField form-select signupinput mt-1 bg-white">
                                        <option value="male">Male </option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <span id="genderMsg" class="custom-text-danger text-danger"></span>
                                </div>

                                <div class="mb-6 col-md-6">
                                    <label for="email" class="form-label">City <span
                                            class="text-danger"><b>*</b></span></label>
                                    <input type="text" class="form-control" id="city" name="city" placeholder="City"
                                        value="{{old('city')}}" autofocus />
                                    @error('city')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-6 col-md-6">
                                    <label for="email" class="form-label">State <span
                                            class="text-danger"><b>*</b></span></label>
                                    <input type="text" class="form-control" id="state" name="state" placeholder="State"
                                        value="{{old('state')}}" autofocus />
                                    @error('state')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-6 col-md-6">
                                    <label for="email" class="form-label">Pincode <span
                                            class="text-danger"><b>*</b></span></label>
                                    <input type="text" class="form-control" id="pincode" name="pincode"
                                        placeholder="Pincode" value="{{old('pincode')}}" autofocus />
                                    @error('pincode')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-6 col-md-12">
                                    <label for="email" class="form-label">Address <span
                                            class="text-danger"><b>*</b></span></label>
                                    <textarea class="form-control" rows="1" id="address" name="address"
                                        placeholder="Address">
                                    {{old('address')}}
                                    </textarea>
                                    @error('address')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                            </div>


                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password">Password <span
                                        class="text-danger"><b>*</b></span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                @error('password')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <button class="btn btn-primary d-grid w-100" type="submit">Register</button>
                            </div>
                        </form>

                        <p class="text-center">
                            <span>Already Have an Account?</span>
                            <a href="{{url('/')}}">
                                <span>Login</span>
                            </a>
                        </p>
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="{{asset('')}}assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{asset('')}}assets/vendor/libs/popper/popper.js"></script>
    <script src="{{asset('')}}assets/vendor/js/bootstrap.js"></script>
    <script src="{{asset('')}}assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{asset('')}}assets/vendor/js/menu.js"></script>
    <script src="{{asset('')}}assets/js/main.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <script>
        // $(document).on('change', '.actionField', function () {
        //     var gstin = $(this).val();
        //     var business_name = $('#business_name').val();
        //     let type = $(this).attr('_val');
        //     var name = $('#name').val();

        //     var url = 'https://ship.subsolution.in/api/verify-gstin';
        //     var data = {
        //         'gstin': gstin,
        //         'business_name': business_name
        //     };
        //     if (type == 'outlet_pan' || type == 'pancard') {
        //         url = 'https://ship.subsolution.in/api/verify-pan';
        //         data = {
        //             'pan': gstin,
        //             'business_name': type == 'pancard' ? name : business_name
        //         };
        //     }

        //     $.ajax({
        //         url: url,
        //         data: data,
        //         dataType: "JSON",
        //         type: "POST",
        //         beforeSend: function () {
        //             $('#formLoader').css('display', 'flex').removeClass('d-none');
        //         },
        //         success: function (res) {
        //             $('#formLoader').addClass('d-none');

        //             if (type == 'gst')
        //                 $('#gst_verified').val('')

        //             if (type == 'outlet_pan') {
        //                 $('#outlet_pan_verified').val(``);
        //                 $('#pancardName').addClass('d-none').html(``);
        //             }

        //             if (type == 'pancard') {
        //                 $('#pancard_verified').val(``);
        //                 $('#pancardNamePerson').html(``);
        //             }

        //             /*Start Validation Error Message*/
        //             $('span.custom-text-danger').html('');
        //             if (res.validation) {
        //                 $.each(res.validation, (ind, vali) => {
        //                     $.each(vali, (index, msg) => {
        //                         $(`#${index}Msg`).html(`${msg}`);
        //                     })
        //                 })
        //                 return false;
        //             }
        //             /*Start Validation Error Message*/

        //             var val = res?.record?.data;
        //             if (res.status) {

        //                 if (type == 'gst') {
        //                     $('#business_name').val(val?.trade_name_of_business || '');
        //                     $('#business_city').val(val?.principal_place_split_address?.city || val?.principal_place_split_address?.location);
        //                     $('#business_state').val(val?.principal_place_split_address?.state || '');
        //                     $('#business_pincode').val(val?.principal_place_split_address?.pincode || '');
        //                     $('#business_address').val(val?.principal_place_address || '');
        //                     $('#gst_verified').val(1);
        //                     $('#gst_noMsg').html(`<label class='text-success'><i class="fa-solid fa-circle-check"></i> GST Certificate no Verifed successfully.</label>`);
        //                 } else if (type == 'outlet_pan') {

        //                     let gst_verified = $('#gst_verified').val();
        //                     $('#pancardName').removeClass('d-none').html(`<small>Name: <b class="text-primary"><small>${val?.registered_name || ''}</small></b></small>`);
        //                     if (!gst_verified || gst_verified != '') {
        //                         $('#business_name').val(val?.registered_name || '');
        //                         $('#business_city').val(val?.address?.city || '');
        //                         $('#business_state').val(val?.address?.state || '');
        //                         $('#business_pincode').val(val?.address?.pincode || '');
        //                         $('#business_address').val(val?.address?.full_address || '');
        //                     }
        //                     $('#outlet_pan_verified').val(1);
        //                     $('#outlet_panMsg').html(`<label class='text-success'><i class="fa-solid fa-circle-check"></i> Pancard Verified Successfully.</label>`);
        //                 } else if (type == 'pancard') {
        //                     $('#pancardNamePerson').removeClass('d-none').html(`<small>Name: <b class="text-primary"><small>${val?.registered_name || ''}</small></b></small>`);
        //                     $('#pancard_verified').val(1);
        //                     $('#pancardMsg').html(`<label class='text-success'><i class="fa-solid fa-circle-check"></i> Pancard Verified Successfully.</label>`);
        //                 }
        //             } else {
        //                 if (type == 'gst') {
        //                     $('#gst_noMsg').html(`<label class="text-danger">${res.msg || 'GST Certificate No Not Verified, Please try again.'}</label>`);
        //                 } else if (type == 'outlet_pan') {
        //                     $('#outlet_panMsg').html(`<label class="text-danger">${res.msg || 'Pancard Not Verified, Please try again.'}</label>`);
        //                 } else if (type == 'pancard') {
        //                     $('#pancardMsg').html(`<label class="text-danger">${res.msg || 'Pancard Not Verified, Please try again.'}</label>`);
        //                 }
        //             }
        //         }
        //     })
        // });


        // $(document).on('change', '.send_aadhar_otp', function () {
        //     var aadhar_no = $(this).val();
        //     var url = 'https://ship.subsolution.in/api/send-aadhar-otp';
        //     $('#submit').attr('disabled', true);
        //     $.ajax({
        //         url: url,
        //         data: {
        //             "aadhar_no": aadhar_no
        //         },
        //         dataType: "JSON",
        //         type: "POST",
        //         beforeSend: function () {
        //             $('#formLoader').css('display', 'flex').removeClass('d-none');
        //         },
        //         success: function (res) {

        //             $('#formLoader').addClass('d-none');
        //             $('#otpDiv').addClass('d-none');
        //             $('#ref_id').val(``);
        //             $('#aadharVerified').html(``);
        //             var val = res?.record;

        //             /*Start Validation Error Message*/
        //             $('span.custom-text-danger').html('');
        //             if (res.validation) {
        //                 $.each(res.validation, (ind, vali) => {
        //                     $.each(vali, (index, msg) => {
        //                         $(`#${index}Msg`).html(`${msg}`);
        //                     })
        //                 })
        //                 return false;
        //             }
        //             /*Start Validation Error Message*/
        //             if (res?.record?.type == 'db_verified') {
        //                 $('#submit').removeAttr('disabled');
        //                 var val = res?.record?.data;
        //                 aadharVerifiedField(val, res);
        //                 return true;
        //             }

        //             if (res.status) {
        //                 $('#submit').removeAttr('disabled');
        //                 $('#ref_id').val(val.ref_id);
        //                 $('#otpDiv').removeClass('d-none');
        //                 $('#aadhar_noMsg').html(`<label class="text-success">${res.msg || 'OTP Send Successfully!'}</label>`)
        //             } else {
        //                 $('#aadhar_noMsg').html(`<label class="text-danger">${res.msg || 'Something went wrong OTP not send, Please try again!'}</label>`)
        //             }

        //         }
        //     })
        // });


        // $('.otpVerify').on('keyup', function () {
        //     let otp = '';
        //     let isValid = true;

        //     $('.otpVerify').each(function () {
        //         let value = $(this).val();
        //         if (value.length !== 1) {
        //             isValid = false;
        //             return false;
        //         }
        //         otp += value;
        //     });

        //     if (isValid && otp.length === 6) {
        //         $('#errorMessage').text(''); // Clear error message
        //         console.log('OTP is valid:', otp);
        //         verifyOTP(otp);
        //     } else {
        //         $('#errorMessage').text('Please enter a valid 6-digit OTP.');
        //     }
        // });


    //     function verifyOTP(otp) {
    //         var ref_id = $('#ref_id').val();
    //         var url = 'https://ship.subsolution.in/api/verify-aadhar-otp';
    //         $.ajax({
    //             url: url,
    //             data: {
    //                 "ref_no": ref_id,
    //                 "otp": otp,
    //                 'aadhar_no': $('#aadhar_no').val()
    //             },
    //             dataType: "JSON",
    //             type: "POST",
    //             beforeSend: function () {
    //                 $('#formLoader').css('display', 'flex').removeClass('d-none');
    //             },
    //             success: function (res) {
    //                 $('#formLoader').addClass('d-none');

    //                 /*Start Validation Error Message*/
    //                 $('span.custom-text-danger').html('');
    //                 if (res.validation) {
    //                     $.each(res.validation, (ind, vali) => {
    //                         $.each(vali, (index, msg) => {
    //                             $(`#otpMsg`).html(`${msg}`);
    //                         })
    //                     })
    //                     return false;
    //                 }
    //                 /*Start Validation Error Message*/
    //                 var val = res?.record?.data;
    //                 $('#otpDiv').removeClass('d-none');
    //                 aadharVerifiedField(val, res);
    //             }
    //         })
    //     }

    //     function aadharVerifiedField(val, res) {
    //         // console.log('res', res);
    //         // $('#userField').val('');
    //         $('#aadhar_verified').val('');

    //         if (res.status) {
    //             $('.userField').val('');
    //             $('#otpDiv').addClass('d-none');
    //             $('#name').val(val?.name || '');

    //             if (val.dob) {
    //                 var parts = val.dob.split("-");
    //                 var formattedDate = parts[2] + "-" + parts[1] + "-" + parts[0];
    //                 $('#date_of_birth').val(formattedDate);
    //             }
    //             $('#gender').val(val.gender == 'M' ? 'male' : (val.gender == 'F' ? 'female' : 'other'));
    //             $('#city').val(val.split_address.subdist || '');
    //             $('#state').val(val.split_address.state || '');
    //             $('#pincode').val(val.split_address.pincode || '');
    //             $('#address').val(val.address);
    //             $('#aadhar_verified').val(1);
    //             $('#aadharVerified').html(`<label class="text-success">${'<i class="fa-solid fa-circle-check"></i> Aadhar Card Verified Successfully!'}</label><br>
    //              <span><small>Name: <small class="text-primary">${val?.name ?? ''}</small></small></span>`)
    //         } else {
    //             $('#otpMsg').html(`<label class="text-danger">${res.msg || 'Something Went wrong, OTP Not Verifed!'}</label>`)
    //         }
    // }

        // $('input.field').on('input', function (e) {
        //     let inputField = $(e.target);
        //     if (inputField.val().length >= 1) {
        //         let nextField = inputField.next();
        //         if (nextField.length) {
        //             // nextField.removeAttr('disabled');
        //             nextField.focus();
        //         }
        //     }
        // });

        // $('input.field').on('keydown', function (e) {
        //     let inputField = $(e.target);
        //     // Handle Backspace key

        //     if (e.key === 'Backspace' && inputField.val().length === 0) {
        //         // $(e.target).attr('disabled', true);
        //         let prevField = inputField.prev();
        //         if (prevField.length) {
        //             prevField.focus(); // Move focus to the previous field
        //             prevField.val(''); // Clear the previous field's value

        //         }
        //     }
        // });
    </script>
</body>

</html>