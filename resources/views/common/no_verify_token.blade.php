<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 3/12/2019
 * Time: 4:31 PM
 */
?>

@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-header">{{ __('Verification Mail Failed') }}</div>
                    <h3 class="card-body red-text">
                        Token Not Matched or You already completed you verification
                    </h3>
                </div>
            </div>
        </div>
    </div>
@endsection
