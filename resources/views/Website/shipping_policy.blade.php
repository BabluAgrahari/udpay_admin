@extends('Website.Layout.app')
@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary">Shipping Policy</h1>
                    <p class="lead text-muted">Fast and reliable delivery across India</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">Shipping Information</h2>
                            <p>After the order you have placed with us, your products delivery made by our trusted courier partner. Delivery usually takes 5-10 business days from the date of order placement. The estimated delivery times are indicative, hence there may be some unforeseeable delays, which are beyond our control.</p>
                            <div class="alert alert-info">
                                <strong>Note:</strong> In the event, the Company is unable to deliver the Product within the estimated delivery date due to any reason, you will be notified by an e-mail/ WhatsApp or by calling the reason for such delay. You will have the right either to cancel the ordered Product or wait for the Product to be delivered.
                            </div>
                        </div>

                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">Shipping Charges</h2>
                            <div class="card border-0 bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-shipping-fast fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">Shipping Cost</h5>
                                    <p class="card-text"><strong>Rs. 100</strong> will be the shipping charge for all orders below <strong>Rs. 1000</strong>.</p>
                                    <p class="card-text mb-0">Free shipping available for orders above Rs. 1000.</p>
                                </div>
                            </div>
                        </div>

                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">Track My Order</h2>
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <p class="card-text">As soon as you place your order with us, we dispatched your order to our trusted courier partner for shipment within 2 working days. After dispatching your order, you can track your order by logging into your account on our website by using the username and password created at the time of your registration, and view your order details in "Order" section.</p>
                                </div>
                            </div>
                        </div>

                        <div class="policy-section mb-5">
                            <h2 class="h4 text-primary mb-3">Damages & Claims</h2>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">Third Party Products</h5>
                                            <p class="card-text">For Our website or mobile app containing third party website links or vendor products in Uni-deal sections UNIPAY DIGITAL PVT LTD is not liable for such products damaged or lost during shipping.</p>
                                            <div class="alert alert-warning mt-3">
                                                <strong>Note:</strong> Such website have self shipping policy and privacy policy, we recommends carefully read their policies before placing your orders.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">Our Products</h5>
                                            <p class="card-text">For Uni-deal and Unipay Digital Pvt Ltd products: If you received your order damaged, please contact the shipment carrier or our support team directly to file a claim.</p>
                                            <div class="alert alert-info mt-3">
                                                <strong>Important:</strong> Please save all packaging material and damaged goods before filling claim.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="policy-section">
                            <h2 class="h4 text-primary mb-3">Shipping Features</h2>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-truck fa-2x text-primary"></i>
                                        </div>
                                        <h5>Fast Delivery</h5>
                                        <p class="text-muted small">5-10 business days delivery across India</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                                        </div>
                                        <h5>Real-time Tracking</h5>
                                        <p class="text-muted small">Track your order status anytime</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-shield-alt fa-2x text-primary"></i>
                                        </div>
                                        <h5>Secure Packaging</h5>
                                        <p class="text-muted small">Products packed with care</p>
                                    </div>
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