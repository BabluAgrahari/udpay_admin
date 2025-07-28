@extends('Website.Layout.app')
@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary">About UniPay Digital</h1>
                    <p class="lead text-muted">Empowering Digital Commerce with Trust and Innovation</p>
                </div>
            </div>
        </div>
        
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <div class="about-content">
                    <h2 class="h3 mb-4">Who We Are</h2>
                    <p class="lead mb-4">UniPay Digital Private Limited is a leading digital commerce platform dedicated to providing high-quality health and wellness products to our customers across India.</p>
                    <p class="mb-4">Founded with a vision to make premium health supplements and wellness products accessible to everyone, we have built a trusted platform that connects customers with authentic, high-quality products from renowned brands.</p>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="text-primary fw-bold">1000+</h3>
                                <p class="text-muted">Happy Customers</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="text-primary fw-bold">500+</h3>
                                <p class="text-muted">Products</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-image text-center">
                    <img src="{{ asset('front_assets/images/about-logo.png') }}" alt="About UniPay" class="img-fluid rounded shadow-lg" style="max-width: 400px;">
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-12">
                <h2 class="h3 text-center mb-5">Our Mission & Vision</h2>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-bullseye fa-3x text-primary"></i>
                        </div>
                        <h4 class="card-title">Our Mission</h4>
                        <p class="card-text">To provide authentic, high-quality health and wellness products while ensuring exceptional customer service and building lasting relationships with our customers.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-eye fa-3x text-primary"></i>
                        </div>
                        <h4 class="card-title">Our Vision</h4>
                        <p class="card-text">To become the most trusted and preferred digital platform for health and wellness products, contributing to a healthier lifestyle for millions of Indians.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h2 class="h3 text-center mb-5">Why Choose UniPay Digital?</h2>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-shield-alt fa-2x text-primary"></i>
                    </div>
                    <h5>Authentic Products</h5>
                    <p class="text-muted small">All products are sourced directly from authorized distributors and manufacturers</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-shipping-fast fa-2x text-primary"></i>
                    </div>
                    <h5>Fast Delivery</h5>
                    <p class="text-muted small">Quick and reliable delivery across India with real-time tracking</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-headset fa-2x text-primary"></i>
                    </div>
                    <h5>24/7 Support</h5>
                    <p class="text-muted small">Round-the-clock customer support to assist you with any queries</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-undo fa-2x text-primary"></i>
                    </div>
                    <h5>Easy Returns</h5>
                    <p class="text-muted small">Hassle-free return policy for customer satisfaction</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection