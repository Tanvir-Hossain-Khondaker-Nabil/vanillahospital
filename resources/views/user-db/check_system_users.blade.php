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
                <h2 class="title"> Hisebi Access List</h2>

            </div>
            <div class="row justify-content-center justify-content-lg-between">
                <div class="col-lg-6 offset-3">
                    <div class="contact-wrapper">
                        @if(Session::has("type"))
                            <div class="alert alert-{{Session::get('type')}}">
                                {{ Session::get('message') }}
                            </div>
                        @endif
                        <form class="contact-form" action="{{ route('system-users.index') }}" method="get">
                            @csrf
                            <div class="form-group">
                                <label for="surename">Password</label>
                                <input type="password" name="password" placeholder="Enter Password" id="Password" required />
                            </div>


                            <div class="form-group">
                                <input type="submit" value="Show ">
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!--============= Contact Section Ends Here =============-->


@endsection

