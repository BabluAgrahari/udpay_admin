@extends('CRM.Layout.layout')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div id="message"></div>
    <div class="col-xl-12">
        <div class="nav-align-top nav-tabs-shadow mb-6">
            <ul class="nav nav-tabs nav-fill" role="tablist">
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-home" aria-controls="navs-justified-home" aria-selected="true">
                        <span class="d-none d-sm-block"> <i class='bx bxs-business me-1_5 align-text-bottom'></i> Business Details


                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-profile" aria-controls="navs-justified-profile" aria-selected="false" tabindex="-1">
                        <span class="d-none d-sm-block"> <i class='bx bx-user me-1_5 align-text-bottom'></i> Contact Person Details</span>
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-bank" aria-controls="navs-justified-bank" aria-selected="false" tabindex="-1">
                        <span class="d-none d-sm-block"> <i class='bx bxs-bank me-1_5 align-text-bottom'></i> Bank Details</span>
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-kyc" aria-controls="navs-justified-kyc" aria-selected="false" tabindex="-1">
                        <span class="d-none d-sm-block"> <i class='bx bx-user-check me-1_5 align-text-bottom'></i> KYC</span>
                    </button>
                </li>

            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
                    @include('CRM.Profile.companyDetails')
                </div>
                <div class="tab-pane fade" id="navs-justified-profile" role="tabpanel">
                    @include('CRM.Profile.contactPersonDetails')
                </div>
                <div class="tab-pane fade" id="navs-justified-bank" role="tabpanel">
                    @include('CRM.Profile.bankDetails')
                </div>
                <div class="tab-pane fade" id="navs-justified-kyc" role="tabpanel">
                    @include('CRM.Profile.kycDetails')
                </div>

            </div>
        </div>
    </div>
</div>


@endsection