@extends('Website.Layout.app')
@section('content')
<style>
    table th {
    color: #000;
    background: var(--thm-color-two) !important;
}
</style>
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary">Compliance Documents</h1>
                    <p class="lead text-muted">Legal documents and compliance information for UniPay Digital</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">Company Information</h2>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Company Details</h5>
                                            <p class="card-text">
                                                <strong>Company Name:</strong> UniPay Digital Private Limited<br>
                                                <strong>Registration:</strong> Private Limited Company<br>
                                                <strong>GST Number:</strong> Available on request<br>
                                                <strong>PAN Number:</strong> Available on request
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Registered Office</h5>
                                            <p class="card-text">
                                                Office Number 2, First Floor<br>
                                                Ganga Tower, Khatipura Road<br>
                                                Near S D Aggarwal, Jhotwara<br>
                                                Jaipur, Rajasthan - 302012<br>
                                                India
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">Legal Documents</h2>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-file-contract fa-3x text-primary mb-3"></i>
                                            <h5>Terms & Conditions</h5>
                                            <p class="small">Our terms of service and usage conditions</p>
                                            <a href="{{ route('terms.conditions') }}" class="btn btn-outline-primary btn-sm">View Document</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                                            <h5>Privacy Policy</h5>
                                            <p class="small">How we collect, use, and protect your data</p>
                                            <a href="{{ route('privacy.policy') }}" class="btn btn-outline-primary btn-sm">View Document</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-undo fa-3x text-primary mb-3"></i>
                                            <h5>Return Policy</h5>
                                            <p class="small">Our return and refund policies</p>
                                            <a href="{{ route('return.policy') }}" class="btn btn-outline-primary btn-sm">View Document</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">Regulatory Compliance</h2>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Consumer Protection</h5>
                                            <p class="card-text">We comply with all applicable consumer protection laws and regulations in India. Our policies are designed to protect consumer rights and ensure fair business practices.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Data Protection</h5>
                                            <p class="card-text">We follow strict data protection guidelines and comply with relevant privacy laws. Your personal information is protected and used only for legitimate business purposes.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">Business Licenses & Certifications</h2>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Document Type</th>
                                            <th>Status</th>
                                            <th>Validity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Company Registration</td>
                                            <td><span class="badge bg-success">Active</span></td>
                                            <td>Valid</td>
                                        </tr>
                                        <tr>
                                            <td>GST Registration</td>
                                            <td><span class="badge bg-success">Active</span></td>
                                            <td>Valid</td>
                                        </tr>
                                        <tr>
                                            <td>PAN Registration</td>
                                            <td><span class="badge bg-success">Active</span></td>
                                            <td>Valid</td>
                                        </tr>
                                        <tr>
                                            <td>Business License</td>
                                            <td><span class="badge bg-success">Active</span></td>
                                            <td>Valid</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">Quality Assurance</h2>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="text-center">
                                        <i class="fas fa-certificate fa-2x text-primary mb-2"></i>
                                        <h6>Product Quality</h6>
                                        <p class="small">All products sourced from authorized distributors</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="text-center">
                                        <i class="fas fa-award fa-2x text-primary mb-2"></i>
                                        <h6>Service Standards</h6>
                                        <p class="small">Committed to high customer service standards</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="text-center">
                                        <i class="fas fa-check-circle fa-2x text-primary mb-2"></i>
                                        <h6>Compliance</h6>
                                        <p class="small">Full compliance with all applicable regulations</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="policy-section">
                            <h2 class="h4 text-primary mb-3">Contact for Legal Inquiries</h2>
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <p class="card-text">For any legal inquiries or to request specific compliance documents, please contact our legal team:</p>
                                    <p class="card-text">
                                        <strong>Email:</strong> <a href="mailto:enquiry@uni-pay.in">enquiry@uni-pay.in</a><br>
                                        <strong>Phone:</strong> <a href="tel:+919549899933">(+91) 9549899933</a><br>
                                        <strong>Address:</strong> Office Number 2, First Floor, Ganga Tower, Khatipura Road, Near S D Aggarwal, Jhotwara, Jaipur, Rajasthan - 302012
                                    </p>
                                    <p class="card-text mb-0"><strong>Note:</strong> All legal documents are subject to periodic updates. Please check back regularly for the most current versions.</p>
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
