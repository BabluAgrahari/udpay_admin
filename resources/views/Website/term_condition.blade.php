@extends('Website.Layout.app')
@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary">Terms & Conditions</h1>
                    <p class="lead text-muted">Please read these terms carefully before using our services</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">DISCLAIMER</h2>
                            
                            <div class="card border-0 bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Product Disclaimer</h5>
                                    <p class="card-text">The information about our products as available in our Personal care & Health Guide is not intended to prevent, diagnose, treat, or cure any disease. This information is intended as an introduction to value addition in life through supplements.</p>
                                    <p class="card-text">Our products aim at maintaining holistic balance in body and immunity level but are no way substitute of physicians' diagnosis. We are not medical professionals or researchers and we cannot prescribe what product can cure your disease.</p>
                                    <div class="alert alert-warning mt-3">
                                        <strong>Important:</strong> We cannot answer medical questions to prescribe cures, treatment or to guess what is wrong with you. Consult your doctor about your health conditions and use our supplements for value addition in life. Any product used in excessive amounts will invite problems.
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Product Quality Disclaimer</h5>
                                    <p class="card-text">Unipay Digital Private Limited shall be responsible for the quality of products only if such products are bought from authorised channels. The buyer shall be solely responsible for all consequences for the purchase and use of products bought from unauthorised sources including unauthorised websites, E-commerce marketplace or unauthorised party.</p>
                                </div>
                            </div>

                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Website Disclaimer</h5>
                                    <p class="card-text">The contents of this site are only for information purpose. Users are advised to rely on information posted herein for any purpose only after verification and confirmation of the same from authentic and authoritative sources.</p>
                                    <p class="card-text">Neither Unipay Digital Private Limited nor the site developer is responsible for any consequences that may arise out of using such information without verification / confirmation.</p>
                                    <div class="alert alert-info mt-3">
                                        <strong>Note:</strong> There may be time gap in internet/online posting/ transmission of information and availability of such information at browsers' end. Exact status may be confirmed from source. For any query, suggestions regarding this website please contact at <a href="mailto:enquiry@uni-pay.in" class="text-decoration-none">enquiry@uni-pay.in</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">Copyright & Usage Rights</h2>
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <p class="card-text">No contents / portion of the contents, graphics, picture or presentation in this site may be used without explicit permission in writing from the author of this site. Usage of contents / parts thereof without verifiable and expressed permission from author or his authorized person will attract legal consequences.</p>
                                </div>
                            </div>
                        </div>

                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">General Terms</h2>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-user-check fa-2x text-primary mb-2"></i>
                                            <h5>User Responsibility</h5>
                                            <p class="small">Users are responsible for providing accurate information and maintaining the security of their accounts.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-shield-alt fa-2x text-primary mb-2"></i>
                                            <h5>Data Protection</h5>
                                            <p class="small">We are committed to protecting your personal information as outlined in our Privacy Policy.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-gavel fa-2x text-primary mb-2"></i>
                                            <h5>Legal Compliance</h5>
                                            <p class="small">All transactions and activities must comply with applicable laws and regulations.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-sync-alt fa-2x text-primary mb-2"></i>
                                            <h5>Policy Updates</h5>
                                            <p class="small">We reserve the right to update these terms at any time. Continued use constitutes acceptance.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="policy-section">
                            <h2 class="h4 text-primary mb-3">Contact Information</h2>
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <p class="card-text">For any questions regarding these terms and conditions, please contact us at <a href="mailto:enquiry@uni-pay.in" class="text-decoration-none">enquiry@uni-pay.in</a></p>
                                    <p class="card-text mb-0"><strong>Last Updated:</strong> {{ date('F d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection