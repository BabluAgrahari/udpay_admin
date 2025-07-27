@extends('CRM.Layout.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row card-header">
                <div class="col-md-10">
                    <h5>User Details - {{ $user->full_name ?? ($user->first_name . ' ' . $user->last_name) }}</h5>
                </div>
                <div class="col-md-2 text-right">
                    <a href="{{ url('crm/user') }}" class="btn btn-outline-warning btn-sm">
                        <i class='bx bx-left-arrow-circle' style="line-height: 0"></i>&nbsp;Back
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Basic User Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-primary"><i class="bx bx-user"></i> Basic Information</h6>
                        <hr>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">User ID:</th>
                                <td>{{ $user->user_id ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Reference ID:</th>
                                <td>{{ $user->ref_id ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Username:</th>
                                <td>{{ $user->user_nm ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Alpha Numeric UID:</th>
                                <td>{{ $user->alpha_num_uid ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Full Name:</th>
                                <td>{{ $user->full_name ?? ($user->first_name . ' ' . $user->last_name) }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $user->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Mobile:</th>
                                <td>{{ $user->mobile ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Role:</th>
                                <td>
                                    <span class="badge bg-label-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'merchant' ? 'warning' : ($user->role == 'customer' ? 'info' : 'secondary')) }}">
                                        {{ ucwords($user->role ?? 'N/A') }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>{!! status($user->isactive) !!}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Date of Birth:</th>
                                <td>{{ $user->dob ? $user->dFormat($user->dob) : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Gender:</th>
                                <td>{{ ucwords($user->gender ?? 'N/A') }}</td>
                            </tr>
                            <tr>
                                <th>Created On:</th>
                                <td>{{ $user->dFormat($user->created) }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated:</th>
                                <td>{{ $user->updated ? $user->dFormat($user->updated) : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Modified On:</th>
                                <td>{{ $user->modified_on ? $user->dFormat($user->modified_on) : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Upgrade Date:</th>
                                <td>{{ $user->upgrade_date ? $user->dFormat($user->upgrade_date) : 'N/A' }}</td>
                            </tr>
                        </table>
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
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Restricted:</th>
                                <td>
                                    @if($user->restricted == 1)
                                        <span class="badge bg-danger">Yes</span>
                                    @else
                                        <span class="badge bg-success">No</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>User Flag:</th>
                                <td>
                                    @if($user->uflag == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Royalty:</th>
                                <td>{{ $user->royalty ?? 0 }}</td>
                            </tr>
                            <tr>
                                <th>Plan Membership:</th>
                                <td>{{ $user->planMem ?? 0 }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Verification Code:</th>
                                <td>{{ $user->vercode ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Verification Code 1:</th>
                                <td>{{ $user->vercode1 ?? 'N/A' }}</td>
                            </tr>
                            
                            <tr>
                                <th>E-PIN:</th>
                                <td>{{ $user->epin ?? 'N/A' }}</td>
                            </tr>
                            
                        </table>
                    </div>
                </div>

                <!-- KYC Information -->
                @if($user->kyc)
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-primary"><i class="bx bx-id-card"></i> KYC Information</h6>
                        <hr>
                    </div>
                </div>

                <!-- KYC Status -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>KYC Status:</strong>
                                    @if($user->kyc->kyc_flag == 1)
                                        <span class="badge bg-success">Verified</span>
                                    @else
                                        <span class="badge bg-warning">Pending Verification</span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <strong>Personal Details:</strong>
                                    @if($user->kyc->personal_flag == 1)
                                        <span class="badge bg-success">Complete</span>
                                    @else
                                        <span class="badge bg-danger">Incomplete</span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <strong>Bank Details:</strong>
                                    @if($user->kyc->bank_flag == 1)
                                        <span class="badge bg-success">Complete</span>
                                    @else
                                        <span class="badge bg-danger">Incomplete</span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <strong>Documents:</strong>
                                    @if($user->kyc->pan_front || $user->kyc->aadhar_front)
                                        <span class="badge bg-success">Uploaded</span>
                                    @else
                                        <span class="badge bg-danger">Missing</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Details -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-secondary">Personal Details</h6>
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Full Name:</th>
                                <td>{{ $user->kyc->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Mobile Number:</th>
                                <td>{{ $user->kyc->mobile_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Date of Birth:</th>
                                <td>{{ $user->kyc->dob ? date('d M, Y', strtotime($user->kyc->dob)) : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Gender:</th>
                                <td>{{ ucwords($user->kyc->gender ?? 'N/A') }}</td>
                            </tr>
                            <tr>
                                <th>PAN Number:</th>
                                <td>{{ $user->kyc->pan_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Aadhar Number:</th>
                                <td>{{ $user->kyc->aadhar_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Occupation:</th>
                                <td>{{ $user->kyc->work ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-secondary">Address Details</h6>
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Address:</th>
                                <td>{{ $user->kyc->address ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Locality:</th>
                                <td>{{ $user->kyc->locality ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>District:</th>
                                <td>{{ $user->kyc->district ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>State:</th>
                                <td>{{ $user->kyc->state ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Pincode:</th>
                                <td>{{ $user->kyc->pincode ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>ID Proof Type:</th>
                                <td>{{ $user->kyc->id_proof ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Bank Details -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-secondary">Bank Details</h6>
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Bank Name:</th>
                                <td>{{ $user->kyc->bank ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Account Number:</th>
                                <td>{{ $user->kyc->account_number ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>IFSC Code:</th>
                                <td>{{ $user->kyc->ifsc_code ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Branch:</th>
                                <td>{{ $user->kyc->branch ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-secondary">Nominee Details</h6>
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Nominee Name:</th>
                                <td>{{ $user->kyc->nominee ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Relation:</th>
                                <td>{{ $user->kyc->relation ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Documents -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-secondary">Documents</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <i class="bx bx-file-pdf" style="font-size: 2rem; color: #dc3545;"></i>
                                        <p class="mb-1"><strong>PAN Front</strong></p>
                                        @if($user->kyc->pan_front)
                                            <a href="{{ $user->kyc->pan_front }}" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                                        @else
                                            <span class="text-muted">Not uploaded</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <i class="bx bx-file-pdf" style="font-size: 2rem; color: #198754;"></i>
                                        <p class="mb-1"><strong>Aadhar Front</strong></p>
                                        @if($user->kyc->aadhar_front)
                                            <a href="{{ $user->kyc->aadhar_front }}" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                                        @else
                                            <span class="text-muted">Not uploaded</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <i class="bx bx-file-pdf" style="font-size: 2rem; color: #198754;"></i>
                                        <p class="mb-1"><strong>Aadhar Back</strong></p>
                                        @if($user->kyc->aadhar_back)
                                            <a href="{{ $user->kyc->aadhar_back }}" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                                        @else
                                            <span class="text-muted">Not uploaded</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <i class="bx bx-file-pdf" style="font-size: 2rem; color: #0d6efd;"></i>
                                        <p class="mb-1"><strong>Bank Document</strong></p>
                                        @if($user->kyc->bank_doc)
                                            <a href="{{ $user->kyc->bank_doc }}" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                                        @else
                                            <span class="text-muted">Not uploaded</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <h6><i class="bx bx-info-circle"></i> KYC Information</h6>
                            <p class="mb-0">No KYC information has been submitted for this user.</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="row">
                    <div class="col-12 text-center">
                        <a href="{{ url('crm/user/' . $user->_id . '/edit') }}" class="btn btn-primary">
                            <i class="bx bx-edit"></i> Edit User
                        </a>
                        <a href="{{ url('crm/user/' . $user->_id . '/kyc') }}" class="btn btn-info">
                            <i class="bx bx-id-card"></i> Manage KYC
                        </a>
                        <a href="{{ url('crm/user') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 