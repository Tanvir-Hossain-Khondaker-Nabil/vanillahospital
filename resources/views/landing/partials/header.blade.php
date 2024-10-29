<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 9/29/2020
 * Time: 1:45 PM
 */

?>


<header class="header-section">
    <div class="container">
        <div class="header-wrapper">
            <div class="logo" style="width: 265px !important">
                <a href="{{ url('/') }}">
                    <h3 class="pt-3">{{ human_words(config('app.name')) }}</h3>
{{--                    <img src="{{ asset('public/frontend_assets/images/logo/logo.png') }}" alt="logo">--}}
                </a>
            </div>

            @if(config('view.front_header_menu'))
            <ul class="menu">
                <li>
                    <a href="{{ route('index') }}">Home</a>

                </li>
                <li>
                    <a href="{{ route('feature') }}">Feature</a>

                </li>
                <li>
                    <a href="{{ route('pricing') }}">Pricing</a>
                </li>
                <li>
                    <a href="{{ route('register') }}">Sign Up</a>

                </li>
                <li>
                    <a href="{{ route('login') }}">Sign In</a>

                </li>
                <li>
                    <a href="{{ route('contact') }}">contact</a>
                </li>
                <li class="d-sm-none">
                    <a href="{{ route('register') }}" class="m-0 header-button">Get Hisebi</a>
                </li>
            </ul>
            @endif
            <div class="header-bar d-lg-none">
                <span></span>
                <span></span>
                <span></span>
            </div>
            @if(config('view.front_header_menu'))
            <div class="header-right">
                <select class="select-bar">
                    <option value="en">En</option>
                    <option value="Bn">Bn</option>
                </select>
{{--                <a href="{{ route('register') }}" class="header-button d-none d-sm-inline-block">Get {{ human_words(config('app.name')) }}</a>--}}
            </div>
            @endif
        </div>
    </div>
</header>
