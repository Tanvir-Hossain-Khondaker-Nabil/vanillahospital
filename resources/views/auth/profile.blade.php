<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/13/2020
 * Time: 12:56 PM
 */
 $route =  \Auth::user()->can(['auth.profile']) ? route('auth.profile') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'User Profile',
        'href' => $route,
    ],

    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'User Profile',
    'title'=>'User Profile',
    'heading' => 'User Profile',
];


?>
@extends('layouts.back-end.master', $data)

@section('contents')

@include('common._alert')
        <!-- If no success message in flash session show User Profile form  -->
        <div class="panel panel-default">
            <div class="panel-body">
                {!! Form::open(['method' => 'post', 'files'=>true, 'route' => ['auth.update_profile']]) !!}
                <div class="row">
                    <div class="col-xs-6 form-group">
                        {!! Form::label('full_name', 'Full Name', ['class' => 'control-label']) !!}
                        {!! Form::text('full_name', auth()->user()->full_name,['class' => 'form-control', 'placeholder' => '']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 form-group">
                        {!! Form::label('email', 'Email', ['class' => 'control-label']) !!}
                        {!! Form::text('email', auth()->user()->email,['class' => 'form-control', 'placeholder' => '','disabled'=>true]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 form-group">
                        {!! Form::label('phone', 'Phone Number', ['class' => 'control-label']) !!}
                        {!! Form::text('phone', auth()->user()->phone,['class' => 'form-control', 'placeholder' => '']) !!}
                    </div>
                </div>

                <div class="row">

                    <div class="col-xs-6 form-group">
                        {!! Form::label('Photo', 'Profile Photo', ['class' => 'control-label']) !!}
                        <img src=" {{ !empty(auth()->user()->photo) ? auth()->user()->profile_photo :  asset('public/adminLTE/dist/img/user2-160x160.jpg') }}" class="img-circle w-25" alt="User Image">
                        <input type="file" name="profile_img"  accept="image/gif, .jpg, .jpeg, .png" class="mt-4"/>
                    </div>
                </div>


                {!! Form::submit("Save", ['class' => 'btn btn-success']) !!}
                {!! Form::close() !!}
            </div>
        </div>

@stop
