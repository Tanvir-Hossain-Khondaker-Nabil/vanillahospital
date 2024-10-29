<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 10/1/2020
 * Time: 3:02 PM
 */
?>

@extends('landing.master')


@section('content')
    <!--============= Sign In Section Starts Here =============-->
    <div class="account-section bg_img" data-background="{{ asset('public/frontend_assets/images/account-bg.jpg') }}">
        <div class="container">
            <div class="account-title text-center">
                {{--                <a href="index.html" class="back-home"><i class="fas fa-angle-left"></i><span>Back <span class="d-none d-sm-inline-block">To Mosto</span></span></a>--}}
                {{--                <a href="#0" class="logo">--}}
                {{--                    <img src="{{ asset('public/frontend_assets/images/logo/logo.png') }}" alt="logo">--}}
                {{--                </a>--}}
            </div>
            <div class="account-wrapper">
                <div class="account-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4 class="title mb-20">{{ __('Reset Password') }} </h4>
                    <form method="POST" action="{{ route('password.email') }}"  class="account-form">
                        @csrf
                        <div class="form-group">
                            <label for="sign-up">Your Email </label>
                            <input type="email" placeholder="Enter Your Email " class="{{ $errors->has('email') ? ' is-invalid' : '' }}" id="sign-up" name="email">

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="mt-2 mb-2"> {{ __('Send Password Reset Link') }}
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!--============= Sign In Section Ends Here =============-->

@endsection
