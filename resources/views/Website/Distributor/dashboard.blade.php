@extends('Website.Layout.app')

@section('content')
    <section class="section-padding py-5 pt-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="dashboard">
                        <div class="sidebar">
                            <a href="{{ url('distributor/dashboard') }}"
                                class="tab-btn {{ $type == 'dashboard' ? 'active' : '' }}"><i
                                    class="fa-solid fa-home me-1"></i> Dashboard</a>

                            <a href="{{ url('distributor/kyc') }}" class="tab-btn {{ $type == 'kyc' ? 'active' : '' }}"><i
                                    class="fa-solid fa-id-card me-1"></i>
                                KYC Verification</a>
                            <a href="{{ url('distributor/my-direct-referral') }}"
                                class="tab-btn {{ $type == 'my-direct-referral' ? 'active' : '' }}"><i
                                    class="fa-solid fa-tree me-1"></i> My Direct Referral</a>
                            <a href="{{ url('distributor/team-generation') }}"
                                class="tab-btn {{ $type == 'team-generation' ? 'active' : '' }}"><i
                                    class="fa-solid fa-tree me-1"></i> Team Generation</a>
                            <a href="{{ url('distributor/my-acheivements') }}"
                                class="tab-btn {{ $type == 'my-acheivements' ? 'active' : '' }}"><i
                                    class="fa-solid fa-trophy me-1"></i> My Acheivements</a>

                            <a href="{{ url('distributor/signup') }}/{{ Auth::user()->ref_id??'' }}"
                                class="tab-btn {{ $type == 'signup' ? 'active' : '' }}" target="_blank"><i
                                    class="fa-solid fa-link me-1"></i>My Referral Link</a>
                        </div>
 
                        <div class="tab-content">

                            @if ($type == 'dashboard')
                                {{ 'dashboard' }}
                            @elseif($type == 'profile')
                                @include('Website.Distributor.profile')
                            @elseif($type == 'kyc')
                                @include('Website.Distributor.kyc')
                            @elseif($type == 'settings')
                                @include('Website.Distributor.settings')
                            @elseif($type == 'my-direct-referral')
                                @include('Website.Distributor.my_direct_referal')
                            @elseif($type == 'team-generation')
                                @include('Website.Distributor.team_generations')
                            @elseif($type == 'my-acheivements')
                                @include('Website.Distributor.my_acheivements')
                            @endif

                        </div>
                    </div>
                </div>
    </section>
@endsection
