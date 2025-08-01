@extends('Website.Layout.app')
@section('content')
<section class="section-padding py-4">
    <div class="container">
        <div class="text-center">
            <h4 class="mb-2">Contact Us</h4>
            <p>Any question or remarks? Just write us a message!</p>
        </div>
        <div class="contact-wrapper">
            <div class="row">
                <div class="col-lg-4">
                    <div class="contact-left">
                        <div class="contact-title">
                            <h4>Contact Information</h4>
                            <p>Say something to start a live chat!</p>
                        </div>
                        <ul class="contact-list">
                            <li class="contact-item"><i class="fa-solid fa-phone-volume"></i> +1012 3456 789</li>
                            <li class="contact-item"><i class="fa-solid fa-envelope"></i> demo@gmail.com</li>
                            <li class="contact-item"><i class="fa-solid fa-location-dot"></i>132 Dartmouth Street Boston, Massachusetts 02156 United States</li>
                        </ul>
                        <ul class="ft-social-icon">
                            <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                            <li><a href="#"><i class="fa-solid fa-paper-plane"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="contact-form">
                        <form>
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" >
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" >
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" >
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control" >
                                </div>
                                <div class="col-sm-12 form-group">
                                    <label>Select Subject?</label>
                                    <div class="subject-contant">
                                        <label><input type="radio" name="subject">General Inquiry</label>
                                        <label><input type="radio" name="subject">General Inquiry</label>
                                        <label><input type="radio" name="subject">General Inquiry</label>
                                        <label><input type="radio" name="subject">General Inquiry</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 form-group">
                                    <label>Message</label>
                                    <input type="text" class="form-control" placeholder="Write your message..">
                                </div>
                            </div>
                            <div class="text-end mt-4">
                                <button type="button" class="thm-btn">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection