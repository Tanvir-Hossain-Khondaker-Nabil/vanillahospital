<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 3/12/2019
 * Time: 5:26 PM
 */
?>

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Confirmation') }}</div>

                    <div class="card-body">

                        {{ __('Thank you for your email Verification. Now you can Login') }}<br/>
                         <a class="btn btn-success" href="{{ route('admin.signin') }}">{{ __('Login') }}</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

