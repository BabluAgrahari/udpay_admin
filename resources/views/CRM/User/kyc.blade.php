@extends('CRM.Layout.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row card-header">
                <div class="col-md-10">
                    <h5>KYC Management - {{ $user->full_name ?? ($user->first_name . ' ' . $user->last_name) }}</h5>
                </div>
                <div class="col-md-2 text-right">
                    <a href="{{ url('crm/user') }}" class="btn btn-outline-warning btn-sm">
                        <i class='bx bx-left-arrow-circle' style="line-height: 0"></i>&nbsp;Back
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div id="message"></div>

                <!-- KYC Status Overview -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h6><i class="bx bx-info-circle"></i> KYC Status Overview</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Overall Status:</strong>
                                    @if($user->kyc)
                                        @if($user->kyc->kyc_flag == 1)
                                            <span class="badge bg-success">Verified</span>
                                        @else
                                            <span class="badge bg-warning">Pending Verification</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Not Submitted</span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <strong>Personal Details:</strong>
                                    @if($user->kyc && $user->kyc->personal_flag == 1)
                                        <span class="badge bg-success">Complete</span>
                                    @else
                                        <span class="badge bg-danger">Incomplete</span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <strong>Bank Details:</strong>
                                    @if($user->kyc && $user->kyc->bank_flag == 1)
                                        <span class="badge bg-success">Complete</span>
                                    @else
                                        <span class="badge bg-danger">Incomplete</span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <strong>Documents:</strong>
                                    @if($user->kyc && ($user->kyc->pan_front || $user->kyc->aadhar_front))
                                        <span class="badge bg-success">Uploaded</span>
                                    @else
                                        <span class="badge bg-danger">Missing</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="kycForm" method="post" action="{{ url('crm/user/' . $user->_id . '/kyc') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Personal Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary"><i class="bx bx-user-circle"></i> Personal Information</h6>
                            <hr>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Full Name <span class="text-danger"><b>*</b></span></label>
                            <input type="text" class="form-control" name="name" id="name"
                                value="{{ $user->kyc->name ?? '' }}" placeholder="Full Name">
                            <span class="text-danger" id="name_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Mobile Number <span class="text-danger"><b>*</b></span></label>
                            <input type="text" class="form-control" name="mobile_no" id="mobile_no"
                                value="{{ $user->kyc->mobile_no ?? '' }}" placeholder="Mobile Number">
                            <span class="text-danger" id="mobile_no_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Date of Birth <span class="text-danger"><b>*</b></span></label>
                            <input type="date" class="form-control" name="dob" id="dob"
                                value="{{ !empty($user->kyc->dob)  ? date('Y-m-d', strtotime($user->kyc->dob)) : '' }}">
                            <span class="text-danger" id="dob_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Gender <span class="text-danger"><b>*</b></span></label>
                            <select class="form-select" name="gender" id="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ ($user->kyc->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ ($user->kyc->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ ($user->kyc->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <span class="text-danger" id="gender_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>PAN Number</label>
                            <input type="text" class="form-control" name="pan_no" id="pan_no"
                                value="{{ $user->kyc->pan_no ?? '' }}" placeholder="PAN Number">
                            <span class="text-danger" id="pan_no_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Aadhar Number</label>
                            <input type="text" class="form-control" name="aadhar_no" id="aadhar_no"
                                value="{{ $user->kyc->aadhar_no ?? '' }}" placeholder="Aadhar Number">
                            <span class="text-danger" id="aadhar_no_msg"></span>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary"><i class="bx bx-map"></i> Address Information</h6>
                            <hr>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Address <span class="text-danger"><b>*</b></span></label>
                            <textarea class="form-control" name="address" id="address" rows="3" placeholder="Complete Address">{{ $user->kyc->address ?? '' }}</textarea>
                            <span class="text-danger" id="address_msg"></span>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>State <span class="text-danger"><b>*</b></span></label>
                            <input type="text" class="form-control" name="state" id="state"
                                value="{{ $user->kyc->state ?? '' }}" placeholder="State">
                            <span class="text-danger" id="state_msg"></span>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>District <span class="text-danger"><b>*</b></span></label>
                            <input type="text" class="form-control" name="district" id="district"
                                value="{{ $user->kyc->district ?? '' }}" placeholder="District">
                            <span class="text-danger" id="district_msg"></span>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Locality <span class="text-danger"><b>*</b></span></label>
                            <input type="text" class="form-control" name="locality" id="locality"
                                value="{{ $user->kyc->locality ?? '' }}" placeholder="Locality">
                            <span class="text-danger" id="locality_msg"></span>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Pincode <span class="text-danger"><b>*</b></span></label>
                            <input type="number" class="form-control" name="pincode" id="pincode"
                                value="{{ $user->kyc->pincode ?? '' }}" placeholder="Pincode">
                            <span class="text-danger" id="pincode_msg"></span>
                        </div>
                    </div>

                    <!-- Bank Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary"><i class="bx bx-bank"></i> Bank Information</h6>
                            <hr>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Bank Name</label>
                            <input type="text" class="form-control" name="bank" id="bank"
                                value="{{ $user->kyc->bank ?? '' }}" placeholder="Bank Name">
                            <span class="text-danger" id="bank_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Account Number</label>
                            <input type="text" class="form-control" name="account_number" id="account_number"
                                value="{{ $user->kyc->account_number ?? '' }}" placeholder="Account Number">
                            <span class="text-danger" id="account_number_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>IFSC Code</label>
                            <input type="text" class="form-control" name="ifsc_code" id="ifsc_code"
                                value="{{ $user->kyc->ifsc_code ?? '' }}" placeholder="IFSC Code">
                            <span class="text-danger" id="ifsc_code_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Branch</label>
                            <input type="text" class="form-control" name="branch" id="branch"
                                value="{{ $user->kyc->branch ?? '' }}" placeholder="Branch">
                            <span class="text-danger" id="branch_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Occupation</label>
                            <input type="text" class="form-control" name="work" id="work"
                                value="{{ $user->kyc->work ?? '' }}" placeholder="Occupation">
                            <span class="text-danger" id="work_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>ID Proof Type</label>
                            <input type="text" class="form-control" name="id_proof" id="id_proof"
                                value="{{ $user->kyc->id_proof ?? '' }}" placeholder="ID Proof Type">
                            <span class="text-danger" id="id_proof_msg"></span>
                        </div>
                    </div>

                    <!-- Nominee Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary"><i class="bx bx-user-plus"></i> Nominee Information</h6>
                            <hr>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nominee Name</label>
                            <input type="text" class="form-control" name="nominee" id="nominee"
                                value="{{ $user->kyc->nominee ?? '' }}" placeholder="Nominee Name">
                            <span class="text-danger" id="nominee_msg"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Relation</label>
                            <input type="text" class="form-control" name="relation" id="relation"
                                value="{{ $user->kyc->relation ?? '' }}" placeholder="Relation">
                            <span class="text-danger" id="relation_msg"></span>
                        </div>
                    </div>

                    <!-- Document Upload -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary"><i class="bx bx-upload"></i> Document Upload</h6>
                            <hr>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>PAN Front</label>
                            <input type="file" class="form-control" name="pan_front" id="pan_front" accept="image/*,.pdf">
                            @if($user->kyc && $user->kyc->pan_front)
                                <small class="text-success">File uploaded: {{ basename($user->kyc->pan_front) }}</small>
                            @endif
                            <span class="text-danger" id="pan_front_msg"></span>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Aadhar Front</label>
                            <input type="file" class="form-control" name="aadhar_front" id="aadhar_front" accept="image/*,.pdf">
                            @if($user->kyc && $user->kyc->aadhar_front)
                                <small class="text-success">File uploaded: {{ basename($user->kyc->aadhar_front) }}</small>
                            @endif
                            <span class="text-danger" id="aadhar_front_msg"></span>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Aadhar Back</label>
                            <input type="file" class="form-control" name="aadhar_back" id="aadhar_back" accept="image/*,.pdf">
                            @if($user->kyc && $user->kyc->aadhar_back)
                                <small class="text-success">File uploaded: {{ basename($user->kyc->aadhar_back) }}</small>
                            @endif
                            <span class="text-danger" id="aadhar_back_msg"></span>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Bank Document</label>
                            <input type="file" class="form-control" name="bank_doc" id="bank_doc" accept="image/*,.pdf">
                            @if($user->kyc && $user->kyc->bank_doc)
                                <small class="text-success">File uploaded: {{ basename($user->kyc->bank_doc) }}</small>
                            @endif
                            <span class="text-danger" id="bank_doc_msg"></span>
                        </div>
                    </div>

                    <div class="mb-4 col-md-12 text-center">
                        <button type="submit" class="btn btn-sm btn-outline-primary" id="saveBtn">Save KYC Details</button>
                    </div>
                </form>

                <!-- KYC Verification Section -->
                @if($user->kyc)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="text-primary"><i class="bx bx-check-shield"></i> KYC Verification</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-success" onclick="updateKycStatus(1)">
                                            <i class="bx bx-check"></i> Approve KYC
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-danger" onclick="updateKycStatus(0)">
                                            <i class="bx bx-x"></i> Reject KYC
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('script')
        <script>
            $('form#kycForm').submit(function (e) {
                e.preventDefault();
                formData = new FormData(this);
                let url = $(this).attr('action');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#saveBtn').html(
                            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Saving...`
                        ).attr('disabled', true);
                    },
                    success: function (res) {
                        $('#saveBtn').html('Save KYC Details').removeAttr('disabled');
                        
                        // Clear previous error messages
                        $('span.text-danger').html('');
                        
                        if (res.validation) {
                            $.each(res.validation, (index, msg) => {
                                $(`#${index}_msg`).html(`${msg}`);
                            });
                            return false;
                        }

                        alertMsg(res.status, res.msg, 3000);
                        
                        if (res.status) {
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }
                    },
                    error: function() {
                        $('#saveBtn').html('Save KYC Details').removeAttr('disabled');
                        alertMsg(false, 'Something went wrong!', 3000);
                    }
                });
            });

            function updateKycStatus(status) {
                const action = status ? 'approve' : 'reject';
                if (confirm(`Are you sure you want to ${action} this KYC?`)) {
                    $.ajax({
                        url: "{{ url('crm/user/' . $user->_id . '/kyc-status') }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            kyc_flag: status
                        },
                        success: function(response) {
                            if (response.status) {
                                alertMsg(true, response.msg, 3000);
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                alertMsg(false, response.msg, 3000);
                            }
                        },
                        error: function() {
                            alertMsg(false, 'Something went wrong!', 3000);
                        }
                    });
                }
            }
        </script>
    @endpush
@endsection 