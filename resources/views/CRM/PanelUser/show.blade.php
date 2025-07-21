@extends('CRM.Layout.layout')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header pt-3 pb-3">
            <div class="row">
                <div class="col-md-10">
                    <h5>Panel User Details - {{ $user->first_name }} {{ $user->last_name }}</h5>
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
                <div class="col-md-3 text-center">
                    @if($user->profile_pic)
                        <img src="{{ asset($user->profile_pic) }}" alt="Profile Picture" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Default Profile" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @endif
                    
                    <div class="mb-3">
                        <span class="badge bg-label-{{ $user->role == 'admin' ? 'danger' : 'warning' }} fs-6">
                            {{ ucwords($user->role) }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        {!! status($user->isactive) !!}
                    </div>
                </div>
                
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-striped">
                                <tr>
                                    <th width="30%">Full Name:</th>
                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Role:</th>
                                    <td>
                                        <span class="badge bg-label-{{ $user->role == 'admin' ? 'danger' : 'warning' }}">
                                            {{ ucwords($user->role) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>{!! status($user->isactive) !!}</td>
                                </tr>
                                <tr>
                                    <th>Created On:</th>
                                    <td>{{ $user->dFormat($user->created) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Address:</th>
                                    <td>{{ $user->address ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <th>City:</th>
                                    <td>{{ $user->city ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <th>State:</th>
                                    <td>{{ $user->state ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <th>Zip Code:</th>
                                    <td>{{ $user->zip_code ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>{{ $user->updated ? $user->dFormat($user->updated) : 'Not updated' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-end">
                        <a href="{{ url('crm/panel-users/' . $user->_id . '/edit') }}" class="btn btn-primary me-2">
                            <i class="bx bx-edit"></i> Edit Panel User
                        </a>
                        <a href="{{ url('crm/panel-users/' . $user->_id . '/change-password') }}" class="btn btn-warning me-2">
                            <i class="bx bx-key"></i> Change Password
                        </a>
                        <button type="button" class="btn btn-danger" onclick="deleteUser('{{ $user->_id }}')">
                            <i class="bx bx-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this panel user? This action cannot be undone.')) {
            $.ajax({
                url: "{{ url('crm/panel-users') }}/" + userId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status) {
                        alertMsg(response.status, response.msg, 3000);
                        setTimeout(function() {
                            window.location.href = "{{ url('crm/panel-users') }}";
                        }, 1000);
                    } else {
                        alertMsg(false, response.msg, 3000);
                    }
                },
                error: function() {
                    alertMsg(false, xhr.responseJSON.msg, 3000);
                }
            });
        }
    }
</script>
@endpush
@endsection 