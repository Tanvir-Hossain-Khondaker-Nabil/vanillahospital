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
                <h2 class="title"> Hisebi New Subdomain Info</h2>

            </div>
            <div class="row justify-content-center justify-content-lg-between">
                <div class="col-lg-6 offset-3">
                    <div class="contact-wrapper pb-5">
                        @if(Session::has("type"))
                            <div class="alert alert-{{Session::get('type')}}">
                                {{ Session::get('message') }}
                            </div>
                        @endif
                            <div class="form-group">
                                <label for="surename">Company Name</label> <br/>
                                <label for="surename" class="font-weight-bold text-primary">
                                    {{ Session::get('name') }}
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="surename">Subdomain Link</label> <br/>

                                <label for="surename"  class="font-weight-bold">
                                    <a href="http://{{ Session::get('domain') }}">  {{ Session::get('domain') }} </a>
                                </label>
                            </div>
                            @if(!Session::has("db"))
                                <div class="form-group text-center">
                                    {{--<label for="surename"> Subdomain DB Data Insert </label> <br/>--}}

                                    <label for="surename"  class="font-weight-bold ">
                                        <a href="http://{{ Session::get('domain') }}/database-process/{{ Session::get('id') }}" target="_blank" class="btn btn-primary">  Click to Complete Processing </a>
                                        {{--<br/> or  <br/>--}}
                                        {{--{{ Session::get('domain') }}/database-proccess/{{ Session::get('id') }}--}}
                                    </label>
                                </div>

                            @endif
                                @if(Session::has("db"))
                                    <div class="form-group">
                                        <label for="surename"> Login Info: </label> <br/>

                                        <label for="surename"  class="font-weight-bold">
                                            Email: {{ Session::get('email') }}
                                        </label><br/>
                                        <label for="surename"  class="font-weight-bold">
                                            Password:  {{ Session::get('password') }}
                                        </label>
                                    </div>

                                @endif


                    </div>
                </div>

            </div>
        </div>
    </section>
    <!--============= Contact Section Ends Here =============-->


@endsection

