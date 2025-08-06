<style>
    /* KYC Multi-Step Form Styles */
    .step-indicator {
        text-align: center;
        padding: 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .form-label {
        margin-bottom: .1rem !important;
    }

    .step-indicator.active {
        background-color: rgba(1, 96, 56, 0.1);
        color: var(--brand-green);
    }

    .step-indicator.completed {
        background-color: rgba(241, 97, 74, 0.1);
        color: var(--brand-orange);
    }

    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .step-indicator.active .step-number {
        background-color: var(--brand-green);
        color: white;
    }

    .step-indicator.completed .step-number {
        background-color: var(--brand-orange);
        color: white;
    }

    .step-title {
        font-size: 0.875rem;
        font-weight: 500;
    }

    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
    }

    .upload-card {
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .upload-card:hover {
        border-color: var(--brand-green);
        background-color: rgba(1, 96, 56, 0.05);
    }

    .upload-icon {
        margin-bottom: 1rem;
    }

    .upload-preview {
        max-width: 100%;
        max-height: 150px;
        border-radius: 8px;
        overflow: hidden;
    }

    .upload-preview img {
        width: 100%;
        height: auto;
        object-fit: cover;
    }

    .review-section {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 1.5rem;
    }

    .review-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #dee2e6;
    }

    .review-item:last-child {
        border-bottom: none;
    }

    .review-item .label {
        font-weight: 500;
        color: #6c757d;
    }

    .review-item .value {
        font-weight: 600;
        color: var(--brand-green);
    }

    .form-navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 2rem;
        border-top: 1px solid #dee2e6;
    }

    .form-navigation .btn {
        min-width: 140px;
    }

    /* Validation Styles */
    .is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }

    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .form-control.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .form-select.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
</style>
<div class="tab-panel active edit-form-open" id="kyc">
    <h3 class="tab-title account-top">KYC Verification Process</h3>
    <div class="tab-content-body">
        <!-- Multi-Step Form -->
        <div class="kyc-form-container">
            <!-- Progress Bar -->
            <div class="progress mb-4" style="height: 8px;">
                <div class="progress-bar bg-brand-green" id="kycProgress" role="progressbar" style="width: 25%"></div>
            </div>

            <!-- Step Indicators -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="step-indicator active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-title">Personal Information</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-indicator" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-title">Bank Details</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-indicator" data-step="3">
                        <div class="step-number">3</div>
                        <div class="step-title">Upload KYC Docs</div>
                    </div>
                </div>
            </div>

            <!-- Form Steps -->

            <!-- Message Container -->
            <div id="messageError" style="display: none;"></div>
            <!-- Step 1: Personal Information -->
            <div class="form-step active" id="step1">
                <h6 class="text-brand-green mb-3"><i class="fa-solid fa-user me-2"></i>Personal Information</h6>
                <form id="personalInfoForm" enctype="multipart/form-data"
                    action="{{ url('distributor/kyc/personal-details') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">User ID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ $user->user_id }}" readonly required>
                            <span class="text-danger error" id="user_id_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Referral ID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ $user->ref_id ?? '' }}" readonly
                                required>
                            <span class="text-danger error" id="ref_id_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ $kyc->name ?? '' }}"
                                required>
                            <span class="text-danger error" id="name_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required
                                value="{{ $user->email ?? '' }}">
                            <span class="text-danger error" id="email_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">PIN Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="pincode" required
                                value="{{ $kyc->pincode ?? '' }}">
                            <span class="text-danger error" id="pincode_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Locality <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="locality" required
                                value="{{ $kyc->locality ?? '' }}">
                            <span class="text-danger error" id="locality_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="district" required
                                value="{{ $kyc->district ?? '' }}">
                            <span class="text-danger error" id="district_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">State <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="state" required
                                value="{{ $kyc->state ?? '' }}">
                            <span class="text-danger error" id="state_error"></span>
                        </div>
                        <div class="col-12 mb-2">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="3">{{ $kyc->address ?? '' }}</textarea>
                            <span class="text-danger error" id="address_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" name="mobile" required
                                value="{{ $kyc->mobile ?? '' }}">
                            <span class="text-danger error" id="mobile_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Occupation <span class="text-danger">*</span></label>
                            <select class="form-select" name="occupation" required>
                                <option value="">Select Occupation....</option>
                                <option value="teacher" @selected(!empty($kyc->work) && $kyc->work == 'teacher')>Teacher</option>
                                <option value="govt_job" @selected(!empty($kyc->work) && $kyc->work == 'govt_job')>Govt. Job</option>
                                <option value="electrician" @selected(!empty($kyc->work) && $kyc->work == 'electrician')>Electrician</option>
                                <option value="plumber" @selected(!empty($kyc->work) && $kyc->work == 'plumber')>Plumber</option>
                                <option value="labour" @selected(!empty($kyc->work) && $kyc->work == 'labour')>Labour</option>
                                <option value="carpenter" @selected(!empty($kyc->work) && $kyc->work == 'carpenter')>Carpenter</option>
                                <option value="penter" @selected(!empty($kyc->work) && $kyc->work == 'penter')>Penter</option>
                                <option value="advocate" @selected(!empty($kyc->work) && $kyc->work == 'advocate')>Advocate</option>
                                <option value="doctor" @selected(!empty($kyc->work) && $kyc->work == 'doctor')>Doctor</option>
                                <option value="farmer" @selected(!empty($kyc->work) && $kyc->work == 'farmer')>Farmer</option>
                                <option value="pest_control" @selected(!empty($kyc->work) && $kyc->work == 'pest_control')>Pest Control</option>
                                <option value="other" @selected(!empty($kyc->work) && $kyc->work == 'other')>Other</option>
                            </select>
                            <span class="text-danger error" id="occupation_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="dob" required
                                value="{{ $kyc->dob ?? '' }}" max="{{ date('Y-m-d') }}"
                                min="{{ date('Y-m-d', strtotime('-100 years')) }}">
                            <span class="text-danger error" id="dob_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Nominee <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nominee" required
                                value="{{ $kyc->nominee ?? '' }}">
                            <span class="text-danger error" id="nominee_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Relation <span class="text-danger">*</span></label>
                            <select class="form-select" name="relation" required>
                                <option value="">Select Relation</option>
                                <option value="spouse" @selected(!empty($kyc->relation) && $kyc->relation == 'spouse')>Spouse</option>
                                <option value="son" @selected(!empty($kyc->relation) && $kyc->relation == 'son')>Son</option>
                                <option value="daughter" @selected(!empty($kyc->relation) && $kyc->relation == 'daughter')>Daughter</option>
                                <option value="father" @selected(!empty($kyc->relation) && $kyc->relation == 'father')>Father</option>
                                <option value="mother" @selected(!empty($kyc->relation) && $kyc->relation == 'mother')>Mother</option>
                                <option value="brother" @selected(!empty($kyc->relation) && $kyc->relation == 'brother')>Brother</option>
                                <option value="sister" @selected(!empty($kyc->relation) && $kyc->relation == 'sister')>Sister</option>
                                <option value="other" @selected(!empty($kyc->relation) && $kyc->relation == 'other')>Other</option>
                            </select>
                            <span class="text-danger error" id="relation_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <select class="form-select" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male" @selected(!empty($kyc->gender) && $kyc->gender == 'male')>Male</option>
                                <option value="female" @selected(!empty($kyc->gender) && $kyc->gender == 'female')>Female</option>
                                <option value="other" @selected(!empty($kyc->gender) && $kyc->gender == 'other')>Other</option>
                            </select>
                            <span class="text-danger error" id="gender_error"></span>
                        </div>
                    </div>
                    <div class="form-navigation mt-4">

                        <button type="submit" class="btn btn-brand-green" form="personalInfoForm">
                            Save & Continue<i class="fa-solid fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 2: Bank Details -->
            <div class="form-step" id="step2">
                <h6 class="text-brand-green mb-3"><i class="fa-solid fa-university me-2"></i>Bank Details</h6>
                <form id="bankInfoForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="holder_name" required
                                value="{{ $kyc->holder_name ?? '' }}">
                            <span class="text-danger error" id="holder_name_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Account Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="account_no" required
                                value="{{ $kyc->ac_number ?? '' }}">
                            <span class="text-danger error" id="account_no_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Confirm Account Number <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="confirm_account_no" required
                                value="{{ $kyc->ac_number ?? '' }}">
                            <span class="text-danger error" id="confirm_account_no_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">IFSC Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ifsc_code" placeholder="ABCD0001234"
                                required value="{{ $kyc->ifsc_code ?? '' }}">
                            <span class="text-danger error" id="ifsc_code_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Branch <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="branch" required
                                value="{{ $kyc->branch ?? '' }}">
                            <span class="text-danger error" id="branch_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="bank" required
                                value="{{ $kyc->bank ?? '' }}">
                            <span class="text-danger error" id="bank_error"></span>
                        </div>
                    </div>
                    <div class="form-navigation mt-4">
                        <div></div>
                        <button type="submit" class="btn btn-brand-green" form="bankInfoForm">
                            Save & Continue<i class="fa-solid fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 3: Upload KYC Documents -->
            <div class="form-step" id="step3">
                <h6 class="text-brand-green mb-3"><i class="fa-solid fa-file-upload me-2"></i>Upload KYC Documents
                </h6>
                <form id="kycDocsForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">PAN Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="pan_numer"
                                value="{{ $kyc->pan_number ?? '' }}" placeholder="ABCDE1234F" required>
                            <span class="text-danger error" id="pan_no_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="upload-card">
                                <div class="upload-icon">
                                    <i class="fa-solid fa-id-card fa-3x text-brand-green"></i>
                                </div>
                                <h6 class="mt-3">PAN Document</h6>
                                <p class="text-muted small">Upload clear image of your PAN card</p>
                                <input type="file" class="form-control" name="pan_docs" accept="image/*"
                                    required>
                                <div class="upload-preview mt-2" id="panDocsPreview"></div>
                            </div>
                            <span class="text-danger error" id="pan_docs_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Aadhaar Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="aadhar_no" placeholder="1234 5678 9012"
                                value="{{ $kyc->aadhaar_no ?? '' }}" required>
                            <span class="text-danger error" id="aadhar_no_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="upload-card">
                                <div class="upload-icon">
                                    <i class="fa-solid fa-address-card fa-3x text-brand-orange"></i>
                                </div>
                                <h6 class="mt-3">Aadhaar Document</h6>
                                <p class="text-muted small">Upload front and back of Aadhaar card</p>
                                <input type="file" class="form-control" name="aadhar_docs" accept="image/*"
                                    required>
                                <div class="upload-preview mt-2" id="aadharDocsPreview"></div>
                            </div>
                            <span class="text-danger error" id="aadhar_docs_error"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="upload-card">
                                <div class="upload-icon">
                                    <i class="fa-solid fa-camera fa-3x text-brand-green"></i>
                                </div>
                                <h6 class="mt-3">Selfie Photo</h6>
                                <p class="text-muted small">Take a clear selfie with your ID</p>
                                <input type="file" class="form-control" name="selfi" accept="image/*" required>
                                <div class="upload-preview mt-2" id="selfiPreview"></div>
                            </div>
                            <span class="text-danger error" id="selfi_error"></span>
                        </div>
                    </div>
                    <div class="form-navigation mt-4">
                        <div></div>
                        <button type="submit" class="btn btn-brand-orange" form="kycDocsForm">
                            <i class="fa-solid fa-paper-plane me-2"></i>Submit KYC
                        </button>
                    </div>
                </form>
            </div>
            <!-- Step Navigation Buttons -->
            <div class="form-navigation mt-4">
                <button type="button" class="btn btn-outline-brand-green" id="prevBtn">
                    <i class="fa-solid fa-arrow-left me-2"></i>Previous
                </button>
                <div></div>
            </div>
            </form>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        $(document).ready(function() {
            let currentStep = 1;
            const totalSteps = 3;

            // Initialize form
            updateStepIndicators();
            updateProgressBar();
            // Previous button functionality
            $('#prevBtn').on('click', function() {
                if (currentStep > 1) {
                    currentStep--;
                    updateForm();
                }
            });
            // Account number confirmation validation
            $('input[name="confirm_account_no"]').on('input', function() {
                const accountNo = $('input[name="account_no"]').val();
                const confirmAccountNo = $(this).val();

                if (accountNo && confirmAccountNo && accountNo !== confirmAccountNo) {
                    $(this)[0].setCustomValidity('Account numbers do not match');
                    $(this).addClass('is-invalid');
                } else {
                    $(this)[0].setCustomValidity('');
                    $(this).removeClass('is-invalid');
                }
            });

            // File upload preview
            setupFileUploads();

            function updateForm() {
                $('.form-step').removeClass('active');
                $('#step' + currentStep).addClass('active');
                const $prevBtn = $('#prevBtn');
                if (currentStep === 1) {
                    $prevBtn.hide();
                } else {
                    $prevBtn.show();
                }

                updateStepIndicators();
                updateProgressBar();
            }

            function updateStepIndicators() {
                $('.step-indicator').each(function(index) {
                    const stepNumber = index + 1;
                    $(this).removeClass('active completed');

                    if (stepNumber === currentStep) {
                        $(this).addClass('active');
                    } else if (stepNumber < currentStep) {
                        $(this).addClass('completed');
                    }
                });
            }

            function updateProgressBar() {
                const progress = (currentStep / totalSteps) * 100;
                $('#kycProgress').css('width', progress + '%');
            }


            function setupFileUploads() {
                $('input[type="file"]').on('change', function() {
                    const file = this.files[0];
                    const previewId = $(this).attr('name') + 'Preview';
                    const $preview = $('#' + previewId);

                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $preview.html(`<img src="${e.target.result}" alt="Preview">`);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Real-time validation for better UX
            $('input, select, textarea').on('blur', function() {
                const $field = $(this);
                if ($field.attr('required') && !$field.val().trim()) {
                    $field.addClass('is-invalid');
                    $('#' + $field.attr('name') + '_error').text('This field is required');
                } else {
                    $field.removeClass('is-invalid');
                    $('#' + $field.attr('name') + '_error').text('');
                }
            });
            // Clear validation errors on input
            $('input, select, textarea').on('input change', function() {
                const $field = $(this);
                if ($field.hasClass('is-invalid') && $field.val().trim()) {
                    $field.removeClass('is-invalid');
                    $('#' + $field.attr('name') + '_error').text('');
                }
            });

            // Clear file validation errors
            $('input[type="file"]').on('change', function() {
                const $field = $(this);
                if ($field.hasClass('is-invalid')) {
                    $field.removeClass('is-invalid');
                    $('#' + $field.attr('name') + '_error').text('');
                }
            });

            // Step indicator click functionality
            $('.step-indicator').on('click', function() {
                const stepNumber = $(this).data('step');
                if (stepNumber <= currentStep) {
                    currentStep = stepNumber;
                    updateForm();
                }
            });

            // Add tooltips to step indicators
            $('.step-indicator').each(function() {
                const stepNumber = $(this).data('step');
                const stepTitle = $(this).find('.step-title').text();
                $(this).attr('title', `Step ${stepNumber}: ${stepTitle}`);
            });


            $('form#personalInfoForm').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData($('#personalInfoForm')[0]);
                const $submitBtn = $('button[form="personalInfoForm"]');
                const originalText = $submitBtn.html();
                $submitBtn.html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Saving...').prop(
                    'disabled', true);

                // Clear previous errors
                $('.error').text('');

                $.ajax({
                    url: '{{ url('distributor/kyc/personal-details') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('Success response:', response);
                        if (response.status) {
                            currentStep = 2;
                            updateForm();
                        }
                        sweetalert(response.status, response.msg);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.validation;
                            $.each(errors, function(field, messages) {
                                $('#' + field + '_error').text(messages[0]);
                            });
                        } else {
                            sweetalert(false, 'Something went wrong! Please try again.');
                        }
                    },
                    complete: function() {
                        $submitBtn.html(originalText).prop('disabled', false);
                    }
                });
            });

            $('form#bankInfoForm').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData($('#bankInfoForm')[0]);
                const $submitBtn = $('button[form="bankInfoForm"]');
                const originalText = $submitBtn.html();
                $submitBtn.html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Saving...').prop(
                    'disabled', true);

                // Clear previous errors
                $('.error').text('');

                $.ajax({
                    url: '{{ url('distributor/kyc/bank-details') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status) {
                            currentStep = 3;
                            updateForm();
                        }
                        sweetalert(response.status, response.msg);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.validation;
                            $.each(errors, function(field, messages) {
                                $('#' + field + '_error').text(messages[0]);
                            });
                        } else {
                            sweetalert(false, 'Something went wrong! Please try again.');
                        }
                    },
                    complete: function() {
                        $submitBtn.html(originalText).prop('disabled', false);
                    }
                });
            });

            $('form#kycDocsForm').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData($('#kycDocsForm')[0]);
                const $submitBtn = $('button[form="kycDocsForm"]');
                const originalText = $submitBtn.html();
                $submitBtn.html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Submitting...').prop(
                    'disabled',
                    true);

                // Clear previous errors
                $('.error').text('');

                $.ajax({
                    url: '{{ url('distributor/kyc/documents') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        sweetalert(response.status, response.msg);
                        if (response.status) {
                            showSuccessMessage();
                            $('#kycDocsForm')[0].reset();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.validation;
                            $.each(errors, function(field, messages) {
                                $('#' + field + '_error').text(messages[0]);
                            });
                        } else {
                            sweetalert(false, 'Something went wrong! Please try again.');
                        }
                    },
                    complete: function() {
                        $submitBtn.html(originalText).prop('disabled', false);
                    }
                });
            });


            function showSuccessMessage() {
                const successHtml = `
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fa-solid fa-check-circle fa-5x text-brand-green"></i>
                        </div>
                        <h4 class="text-brand-green mb-3">KYC Submitted Successfully!</h4>
                        <p class="text-muted">Your KYC application has been submitted and is under review. We'll notify you once the verification is complete.</p>
                        <button class="btn btn-brand-green mt-3" onclick="location.reload()">
                            <i class="fa-solid fa-refresh me-2"></i>Submit Another KYC
                        </button>
                    </div>
                `;
                $('.kyc-form-container').html(successHtml);
            }
        });
    </script>
@endpush
