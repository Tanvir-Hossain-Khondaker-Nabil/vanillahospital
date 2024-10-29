<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 10/4/2020
 * Time: 11:27 AM
 */
?>

@extends('landing.master')

@section('content')

    <section class="page-header single-header bg_img oh" data-background="{{ asset('public/frontend_assets/images/page-header.png') }}">
        <div class="bottom-shape d-none d-md-block">
            <img src="{{ asset('public/frontend_assets/css/img/page-header.png') }}" alt="css">
        </div>
    </section>

<!--============= Contact Section Starts Here =============-->
<section class="contact-section padding-top padding-bottom">
    <div class="container">
        <div class="section-header mw-100 cl-white">
            <h2 class="title">Contact Hisebi</h2>
            <p>Whether you're looking for a demo, have a support question or a commercial query get in touch.</p>
        </div>
        <div class="row justify-content-center justify-content-lg-between">
            <div class="col-lg-7">
                <div class="contact-wrapper">
                    <h4 class="title text-center mb-30">Get in Touch</h4>
                    <form class="contact-form" id="contact_form_submit">
                        @csrf
                        <div class="form-group">
                            <label for="surename">Your Company Name</label>
                            <input type="text" placeholder="Enter Your Company Name" id="company_name" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Your Full Name</label>
                            <input type="text" placeholder="Enter Your Full Name" id="name" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" placeholder="Enter Your Phone Number " id="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Your Email </label>
                            <input type="text" placeholder="Enter Your Email " id="email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Your Subject</label>
                            <input type="text" placeholder="Enter Your Subject " id="subject" required>
                        </div>
                        <div class="form-group mb-0">
                            <label for="message">Your Message </label>
                            <textarea id="message" placeholder="Enter Your Message" required></textarea>
                            <div class="form-check">
                                <input type="checkbox" id="check" checked><label for="check">I agree to receive emails, newsletters and promotional messages</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Send Message">
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="contact-content">
                    <div class="man d-lg-block d-none">
                        <img src="{{ asset('public/frontend_assets/images/contact/man.png') }}" alt="bg">
                    </div>
                    <div class="section-header left-style">
                        <h3 class="title">Have questions?</h3>
                        <p>Have questions about payments or price
                            plans? We have answers!</p>
                        <a href="#0">Read F.A.Q <i class="fas fa-angle-right"></i></a>
                    </div>
                    <div class="contact-area">
                        <div class="contact-item">
                            <div class="contact-thumb">
                                <img src="{{ asset('public/frontend_assets/images/contact/contact1.png') }}" alt="contact">
                            </div>
                            <div class="contact-contact">
                                <h5 class="subtitle">Email Us</h5>
                                <a href="Mailto:info@Hisebi.com">hisebionline@gmail.com</a>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-thumb">
                                <img src="{{ asset('public/frontend_assets/images/contact/contact2.png') }}" alt="contact">
                            </div>
                            <div class="contact-contact">
                                <h5 class="subtitle">Call Us</h5>
                                <a href="Tel:565656855">+(880)1816-306190</a>
                                <a href="Tel:565656855">+(880)1722-964303</a>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-thumb">
                                <img src="{{ asset('public/frontend_assets/images/contact/contact3.png') }}" alt="contact">
                            </div>
                            <div class="contact-contact">
                                <h5 class="subtitle">Visit Us</h5>
                                <p>House : 2304,Road: 02,Panthapath,Dhaka</p>
                                <p>150/A,Muradpur CDA Avenue,Chattogram</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--============= Contact Section Ends Here =============-->


<!--============= Map Section Starts Here =============-->
<div class="map-section padding-bottom-2">
    <div class="container">
        <div class="section-header">
            <div class="thumb">
                <img src="{{ asset('public/frontend_assets/images/contact/earth.png') }}" alt="contact">
            </div>
            <h3 class="title">We Are Easy To Find</h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="maps-wrapper">
                    <div class="maps">


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--============= Map Section Ends Here =============-->


<!--============= Do Section Starts Here =============-->
<div class="do-section padding-bottom padding-top-2">
    <div class="container">
        <div class="row mb-30-none justify-content-center">
            <div class="col-md-6">
                <div class="do-item cl-white">
                    <h3 class="title">About Us</h3>
                    <p>Find out who we are and what we do!</p>
                    <a href="#"><i class="fas fa-angle-left"></i></a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="do-item cl-white">
                    <h3 class="title">Partners</h3>
                    <p>Become a Hisebi
                        Solutions Partner!</p>
                    <a href="#"><i class="fas fa-angle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!--============= Do Section Ends Here =============-->


@endsection


@push('scripts')

    <script src="https://maps.google.com/maps/api/js?key=AIzaSyCo_pcAdFNbTDCAvMwAD19oRTuEmb9M50c"></script>


    <script src="{{ asset('public/frontend_assets/js/map.js') }}"></script>

    <script type="text/javascript">
        (function ($) {
            "use strict";

            jQuery(document).ready(function ($) {
                $(document).on('submit', '#contact_form_submit', function (e) {
                    e.preventDefault();
                    var name = $('#name').val();
                    var email = $('#email').val();
                    var subject = $('#subject').val();
                    var surename = $('#company_name').val();
                    var message = $('#message').val();
                    var phone = $('#phone').val();
                    var check = $('#check').val();

                    if (name && email && message && surename) {
                        $.ajax({
                            type: "POST",
                            url: '{{ route("store-contact") }}',
                            data: {
                                'name': name,
                                'email': email,
                                'subject': subject,
                                'company_name': company_name,
                                'message': message,
                                'phone': phone,
                            },
                            success: function (data) {
                                $('#contact_form_submit').children('.email-success').remove();
                                $('#contact_form_submit').prepend('<span class="alert alert-success email-success">' + data + '</span>');
                                $('#name').val('');
                                $('#email').val('');
                                $('#message').val('');
                                $('#company_name').val('');
                                $('#subject').val('');
                                $('#phone').val('');
                                // $('#map').height('576px');
                                $('.email-success').fadeOut(3000);
                            },
                            error: function (res) {

                            }
                        });
                    } else {
                        $('#contact_form_submit').children('.email-success').remove();
                        $('#contact_form_submit').prepend('<span class="alert alert-danger email-success">All Fields are Required.</span>');
                        // $('#map').height('576px');
                        $('.email-success').fadeOut(3000);
                    }

                });
            })

        }(jQuery));
    </script>
@endpush
