<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 9/29/2020
 * Time: 1:44 PM
 */
?>

@extends('landing.master')


@section('content')
<!--============= Banner Section Starts Here =============-->
<section class="banner-7 bg_img oh bottom_right" data-background="{{ asset('public/frontend_assets/images/banner/banner-bg-7.jpg') }}">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="banner-content-7">
                    <h1 class="title">Enlarge Your Business With Web app</h1>
                    <p>
                        The simple, intuitive and powerful app to manage your work.
                    </p>
                    <div class="banner-button-group">
                        <a href="#0" class="button-4">Start Using for Free</a>
                        <a href="#0" class="button-4 active">Explore Features</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-lg-block d-none">
                <img src="{{ asset('public/frontend_assets/images/banner/banner-7.png') }}" alt="banner">
            </div>
            <div class="col-12">
                <div class="counter-wrapper-3">
                    <div class="counter-item">
                        <div class="counter-thumb">
                            <img src="{{ asset('public/frontend_assets/images/icon/counter1.png') }}" alt="icon">
                        </div>
                        <div class="counter-content">
                            <h2 class="title"><span class="counter">17501</span></h2>
                            <span class="name">Premium User</span>
                        </div>
                    </div>
                    <div class="counter-item">
                        <div class="counter-thumb">
                            <img src="{{ asset('public/frontend_assets/images/icon/counter2.png') }}" alt="icon">
                        </div>
                        <div class="counter-content">
                            <h2 class="title"><span class="counter">1987</span></h2>
                            <span class="name">Daily Visitors</span>
                        </div>
                    </div>
                    <div class="counter-item">
                        <div class="counter-thumb">
                            <img src="{{ asset('public/frontend_assets/images/icon/counter5.png') }}" alt="icon">
                        </div>
                        <div class="counter-content">
                            <h2 class="title"><span class="counter">95</span><span>%</span></h2>
                            <span class="name">Satisfaction</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--============= Banner Section Ends Here =============-->


<!--============= To Access Section Starts Here =============-->
<section class="to-access-section padding-top padding-bottom bg_img mb-lg-5" data-background="{{ asset('public/frontend_assets/images/feature/to-access-bg.png') }}" id="feature">
    <div class="container">
        <div class="section-header">
            <h5 class="cate">Amazing features to convince you</h5>
            <h2 class="title">To Use Our Application</h2>
            <p>In the process of making a app, the satisfaction of users is the most
                important and the focus is on usability and completeness</p>
        </div>
        <div class="row mb-30 justify-content-center">
            <div class="col-lg-3 col-sm-6">
                <div class="to-access-item">
                    <div class="to-access-thumb">
                        <span class="anime"></span>
                        <div class="thumb">
                            <img src="{{ asset('public/frontend_assets/images/icon/access1.png') }}" alt="access">
                        </div>
                    </div>
                    <h5 class="title">Productivity</h5>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="to-access-item active">
                    <div class="to-access-thumb">
                        <span class="anime"></span>
                        <div class="thumb">
                            <img src="{{ asset('public/frontend_assets/images/icon/access2.png') }}" alt="access">
                        </div>
                    </div>
                    <h5 class="title">Optimization</h5>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="to-access-item">
                    <div class="to-access-thumb">
                        <span class="anime"></span>
                        <div class="thumb">
                            <img src="{{ asset('public/frontend_assets/images/icon/access3.png') }}" alt="access">
                        </div>
                    </div>
                    <h5 class="title">Safety</h5>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="to-access-item">
                    <div class="to-access-thumb">
                        <span class="anime"></span>
                        <div class="thumb">
                            <img src="{{ asset('public/frontend_assets/images/icon/access4.png') }}" alt="access">
                        </div>
                    </div>
                    <h5 class="title">Sustainability</h5>
                </div>
            </div>
        </div>
        <div class="text-center">
            <a href="feature.html" class="get-button">Explore Features</a>
        </div>
    </div>
</section>
<!--============= To Access Section Ends Here =============-->


<!--============= How It Works Section Starts Here =============-->
<section class="work-section padding-bottom bg_img mb-md-95 pb-md-0" data-background="{{ asset('public/frontend_assets/images/work/work-bg.jpg') }}"  id="how">
    <div class="oh padding-top pos-rel">
        <div class="top-shape d-none d-lg-block">
            <img src="{{ asset('public/frontend_assets/css/img/work-shape.png') }}" alt="css">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-xl-7">
                    <div class="section-header left-style cl-white">
                        <h5 class="cate">Describe Your App</h5>
                        <h2 class="title">Let’s See How It Work</h2>
                        <p>It's easier than you think.Follow the simple easy steps</p>
                    </div>
                </div>
            </div>
            <div class="work-slider owl-carousel owl-theme" data-slider-id="2">
                <div class="work-item">
                    <div class="work-thumb">
                        <img src="{{ asset('public/frontend_assets/images/work/work1.png') }}" alt="work">
                    </div>
                    <div class="work-content cl-white">
                        <h3 class="title">Register</h3>
                        <p>First, you need to register a user account on our website before
                            configuring and using it on a regular basis.</p>
                        <a href="#0" class="get-button white light">Get Started <i class="flaticon-right"></i></a>
                    </div>
                </div>
                <div class="work-item">
                    <div class="work-thumb">
                        <img src="{{ asset('public/frontend_assets/images/work/work1.png') }}" alt="work">
                    </div>
                    <div class="work-content cl-white">
                        <h3 class="title">Configure</h3>
                        <p>First, you need to register a user account on our website before
                            configuring and using it on a regular basis.</p>
                        <a href="#0" class="get-button white light">Get Started <i class="flaticon-right"></i></a>
                    </div>
                </div>
                <div class="work-item">
                    <div class="work-thumb">
                        <img src="{{ asset('public/frontend_assets/images/work/work1.png') }}" alt="work">
                    </div>
                    <div class="work-content cl-white">
                        <h3 class="title">Integrate</h3>
                        <p>First, you need to register a user account on our website before
                            configuring and using it on a regular basis.</p>
                        <a href="#0" class="get-button white light">Get Started <i class="flaticon-right"></i></a>
                    </div>
                </div>
                <div class="work-item">
                    <div class="work-thumb">
                        <img src="{{ asset('public/frontend_assets/images/work/work1.png') }}" alt="work">
                    </div>
                    <div class="work-content cl-white">
                        <h3 class="title">Yay! Done</h3>
                        <p>First, you need to register a user account on our website before
                            configuring and using it on a regular basis.</p>
                        <a href="#0" class="get-button white light">Get Started <i class="flaticon-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="count-slider owl-thumbs" data-slider-id="2">
            <div class="count-item">
                <span class="serial">01</span>
                <h5 class="title">Register</h5>
            </div>
            <div class="count-item">
                <span class="serial">02</span>
                <h5 class="title">Configure</h5>
            </div>
            <div class="count-item">
                <span class="serial">03</span>
                <h5 class="title">Integrade</h5>
            </div>
            <div class="count-item">
                <span class="serial">04</span>
                <h5 class="title">Yay! Done</h5>
            </div>
        </div>
    </div>
</section>
<!--============= How It Works Section Ends Here =============-->


<!--============= Access Section Starts Here =============-->
<section class="access-section padding-bottom padding-top oh">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-4 col-xl-3 rtl d-none d-lg-block">
                <img src="{{ asset('public/frontend_assets/images/access/access1.png') }}" alt="access">
            </div>
            <div class="col-lg-8">
                <div class="access-content">
                    <div class="section-header left-style mb-olpo">
                        <h5 class="cate">All in One Access</h5>
                        <h2 class="title">Everything your business needs in one central place</h2>
                        <p>Our innovative, easy-to-use tools live in just one platform, saving you time and
                            streamlining your work. Make admin tasks more efficient, get paid faster, and collaborate more seamlessly.</p>
                    </div>
                    <a href="#0" class="get-button">get free  trial <i class="flaticon-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--============= Access Section Ends Here =============-->


<!--============= Feature Video Section Starts Here =============-->
<section class="feature-video-section padding-top dark-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="section-header mw-100">
                    <h5 class="cate">Amazing features to convince you</h5>
                    <h2 class="title">Watch our video presentation</h2>
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <p>In the process of making a app, the satisfaction of users is the most
                                important and the focus is on usability and completeness</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="pos-rel mw-100">
                    <img class="w-100" src="{{ asset('public/frontend_assets/images/feature/feature-video.png') }}" alt="bg">
                    <a href="https://www.youtube.com/watch?v=GT6-H4BRyqQ" class="video-button-2 popup">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <i class="flaticon-play"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--============= Feature Video Section Ends Here =============-->


<!--============= Advance Feature Section Starts Here =============-->
<section class="advance-feature-section padding-top-2 padding-bottom-2">
    <div class="container">
        <div class="advance-feature-item padding-top-2 padding-bottom-2">
            <div class="advance-feature-thumb">
                <img src="{{ asset('public/frontend_assets/images/feature/advance1.png') }}" alt="feature">
            </div>
            <div class="advance-feature-content">
                <div class="section-header left-style mb-olpo">
                    <h5 class="cate">Advance features give you full control.</h5>
                    <h2 class="title">See what you can achieve!</h2>
                    <p>The simple, intuitive user interface is designed to help you see exactly what you need to focus on.As a team member you can focus on your work and as a team leader, you can easily manage your team.</p>
                </div>
                <a href="#0" class="get-button">get free  trial <i class="flaticon-right"></i></a>
            </div>
        </div>
        <div class="advance-feature-item padding-top-2 padding-bottom-2">
            <div class="advance-feature-thumb">
                <img src="{{ asset('public/frontend_assets/images/feature/advance2.png') }}" alt="feature">
            </div>
            <div class="advance-feature-content">
                <div class="section-header left-style mb-olpo">
                    <h5 class="cate">Powerful and Flexible</h5>
                    <h2 class="title">Collaboration for team work</h2>
                    <p>The simple, intuitive user interface is designed to help you see exactly what you need to focus on.As a team member you can focus on your work and as a team leader, you can easily manage your team.</p>
                </div>
                <a href="#0" class="get-button">get free  trial <i class="flaticon-right"></i></a>
            </div>
        </div>
        <div class="advance-feature-item padding-top-2 padding-bottom-2">
            <div class="advance-feature-thumb">
                <img src="{{ asset('public/frontend_assets/images/feature/advance3.png') }}" alt="feature">
            </div>
            <div class="advance-feature-content">
                <div class="section-header left-style mb-olpo">
                    <h5 class="cate">Discover powerful tool for your customers.</h5>
                    <h2 class="title">Great template for your Web app</h2>
                    <p>The simple, intuitive user interface is designed to help you see exactly what you need to focus on.As a team member you can focus on your work and as a team leader, you can easily manage your team.</p>
                </div>
                <a href="#0" class="get-button">get free  trial <i class="flaticon-right"></i></a>
            </div>
        </div>
    </div>
</section>
<!--============= Advance Feature Section Ends Here =============-->


<!--============= Pricing Section Starts Here =============-->
<section class="pricing-section padding-top oh padding-bottom pb-lg-half bg_img pos-rel" data-background="{{ asset('public/frontend_assets/images/bg/pricing-bg.jpg') }}" id="pricing">
    <div class="top-shape d-none d-md-block">
        <img src="{{ asset('public/frontend_assets/css/img/top-shape.png') }}" alt="css">
    </div>
    <div class="bottom-shape d-none d-md-block mw-0">
        <img src="{{ asset('public/frontend_assets/css/img/bottom-shape.png') }}" alt="css">
    </div>
    <div class="ball-2" data-paroller-factor="-0.30" data-paroller-factor-lg="0.60"
         data-paroller-type="foreground" data-paroller-direction="horizontal">
        <img src="{{ asset('public/frontend_assets/images/balls/1.png') }}" alt="balls">
    </div>
    <div class="ball-3" data-paroller-factor="0.30" data-paroller-factor-lg="-0.30"
         data-paroller-type="foreground" data-paroller-direction="horizontal">
        <img src="{{ asset('public/frontend_assets/images/balls/2.png') }}" alt="balls">
    </div>
    <div class="ball-4" data-paroller-factor="0.30" data-paroller-factor-lg="-0.30"
         data-paroller-type="foreground" data-paroller-direction="horizontal">
        <img src="{{ asset('public/frontend_assets/images/balls/3.png') }}" alt="balls">
    </div>
    <div class="ball-5" data-paroller-factor="0.30" data-paroller-factor-lg="-0.30"
         data-paroller-type="foreground" data-paroller-direction="vertical">
        <img src="{{ asset('public/frontend_assets/images/balls/4.png') }}" alt="balls">
    </div>
    <div class="ball-6" data-paroller-factor="-0.30" data-paroller-factor-lg="0.60"
         data-paroller-type="foreground" data-paroller-direction="horizontal">
        <img src="{{ asset('public/frontend_assets/images/balls/5.png') }}" alt="balls">
    </div>
    <div class="ball-7" data-paroller-factor="-0.30" data-paroller-factor-lg="0.60"
         data-paroller-type="foreground" data-paroller-direction="vertical">
        <img src="{{ asset('public/frontend_assets/images/balls/6.png') }}" alt="balls">
    </div>
    <div class="container">
        <div class="section-header cl-white">
            <h5 class="cate">Choose a plan that's right for you</h5>
            <h2 class="title">Simple Pricing Plans</h2>
            <p>
                Hisebi has plans, from free to paid, that scale with your needs. Subscribe to a plan that fits the size of your business.
            </p>
        </div>
        <div class="tab-up">
            <ul class="tab-menu pricing-menu">
                <li class="active">Monthly</li>
                <li>Yearly</li>
            </ul>
            <div class="tab-area">
                <div class="tab-item active">
                    <div class="pricing-slider-wrapper">
                        <div class="pricing-slider owl-theme owl-carousel">
                            <div class="pricing-item-2">
                                <h5 class="cate">Startup</h5>
                                <div class="thumb">
                                    <img src="{{ asset('public/frontend_assets/images/pricing/pricing1.png') }}" alt="pricing">
                                </div>
                                <h2 class="title"><sup>$</sup>5.0</h2>
                                <span class="info">Per Month</span>
                                <ul class="pricing-content-3">
                                    <li>3 Users 10 GB Storage</li>
                                    <li>Monthly Updates</li>
                                    <li>eCommerce Integration</li>
                                    <li>Interface Customization</li>
                                    <li>24/7 Support</li>
                                </ul>
                                <a href="#0" class="get-button">Select Plan<i class="flaticon-right"></i></a>
                            </div>
                            <div class="pricing-item-2">
                                <h5 class="cate">Standard</h5>
                                <div class="thumb">
                                    <img src="{{ asset('public/frontend_assets/images/pricing/pricing2.png') }}" alt="pricing">
                                </div>
                                <h2 class="title"><sup>$</sup>10</h2>
                                <span class="info">Per Month</span>
                                <ul class="pricing-content-3">
                                    <li>5 Users 20 GB Storage </li>
                                    <li>Weekly Updates</li>
                                    <li>eCommerce Integration</li>
                                    <li>Interface Customization</li>
                                    <li>24/7 Support</li>
                                </ul>
                                <a href="#0" class="get-button">Select Plan<i class="flaticon-right"></i></a>
                            </div>
                            <div class="pricing-item-2">
                                <h5 class="cate">Business</h5>
                                <div class="thumb">
                                    <img src="{{ asset('public/frontend_assets/images/pricing/pricing3.png') }}" alt="pricing">
                                </div>
                                <h2 class="title"><sup>$</sup>20</h2>
                                <span class="info">Per Month</span>
                                <ul class="pricing-content-3">
                                    <li>10 Users 40 GB Storage </li>
                                    <li>Daily Updates</li>
                                    <li>eCommerce Integration</li>
                                    <li>Interface Customization</li>
                                    <li>24/7 Support</li>
                                </ul>
                                <a href="#0" class="get-button">Select Plan<i class="flaticon-right"></i></a>
                            </div>
                            <div class="pricing-item-2">
                                <h5 class="cate">Premium</h5>
                                <div class="thumb">
                                    <img src="{{ asset('public/frontend_assets/images/pricing/pricing4.png') }}" alt="pricing">
                                </div>
                                <h2 class="title"><sup>$</sup>30</h2>
                                <span class="info">Per Month</span>
                                <ul class="pricing-content-3">
                                    <li>15 Users 60 GB Storage </li>
                                    <li>Free Updates</li>
                                    <li>eCommerce Integration</li>
                                    <li>Interface Customization</li>
                                    <li>24/7 Support</li>
                                </ul>
                                <a href="#0" class="get-button">Select Plan<i class="flaticon-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-item">
                    <div class="pricing-slider-wrapper">
                        <div class="pricing-slider owl-theme owl-carousel">
                            <div class="pricing-item-2">
                                <h5 class="cate">Startup</h5>
                                <div class="thumb">
                                    <img src="{{ asset('public/frontend_assets/images/pricing/pricing1.png') }}" alt="pricing">
                                </div>
                                <h2 class="title"><sup>$</sup>60</h2>
                                <span class="info">Per Month</span>
                                <ul class="pricing-content-3">
                                    <li>3 Users 10 GB Storage</li>
                                    <li>Monthly Updates</li>
                                    <li>eCommerce Integration</li>
                                    <li>Interface Customization</li>
                                    <li>24/7 Support</li>
                                </ul>
                                <a href="#0" class="get-button">Select Plan<i class="flaticon-right"></i></a>
                            </div>
                            <div class="pricing-item-2">
                                <h5 class="cate">Standard</h5>
                                <div class="thumb">
                                    <img src="{{ asset('public/frontend_assets/images/pricing/pricing2.png') }}" alt="pricing">
                                </div>
                                <h2 class="title"><sup>$</sup>120</h2>
                                <span class="info">Per Month</span>
                                <ul class="pricing-content-3">
                                    <li>5 Users 20 GB Storage </li>
                                    <li>Weekly Updates</li>
                                    <li>eCommerce Integration</li>
                                    <li>Interface Customization</li>
                                    <li>24/7 Support</li>
                                </ul>
                                <a href="#0" class="get-button">Select Plan<i class="flaticon-right"></i></a>
                            </div>
                            <div class="pricing-item-2">
                                <h5 class="cate">Business</h5>
                                <div class="thumb">
                                    <img src="{{ asset('public/frontend_assets/images/pricing/pricing3.png') }}" alt="pricing">
                                </div>
                                <h2 class="title"><sup>$</sup>180</h2>
                                <span class="info">Per Month</span>
                                <ul class="pricing-content-3">
                                    <li>10 Users 40 GB Storage </li>
                                    <li>Daily Updates</li>
                                    <li>eCommerce Integration</li>
                                    <li>Interface Customization</li>
                                    <li>24/7 Support</li>
                                </ul>
                                <a href="#0" class="get-button">Select Plan<i class="flaticon-right"></i></a>
                            </div>
                            <div class="pricing-item-2">
                                <h5 class="cate">Premium</h5>
                                <div class="thumb">
                                    <img src="{{ asset('public/frontend_assets/images/pricing/pricing4.png') }}" alt="pricing">
                                </div>
                                <h2 class="title"><sup>$</sup>270</h2>
                                <span class="info">Per Month</span>
                                <ul class="pricing-content-3">
                                    <li>15 Users 60 GB Storage </li>
                                    <li>Free Updates</li>
                                    <li>eCommerce Integration</li>
                                    <li>Interface Customization</li>
                                    <li>24/7 Support</li>
                                </ul>
                                <a href="#0" class="get-button">Select Plan<i class="flaticon-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--============= Pricing Section Ends Here =============-->


<!--============= Testimonial Section Starts Here =============-->
<section class="testimonial-section padding-top pt-lg-half" id="client">
    <div class="container">
        <div class="section-header">
            <h5 class="cate">Testimonials</h5>
            <h2 class="title">5000+ happy clients all around the world</h2>
        </div>
        <div class="testimonial-wrapper">
            <a href="#0" class="testi-next trigger">
                <img src="{{ asset('public/frontend_assets/images/client/left.png') }}" alt="client">
            </a>
            <a href="#0" class="testi-prev trigger">
                <img src="{{ asset('public/frontend_assets/images/client/right.png') }}" alt="client">
            </a>
            <div class="testimonial-area testimonial-slider owl-carousel owl-theme">
                <div class="testimonial-item">
                    <div class="testimonial-thumb">
                        <div class="thumb">
                            <img src="{{ asset('public/frontend_assets/images/client/client1.jpg') }}" alt="client">
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <div class="ratings">
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                        </div>
                        <p>
                            Awesome product. The guys have put huge effort into this app and focused on simplicity and ease of use.
                        </p>
                        <h5 class="title"><a href="#0">Bela Bose</a></h5>
                    </div>
                </div>
                <div class="testimonial-item">
                    <div class="testimonial-thumb">
                        <div class="thumb">
                            <img src="{{ asset('public/frontend_assets/images/client/client1.jpg') }}" alt="client">
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <div class="ratings">
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                        </div>
                        <p>
                            Awesome product. The guys have put huge effort into this app and focused on simplicity and ease of use.
                        </p>
                        <h5 class="title"><a href="#0">Raihan Rafuj</a></h5>
                    </div>
                </div>
            </div>
            <div class="button-area">
                <a href="#0" class="button-2">Leave a review <i class="flaticon-right"></i></a>
            </div>
        </div>
    </div>
</section>
<!--============= Testimonial Section Ends Here =============-->


<!--============= Faq Section Starts Here =============-->
<section class="faq-section padding-top">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="faq-header">
                    <div class="cate">
                        <img src="{{ asset('public/frontend_assets/images/cate.png') }}" alt="cate">
                    </div>
                    <h2 class="title">Frequently Asked Questions</h2>
                    <a href="#0">More Questions <i class="flaticon-right"></i></a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="faq-wrapper mb--38">
                    <div class="faq-item">
                        <div class="faq-thumb">
                            <i class="flaticon-pdf"></i>
                        </div>
                        <div class="faq-content">
                            <h4 class="title">Is the Web App Secure?</h4>
                            <p>
                                Web application security is the process of protecting websites and online services against different security threats that exploit vulnerabilities in an application’s code.
                            </p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-thumb">
                            <i class="flaticon-pdf"></i>
                        </div>
                        <div class="faq-content">
                            <h4 class="title">What features does the Web App have?</h4>
                            <p>
                                Both the Mobile Apps and the Web App give you the ability to you to access your account information, view news releases, report an outage, and contact us via email or phone.
                            </p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-thumb">
                            <i class="flaticon-pdf"></i>
                        </div>
                        <div class="faq-content">
                            <h4 class="title">How do I get the Mobile App for my phone?</h4>
                            <p>
                                Both the Mobile Apps and the Web App give you the ability to you to access your account information, view news releases, report an outage, and contact us via email or phone.
                            </p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-thumb">
                            <i class="flaticon-pdf"></i>
                        </div>
                        <div class="faq-content">
                            <h4 class="title">How does Hisebi differ from usual apps? </h4>
                            <p>
                                Both the Mobile Apps and the Web App give you the ability to you to access your account information, view news releases, report an outage, and contact us via email or phone.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--============= Faq Section Ends Here =============-->


<!--============= Trial Section Starts Here =============-->
<section class="trial-section padding-bottom padding-top">
    <div class="container">
        <div class="trial-wrapper padding-top padding-bottom pr">
            <div class="ball-1">
                <img src="{{ asset('public/frontend_assets/images/balls/balls.png') }}" alt="balls">
            </div>
            <div class="trial-content cl-white">
                <h3 class="title">Start your 30 days free trials today!</h3>
                <p>
                    We have provided 30 Days Money Back <br> Guarantee in case of dissatisfaction with our product.
                </p>
            </div>
            <div class="trial-button">
                <a href="#0" class="transparent-button">Get Free Trial <i class="flaticon-right ml-xl-2"></i></a>
            </div>
        </div>
    </div>
</section>
<!--============= Trial Section Ends Here =============-->
@endsection
