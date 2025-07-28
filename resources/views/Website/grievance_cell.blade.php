@extends('Website.Layout.app')
@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary">Grievance Cell</h1>
                    <p class="lead text-muted">We're here to address your concerns and resolve issues promptly</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">Our Commitment</h2>
                            <p>At UniPay Digital, we are committed to providing excellent customer service and ensuring that all your concerns are addressed promptly and effectively. Our Grievance Cell is dedicated to resolving any issues you may face while using our services.</p>
                        </div>

                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">How to Lodge a Grievance</h2>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                                            <h5>Email Support</h5>
                                            <p class="small">Send us a detailed email with your complaint</p>
                                            <a href="mailto:support@uni-pay.in" class="btn btn-outline-primary btn-sm">Send Email</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                                            <h5>Phone Support</h5>
                                            <p class="small">Call us directly for immediate assistance</p>
                                            <a href="tel:+919549899933" class="btn btn-outline-primary btn-sm">Call Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">Grievance Categories</h2>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-shopping-bag fa-2x text-primary mb-2"></i>
                                            <h6>Order Related</h6>
                                            <p class="small">Issues with order placement, tracking, or delivery</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-box fa-2x text-primary mb-2"></i>
                                            <h6>Product Issues</h6>
                                            <p class="small">Damaged products, quality concerns, or wrong items</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-credit-card fa-2x text-primary mb-2"></i>
                                            <h6>Payment Problems</h6>
                                            <p class="small">Billing issues, refund delays, or payment errors</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-user fa-2x text-primary mb-2"></i>
                                            <h6>Account Issues</h6>
                                            <p class="small">Login problems, account access, or profile updates</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-headset fa-2x text-primary mb-2"></i>
                                            <h6>Service Quality</h6>
                                            <p class="small">Customer service experience or website issues</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-exclamation-triangle fa-2x text-primary mb-2"></i>
                                            <h6>Other Complaints</h6>
                                            <p class="small">Any other issues not covered above</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">Grievance Resolution Process</h2>
                            <div class="timeline">
                                <div class="row">
                                    <div class="col-md-3 text-center mb-3">
                                        <div class="timeline-step">
                                            <div class="timeline-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                                <i class="fas fa-1"></i>
                                            </div>
                                            <h6>Lodge Complaint</h6>
                                            <p class="small">Submit your grievance through email or phone</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center mb-3">
                                        <div class="timeline-step">
                                            <div class="timeline-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                                <i class="fas fa-2"></i>
                                            </div>
                                            <h6>Review & Acknowledge</h6>
                                            <p class="small">We review and acknowledge within 24 hours</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center mb-3">
                                        <div class="timeline-step">
                                            <div class="timeline-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                                <i class="fas fa-3"></i>
                                            </div>
                                            <h6>Investigation</h6>
                                            <p class="small">Thorough investigation of the issue</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center mb-3">
                                        <div class="timeline-step">
                                            <div class="timeline-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                                <i class="fas fa-4"></i>
                                            </div>
                                            <h6>Resolution</h6>
                                            <p class="small">Provide solution within 3-5 business days</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">Contact Information</h2>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title">Grievance Officer</h5>
                                            <p class="card-text">
                                                <strong>Email:</strong> <a href="mailto:support@uni-pay.in">support@uni-pay.in</a><br>
                                                <strong>Phone:</strong> <a href="tel:+919549899933">(+91) 9549899933</a><br>
                                                <strong>Address:</strong> Office Number 2, First Floor, Ganga Tower, Khatipura Road, Near S D Aggarwal, Jhotwara, Jaipur, Rajasthan - 302012
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title">Business Hours</h5>
                                            <p class="card-text">
                                                <strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM<br>
                                                <strong>Saturday:</strong> 9:00 AM - 4:00 PM<br>
                                                <strong>Sunday:</strong> Closed<br>
                                                <strong>Response Time:</strong> Within 24 hours
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="policy-section">
                            <h2 class="h4 text-primary mb-3">Escalation Process</h2>
                            <div class="alert alert-info">
                                <h5>If your grievance is not resolved within 5 business days:</h5>
                                <p class="mb-0">You can escalate your complaint to our senior management team. Please mention your previous complaint reference number when escalating. We are committed to resolving all grievances to your satisfaction.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 