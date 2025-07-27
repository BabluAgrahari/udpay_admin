@extends('CRM.Layout.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row card-header">
                <div class="col-md-10">
                    <h5>Edit User</h5>
                </div>
                <div class="col-md-2 text-right">
                    <a href="{{ url('crm/user') }}" class="btn btn-outline-warning btn-sm">
                        <i class='bx bx-left-arrow-circle' style="line-height: 0"></i>&nbsp;Back
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div id="message"></div>
                <form id="updateForm" method="post" action="{{ url('crm/user/' . $user->_id) }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic User Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary"><i class="bx bx-user"></i> Basic Information</h6>
                            <hr>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>User ID</label>
                            <input type="text" class="form-control" name="user_id" id="user_id"
                                value="{{ $user->user_id ?? '' }}" placeholder="User ID" disabled>
                            <span class="text-danger" id="user_id_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Reference ID</label>
                            <input type="text" class="form-control" name="ref_id" id="ref_id"
                                value="{{ $user->ref_id ?? '' }}" placeholder="Reference ID" disabled>
                            <span class="text-danger" id="ref_id_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Username</label>
                            <input type="text" class="form-control" name="user_nm" id="user_nm"
                                value="{{ $user->user_nm ?? '' }}" placeholder="Username" disabled>
                            <span class="text-danger" id="user_nm_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Alpha Numeric UID</label>
                            <input type="text" class="form-control" name="alpha_num_uid" id="alpha_num_uid"
                                value="{{ $user->alpha_num_uid ?? '' }}" placeholder="Alpha Numeric UID" disabled>
                            <span class="text-danger" id="alpha_num_uid_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>First Name <span class="text-danger"><b>*</b></span></label>
                            <input type="text" class="form-control" name="first_name" id="first_name"
                                value="{{ $user->first_name ?? '' }}" placeholder="First Name">
                            <span class="text-danger" id="first_name_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Last Name <span class="text-danger"><b>*</b></span></label>
                            <input type="text" class="form-control" name="last_name" id="last_name"
                                value="{{ $user->last_name ?? '' }}" placeholder="Last Name">
                            <span class="text-danger" id="last_name_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Email <span class="text-danger"><b>*</b></span></label>
                            <input type="email" class="form-control" name="email" id="email"
                                value="{{ $user->email ?? '' }}" placeholder="Email">
                            <span class="text-danger" id="email_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Mobile <span class="text-danger"><b>*</b></span></label>
                            <input type="text" class="form-control" name="mobile" id="mobile"
                                value="{{ $user->mobile ?? '' }}" placeholder="Mobile">
                            <span class="text-danger" id="mobile_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Date of Birth</label>
                            <input type="date" class="form-control" name="dob" id="dob"
                                value="{{ $user->dob ? date('Y-m-d', strtotime($user->dob)) : '' }}">
                            <span class="text-danger" id="dob_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Gender</label>
                            <select class="form-select" name="gender" id="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <span class="text-danger" id="gender_msg"></span>
                        </div>

                        
                    </div>

                    <!-- System Settings -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary"><i class="bx bx-cog"></i> System Settings</h6>
                            <hr>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Status</label>
                            <select class="form-select" name="isactive" id="isactive">
                                <option value="1" {{ $user->isactive == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $user->isactive == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <span class="text-danger" id="isactive_msg"></span>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Restricted</label>
                            <select class="form-select" name="restricted" id="restricted">
                                <option value="0" {{ ($user->restricted ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                <option value="1" {{ ($user->restricted ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                            </select>
                            <span class="text-danger" id="restricted_msg"></span>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>User Flag</label>
                            <select class="form-select" name="uflag" id="uflag">
                                <option value="0" {{ ($user->uflag ?? 0) == 0 ? 'selected' : '' }}>Inactive</option>
                                <option value="1" {{ ($user->uflag ?? 0) == 1 ? 'selected' : '' }}>Active</option>
                            </select>
                            <span class="text-danger" id="uflag_msg"></span>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Royalty</label>
                            <input type="number" class="form-control" name="royalty" id="royalty"
                                value="{{ $user->royalty ?? 0 }}" placeholder="Royalty">
                            <span class="text-danger" id="royalty_msg"></span>
                        </div>
                    </div>

                    <!-- Verification & Security -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary"><i class="bx bx-shield"></i> Verification & Security</h6>
                            <hr>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Verification Code</label>
                            <input type="number" class="form-control" name="vercode" id="vercode"
                                value="{{ $user->vercode ?? '' }}" placeholder="Verification Code">
                            <span class="text-danger" id="vercode_msg"></span>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Verification Code 1</label>
                            <input type="number" class="form-control" name="vercode1" id="vercode1"
                                value="{{ $user->vercode1 ?? '' }}" placeholder="Verification Code 1">
                            <span class="text-danger" id="vercode1_msg"></span>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>E-PIN</label>
                            <input type="number" class="form-control" name="epin" id="epin"
                                value="{{ $user->epin ?? '' }}" placeholder="E-PIN">
                            <span class="text-danger" id="epin_msg"></span>
                        </div>
                    </div>

                    <!-- Membership & Dates -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary"><i class="bx bx-calendar"></i> Membership & Dates</h6>
                            <hr>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Plan Membership</label>
                            <input type="number" class="form-control" name="planMem" id="planMem"
                                value="{{ $user->planMem ?? 0 }}" placeholder="Plan Membership">
                            <span class="text-danger" id="planMem_msg"></span>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Upgrade Date</label>
                            <input type="date" class="form-control" name="upgrade_date" id="upgrade_date"
                                value="{{ $user->upgrade_date ? date('Y-m-d', strtotime($user->upgrade_date)) : '' }}">
                            <span class="text-danger" id="upgrade_date_msg"></span>
                        </div>


                    </div>

                    <div class="mb-4 col-md-12 text-center">
                        <button type="submit" class="btn btn-sm btn-outline-primary" id="updateBtn">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            $('form#updateForm').submit(function (e) {
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
                        $('#updateBtn').html(
                            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Updating...`
                        ).attr('disabled', true);
                    },
                    success: function (res) {
                        $('#updateBtn').html('Update User').removeAttr('disabled');
                        
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
                                window.location.href = "{{ url('crm/user') }}";
                            }, 1000);
                        }
                    },
                    error: function() {
                        $('#updateBtn').html('Update User').removeAttr('disabled');
                        alertMsg(false, 'Something went wrong!', 3000);
                    }
                });
            });
        </script>
    @endpush
@endsection 