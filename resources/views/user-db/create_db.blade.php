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
            <div class="account-wrapper " style="max-width: 100%;">
                <div class="account-header">
                    <h2 class="title">Create Hisebi Access</h2>
                    <p>Whether you're looking for a demo, have a support question or a commercial query get in touch.</p>
                </div>
                <div class="account-body">

                    <form  class="account-form" action="{{ route('system.store') }}" method="post">
                        @if(Session::has("type"))
                            <div class="alert alert-{{Session::get('type')}}">
                                {{ Session::get('message') }}
                            </div>
                        @endif
                        @csrf
                        <div class="text-center">
                            @include('common._alert')
                            @include('common._error')
                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="surename">Your Full Name</label>
                                    <input type="text" name="full_name" placeholder="Enter Your Full Name" id="full_name" required />
                                </div>
                                <div class="form-group">
                                    <label for="surename">Your Company Name</label>
                                    <input type="text" name="name" placeholder="Enter Your Company Name" id="company_name" required />
                                </div>

                                <div class="form-group ">
                                    <label for="num_of_emp">{{ __('Number of Employee') }}</label>

                                    <input id="num_of_emp" type="number" name="num_of_emp" required  value="{{ old('num_of_emp') }}">
                                </div>

                                <div class="form-group">
                                    <label for="name">Login Password</label>
                                    <input type="password" name="password" placeholder="Enter Your Password"   autocomplete="off"  class="show_password"/>
                                </div>

                                <div class="form-group">
                                    <input type="checkbox" id="show_password" value="1" style="height: 15px; padding: 0 15px; width: auto;" />
                                    <label for="name" class="text-warning">Show Password</label>
                                </div>


                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="surename">Your Phone Number</label>
                                    <input type="text" name="phone" placeholder="Enter Your Phone Number" id="full_name" required />
                                </div>
                                <div class="form-group">
                                    <label for="display_name" >Company Type </label>
                                    {!! Form::select('company_type_id',get_company_types(),null,['id'=>'company_types','class'=>'form-control select2','placeholder'=>'Select company type','required']); !!}
                                </div>

                                <div class="form-group">
                                    <label for="surename">Login Email</label>
                                    <input type="email" name="email" placeholder="Enter Your Email"  required autocomplete="off" />
                                </div>
                                <div class="form-group">
                                    <label for="name">Confirm Login Password</label>
                                    <input type="password" name="password_confirmation" placeholder="Enter Your Password"   autocomplete="off" class="show_password"/>
                                </div>


                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                <label for="name">Company Features </label>
                                </div>
                                <div class="row">
                                    @foreach($features as $value)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="checkbox" name="features[]" value="{{$value}}" style="height: 15px; padding: 0 15px; width: auto;" /> {{ ucfirst(str_replace( '_', ' ', $value)) }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" >
                                        <i class="fa fa-check"> </i>  {{ __('Create') }}
                                    </button>
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </section>
    <!--============= Contact Section Ends Here =============-->


@endsection

