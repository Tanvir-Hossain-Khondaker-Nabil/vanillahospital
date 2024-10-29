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
                    <h4 class="title mb-20">Welcome To Hisebi</h4>
                    <form method="POST" action="{{ route('support_pin_login') }}"  class="account-form">
                        @csrf

            
    
                        <div class="form-group">
                            <label for="pass">Email/Phone</label>
                            <input type="text" class="{{ $errors->has('email') ? ' is-invalid' : '' }}"  name="email">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                    
                        </div>
    
                        <div class="form-group">
                            <label for="pass">Support Pin</label>
                            <input type="password" class="{{ $errors->has('support_pin') ? ' is-invalid' : '' }}"  id="pass" name="support_pin">
                            @if ($errors->has('support_pin'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('support_pin') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="form-group text-center">
                            <button type="submit" class="mt-2 mb-2">Sign In</button>
                        </div>
                    </form>
                </div>
            
            </div>
        </div>
    </div>
    <!--============= Sign In Section Ends Here =============-->

@endsection

@push('scripts')

<script>

    // $('.select2').select2();
    $("#email-use").click( function(e){
            e.preventDefault();

            $('#phone-form').addClass('d-none');
            $('#email-form').removeClass('d-none');
    });
    $("#phone-use").click( function(e){
            e.preventDefault();

            $('#phone-form').removeClass('d-none');
            $('#email-form').addClass('d-none');
    });
</script>
@endpush