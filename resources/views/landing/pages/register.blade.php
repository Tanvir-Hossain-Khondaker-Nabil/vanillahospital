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
{{--            <a href="index.html" class="back-home"><i class="fas fa-angle-left"></i><span>Back <span class="d-none d-sm-inline-block">To Hisebi</span></span></a>--}}
{{--            <a href="#0" class="logo">--}}
{{--                <img src="{{ asset('public/frontend_assets/images/logo/logo.png') }}" alt="logo">--}}
{{--            </a>--}}
        </div>
        <div class="account-wrapper " style="max-width: 100%;">
            <div class="account-header">
                <h4 class="title">Let's get started</h4>
                {{--<a href="#0" class="sign-in-with"><img src="{{ asset('public/frontend_assets/images/icon/google.png') }}" alt="icon"><span>Sign Up with Google</span></a>--}}
            </div>
            <div class="or">
                <span>OR</span>
            </div>
            <div class="account-body">
                <span class="d-block mb-20">Sign up with your work email / Phone</span>

                <form  class="account-form" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="text-center">
                        @include('common._alert')
                        @include('common._error')
                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            {{-- <div class="p-info"> --}}
                                <div class="form-group ">
                                    <label for="name" >{{ __('Name') }} <span class="text-red"> * </span></label>
                                        <input id="name" type="text" class="{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
            
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                        @endif
                                </div>
            
            
                                <div class="form-group d-none"  id="phone-form">
                                    <label for="sign-up" >{{ __('Phone') }} <span class="text-red"> * </span></label>
                                    <input type="text" placeholder="Enter Your Phone Number " name="phone" value="{{ old('phone') }}">
                                    <label class="w-100 text-right">  <span class="text-primary small"  id="email-use">  Use Email Instead </span> </label>
                                </div>
                                <div class="form-group"  id="email-form">
                                    <label for="sign-up" >{{ __('E-Mail') }} <span class="text-red"> * </span></label>
                                    <input type="text" placeholder="Enter Your Email " name="email"  value="{{ old('email') }}">
                                    <label class="w-100 text-right">  <span class=" text-primary small" id="phone-use">  Use Phone Instead </span> </label>
                                </div>
            
            
                                <div class="form-group ">
                                    <label for="password">{{ __('Password') }} <span class="text-red"> * </span></label>
            
                                        <input id="password" type="password" class="{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
            
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                        @endif
                                </div>
            
                                <div class="form-group ">
                                    <label for="password-confirm" >{{ __('Confirm Password') }} <span class="text-red"> * </span></label>
                                    <input id="password-confirm" type="password" name="password_confirmation" required>
                                </div>
    
                                <div class="form-group">
                                    <label for="display_name" >Country <span class="text-red"> * </span> </label>
                                    {!! Form::select('country_id', $countries,null, ['id'=>'country', 'class'=>'form-control select2', 'placeholder'=>'Select Country', 'required']); !!}
                                </div>
    
                                {{-- <div class="form-group text-center">
                                    <button type="button" id="next-reg">
                                        {{ __('Next') }} <i class="fa fa-arrow-right"> </i> 
                                    </button>
                                    <span class="d-block mt-15">Already have an account? <a href="{{ route('login') }}">Sign In</a></span>
                                </div> --}}
                            {{-- </div> --}}
                        
                        </div>    
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="display_name" >Company Type <span class="text-red"> * </span> </label>
                                {!! Form::select('company_type_id',$company_types,null,['id'=>'company_types','class'=>'form-control select2','placeholder'=>'Select company type','required']); !!}
                            </div>

        
                            <div class="form-group ">
                                <label for="num_of_emp">{{ __('Number of Employee') }} <span class="text-red"> * </span></label>
        
                                <input id="num_of_emp" type="number" name="num_of_emp" required  value="{{ old('num_of_emp') }}">
                            </div>
                            <div class="form-group ">
                                <label for="company">{{ __('common.company_name') }} <span class="text-red"> * </span></label>
                                <input id="company" type="text"  name="company_name" required  value="{{ old('company_name') }}">
                            </div>
        
                            <div class="form-group ">
                                <label for="company_address">{{ __('common.company_address') }} <span class="text-red"> * </span></label>
                                <input id="company_address" type="text" name="company_address" required value="{{ old('company_address') }}">
                            </div>
                            <div class="form-group ">
                                <label for="company_phone">{{ __('Company Phone') }}</label>
                                <input id="company_phone" type="text" name="company_phone" value="{{ old('company_phone') }}">
                            </div>
        
                            <div class="form-group ">
                                <label for="company_email">{{ __('Company Email') }}</label>
        
                                <input id="company_email" type="email"  name="company_email" value="{{ old('company_email') }}">
                            </div>
                            
                            <div class="form-group text-right">
                                <button type="submit" >
                                    <i class="fa fa-check"> </i>  {{ __('Register') }}
                                </button>
                            </div>
                        </div>    
                    </div>

                    <div class="c-info ">
                        <div class="form-group text-center">
                            <span class="d-block mt-15">Already have an account? <a href="{{ route('login') }}">Sign In</a></span>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
        <div class="sponsor-slider-wrapper cl-white text-center mt-40">
            <h5 class="slider-heading mb-3">Used by over 1,000,000 people worldwide</h5>
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
    $("#next-reg").click( function(e){
            e.preventDefault();

            $('.p-info').addClass('d-none');
            $('.c-info').removeClass('d-none');
    });
    $("#back-reg").click( function(e){
            e.preventDefault();

            $('.p-info').removeClass('d-none');
            $('.c-info').addClass('d-none');
    });
</script>
@endpush
