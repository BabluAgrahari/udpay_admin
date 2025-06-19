@extends('CRM.Layout.layout')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-align-top">
                    <ul class="nav nav-pills flex-column flex-md-row mb-6">
                        @can('isSuperAdmin')
                            <li class="nav-item"><a class="nav-link " href="{{url('crm/setting')}}"><i
                                        class="bx bx-sm bx-user me-1_5"></i> Account</a></li>
                        @endcan
                        <li class="nav-item"><a class="nav-link active" href="{{url('crm/reset-password')}}"><i
                                    class='bx bxs-key me-1_5'></i> Reset Password</a></li>
                    </ul>
                </div>
                <div class="card mb-6">
                    <!-- Account -->
                    <form id="resetPassword" method="POST" action="{{url('crm/reset-password-save')}}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="card-body pt-4">

                            <div class="row">
                                <div class="col-md-6 m-auto">
                                    <div class="row g-6">
                                        <div class="col-md-12">
                                            <label for="firstName" class="form-label">Old Password <span
                                                    class="text-danger"><b>*</b></span></label>
                                            <input class="form-control" type="password" id="old_password"
                                                name="old_password" value="" placeholder="Old Password" autofocus="">
                                            <span class="text-danger msg" id="old_password_msg"></span>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="email" class="form-label">New Password <span
                                                    class="text-danger"><b>*</b></span></label>
                                            <input class="form-control" type="password" id="password" name="password"
                                                value="" placeholder="Enter password">
                                            <span class="text-danger msg" id="password_msg"></span>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label" for="phoneNumber">Confirm Password <span
                                                    class="text-danger"><b>*</b></span></label>
                                            <input type="text" id="" name="confirm_password" value="" class="form-control"
                                                placeholder="Confirm Password">
                                            <span class="text-danger msg" id="confirm_password_msg"></span>
                                        </div>

                                    </div>
                                    <div class="mt-6 text-center">
                                        <button type="submit" id="saveBtn" class="btn btn-primary me-3">Reset
                                            Password</button>
                                        <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                                    </div>
                                </div>
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
        $('form#resetPassword').submit(function (e) {
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

                    $('#saveBtn').html('Reset Password').removeAttr('disabled');

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