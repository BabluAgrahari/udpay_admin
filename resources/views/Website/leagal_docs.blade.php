@extends('Website.Layout.app')
@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary">Legal Documents</h1>
                    <p class="lead text-muted">Important legal documents and compliance information</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">Important Legal Information</h2>
                            <p>This page contains important legal documents and information related to UniPay Digital Private Limited. Please read these documents carefully as they govern your use of our services.</p>
                        </div>

                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">Available Documents</h2>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Terms & Conditions</h5>
                                            <p class="card-text">Complete terms and conditions governing the use of our website and services.</p>
                                            <a href="{{ route('terms.conditions') }}" class="btn btn-primary">View Document</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Privacy Policy</h5>
                                            <p class="card-text">How we collect, use, and protect your personal information.</p>
                                            <a href="{{ route('privacy.policy') }}" class="btn btn-primary">View Document</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Return Policy</h5>
                                            <p class="card-text">Our policies regarding returns, exchanges, and refunds.</p>
                                            <a href="{{ route('return.policy') }}" class="btn btn-primary">View Document</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Shipping Policy</h5>
                                            <p class="card-text">Information about shipping methods, costs, and delivery times.</p>
                                            <a href="{{ route('shipping.policy') }}" class="btn btn-primary">View Document</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">Legal Disclaimers</h2>
                            <div class="alert alert-warning">
                                <h5>Important Notice</h5>
                                <p class="mb-0">The information provided on this website is for general informational purposes only. While we strive to keep the information up to date and correct, we make no representations or warranties of any kind about the completeness, accuracy, reliability, suitability, or availability of the information, products, services, or related graphics contained on the website for any purpose.</p>
                            </div>
                        </div>

                        <div class="policy-section">
                            <h2 class="h4 text-primary mb-3">Contact Information</h2>
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <p class="card-text">For any questions regarding these legal documents, please contact us:</p>
                                    <p class="card-text">
                                        <strong>Email:</strong> <a href="mailto:enquiry@uni-pay.in">enquiry@uni-pay.in</a><br>
                                        <strong>Phone:</strong> <a href="tel:+919549899933">(+91) 9549899933</a><br>
                                        <strong>Address:</strong> Office Number 2, First Floor, Ganga Tower, Khatipura Road, Near S D Aggarwal, Jhotwara, Jaipur, Rajasthan - 302012
                                    </p>
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