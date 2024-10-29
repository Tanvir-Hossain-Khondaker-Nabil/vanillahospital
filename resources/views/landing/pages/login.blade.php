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
                    {{-- <h4 class="title mb-20">Welcome To {{ human_words(config('app.name')) }}</h4> --}}
                    <h4 class="title mb-20">{{__("common.welcome_to_app",['app_name' => human_words(config('app.name'))])}} </h4>
                    <form method="POST" action="{{ route('login') }}"  class="account-form">
                        @csrf
                        <input type="hidden" name="sign_with" value="{{ old('sign_with') ?  old('sign_with') : 'email'}}" id="sign_with"/>

                        <div class="form-group  {{ old('sign_with') != 'phone' ? 'd-none' : '' }}"  id="phone-form">
                            <label for="sign-up" >{{ __('common.phone') }} <span class="text-red"> * </span></label>

                            <input type="text" class="{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Enter Your Phone Number " id="phone" name="email" value="{{ old('phone') }}">
                            <label class="w-100 text-right">  <span class="text-primary small"  id="email-use">  Use Email Instead </span> </label>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group {{ old('sign_with') == 'phone' ? 'd-none' : '' }}"  id="email-form">
                            <label for="sign-up" >{{ __('common.email') }} <span class="text-red"> * </span></label>
                            <input type="text"  class="{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{__('common.enter_your_email')}} " id="email" name="email"  value="{{ old('email') }}">
                            <label class="w-100 text-right">  <span class=" text-primary small" id="phone-use">  {{__('common.use_phone_instead')}} </span> </label>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="pass">{{__('common.password')}}</label>
                            <input type="password" class="show_password {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{__('common.enter_your_password')}}" id="pass" name="password">
                            <div class="form-group">
                                <input type="checkbox" id="show_password" value="1" style="height: 15px; padding: 0 15px; width: auto;" />
                                <label for="name" class="text-warning">{{__('common.show_password')}}</label>
                            </div>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                            @if (Route::has('password.request'))
                            <span class="sign-in-recovery">{{__('common.forgot_your_password')}} <a href="{{ route('password.request') }}">{{__('common.recover_password')}}</a></span>
                            @endif
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="mt-2 mb-2">{{__('common.sign_in')}}</button>
                        </div>
                    </form>
                </div>
{{--                <div class="or">--}}
{{--                    <span>OR</span>--}}
{{--                </div>--}}
{{--                <div class="account-header pb-0">--}}
{{--                    --}}{{--<span class="d-block mb-30 mt-2">Sign up with your work email</span>--}}
{{--                    --}}{{--<a href="#0" class="sign-in-with"><img src="{{ asset('public/frontend_assets/images/icon/google.png') }}" alt="icon"><span>Sign Up with Google</span></a>--}}
{{--                    <span class="d-block mt-15">Don't have an account? <a href="#">Sign Up Here</a></span>--}}
{{--                </div>--}}
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
            $('#sign_with').val('email');
            $('#email-form').removeClass('d-none');
            $('#phone').attr('disabled', true);
    });
    $("#phone-use").click( function(e){
            e.preventDefault();

            $('#phone-form').removeClass('d-none');
            $('#sign_with').val('phone');
            $('#email-form').addClass('d-none');
            $('#email').attr('disabled', true);
    });
</script>
@endpush
