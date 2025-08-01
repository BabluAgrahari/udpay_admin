@extends('Website.Layout.app')

@section('content')
    <section class="signin-section section-padding">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar Navigation -->
                <div class="col-lg-3 col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-gradient-brand text-white">
                            <h5 class="mb-0"><i class="fa-solid fa-user-tie me-2"></i>Distributor Panel</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                               
                                <a href="{{ url('distributor/dashboard') }}" class="nav-link d-flex align-items-center {{ $type == 'dashboard' ? 'active' : '' }}" id="v-pills-dashboard-tab" data-bs-toggle="pill" data-bs-target="#v-pills-dashboard" type="button" role="tab" aria-controls="v-pills-dashboard" aria-selected="false">
                                    <i class="fa-solid fa-home me-3"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ url('distributor/profile') }}" class="nav-link d-flex align-items-center {{ $type == 'profile' ? 'active' : '' }}" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                                    <i class="fa-solid fa-user me-3"></i>
                                    <span>Profile</span>
                                </a>
                                <a href="{{ url('distributor/kyc') }}" class="nav-link d-flex align-items-center {{ $type == 'kyc' ? 'active' : '' }}" id="v-pills-kyc-tab" data-bs-toggle="pill" data-bs-target="#v-pills-kyc" type="button" role="tab" aria-controls="v-pills-kyc" aria-selected="false">
                                    <i class="fa-solid fa-id-card me-3"></i>
                                    <span>KYC Verification</span>
                                </a>
                                <a class="nav-link d-flex align-items-center {{ $type == 'settings' ? 'active' : '' }}" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                                    <i class="fa-solid fa-cog me-3"></i>
                                    <span>Settings</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9 col-md-8">
                    @if($type == 'dashboard')
                        @include('Website.Distributor.dashboard')
                    @elseif($type == 'profile')
                        @include('Website.Distributor.profile')
                    @elseif($type == 'kyc')
                        @include('Website.Distributor.kyc')
                    @endif
                    
                    
                    {{-- <div class="tab-content" id="v-pills-tabContent">
                      
                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <h5 class="text-muted">Profile Settings</h5>
                                    <p class="text-muted">Profile management features will be implemented here.</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="v-pills-kyc" role="tabpanel" aria-labelledby="v-pills-kyc-tab"> --}}
                          
                        {{-- </div>
                        <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <h5 class="text-muted">Settings</h5>
                                    <p class="text-muted">System settings and preferences will be implemented here.</p>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Brand Colors */
        :root {
            --brand-green: #016038;
            --brand-orange: #f1614a;
        }
        
        .text-brand-green {
            color: var(--brand-green) !important;
        }
        
        .text-brand-orange {
            color: var(--brand-orange) !important;
        }
        
        .bg-brand-green {
            background-color: var(--brand-green) !important;
        }
        
        .bg-brand-orange {
            background-color: var(--brand-orange) !important;
        }
        
        .bg-gradient-brand {
            background: linear-gradient(135deg, var(--brand-green) 0%, var(--brand-orange) 100%);
        }
        
        .btn-brand-green {
            background-color: var(--brand-green);
            border-color: var(--brand-green);
            color: white;
        }
        
        .btn-brand-green:hover {
            background-color: #014a2e;
            border-color: #014a2e;
            color: white;
        }
        
        .btn-brand-orange {
            background-color: var(--brand-orange);
            border-color: var(--brand-orange);
            color: white;
        }
        
        .btn-brand-orange:hover {
            background-color: #e55a43;
            border-color: #e55a43;
            color: white;
        }
        
        .btn-outline-brand-green {
            color: var(--brand-green);
            border-color: var(--brand-green);
        }
        
        .btn-outline-brand-green:hover {
            background-color: var(--brand-green);
            border-color: var(--brand-green);
            color: white;
        }
        
        .btn-outline-brand-orange {
            color: var(--brand-orange);
            border-color: var(--brand-orange);
        }
        
        .btn-outline-brand-orange:hover {
            background-color: var(--brand-orange);
            border-color: var(--brand-orange);
            color: white;
        }
        
        .nav-pills .nav-link {
            border-radius: 0;
            padding: 1rem 1.5rem;
            color: #6c757d;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }
        
        .nav-pills .nav-link:hover {
            background-color: #f8f9fa;
            color: var(--brand-green);
            border-left-color: var(--brand-orange);
        }
        
        .nav-pills .nav-link.active {
            background-color: rgba(1, 96, 56, 0.1);
            color: var(--brand-green);
            border-left-color: var(--brand-green);
        }
        
        .accordion-button {
            border-radius: 0 !important;
            font-weight: 500;
        }
        
        .accordion-button:not(.collapsed) {
            background-color: rgba(1, 96, 56, 0.1) !important;
            color: var(--brand-green) !important;
            box-shadow: none !important;
        }
        
        .card {
            border-radius: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
        }
        
        .progress {
            border-radius: 10px;
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
        }
        
        .badge {
            border-radius: 6px;
        }
        
        .nav-pills .nav-link.active {
            background-color: rgba(1, 96, 56, 0.1);
            color: var(--brand-green);
            border-left-color: var(--brand-green);
        }
        
            
    </style>
    
  
@endsection
