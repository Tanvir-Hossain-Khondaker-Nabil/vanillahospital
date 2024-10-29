<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 10/4/2020
 * Time: 1:45 PM
 */
?>



@extends('landing.master')

@section('content')


<!--============= Header Section Ends Here =============-->
<section class="page-header bg_img" data-background="{{ asset('public/frontend_assets/images/page-header.png') }}">
    <div class="bottom-shape d-none d-md-block">
        <img src="{{ asset('public/frontend_assets/css/img/page-header.png') }}" alt="css">
    </div>
    <div class="container">
        <div class="page-header-content cl-white">
            <h2 class="title">Our Partners</h2>
            <ul class="breadcrumb">
                <li>
                    <a href="{{ route('index') }}">Home</a>
                </li>
                <li>
                    <a href="#0">Pages</a>
                </li>
                <li>
                    Our Partners
                </li>
            </ul>
        </div>
    </div>
</section>
<!--============= Header Section Ends Here =============-->



<!--============= Partner Section Starts Here =============-->
<section class="partner-section padding-top padding-bottom oh">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-9">
                <div class="section-header mw-100">
                    <h5 class="cate">Meet With Our Valuable Partner</h5>
                    <h2 class="title">They trust us... would you?</h2>
                    <p>
                        Our partners are some of the most reputable lenders on the market. Join their ranks and cooperate with us to increase your profit.
                    </p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mb-30-none">
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="partner-item">
                    <a href="#0">
                        <img src="{{ asset('public/frontend_assets/images/sponsor/1.png') }}" alt="sponsor">
                    </a>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="partner-item">
                    <a href="#0">
                        <img src="{{ asset('public/frontend_assets/images/sponsor/2.png') }}" alt="sponsor">
                    </a>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="partner-item">
                    <a href="#0">
                        <img src="{{ asset('public/frontend_assets/images/sponsor/3.png') }}" alt="sponsor">
                    </a>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="partner-item">
                    <a href="#0">
                        <img src="{{ asset('public/frontend_assets/images/sponsor/4.png') }}" alt="sponsor">
                    </a>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="partner-item">
                    <a href="#0">
                        <img src="{{ asset('public/frontend_assets/images/sponsor/5.png') }}" alt="sponsor">
                    </a>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="partner-item">
                    <a href="#0">
                        <img src="{{ asset('public/frontend_assets/images/sponsor/6.png') }}" alt="sponsor">
                    </a>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="partner-item">
                    <a href="#0">
                        <img src="{{ asset('public/frontend_assets/images/sponsor/7.png') }}" alt="sponsor">
                    </a>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="partner-item">
                    <a href="#0">
                        <img src="{{ asset('public/frontend_assets/images/sponsor/8.png') }}" alt="sponsor">
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--============= Partner Section Ends Here =============-->


<!--============= User Counter Section Starts Here =============-->
<section class="user-counter padding-bottom oh">
    <div class="container">
        <div class="user-counter-wrapper padding-bottom padding-top">
            <div class="user-counter-bg bg_contain bg_img" data-background="{{ asset('public/frontend_assets/images/bg/user-counter-bg.png') }}"></div>
            <div class="section-header cl-white mb-0">
                <h5 class="cate">Worldwide</h5>
                <h2 class="title"><span class="counter">2756</span></h2>
                <p>Clients Satisfied with our services</p>
            </div>
        </div>
    </div>
</section>
<!--============= User Counter Section Ends Here =============-->


<!--============= Join Team Section Ends Here =============-->
<section class="trial-section padding-bottom">
    <div class="container">
        <div class="trial-wrapper padding-top padding-bottom bg_img style-three" data-background="{{ asset('public/frontend_assets/images/bg/trial2.png') }}">
            <div class="trial-content cl-white">
                <h3 class="title m-0">Are You Want to Join Our Team?</h3>
            </div>
            <div class="trial-button">
                <a href="#0" class="button-3 long-light active text-light">JOIN OUR TEAM <i class="flaticon-right ml-xl-2"></i></a>
            </div>
        </div>
    </div>
</section>
<!--============= Join Team Section Ends Here =============-->



@endsection
