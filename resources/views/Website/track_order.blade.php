@extends('Website.Layout.app')
@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary">Track Your Order</h1>
                    <p class="lead text-muted">Stay updated with your order status and delivery progress</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <div class="track-order-form mb-5">
                            <h3 class="h4 text-primary mb-4">Track Order Status</h3>
                            <form>
                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label for="orderNumber" class="form-label">Order Number / Tracking ID</label>
                                        <input type="text" class="form-control" id="orderNumber" placeholder="Enter your order number or tracking ID" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="submit" class="btn btn-primary w-100">Track Order</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Sample Order Tracking Result -->
                        <div class="tracking-result" id="trackingResult" style="display: none;">
                            <div class="order-summary mb-4">
                                <h4 class="text-primary">Order Summary</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Order Number:</strong> <span id="orderNum">UNI123456789</span></p>
                                        <p><strong>Order Date:</strong> <span id="orderDate">15 Dec 2024</span></p>
                                        <p><strong>Expected Delivery:</strong> <span id="expectedDelivery">20 Dec 2024</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Total Amount:</strong> <span id="totalAmount">₹1,250</span></p>
                                        <p><strong>Payment Status:</strong> <span class="badge bg-success">Paid</span></p>
                                        <p><strong>Order Status:</strong> <span class="badge bg-info">In Transit</span></p>
                                    </div>
                                </div>
                            </div>

                            <div class="tracking-timeline">
                                <h4 class="text-primary mb-4">Order Timeline</h4>
                                <div class="timeline">
                                    <div class="timeline-item completed">
                                        <div class="timeline-marker bg-success">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Order Placed</h6>
                                            <p class="text-muted">Your order has been successfully placed</p>
                                            <small class="text-muted">Dec 15, 2024 - 10:30 AM</small>
                                        </div>
                                    </div>
                                    <div class="timeline-item completed">
                                        <div class="timeline-marker bg-success">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Order Confirmed</h6>
                                            <p class="text-muted">Your order has been confirmed and is being processed</p>
                                            <small class="text-muted">Dec 15, 2024 - 11:45 AM</small>
                                        </div>
                                    </div>
                                    <div class="timeline-item completed">
                                        <div class="timeline-marker bg-success">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Order Shipped</h6>
                                            <p class="text-muted">Your order has been shipped via our courier partner</p>
                                            <small class="text-muted">Dec 16, 2024 - 2:15 PM</small>
                                        </div>
                                    </div>
                                    <div class="timeline-item active">
                                        <div class="timeline-marker bg-primary">
                                            <i class="fas fa-truck"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>In Transit</h6>
                                            <p class="text-muted">Your order is on its way to your delivery address</p>
                                            <small class="text-muted">Dec 17, 2024 - 9:20 AM</small>
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-light">
                                            <i class="fas fa-home"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Out for Delivery</h6>
                                            <p class="text-muted">Your order is out for delivery</p>
                                            <small class="text-muted">Expected: Dec 20, 2024</small>
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-light">
                                            <i class="fas fa-box"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Delivered</h6>
                                            <p class="text-muted">Your order has been delivered</p>
                                            <small class="text-muted">Expected: Dec 20, 2024</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Demo Button for Testing -->
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-outline-primary" onclick="showTrackingResult()">View Sample Tracking</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 bg-light">
                    <div class="card-body p-4">
                        <h4 class="text-primary mb-3">Need Help?</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6><i class="fas fa-phone text-primary me-2"></i>Call Us</h6>
                                <p class="mb-0">(+91) 9549899933<br>(+91) 9782144754</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6><i class="fas fa-envelope text-primary me-2"></i>Email Us</h6>
                                <p class="mb-0"><a href="mailto:support@uni-pay.in">support@uni-pay.in</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
}

.timeline-content {
    background: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-item.completed .timeline-content {
    border-left: 4px solid #28a745;
}

.timeline-item.active .timeline-content {
    border-left: 4px solid #007bff;
}
</style>

<script>
function showTrackingResult() {
    document.getElementById('trackingResult').style.display = 'block';
}
</script>
@endsection 