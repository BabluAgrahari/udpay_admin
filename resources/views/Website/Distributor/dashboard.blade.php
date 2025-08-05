@extends('Website.Layout.app')

@section('content')
    <section class="section-padding py-5 pt-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="dashboard">
                        <div class="sidebar">
                            <a href="{{ url('distributor/dashboard') }}" class="tab-btn active"><i
                                    class="fa-solid fa-home me-3"></i> Dashboard</a>
                            <a href="{{ url('distributor/profile') }}" class="tab-btn"><i class="fa-solid fa-user me-3"></i>
                                Profile</a>
                            <a href="{{ url('distributor/kyc') }}" class="tab-btn"><i class="fa-solid fa-id-card me-3"></i>
                                KYC Verification</a>
                            <a href="{{ url('distributor/my-direct-referral') }}" class="tab-btn"><i
                                    class="fa-solid fa-tree me-3"></i> My Direct Referral</a>
                            <a href="{{ url('distributor/team-generation') }}" class="tab-btn"><i
                                    class="fa-solid fa-tree me-3"></i> Team Generation</a>
                            <a href="{{ url('distributor/my-acheivements') }}" class="tab-btn"><i
                                    class="fa-solid fa-trophy me-3"></i> My Acheivements</a>
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
