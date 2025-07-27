@extends('CRM.Layout.layout')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header pt-3 pb-3">
            <div class="row">
                <div class="col-md-10">
                    <h5>Edit Panel User - {{ $user->first_name }} {{ $user->last_name }}</h5>
                </div>
                <div class="col-md-2 text-right">
                    <a href="{{ url('crm/panel-users') }}" class="btn btn-outline-warning btn-sm">
                        <i class='bx bx-left-arrow-circle'></i>&nbsp;Back
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <form id="updateForm" method="post" action="{{ url('crm/panel-users/' . $user->_id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="first_name" id="first_name" value="{{ $user->first_name }}" required>
                            <span class="text-danger" id="first_name_msg"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="last_name" id="last_name" value="{{ $user->last_name }}" required>
                            <span class="text-danger" id="last_name_msg"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            <small class="text-muted">Email cannot be changed</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
<<<<<<< HEAD
                            <select class="form-select" name="role" id="role" required>
                                <option value="">Select Role</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="vendor" {{ $user->role == 'vendor' ? 'selected' : '' }}>Vendor</option>
=======
                            <select class="form-select" name="role_id" id="role_id" required>
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->_id }}" {{ $user->role_id == $role->_id ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $role->role)) }}</option>
                                @endforeach
>>>>>>> 9cae8d43cbd99df28bc9af661b0d7feb4165cf42
                            </select>
                            <span class="text-danger" id="role_msg"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" name="profile_pic" id="profile_pic" accept="image/*">
                            <span class="text-danger" id="profile_pic_msg"></span>
                            @if($user->profile_pic)
                                <div class="mt-2">
                                    <img src="{{ asset($user->profile_pic) }}" alt="Current Profile" class="img-thumbnail" width="100">
                                    <small class="text-muted">Current profile picture</small>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <a href="{{ url('crm/panel-users/' . $user->_id . '/change-password') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bx bx-key"></i> Change Password
                            </a>
                            <small class="text-muted d-block">Click to change password</small>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" name="address" id="address" rows="3">{{ $user->address }}</textarea>
                    <span class="text-danger" id="address_msg"></span>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="city" id="city" value="{{ $user->city }}">
                            <span class="text-danger" id="city_msg"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">State</label>
                            <input type="text" class="form-control" name="state" id="state" value="{{ $user->state }}">
                            <span class="text-danger" id="state_msg"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Zip Code</label>
                            <input type="text" class="form-control" name="zip_code" id="zip_code" value="{{ $user->zip_code }}">
                            <span class="text-danger" id="zip_code_msg"></span>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary" id="submitBtn">Update Panel User</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
<script>
    $(document).ready(function() {
        $('#updateForm').on('submit', function(e) {
            e.preventDefault();
            
            $('#submitBtn').html('Updating...').attr('disabled', true);
            
            // Clear previous error messages
            $('.text-danger').html('');
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {
                        toastr.success(response.msg);
                        setTimeout(function() {
                            window.location.href = "{{ url('crm/panel-users') }}";
                        }, 1000);
                    } else {
                        toastr.error(response.msg);
                        $('#submitBtn').html('Update Panel User').removeAttr('disabled');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(function(key) {
                            $('#' + key + '_msg').html(errors[key][0]);
                        });
                    } else {
                        toastr.error('Something went wrong!');
                    }
                    $('#submitBtn').html('Update Panel User').removeAttr('disabled');
                }
            });
        });
    });
</script>
@endpush
@endsection 