<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 10/4/2020
 * Time: 1:46 PM
 */
?>



@extends('landing.master')

@section('content')


<!--============= Feature Section Starts Here =============-->
<section class="feature-section padding-top oh pos-rel padding-bottom-2 pb-xl-0">
    <div class="feature-shapes style-two d-none d-lg-block">
        <img src="{{ asset('public/frontend_assets/images/feature/feature-shape2.png') }}" alt="feature">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="section-header mw-100">
                    <h5 class="cate">An Exhaustive list of Amazing Features</h5>
                    <h2 class="title">The only application you’ll need to power your life.</h2>
                    <p class="mw-500">Numerous features make it possible to customize the system
                        in accordance with all your needs.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 rtl">
                <div class="feature--thumb style-two pr-xl-4 ltr">
                    <div class="feat-slider owl-carousel owl-theme" data-slider-id="1">
                        <div class="main-thumb">
                            <img src="{{ asset('public/frontend_assets/images/feature/pro-main5.png') }}" alt="feature">
                        </div>
                        <div class="main-thumb">
                            <img src="{{ asset('public/frontend_assets/images/feature/pro-main6.png') }}" alt="feature">
                        </div>
                        <div class="main-thumb">
                            <img src="{{ asset('public/frontend_assets/images/feature/pro-main7.png') }}" alt="feature">
                        </div>
                        <div class="main-thumb">
                            <img src="{{ asset('public/frontend_assets/images/feature/pro-main6.png') }}" alt="feature">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="feature-wrapper mb-30-none owl-thumbs" data-slider-id="1">
                    <div class="feature-item">
                        <div class="feature-thumb">
                            <div class="thumb">
                                <img src="{{ asset('public/frontend_assets/images/feature/pro5.png') }}" alt="feature">
                            </div>
                        </div>
                        <div class="feature-content">
                            <h4 class="title">Latest Technology</h4>
                            <p>The satisfaction of users is the most important and the focus is on usability and completeness</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-thumb">
                            <div class="thumb">
                                <img src="{{ asset('public/frontend_assets/images/feature/pro2.png') }}" alt="feature">
                            </div>
                        </div>
                        <div class="feature-content">
                            <h4 class="title">Flexible Usability</h4>
                            <p>The satisfaction of users is the most important and the focus is on usability and completeness</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-thumb">
                            <div class="thumb">
                                <img src="{{ asset('public/frontend_assets/images/feature/pro3.png') }}" alt="feature">
                            </div>
                        </div>
                        <div class="feature-content">
                            <h4 class="title">Easy to Manage Your All Data</h4>
                            <p>The satisfaction of users is the most important and the focus is on usability and completeness</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-thumb">
                            <div class="thumb">
                                <img src="{{ asset('public/frontend_assets/images/feature/pro4.png') }}" alt="feature">
                            </div>
                        </div>
                        <div class="feature-content">
                            <h4 class="title">Designed for all devices</h4>
                            <p>The satisfaction of users is the most important and the focus is on usability and completeness</p>
                        </div>
                    </div>
                </div>
                <div class="feat-nav">
                    <a href="#0" class="feat-prev"><i class="flaticon-left"></i></a>
                    <a href="#0" class="feat-next active"><i class="flaticon-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--============= Feature Section Ends Here =============-->


<!--============= Faster Section Starts Here =============-->
<div class="faster-section padding-top-2 oh padding-bottom">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-6">
                <div class="faster-content">
                    <div class="section-header left-style mb-olpo">
                        <h5 class="cate">All-in-one responsive app for you</h5>
                        <h2 class="title">Hisebi Software
                            is up to 2x faster</h2>
                        <p>Hisebi App introduces a new way of managing your work and getting better results for your business.Hisebi One gives you a comprehensive and customizable platform to break down silos between departments and boost performance across your
                            organization.</p>
                    </div>
                    <div class="group">
                        <a href="#0" class="get-button active">Start Using for free<i class="flaticon-right"></i></a>
                        <a href="#0" class="get-button">More About Hisebi<i class="flaticon-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-5 d-none d-lg-block">
                <img src="{{ asset('public/frontend_assets/images/feature/faster.png') }}" alt="feature">
            </div>
        </div>
    </div>
</div>
<!--============= Faster Section Ends Here =============-->


<!--============= Comunity Section Starts Here =============-->
<section class="comunity-section padding-top padding-bottom oh pos-rel">
    <div class="comunity-bg bg_img" data-background="{{ asset('public/frontend_assets/images/client/Hisebi-client.jpg') }}"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-header cl-white">
                    <h5 class="cate">Join to comunity</h5>
                    <h2 class="title">Over 1 000 000 users</h2>
                    <p>Join and share in a community of like-minded members</p>
                </div>
            </div>
        </div>
        <div class="comunity-wrapper">
            <div class="buttons"><a href="#0" class="button-3 active">Join to comunity <i class="flaticon-right"></i></a></div>
            <div class="comunity-area">
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/1.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/2.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/3.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/4.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/5.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/6.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/7.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/8.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/9.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/10.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/11.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/12.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/13.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/14.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/15.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/16.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/17.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/18.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/19.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/22.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/20.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/21.png') }}" alt="comunity"></div>
            </div>
            <div class="comunity-area two">
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/1.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/2.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/3.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/4.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/5.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/6.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/7.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/8.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/9.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/10.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/11.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/12.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/13.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/14.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/15.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/16.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/17.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/18.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/19.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/22.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/20.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/21.png') }}" alt="comunity"></div>
            </div>
            <div class="comunity-area three">
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/1.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/2.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/3.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/4.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/5.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/6.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/7.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/8.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/9.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/10.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/11.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/12.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/13.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/14.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/15.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/16.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/17.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/18.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/19.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/22.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/20.png') }}" alt="comunity"></div>
                <div class="comunity-item"><img src="{{ asset('public/frontend_assets/images/comunity/21.png') }}" alt="comunity"></div>
            </div>
        </div>
    </div>
</section>
<!--============= Comunity Section Ends Here =============-->


<!--============= Sponsor Section Section Here =============-->
<section class="sponsor-section padding-bottom">
    <div class="container">
        <div class="section-header mw-100">
            <h5 class="cate">Used by over 1,000,000 people worldwide</h5>
            <h2 class="title">Companies that trust us</h2>
        </div>
        <div class="sponsor-slider-4 owl-theme owl-carousel">
            <div class="sponsor-thumb">
                <img src="{{ asset('public/frontend_assets/images/sponsor/sponsor1.png') }}" alt="sponsor">
            </div>
            <div class="sponsor-thumb">
                <img src="{{ asset('public/frontend_assets/images/sponsor/sponsor2.png') }}" alt="sponsor">
            </div>
            <div class="sponsor-thumb">
                <img src="{{ asset('public/frontend_assets/images/sponsor/sponsor3.png') }}" alt="sponsor">
            </div>
            <div class="sponsor-thumb">
                <img src="{{ asset('public/frontend_assets/images/sponsor/sponsor4.png') }}" alt="sponsor">
            </div>
            <div class="sponsor-thumb">
                <img src="{{ asset('public/frontend_assets/images/sponsor/sponsor5.png') }}" alt="sponsor">
            </div>
            <div class="sponsor-thumb">
                <img src="{{ asset('public/frontend_assets/images/sponsor/sponsor6.png') }}" alt="sponsor">
            </div>
            <div class="sponsor-thumb">
                <img src="{{ asset('public/frontend_assets/images/sponsor/sponsor7.png') }}" alt="sponsor">
            </div>
            <div class="sponsor-thumb">
                <img src="{{ asset('public/frontend_assets/images/sponsor/sponsor1.png') }}" alt="sponsor">
            </div>
            <div class="sponsor-thumb">
                <img src="{{ asset('public/frontend_assets/images/sponsor/sponsor2.png') }}" alt="sponsor">
            </div>
            <div class="sponsor-thumb">
                <img src="{{ asset('public/frontend_assets/images/sponsor/sponsor3.png') }}" alt="sponsor">
            </div>
            <div class="sponsor-thumb">
                <img src="{{ asset('public/frontend_assets/images/sponsor/sponsor4.png') }}" alt="sponsor">
            </div>
            <div class="sponsor-thumb">
                <img src="{{ asset('public/frontend_assets/images/sponsor/sponsor5.png') }}" alt="sponsor">
            </div>
            <div class="sponsor-thumb">
                <img src="{{ asset('public/frontend_assets/images/sponsor/sponsor6.png') }}" alt="sponsor">
            </div>
            <div class="sponsor-thumb">
                <img src="{{ asset('public/frontend_assets/images/sponsor/sponsor7.png') }}" alt="sponsor">
            </div>
        </div>
    </div>
</section>
<!--============= Sponsor Section Ends Here =============-->



@endsection
