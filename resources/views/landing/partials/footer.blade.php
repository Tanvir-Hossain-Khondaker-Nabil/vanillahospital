<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 9/29/2020
 * Time: 1:45 PM
 */
?>

<footer class="footer-section bg_img" data-background="{{ asset('public/frontend_assets/images/footer/footer-bg.jpg') }}" >
    <div class="container">
        <div class="footer-top padding-top padding-bottom">
            <div class="logo text-center" style="max-width: 285px !important">
                <a href="#0">
                    <h3 class="pt-3">{{ human_words(config('app.name')) }}</h3>
{{--                    <img src="{{ asset('public/frontend_assets/images/logo/footer-logo.png') }}" alt="logo">--}}
                </a>
            </div>
            <ul class="social-icons">
                <li>
                    <a href="#0" class="active"><i class="fab fa-facebook-f"></i></a>
                </li>
                <li>
                    <a href="#0" ><i class="fab fa-twitter"></i></a>
                </li>
                <li>
                    <a href="#0"><i class="fab fa-pinterest-p"></i></a>
                </li>
                <li>
                    <a href="#0"><i class="fab fa-google-plus-g"></i></a>
                </li>
                <li>
                    <a href="#0"><i class="fab fa-instagram"></i></a>
                </li>
            </ul>
        </div>
        @if(config('view.front_footer_menu'))
        <div class="footer-bottom">
            <ul class="footer-link">
                <li>
                    <a href="#0">About</a>
                </li>
                <li>
                    <a href="#0">FAQs</a>
                </li>
                <li>
                    <a href="#0">Contact</a>
                </li>
                <li>
                    <a href="#0">Terms of Service</a>
                </li>
                <li>
                    <a href="#0">Privacy</a>
                </li>
            </ul>
        </div>
        @endif
        <div class="copyright">
            <p>
                Copyright © 2023. Developed By <a href="http://rcreation-bd.com" target="_blank">R-Creation</a>
            </p>
            {{--<p style="color:#fff; font-weight:bold">For Support or Query 01816306190,01722964303,01813316786</p>--}}
        </div>
    </div>
</footer>
