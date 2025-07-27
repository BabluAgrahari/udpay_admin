@extends('Website.Layout.app')
@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary">Get in Touch</h1>
                    <p class="lead text-muted">We're here to help and answer any questions you might have</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <h3 class="mb-4">Send us a Message</h3>
                        <form>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstName" class="form-label">First Name *</label>
                                    <input type="text" class="form-control" id="firstName" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control" id="lastName" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="phone" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject *</label>
                                <select class="form-select" id="subject" required>
                                    <option value="">Select a subject</option>
                                    <option value="general">General Inquiry</option>
                                    <option value="order">Order Related</option>
                                    <option value="product">Product Information</option>
                                    <option value="support">Technical Support</option>
                                    <option value="complaint">Complaint</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message *</label>
                                <textarea class="form-control" id="message" rows="5" placeholder="Please describe your inquiry in detail..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg px-5">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="contact-info">
                    <h3 class="mb-4">Contact Information</h3>
                    
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="card-title">Office Address</h5>
                                    <p class="card-text">
                                        <strong>Address 1:</strong><br>
                                        Office Number 2, First Floor<br>
                                        Ganga Tower, Khatipura Road<br>
                                        Near S D Aggarwal, Jhotwara<br>
                                        Jaipur, Rajasthan - 302012
                                    </p>
                                    <p class="card-text">
                                        <strong>Address 2:</strong><br>
                                        Plot No. 130, Officer Enclave<br>
                                        Kalwar Road, Jhotwara<br>
                                        Jaipur, Rajasthan - 302012
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-phone fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="card-title">Phone Numbers</h5>
                                    <p class="card-text">
                                        <a href="tel:+919549899933" class="text-decoration-none">(+91) 9549899933</a><br>
                                        <a href="tel:+919782144754" class="text-decoration-none">(+91) 9782144754</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-envelope fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="card-title">Email Address</h5>
                                    <p class="card-text">
                                        <strong>Business Enquiries:</strong><br>
                                        <a href="mailto:enquiry@uni-pay.in" class="text-decoration-none">enquiry@uni-pay.in</a>
                                    </p>
                                    <p class="card-text">
                                        <strong>Customer Support:</strong><br>
                                        <a href="mailto:support@uni-pay.in" class="text-decoration-none">support@uni-pay.in</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clock fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="card-title">Business Hours</h5>
                                    <p class="card-text">
                                        <strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM<br>
                                        <strong>Saturday:</strong> 9:00 AM - 4:00 PM<br>
                                        <strong>Sunday:</strong> Closed
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