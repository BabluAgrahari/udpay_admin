@extends('Website.Layout.app')
@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary">Frequently Asked Questions</h1>
                    <p class="lead text-muted">Find answers to common questions about our products and services</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    <!-- Ordering Questions -->
                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <i class="fas fa-shopping-cart me-3 text-primary"></i>
                                How do I place an order?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>To place an order, simply browse our products, add items to your cart, and proceed to checkout. You'll need to create an account or sign in if you already have one. Follow the checkout process to complete your purchase.</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <i class="fas fa-credit-card me-3 text-primary"></i>
                                What payment methods do you accept?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>We accept various payment methods including:</p>
                                <ul>
                                    <li>Credit/Debit Cards</li>
                                    <li>Net Banking</li>
                                    <li>UPI Payments</li>
                                    <li>Digital Wallets</li>
                                    <li>Cash on Delivery (COD)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Questions -->
                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <i class="fas fa-truck me-3 text-primary"></i>
                                How long does shipping take?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Standard delivery takes 5-10 business days from the date of order placement. We dispatch orders within 2 working days to our trusted courier partners. You can track your order status through your account.</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                <i class="fas fa-shipping-fast me-3 text-primary"></i>
                                What are the shipping charges?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Shipping charges are Rs. 100 for all orders below Rs. 1000. Orders above Rs. 1000 qualify for free shipping across India.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Returns & Refunds -->
                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                <i class="fas fa-undo me-3 text-primary"></i>
                                What is your return policy?
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>You can return items within 30 days of receipt if they are in original unused condition. Damaged or defective products can be returned regardless of the condition. Please contact our support team at <a href="mailto:support@uni-pay.in">support@uni-pay.in</a> to initiate a return.</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header" id="headingSix">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                <i class="fas fa-money-bill-wave me-3 text-primary"></i>
                                How long does it take to process refunds?
                            </button>
                        </h2>
                        <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Refunds are processed within 15 working days from the date of receiving the returned products. The refund will be made in the same form as the original payment method.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Product Questions -->
                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header" id="headingSeven">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                <i class="fas fa-certificate me-3 text-primary"></i>
                                Are your products authentic?
                            </button>
                        </h2>
                        <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Yes, all our products are sourced directly from authorized distributors and manufacturers. We guarantee the authenticity of all products sold on our platform.</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header" id="headingEight">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                <i class="fas fa-expiry me-3 text-primary"></i>
                                How do I check product expiry dates?
                            </button>
                        </h2>
                        <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Product expiry dates are clearly mentioned on each product page. We ensure that all products have sufficient shelf life before shipping. If you receive a product with a short expiry date, please contact our support team immediately.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Account Questions -->
                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header" id="headingNine">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                                <i class="fas fa-user me-3 text-primary"></i>
                                How do I create an account?
                            </button>
                        </h2>
                        <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>You can create an account by clicking on the "Sign Up" or "Register" button on our website. You'll need to provide your basic information including name, email, and phone number. You can also create an account during the checkout process.</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm mb-3">
                        <h2 class="accordion-header" id="headingTen">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                                <i class="fas fa-headset me-3 text-primary"></i>
                                How can I contact customer support?
                            </button>
                        </h2>
                        <div id="collapseTen" class="accordion-collapse collapse" aria-labelledby="headingTen" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>You can contact our customer support team through:</p>
                                <ul>
                                    <li><strong>Phone:</strong> (+91) 9549899933, (+91) 9782144754</li>
                                    <li><strong>Email:</strong> <a href="mailto:support@uni-pay.in">support@uni-pay.in</a></li>
                                    <li><strong>Business Hours:</strong> Monday - Friday: 9:00 AM - 6:00 PM, Saturday: 9:00 AM - 4:00 PM</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Still have questions?</h5>
                            <p class="card-text">If you couldn't find the answer you're looking for, please don't hesitate to contact our support team.</p>
                            <a href="{{ route('contact') }}" class="btn btn-primary">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 