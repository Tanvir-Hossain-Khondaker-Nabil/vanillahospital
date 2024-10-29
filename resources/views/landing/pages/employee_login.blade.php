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
                    <h4 class="title mb-20">Welcome To Vanilla Thunder</h4>
                    <form method="POST" action="{{ route('login') }}"  class="account-form">
                        @csrf
                        <input type="hidden" name="sign_with" value="employee_id" id=""/>

                        <div class="form-group }}"  id="employee_id">
                            <label for="sign-up" >{{ __('Employee id') }} <span class="text-red"> * </span></label>
                            <input type="text"  class="{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Enter Employee Id " id="employee_id" name="employee_id"  value="{{ old('employee_id') }}">

                            @if ($errors->has('employee_id'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('employee_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="pass">Password</label>
                            <input type="password" class="show_password {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Enter Your Password" id="pass" name="password">
                            <div class="form-group">
                                <input type="checkbox" id="show_password" value="1" style="height: 15px; padding: 0 15px; width: auto;" />
                                <label for="name" class="text-warning">Show Password</label>
                            </div>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                            @if (Route::has('password.request'))
                            <span class="sign-in-recovery">Forgot your password? <a href="{{ route('password.request') }}">recover password</a></span>
                            @endif
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="mt-2 mb-2">Sign In</button>
                        </div>
                    </form>
                </div>
                <div class="or">
                    <span>OR</span>
                </div>
                <div class="account-header pb-0">
                    {{--<span class="d-block mb-30 mt-2">Sign up with your work email</span>--}}
                    {{--<a href="#0" class="sign-in-with"><img src="{{ asset('public/frontend_assets/images/icon/google.png') }}" alt="icon"><span>Sign Up with Google</span></a>--}}
                    <span class="d-block mt-15">Don't have an account? <a href="#">Sign Up Here</a></span>
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
