@extends('CRM.Layout.layout')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-align-top">
                    <ul class="nav nav-pills flex-column flex-md-row mb-6">
                        @can('isSuperAdmin')
                            <li class="nav-item"><a class="nav-link active" href="{{url('crm/setting')}}"><i
                                        class="bx bx-sm bx-user me-1_5"></i> Account</a></li>
                        @endcan
                        <li class="nav-item"><a class="nav-link" href="{{url('crm/reset-password')}}"><i
                                    class='bx bxs-key me-1_5'></i> Reset Password</a></li>
                    </ul>
                </div>
                <div class="card mb-6">
                    <!-- Account -->
                    <form id="saveProfile" method="POST" action="{{url('crm/setting')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom">
                                <img src="{{$record->profile_pic ?? '../assets/img/avatars/1.png'}}" alt="user-avatar"
                                    class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar">
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-3 mb-4" tabindex="0">
                                        <span class="d-none d-sm-block">Upload Profile photo</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input type="file" id="upload" class="account-file-input" hidden=""
                                            name="profile_pic" accept="image/png,image/jpeg">
                                    </label>
                                    <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                        <i class="bx bx-reset d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Reset</span>
                                    </button>

                                    <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                                </div>
                            </div>
                            <span class="text-danger" id="profile_pic_msg"></span>
                        </div>
                        <div class="card-body pt-4">

                            <div class="row g-6">
                                <div class="col-md-6">
                                    <label for="firstName" class="form-label">Organization Name <span
                                            class="text-danger"><b>*</b></span></label>
                                    <input class="form-control" type="text" id="name" name="name"
                                        value="{{$record->name ?? ''}}" placeholder="Organization Name" autofocus="">
                                    <span class="text-danger msg" id="name_msg"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">E-mail <span
                                            class="text-danger"><b>*</b></span></label>
                                    <input class="form-control" type="text" id="email" disabled name="email"
                                        value="{{$record->email ?? ''}}" placeholder="john.doe@example.com">
                                    <span class="text-danger msg" id="email_msg"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="phoneNumber">Phone Number <span
                                            class="text-danger"><b>*</b></span></label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">IN (+91)</span>
                                        <input type="text" id="phoneNumber" name="phone_no"
                                            value="{{$record->phone_no ?? ''}}" class="form-control" placeholder="Phone No">
                                    </div>
                                    <span class="text-danger msg" id="phone_no_msg"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="address" class="form-label">Address <span
                                            class="text-danger"><b>*</b></span></label>
                                    <input type="text" class="form-control" id="address" value="{{$record->address ?? ''}}"
                                        name="address" placeholder="Address">
                                    <span class="text-danger msg" id="address_msg"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="state" class="form-label">State <span
                                            class="text-danger"><b>*</b></span></label>
                                    <input class="form-control" type="text" id="state" name="state"
                                        value="{{$record->state ?? ''}}" placeholder="State">
                                    <span class="text-danger msg" id="state_msg"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="city" class="form-label">City <span
                                            class="text-danger"><b>*</b></span></label>
                                    <input class="form-control" type="text" id="city" name="city"
                                        value="{{$record->city ?? ''}}" placeholder="City">
                                    <span class="text-danger msg" id="city_msg"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="zipCode" class="form-label">Zip Code <span
                                            class="text-danger"><b>*</b></span></label>
                                    <input type="text" class="form-control" id="zipCode" name="zip_code"
                                        value="{{$record->zip_code ?? ''}}" placeholder="Zip Code" maxlength="6">
                                    <span class="text-danger msg" id="zip_code_msg"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="zipCode" class="form-label">Country <span
                                            class="text-danger"><b>*</b></span></label>
                                    <input type="text" class="form-control" id="country" name="country"
                                        value="{{$record->country ?? ''}}" placeholder="Country">
                                    <span class="text-danger msg" id="country_msg"></span>
                                </div>

                                <div class="col-md-3">
                                    <label for="zipCode" class="form-label">Logo</label>
                                    <input type="file" class="form-control" id="logo" name="logo"
                                        accept="image/png,image/svg">
                                    <span class="text-danger msg" id="logo_msg"></span>
                                </div>

                                <div class="col-md-3">
                                    @if(!empty($record->logo))
                                        <img src="{{$record->logo ?? ''}}" style="width: 70px; height: 60px;">
                                    @endif
                                </div>

                                <div class="col-md-3">
                                    <label for="zipCode" class="form-label">SVG Icon</label>
                                    <input type="file" class="form-control" id="svg_icon" name="svg_icon"
                                        accept="image/png,image/svg">
                                    <span class="text-danger msg" id="svg_icon_msg"></span>
                                </div>

                                <div class="col-md-3">
                                    @if(!empty($record->svg_icon))
                                        <img src="{{$record->svg_icon ?? ''}}" style="width: 70px; height: 60px;">
                                    @endif
                                </div>

                            </div>
                            <div class="mt-6">
                                <button type="submit" id="saveBtn" class="btn btn-primary me-3">Save Changes</button>
                                <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                            </div>

                        </div>
                    </form>
                    <!-- /Account -->
                </div>

            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('form#saveProfile').submit(function (e) {
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
                    $('#saveBtn').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Saving...`).attr('disabled', true);
                },
                success: function (res) {

                    $('#saveBtn').html('Save Changes').removeAttr('disabled');

                    /*Start Validation Error Message*/
                    $('span.msg').html('');
                    if (res.validation) {
                        $.each(res.validation, (index, msg) => {
                            $(`#${index}_msg`).html(`${msg}`);
                        })
                        return false;
                    }
                    /*End Validation Error Message*/
                    /*Start Status message*/
                    Swal.fire({
                        title: res.status ? 'Success' : 'Error',
                        text: res.msg,
                        icon: res.status ? 'success' : 'error'
                    }).then(function () {
                        if (res.status) {
                            location.reload();
                        }
                    });
                    /*End Status message*/
                }
            });
        });
    </script>
@endpush