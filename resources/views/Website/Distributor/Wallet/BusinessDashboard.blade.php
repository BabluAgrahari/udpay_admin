@extends('Website.Layout.app')

@section('content')
<section class="section-padding">
  <div class="container">
    <div class="row">
    
        <!-- Left Stats -->
        <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="stat-card">
                <h5>My Direct</h5>
                <h3>0</h3>
                <p>Partners</p>
            </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="stat-card">
                <h5>Generation Team</h5>
                <h3>27</h3>
                <p>Partners</p>
            </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="stat-card">
                <h5>Total Team</h5>
                <h3>060</h3>
                <p>SV</p>
            </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="stat-card">
                <h5>My Direct</h5>
                <h3>12800</h3>
                <p>SV</p>
            </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="stat-card">
                <h5>Generation 2</h5>
                <h3>12800</h3>
                <p>SV</p>
            </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="stat-card">
                <h5>Self SV</h5>
                <h3>12800</h3>
                <p>Current Month</p>
            </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="stat-card">
                <h5>Self SV</h5>
                <h3>12800</h3>
                <p>Total</p>
            </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="stat-card">
                <h5>Total</h5>
                <h3>12800</h3>
                <p>Earning</p>
            </div>
            </div>
        </div>
        </div>

        <!-- Profile -->
        <div class="col-lg-3">
        <div class="profile-card">
            <img src="assets/images/user.png" alt="Profile" class="referral-image">
            <h5>Sarah Cameron</h5>
            <p class="mb-3">Uni id : 11798</p>
            <p>Rank: <strong>5 Star</strong></p>
            <button class="thm-btn btn-referral">Share Referral</button> 
            <p class=" mt-3 thm-btn">Wallat Balance: 2,00,000</p>
        </div>
        </div>

    </div>
  </div>
</section>
@endsection