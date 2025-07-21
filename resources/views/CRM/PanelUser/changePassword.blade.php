@extends('CRM.Layout.layout')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header pt-3 pb-3">
            <div class="row">
                <div class="col-md-10">
                    <h5>Change Password - {{ $user->first_name }} {{ $user->last_name }}</h5>
                </div>
                <div class="col-md-2 text-right">
                    <a href="{{ url('crm/panel-users') }}" class="btn btn-outline-warning btn-sm">
                        <i class='bx bx-left-arrow-circle'></i>&nbsp;Back
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="alert alert-info">
                        <h6><i class="bx bx-info-circle"></i> Password Change Information</h6>
                        <ul class="mb-0">
                            <li>You can change the password without providing the old password</li>
                            <li>This feature is available for super admin users</li>
                            <li>Password must be at least 6 characters long</li>
                            <li>Make sure to confirm the new password</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-center">
                        @if($user->profile_pic)
                            <img src="{{ asset($user->profile_pic) }}" alt="Profile Picture" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Default Profile" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                        @endif
                        <h6>{{ $user->first_name }} {{ $user->last_name }}</h6>
                        <p class="text-muted">{{ $user->email }}</p>
                        <span class="badge bg-label-{{ $user->role == 'admin' ? 'danger' : 'warning' }}">
                            {{ ucwords($user->role) }}
                        </span>
                    </div>
                </div>
            </div>

            <form id="changePasswordForm" method="post" action="{{ url('crm/panel-users/' . $user->_id . '/change-password') }}">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">New Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" id="password" required>
                            <span class="text-danger" id="password_msg"></span>
                            <small class="text-muted">Password must be at least 6 characters long</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                            <span class="text-danger" id="password_confirmation_msg"></span>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-warning" id="submitBtn">
                        <i class="bx bx-key"></i> Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
<script>
    $(document).ready(function() {
        $('#changePasswordForm').on('submit', function(e) {
            e.preventDefault();
            
            $('#submitBtn').html('<i class="bx bx-loader bx-spin"></i> Changing Password...').attr('disabled', true);
            
            // Clear previous error messages
            $('.text-danger').html('');
            
            // Validate password confirmation
            const password = $('#password').val();
            const passwordConfirmation = $('#password_confirmation').val();
            
            if (password !== passwordConfirmation) {
                $('#password_confirmation_msg').html('Password confirmation does not match');
                $('#submitBtn').html('<i class="bx bx-key"></i> Change Password').removeAttr('disabled');
                return;
            }
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status) {
                        alertMsg(response.status, response.msg, 3000);
                        setTimeout(function() {
                            window.location.href = "{{ url('crm/panel-users') }}";
                        }, 1000);
                    } else {
                        alertMsg(false, response.msg, 3000);
                        $('#submitBtn').html('<i class="bx bx-key"></i> Change Password').removeAttr('disabled');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(function(key) {
                            $('#' + key + '_msg').html(errors[key][0]);
                        });
                    } else {
                        alertMsg(false, xhr.responseJSON.msg, 3000);
                    }
                    $('#submitBtn').html('<i class="bx bx-key"></i> Change Password').removeAttr('disabled');
                }
            });
        });
    });
</script>
@endpush
@endsection 